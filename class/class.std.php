<?php

/**
 * Classe Vds : Classe statique regroupant toutes les méthodes utiles à l'ensemble du projet
 *
 * @Author : Guy Verghote
 * @Version 1.4
 * @Date : 23/07/2022
 */

class Std
{

// ------------------------------------------------------------------------------
// méthode set permettant la mise à jour d'une colonne d'un enregistrement d'une table
// ------------------------------------------------------------------------------

    /**
     * Modification de la valeur d'un champ (colonne) pour une ligne (id) d'une table($table)
     * Cette méthode peut s'utiliser dans un script PHP répondant à un appel Ajax (demande de modification en mode colonne)
     * La colonne, sa valeur et l'id de l'enregistrement concerné doivent être transmis en POST
     * une quatrième donnée 'unicite' de valeur 1 peut être transmise s'il faut vérifier que la valeur est unique
     * @param string $table nom de la table
     */
    public static function update(string $table) : int | string
    {
        if (!Controle::existe('colonne', 'valeur', 'id')) {
            return "Paramètre manquant";
        }

        // récupération des données
        $colonne = $_POST["colonne"];
        $valeur = trim($_POST["valeur"]);
        $id = $_POST["id"];
        $unicite = isset($_POST['unicite']) ? $_POST['unicite'] : 0;

        // Vérification de l'existence de l'enregistrement à modifier
        $db = Database::getInstance();
        $sql = <<<EOD
            SELECT 1
            FROM $table
            where id = :id;
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('id', $id);
        $curseur->execute();
        $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        if (!$ligne) return "L'enregistrement à modifier n'existe pas : $id";

        // vérification de l'unicité de la valeur
        if ($unicite != 0) {
            $sql = <<<EOD
                Select 1
                From $table
                Where $colonne = :valeur
                and id != :id
EOD;
            $curseur = $db->prepare($sql);
            $curseur->bindParam('valeur', $valeur);
            $curseur->bindParam('id', $id);
            $curseur->execute();
            $ligne = $curseur->fetch();
            $curseur->closeCursor();
            if ($ligne) {
                return "Cette valeur doit être unique";
            }
        }

        // mise à jour
        $sql = <<<EOD
            update $table
            set $colonne = :valeur
            where id = :id;
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('valeur', $valeur);
        $curseur->bindParam('id', $id);
        try {
            $curseur->execute();
            return 1;
        } catch (Exception $e) {
            return substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
        }
    }


    /**
     * Supprime un enregistrement dans une table
     * Cette méthode peut s'utiliser dans un script PHP répondant à un appel Ajax (demande de suppression)
     * l'id de l'enregistrement concerné doit être transmis en POST
     * @param string $table
     * @return false|int|string
     */
    public static function delete(string $table)
    {
        // vérification des paramètres
        if (!isset($_POST['id']))
            return "Données manquantes : absence de l'identifiant";

        // récupération des données transmises
        $id = $_POST['id'];

        // Vérification de l'existence de l'enregistrement à supprimer
        $db = Database::getInstance();
        $sql = <<<EOD
            SELECT 1
            FROM $table
            where id = :id;
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('id', $id);
        $curseur->execute();
        $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        if (!$ligne) return "L'enregistrement à supprimer n'existe pas";

        // requête de suppression
        $sql = <<<EOD
            delete from $table
            where id = :id;
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('id', $id);
        try {
            $curseur->execute();
            return 1;
        } catch (Exception $e) {
            return substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
        }
    }

// ------------------------------------------------------------------------------
// méthode concernant le traitement des erreurs
// ------------------------------------------------------------------------------

    /**
     * Mémoriser l'erreur dans le fichier erreur.log et rediriger  l'utilisateur vers la page erreur/index.php
     * @param string $libelle Libellé de l'erreur
     * @return void
     */
    public static function traiterErreur(string $libelle) : void
    {

        $_SESSION['erreur'] = $libelle;
        header("location:/erreur/index.php");
        exit;
    }

    /**
     * mémoriser l'erreur dans le fichier erreur.log afficher l'ereurr et arrêter le sript
     * @param string $libelle Libellé de l'erreur
     * @return void
     */
    public static function traiterErreurAjax(string $libelle): void
    {

        echo $libelle;
        exit;
    }

    /**
     * Retourne l'utilisateur vers la page d'accueil si il veut rentrer dans une page nécessitant une connection
     * @return void
     */
    public static function necessiteConnexion(): void
    {
        if (!isset($_SESSION['membre'])) {
            $_SESSION['url'] = $_SERVER['PHP_SELF'];
            header("location:/profil/connexion.php");
            exit;
        }
    }


// ------------------------------------------------------------------------------
// méthode concernant la traçabilité
// ------------------------------------------------------------------------------


// ------------------------------------------------------------------------------
// méthode concernant les statistiques
// ------------------------------------------------------------------------------


}