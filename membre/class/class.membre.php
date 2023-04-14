<?php

class Membre
{

    /**
     * Ajout d'un membre avec vérification unicité sur nom, prénom et génération du login
     * @param string $nom
     * @param string $prenom
     * @param string $email
     * @param string $reponse
     * @return bool
     */
    public static function ajouter(string $nom, string $prenom, string $email, string &$reponse): bool
    {
        $ok = false;
        $sql = <<<EOD
            Select id
            From membre
            Where nom = :nom
            and prenom = :prenom
EOD;
        $db = Database::getInstance();
        $curseur = $db->prepare($sql);
        $curseur->bindParam('nom', $nom);
        $curseur->bindParam('prenom', $prenom);
        $curseur->execute();
        $ligne = $curseur->fetch();
        $curseur->closeCursor();
        if ($ligne)
            $reponse = "Ce membre existe déjà";
        else {
            // génération du login
            $login = $nom;
            $i = 2;
            do {
                $sql = <<<EOD
                      SELECT 1 
                      FROM membre
                       Where login = :login
EOD;
                $curseur = $db->prepare($sql);
                $curseur->bindParam('login', $login);
                $curseur->execute();
                $ligne = $curseur->fetch();
                $curseur->closeCursor();
                if (!$ligne) break;
                $login = $nom . $i;
                $i++;
            } while (true);

            // ajout dans la table membre, le mot de passe par défaut est 0000

            $sql = <<<EOD
        insert into membre(nom, prenom, email, login, password)
        values (:nom, :prenom, :email, :login, sha2('0000', 256));
EOD;

            $curseur = $db->prepare($sql);
            $curseur->bindParam('nom', $nom);
            $curseur->bindParam('prenom', $prenom);
            $curseur->bindParam('email', $email);
            $curseur->bindParam('login', $login);
            try {
                $curseur->execute();
                $reponse = $login;
                $ok = true;
            } catch (Exception $e) {
                $reponse = substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
            }
        }
        return $ok;
    }

    /**
     * Retourne la liste des membres
     * @return array
     */
    public static function getLesMembres() : array {
        $db = Database::getInstance();
        $sql = <<<EOD
            Select login, concat(nom, ' ' , prenom) as nomPrenom, email, autMail, photo, ifnull(telephone, 'Non communiqué') as telephone 
            From membre
            Order by nom, prenom;
EOD;
        $curseur = $db->query($sql);
        $lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        return $lesLignes;
    }

}