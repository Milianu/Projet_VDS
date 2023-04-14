<?php

// gestion de la table agenda(id, date, nom, description)
// la colonne description est optionnelle

class Agenda extends Table
{
    public function __construct()
    {
        // appel du contructeur de la classe parent
        parent::__construct('agenda');


        // identifiant de la table
        $input = new InputInt();
        $input->Require = false; // auto-incrément
        $input->Unique = true;
        $this->columns['id'] = $input;

        // le nom doit être renseigné,
        // commencer par une lettre ou un chiffre
        // se terminer par une lettre, un chiffre ou !
        // contenir entre 10 et 70 caractères
        $input = new InputTexte();
        $input->Require = true;
        $input->Unique = true;
        $input->Pattern = "^[0-9A-Za-zÀÇÈÉÊàáâçèéêëî]((.)*[0-9A-Za-zÀÇÈÉÊàáâçèéêëî!])*$";
        $input->MaxLength = 70;
        $input->MinLength = 10;
        $this->columns['nom'] = $input;

        // la date ne doit pas être inférieure à la date du jour
        $input = new InputDate();
        $input->Require = true;
        $input->Unique = false;
        $input->Min = date("Y-m-d");
        $this->columns['date'] = $input;

        // la description est optionnelle
        $input = new InputTextarea();
        $input->Require = false;
        $input->Unique = false;
        $this->columns['description'] = $input;


        // le nom du membre ayant réalisé la mise à jour
        // pas de contrôle puisque la donnée n'est pas saisie
        $input = new InputTexte();
        $input->Require = true;
        $input->Unique = false;
        $this->columns['majPar'] = $input;

        // le type d'événement public ou privé
        $input = new InputList();
        $input->Require = true;
        $input->Values = ['Public', 'Privé'];
        $this->columns['type'] = $input;

    }


    // Récupération de tous les enregistrements avec mise en forme de la date et ajout d'un drapeau sur les enregistrements pouvant être supprimés
    public static function getLesEvenements()
    {
        $sql = <<<EOD
			Select id, nom, date_format(date, '%d/%m/%Y') as dateFr, if(date < current_date, 1, 0) as old, majPar, type
            From agenda
            Order by date desc;
EOD;
        $db = Database::getInstance();
        try {
            $curseur = $db->query($sql);
        } catch (Exception $e) {
            self::$error = $e->getMessage();
            return -1;
        }
        $lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        return $lesLignes;
    }

    // Récupération des enregistrements à afficher (la date doit être supérieure à la date du jour)
    public static function getLesEvenementsAVenir()
    {
        $sql = <<<EOD
		    Select nom, date_format(date, '%d/%m/%Y') as dateFr, description  
            From agenda
            where date >= curdate() 
           
           
EOD;
       if (!isset($_SESSION['membre'])) {
           $sql .= " and type = 'Public'";
       }

        $sql .= " order by date ";
        $db = Database::getInstance();
        try {
            $curseur = $db->query($sql);
        } catch (Exception $e) {
            self::$error = $e->getMessage();
            return -1;
        }
        $lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        return $lesLignes;
    }

    // Suppression de tous les enregistrements dont la date est dépassée
    public function epurer() {
        $nb = $this->remove("date < curdate()");
        if ($nb === - 1) {
           $reponse =  ["error" => $this->validationMessage];
        } elseif ($nb === 0) {
            $reponse = ["success" => "Aucun événement concerné"];
        } elseif ($nb === 1) {
            $reponse = ["success" => "Un événement a été supprimé"];
        } else {
            $reponse = ["success" => "$nb événements ont été supprimés"];
        }
        return json_encode($reponse, JSON_UNESCAPED_UNICODE);
    }





}

