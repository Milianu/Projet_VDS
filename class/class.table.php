<?php
declare(strict_types=1);

/**
 * Classe Table : représente une table SQL
 * @Author : Guy Verghote
 * @Date : 28/02/2023
 */
abstract class  Table implements Iterator
{
    // propriété statique contenant l'erreur rencontrée lors de l'exécution d'une méthode statique

    protected static string $error = '';

    public static function getError()
    {
       return self::$error;
    }

    protected string $tableName;  // nom de la table

    // message d'erreur alimentée par les différentes méthodes
    protected string $validationMessage;

    // Colonnes composant la structure de la table à l'exception de l'id
    // Tableau associatif dont la clé et le nom de la colonne et la valeur un objet Input (contient les règles de validations)
    protected array $columns;

    // Objet InputList contenant les colonnes modifiables en mode colonne
    protected InputList $listOfColumns;



    /**
     * Constructeur
     * @param string $nomTable
     */
    protected function __construct(string $nomTable)
    {
        $this->tableName = $nomTable;
        $this->validationMessage = '';
        $this->columns = [];
        $this->listOfColumns = new InputList();
    }

    /*
    -------------------------------------------------------------------------------------------------------------------
    Les accesseurs
    --------------------------------------------------------------------------------------------------------------------
    */

    // accesseur en lecture sur l'attribut $validationMessage
    public function getValidationMessage() : string
    {
        return  $this->validationMessage;
    }

    // méthode permettant de gérer le tableau associatif $columns

    /**
     * Renseigne la valeur associée à la clé (colonne)
     * @param string $cle
     * @param mixed $valeur
     * @return void
     */
    public function setValue($cle, $valeur)
    {
       $this->columns[$cle]->Value = trim($valeur);
    }

    /**
     * Retourne la valeur associée à la clé (colonne)
     * @param string $cle Nom de la colonne de la table
     * @return mixed Valeur de la colonne
     */
    public function getValue($cle) : mixed
    {
       return $this->columns[$cle]->Value;
    }

    /*
    -------------------------------------------------------------------------------------------------------------------
    Les méthodes de contrôle
    --------------------------------------------------------------------------------------------------------------------
    */

    /**
     * Contrôle la validité et éventuellement l'unicité de la valeur associée à la clé (colonne)
     * @param string $cle
     * @return Erreur|null
     */
    protected function checkColumn(string $cle)
    {
        $erreur = null;
        $input = $this->columns[$cle];
        if (!$input->checkValidity()) {
            $erreur = new Erreur($cle, $input->getValidationMessage());
        } else {
            if ($input->Unique) {
                if (!$this->isUnique($cle, $input->Value)) {
                    $erreur = new Erreur($cle, $this->validationMessage);
                }
            }
        }
        return $erreur;
    }

    /**
     * Vérifie si la valeur de la colonne est bien unique dans la table
     * en cas de modification il faut exclure l'enregistrement en cours de modification
     * @param string $colonne
     * @param string $valeur
     * @return bool
     */
    protected function isUnique(string $colonne, string $valeur): bool
    {
        $db = Database::getInstance();
        $sql = <<<EOD
		        Select 1
		        From $this->tableName
		        Where $colonne = :valeur
EOD;

        // si l'id n'est pas indiqué : il s'agit d'un contrôle d'unicité dans le cadre d'un ajout
        if ($this->columns['id']->Value === null) {
            $curseur = $db->prepare($sql);
            $curseur->bindParam('valeur', $valeur);
        } else {
            $sql .= " and id != :id;";
            $curseur = $db->prepare($sql);
            $curseur->bindParam('valeur', $valeur);
            $curseur->bindParam('id', $this->columns['id']->Value);
        }
        try {
            $curseur->execute();
        } catch (Exception $e) {
            $this->validationMessage = $e->getMessage();
            return false;
        }
        $ligne = $curseur->fetch();
        $curseur->closeCursor();
        if ($ligne) {
            $this->validationMessage = "Cette valeur est déjà utilisée dans un autre enregistrement.";
            return false;
        }
        return true;
    }


    /**
     *
     * Retourne l'enregistrement correspondant ou false si non trouvé
     * @param $id
     * @return false | array
     */
    protected function get($id)
    {
        $db = Database::getInstance();
        $sql = <<<EOD
	        SELECT *
            FROM  $this->tableName
            where id = :id;
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('id', $id);

        try {
            $curseur->execute();
        } catch (Exception $e) {
            $this->validationMessage = "Erreur d'accès aux données : " . $e->getMessage();
            return false;
        }
        $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        if (!$ligne) {
            $this->validationMessage = "Cet enregistrement n'existe pas";
            return false;
        }

        return $ligne;
    }

