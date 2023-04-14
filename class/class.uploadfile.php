<?php
declare(strict_types=1);

/**
 * Classe uploadFile : assure les opération de téléversement d'un fichier
 * @Author : Guy Verghote
 * @Date : 09/02/2023
 */

// chargement du composant permettant de redimensionner l'image
require RACINE . '/vendor/autoload.php';

use Gumlet\ImageResize;

class UploadFile
{
    // un fichier uploadé est représenté par
    //  un attribut value contenant le tableau $_FILES[] associé
    //     Ce tableau comprend les clés suivantes : error, tmp_name, size, name
    //  une propriété Rename permettant d'ajouter un suffixe au nom du fichier s'il est déjà présent sur le serveur
    //  un attrinut name contenant le nom du fichier ssur le serveur
    //  une propriété SansAccent permettant de rétirer les accents et les caractères spéciaux dans le nom du fichier

    // un attribut validateMessage contenant le message d'erreur suite à l'application de la méthode checkValidity

    // Afin de faire le contrôle il faut en tant que propriété :
    //  un tableau des extensions acceptées
    //  un tableau des types MIME acceptés
    //  la taille maximale autorisée pour le fichier
    // le répertoire de stockage sur le serveur
    // la largeur et la hauteur acceptées pour une image si besoin
    // La possibilité de mettre en place un redimensionnement automatique de l'image qui dépasserait les dimensions définies

    // Enfin pour être certain que le fichier a été contrôlé un booléen valide permet de savoir si la méthode checkvalidity a été appelée avec succès

    private array $value;
    // Indique si le fichier sera automatiquement renommé (true) par l'ajout d'un suffixe si le nom existe déjà
    // ou s'il doit conserver son nom (false) et dans ce cas le téléversement sera refusé si ce nom existe déjà sur le serveur
    public bool $Rename;
    // nom à donner au fichier sur le serveur
    private string $name;

    // Indique si les accents seront retirés et si les caractères autres que les lettres, les chiffres, le point, l'espace et le tiret doivent être remplacés par un espace
    public bool $SansAccent;
    private string $validationMessage;

    // Nom et chemin du fichier temporaire sur le serveur

    // Tableau des extensions acceptées
    public array $Extensions = [];

    // tableau des types mimes acceptés (décrit de façon standard la nature et le format du fichier)
    // le type Mime est transmis dans l'entête de la réponse envoyé par le serveur
    // Le type mime est déterminé par le serveur selon sa configuration
    public array $Types = [];

    // Taille maximale autorisée pour le fichier téléversé en octets
    public int $MaxSize;

    // Répertoire sur le serveur dans lequel le fichier téléchargé sera copié
    public string $Directory;

    //  Dimension demandée pour l'image : la hauteur et la largeur en pixel
    // L'image sera redimensionnée selon les dimensions demandées
    public int $Width;
    public int $Height;

    // Indique si l'image doit absolument respecter les dimensions (false) ou si elle sera automatiquement redimensionnée aux dimensions demandées
    public bool $Redimensionner;

    // Drapeau indiquant si l'objet est valide et peut donc être copié
    // membre initialisé dans le constructeur, modifiée dans la méthode checkValidity et utilisé par sécurité dans la méthode copy
    protected bool $valide;

    public function __construct()
    {
        $this->Height = 0;
        $this->Width = 0;
        $this->valide = false;
        $this->Redimensionner = false;
        $this->SansAccent = true;
        $this->name = '';
    }

    public function getValidationMessage()
    {
        return $this->validationMessage;
    }

    public function setvalue($_files)
    {
        $this->value = $_files;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Vérifie la taille, l'extension et le type myme mine du fichier téléversé
     *
     * @return bool
     */
    public function checkValidity(): bool
    {

        $this->valide = false;

        // L'objet doit forcément être renseigné
        if ($this->value === null) {
            $this->validationMessage = "Veuillez renseigner ce champ ";
            return false;
        }

        // récupération des informations sur le fichier téléversé

        $error = $this->value['error'];
        $size = $this->value['size'];
        $nomFichier = ($this->name === '') ? $this->value['name'] : $this->name;

        $tmpName = $this->value['tmp_name'];

        if ($this->SansAccent) {

            // remplacement des accents dans le nom du fichier
            $nomFichier = str_replace('ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
                'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy', $nomFichier);
            // remplacement par des espaces des caractères non alphabétiques
            $nomFichier = preg_replace('/([^.a-z0-9 -]+)/i', ' ', $nomFichier);

        }


        // détection d'une erreur lors de la transmission
        if ($error !== 0) {
            $this->validationMessage = "Une erreur est survenue lors du téléchargement";
            return false;
        }
        // vérification de la taille
        if ($size > $this->MaxSize) {
            $this->validationMessage = "La taille du fichier (size) dépasse la taille autorisée ($this->MaxSize)";
            return false;
        }

        // vérification de l'extension
        $extension = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));
        if (!in_array($extension, $this->Extensions)) {
            $this->validationMessage = "L'extension du fichier n'est pas acceptée";
            return false;
        }
        // contrôle du type mime du pdf
        $type = mime_content_type($tmpName);
        if (!in_array($type, $this->Types)) {
            $this->validationMessage = "Le type du fichier n'est pas accepté : $type";
            return false;
        }