    /*
    -------------------------------------------------------------------------------------------------------------------
     Les méthodes protégées permettant la mise à jour des données
    --------------------------------------------------------------------------------------------------------------------
    */

    /**
     * Alimentation de la valeur de plusieurs clés (colonnes) du tableau $columns
     * @param array $columns Tableau associatif correspond le plus souvent à $_POST
     * @return void
     */
    protected function setValues(array $columns)
    {
       foreach($columns as $cle => $valeur) {
           if (isset($this->columns[$cle])) {
               $this->columns[$cle]->Value = trim($valeur);
           }
       }
    }

    /**
     * Contrôle la valeur attribuée à chaque colonne à partir des règles de validations associées à chaque colonne
     * @return array
     */

    // remarque : l'unicité pourrait s'appliquer sur un champ optionnel d'où l'usage de null
    protected function checkAll(): array
    {
        $lesErreurs = [];
        foreach ($this->columns as $cle => $input) {
            if (!$input->checkValidity()) {
                $lesErreurs[] = new Erreur($cle, $input->getValidationMessage());
            } else {
                if ($input->Unique && $input->Value !== null) {
                    if (!$this->isUnique($cle, $input->Value)) {
                        $lesErreurs[] = new Erreur($cle, $this->validationMessage);
                    }
                }
            }
        }
        return $lesErreurs;
    }

    /**
     * Ajoute un enregistrement dans une table
     * @return true|false|int
     */
    protected function insert()
    {
        $set = self::getClauseSet();
        $sql = "insert into $this->tableName set $set";
        $ok = $this->prepareAndExecute($sql);
        // en cas de réussite si l'id est du type compteur (propriété Require à false)
        // on retourne la valeur du compteur
        if ($ok && !$this->columns['id']->Require) {
            $db = Database::getInstance();
            return $db->lastInsertId();
        } else {
            return $ok;
        }
    }

    /**
     * Modifie un enregistrment dans une table
     * @param $id valeur de l'identifiant de l'enregistrement à mettre à jour
     * @return bool
     */
    protected function update($id)
    {
        $set = self::getClauseSet();
        $sql = "update $this->tableName set $set where id = :id;";
        return $this->prepareAndExecute($sql);
    }

    /**
     * Modifier la valeur d'une colonne de la table pour l'enregistrement courant
     * @param string $colonne
     * @param string $valeur
     * @param $id
     * @return bool
     */
    protected function updateColumn(string $colonne, string $valeur, $id): bool
    {
        $sql = <<<EOD
            update  $this->tableName
             set $colonne= :valeur
             where id = :id;
EOD;
        $db = Database::getInstance();
        $curseur = $db->prepare($sql);
        $curseur->bindParam('valeur', $valeur);
        $curseur->bindParam('id', $id);
        try {
            $curseur->execute();
            return true;
        } catch (Exception $e) {
            $this->validationMessage = substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
            return false;
        }
    }

    /**
     * Supprimer l'enregistrement sélectionné dans une table
     * L'id doit être valide
     * @param $id
     * @return bool
     */
    protected function delete($id): bool
    {
        $sql = <<<EOD
            delete from  $this->tableName
            where id = :id;
EOD;
        $db = Database::getInstance();
        $curseur = $db->prepare($sql);
        $curseur->bindParam('id', $id);
        try {
            $curseur->execute();
            return true;
        } catch (Exception $e) {
            $this->validationMessage = substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
            return false;
        }
    }

    /**
     * Exécute une requête de suppression portant sur plusieurs enregistrements
     * mais dont la condition ne comporte aucun paramètre
     * exemple : suppression des enregistrements dont la date est dépassée
     * @param string $sql
     * @return int  - nombre d'enregistrements supprimés ou -1 en cas d'erreur rencontrée
     */
    protected function remove(string $condition): int
    {
        $db = Database::getInstance();
        try {
            return $db->exec("delete from $this->tableName where $condition");
        } catch (Exception $e) {
            $this->validationMessage = substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
            return -1;
        }
    }


    /*
    -------------------------------------------------------------------------------------------------------------------
    Les méthodes privées permettant la génération dynamique des requêtes insert et update
    --------------------------------------------------------------------------------------------------------------------
    */

    /**
     * Génération de la clause set d'une requête update en parcourant les clés du tableau $columns
     * Seules les clés possédant une valeur sont prises en compte
     * @return string
     */

    // nom = :nom, prenom = :prenom,
    //   colonne ou clé
    private function getClauseSet()
    {
        $set = "";
        foreach ($this->columns as $cle => $input) {
            if($input->Value !== null) {
                $set .= "$cle = :$cle, ";
            }
        }
        // retirer les deux derniers caractères
        $set = substr($set,0, -2);
        return $set;
    }

    /**
     * @param string $sql Requête SQL paramétrée à exécuter
     * @return bool
     */
    private function prepareAndExecute($sql)
    {
        $db = Database::getInstance();
        $curseur = $db->prepare($sql);

        // alimentation des paramètres de la requête
        foreach ($this->columns as $cle => $input) {
            if ($input->Value !== null) {
                $curseur->bindParam($cle, $input->Value);
            }
        }
        try {
            $curseur->execute();
            return true;
        } catch (Exception $e) {
            $this->validationMessage = substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
            return false;
        }
    }



    /*
    -------------------------------------------------------------------------------------------------------------------
    Les méthodes de l'interface Iterator afin de permettre de parcourir un objet table sur sa propriétés columns
    --------------------------------------------------------------------------------------------------------------------
    */

    public function current(): mixed
    {
        return current($this->columns);
    }

    public function next(): void
    {
        next($this->columns);
    }

    public function key(): mixed
    {
        return key($this->columns);
    }

    public function valid(): bool
    {
        $cle = key($this->columns);
        return ($cle !== NULL && $cle !== FALSE);
    }

    public function rewind(): void
    {
        reset($this->columns);
    }

    /*
    -------------------------------------------------------------------------------------------------------------------
    Les méthodes "clé en main" permettant la recherche, l'ajout, la suppression, la modification d'un enregistrement et la modification d'une colonne
    --------------------------------------------------------------------------------------------------------------------
    */

    /**
     * Vérifie le format puis l'existence de l'id passé en GET ou en POST
     * Retourne l'enregistrement ou false en cas d'erreur
     * Le message d'erreur est conservé dans l'attribut validationMessage
     */
    public function rechercher()
    {
        // vérification des données attendues
        if (!isset($_REQUEST['id'])) {
            $this->validationMessage = "Vous devez transmettre l'identifiant de l'enregistrement";
            return false;
        }

        $id = $_REQUEST['id'];

        // Contrôle de la valeur
        $input = $this->columns['id'];
        $input->Value = $id;
        $this->columns['id']->Require = true;

        if (!$input->checkValidity()) {
            $this->validationMessage = $input->getValidationMessage();
            return false;
        }

        // récupération de l'enregistrement correspondant
        $db = Database::getInstance();
        $sql = <<<EOD
	        SELECT *
            FROM  $this->tableName
            where id = :id;
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('id', $id);

        try {
            $curseur->execute();
        } catch (Exception $e) {
            $this->validationMessage = "Erreur d'accès aux données : " . $e->getMessage();
            return false;
        }
        $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        if (!$ligne) {
            $this->validationMessage = "Cet enregistrement n'existe pas";
            return false;
        }
        return $ligne;
    }