        // contrôle de l'unicité
        // Si la propriété Rename est fausse le fichier ne doit pas déjà être présent
        // Si la propriété Rename est vraie un suffixe sera ajouté en cas de doublon

        if (!$this->Rename) {
            //  le fichier ne doit pas déjà être présent dans le répertoire
            if (file_exists($this->Directory . '/' . $nomFichier)) {
                $this->validationMessage = "Ce fichier est déjà présent sur le serveur";
                return false;
            }
        } else {
            // Ajout éventuel d'un suffixe sur le nom du fichier en cas de doublon
            $nom = pathinfo($nomFichier, PATHINFO_FILENAME);
            $i = 1;
            while (file_exists("$this->Directory/$nomFichier")) {
                $nomFichier = "$nom($i).$extension";
                $i++;
            }
        }

        // contrôle éventuel des dimensions si elles sont fixées et si l'image ne doit pas être redimensionnée
        if (!$this->Redimensionner && ($this->Width !== 0 || $this->Height !== 0)) {
            // Récupération des dimensions de l'image
            $lesDimensions = getimagesize($tmpName);
            $width = $lesDimensions[0];
            $height = $lesDimensions[1];
            if ($width > $this->Width || $height > $this->Height) {
                $this->validationMessage = "Les dimensions de l'image ($width*$height) dépassent les dimensions acceptées ($this->Width*$this->Height)";
                return false;
            }
        }
        // mémorisation du nouveau nom
        $this->name = $nomFichier;
        // mémorisation du succès de l'opération de contrôle
        $this->valide = true;
        return true;
    }

    /**
     * Copie du fichier téléversé sur le serveur sous le nom ontenu dans la propriété values
     * Condition : avoir appelé la méthode checkValidity avant et avoir renseigné la propriété Directory
     * @return bool
     */
    public function copy(): bool
    {
        // le fichier ne peut être copié que s'il a été préalablement vérifié
        if (!$this->valide) {
            $this->validationMessage = " Le fichier doit être contrôlé avant d'être copié";
            return false;
        }

        $nomFichier = $this->name;
        $tmpName = $this->value['tmp_name'];

        // adaptation des dimensions aux dimensions demandées

        // Si aucune contrainte sur les dimensions ou si le redimensionnement n'est pas activé, le fichier peut êtr copié
        if (($this->Width === 0 && $this->Height === 0) || !$this->Redimensionner) {
            copy($tmpName, "$this->Directory/$nomFichier");
            return true;
        }


        // au moins une contrainte est fixée
        $lesDimensions = getimagesize($tmpName);
        if ($lesDimensions[0] > $this->Width) {
            if ($lesDimensions[1] > $this->Height) {
                // Contrainte sur la largeur et la hauteur
                $image = new ImageResize($tmpName);
                $image->crop($this->Width, $this->Height, true, ImageResize::CROPCENTER);
                $image->save("$this->Directory/$nomFichier");
            } else {
                // contrainte sur la largeur mais pas la hauteur
                $image = new ImageResize($tmpName);
                $image->crop($this->Width, $lesDimensions[1], true, ImageResize::CROPLEFT);
                $image->save("$this->Directory/$nomFichier");
            }
        } else {
            if ($lesDimensions[1] > $this->Height) {
                // contrainte sur la hauteur mais pas la largeur
                $image = new ImageResize($tmpName);
                $image->crop($lesDimensions[0], $this->Height, true, ImageResize::CROPTOP);
                $image->save("$this->Directory/$nomFichier");
            } else {
                copy($tmpName, "$this->Directory/$nomFichier");
                return true;
            }
        }
        return true;
    }

    public function del()
    {
        $nomFichier = $this->name;
        return @unlink($this->Directory . '/' . $this->name);
    }
}