    /**
     * Ajoute un enregistrement dazns la table
     * Les données d'entrée proviennent du tableau $_POST
     * @return string au format JSON
     */
    public function ajouter()
    {
        // alimentation de la valeur des objets Input par les données transmises
        $this->setValues($_POST);
        // si des données sont calculées elles doivent avoir été initialisées dans le script appelant

        // contrôle des valeurs transmises
        $lesErreurs =  $this->checkAll();

        // En cas d'erreur on ne continue pas
        if (count($lesErreurs) > 0) {
            return json_encode(['error' => $lesErreurs], JSON_UNESCAPED_UNICODE);
        }

        // ajout
        $ok = $this->insert();
        if ($ok) {
            return json_encode(['success' => "Ajout réalisé"], JSON_UNESCAPED_UNICODE);
        } else {
            $lesErreurs[] = new Erreur('msg', $this->validationMessage);
            return json_encode(['error' => $lesErreurs], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Réalise la modification d'un enregistrement
     * Les données d'entrée proviennent du tableau $_POST : toutes les colonnes ne sont pas forcément transmises
     * @param $all à true s'il faut placer la valeur 'null' dans les champs dont la valeur n'est pas transmise
     * @return string  au format JSON contenant une propriété error contenant un tableau d'erreurs ou une propriété success
     */
    public function modifier($all = false)
    {
        // alimentation par les données transmises
        $this->setValues($_POST);

        // contrôle que chaque colonne de la table a bien une valeur sauf si elle n'est pas requise
        // dans le cadre d'une modification l'id est obligatoire
        $this->columns['id']->Require = true;
        $lesErreurs =  $this->checkAll();

        // En cas d'erreur on ne continue pas
        if (count($lesErreurs) > 0) {
            return json_encode(['error' => $lesErreurs], JSON_UNESCAPED_UNICODE);
        }

        // contrôler l'identifiant
        $id = $this->columns['id']->Value;

        $ligne = $this->get($id);
        if (!$ligne) {
            $lesErreurs[] = new Erreur('id', $this->validationMessage);
            return json_encode(['error' => $lesErreurs], JSON_UNESCAPED_UNICODE);
        }

        // modification
        $ok = $this->update($id);
        if (!$ok) {
            $lesErreurs[] = new Erreur('msg', $this->validationMessage);
            return json_encode(['error' => $lesErreurs], JSON_UNESCAPED_UNICODE);
        }

        // les colonnes non renseignées doivent-elles reprendre la valeur null ?
        // Génération de la clause set pour les colonnes devant être effacées (null)
        if($all) {
            $set = "";
            foreach ($this->columns as $cle => $input) {
                if ($input->Value === null) {
                    $set .= "$cle = null, ";
                }
            }
            // suppression de la dernière virgule
            $set = substr($set, 0, -2);

            if ($set !== '') {
                $sql = "update  $this->tableName set $set where id = :id;";
                $db = Database::getInstance();
                $curseur = $db->prepare($sql);
                $curseur->bindParam('id', $id);
                try {
                    $curseur->execute();
                } catch (Exception $e) {
                    $this->validationMessage = substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
                    $lesErreurs[] = new Erreur('msg', $this->validationMessage);
                    return json_encode(['error' => $lesErreurs], JSON_UNESCAPED_UNICODE);
                }
            }
        }
        return json_encode(['success' => "Modification enregistrée"], JSON_UNESCAPED_UNICODE);
    }


    /**
     * Suppression d'un enregistrement d'une table
     * La donnée d'entrée provient du tableau $_POST
     * @return string au format json contenant soit une propriété error, soit une propriété success
     */
    public function supprimer()
    {
        // Données attendues
        if (!isset($_POST['id'])) {
            return json_encode(["error" => "Vous devez fournir l'identifiant de l'enregistrement à supprimer"], JSON_UNESCAPED_UNICODE);
        }

        // récupération des données transmises
        $id = htmlspecialchars(trim($_POST["id"]));

        // contrôler l'identifiant
        $ligne = $this->get($id);
        if (!$ligne) {
            return json_encode(['error' => $this->validationMessage], JSON_UNESCAPED_UNICODE);
        }

        // réalisation de la suppression
        $ok = $this->delete($id);
        if ($ok) {
            return json_encode(['success' => "Suppression réalisée"], JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(["error" => $this->validationMessage], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Modifie la valeur d'une colonne d'un enregistrement
     * Les données d'entrée proviennent du tableau $_POST
     * Ces données sont contrôlées
     * @return string au format JSON contenant une propriété error soit une propriété success
     *LA proprité error peut contenir un tableau ou une chaîne de caractères
     */
    public function modifierColonne() : string
    {
        $lesErreurs = [];

        // vérification de la transmission des données attendues
        // contrôle des paramètres attendus
        if (!isset($_POST['id'])) {
            $lesErreurs[] = new Erreur('id', "Vous devez transmettre l'identifiant de l'objet à modifier");
        }

        if (!isset($_POST['colonne'])) {
            $lesErreurs[] = new Erreur('id', "Vous devez transmettre la colonne à modifier");
        }

        if (!isset($_POST['valeur'])) {
            $lesErreurs[] = new Erreur('id', "Vous devez transmettre la nouvelle valeur de la colonne");
        }

        // En cas d'erreur on ne continue pas
        if (count($lesErreurs) > 0) {
            return json_encode(['error' => $lesErreurs], JSON_UNESCAPED_UNICODE);
        }

        // récupération des données
        $id = htmlspecialchars(trim($_POST["id"]));
        $valeur = htmlspecialchars(trim($_POST["valeur"]));
        $colonne = htmlspecialchars(trim($_POST["colonne"]));

        // contrôle sur la colonne : La colonne doit faire partie des colonnes modifiables de la table
        $this->listOfColumns->Value = $colonne;
        if (!$this->listOfColumns->checkValidity()) {
            return json_encode(["error" => "Cette colonne n'existe pas ou n'est pas modifiable"], JSON_UNESCAPED_UNICODE);
        }

        // contrôle de l'identifiant
        $ligne = $this->get($id);
        if (!$ligne) {
            return json_encode(["error" => $this->validationMessage], JSON_UNESCAPED_UNICODE);
        }

        // contrôle de la valeur à l'aide de l'objet input associé dans la classe
        $input = $this->columns[$colonne];
        $input->Value = $valeur;
        if (!$input->checkValidity()) {
            return json_encode(["error" => "$colonne : " . $input->getValidationMessage()], JSON_UNESCAPED_UNICODE);
        }
        // modification dans la base
        // réalisation de la modification
        $ok = self::updateColumn($colonne, $valeur, $id);
        if ($ok) {
            return json_encode(['success' => "La colonne $colonne a été modifiée"], JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(["error" => $this->getValidationMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

}