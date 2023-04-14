<?php

class lien
{
    /**
     * Récupération des liens
     * @return array
     */
    public static function getLesLiens(): array
    {
        $db = Database::getInstance();
        $sql = <<<EOD
            Select DISTINCT id, nom, url, logo, actif
            From lien
            Order by actif desc, nom;
EOD;
        $curseur = $db->query($sql);
        $lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        return $lesLignes;
    }

    /**
     * Récupération du nom de l'ancien nom du logo pour pourvoir le renommer sur le serveur
     * @return array
     */
    public static function getLeNomLogo(int $id): array
    {
        $db = Database::getInstance();
        $sql = <<<EOD
            Select DISTINCT logo
            From lien
            where id = :id;
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('id', $id);
        $curseur->execute();
        $lesLignes = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        return $lesLignes;
    }

    /**
     * Récupération du nom, de l'url et du logo selon l'id transmis
     * @param string $lien
     * @return bool
     */
    public static function remplirLien(string $lien): array
    {
        $db = Database::getInstance();
        $sql = <<<EOD
            Select id, nom, url, logo
            From lien
            where id = :lien
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('lien', $lien);
        $curseur->execute();
        $lesLignes = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        return $lesLignes;
    }

    /**
     * Ajoute le lien avec son nom, son url, son logo et si il est actif ou non
     * @param string $nom
     * @param string $url
     * @param string $logo
     * @param bool $actif
     * @param string $reponse
     * @return bool
     */
    public static function ajouter(string $nom, string $url, string $logo, bool $actif, string &$reponse): bool
    {
        $ok = false;
        $sql = <<<EOD
            Select id
            From lien
            Where logo = :logo
            or url = :url
EOD;
        $db = Database::getInstance();
        $curseur = $db->prepare($sql);
        $curseur->bindParam('logo', $logo);
        $curseur->bindParam('url', $url);
        $curseur->execute();
        $ligne = $curseur->fetch();
        $curseur->closeCursor();
        if ($ligne)
            $reponse = "Ce membre existe déjà";
        else {
            // ajout dans la table lien
            $sql = <<<EOD
        insert into lien(nom, url, logo, actif)
        values (:nom, :url, :logo, :actif);
EOD;
            $curseur = $db->prepare($sql);
            $curseur->bindParam('nom', $nom);
            $curseur->bindParam('url', $url);
            $curseur->bindParam('logo', $logo);
            $curseur->bindParam('actif', $actif);
            try {
                $curseur->execute();
                $ok = true;
                echo 1;
            } catch (Exception $e) {
                $reponse = substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
            }
        }
        return $ok;
    }

    /**
     * Rend le lien actif ou non
     * @param string $id
     * @param bool $actif
     * @param string $reponse
     * @return bool
     */
    public static function modifierActif(string $id, bool $actif, string &$reponse): bool
    {
        $ok = false;
        $db = Database::getInstance();
        $sql = <<<EOD
            Update lien 
                Set actif = :actif
            where id = :id
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('id', $id);
        $curseur->bindParam('actif', $actif);
        try {
            $curseur->execute();
            $ok = true;
            echo 1;
        } catch (Exception $e) {
            $reponse = substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
        }
        return $ok;
    }

    /**
     * Modifie le lien
     * @param int $id
     * @param string $nom
     * @param string $url
     * @param string $nomlogo
     * @param string $reponse
     * @return bool
     */
    public static function modifier(int $id, string $nom, string $url, string $logo, string &$reponse): bool
    {
        $ok = false;
        $db = Database::getInstance();
        $sql = <<<EOD
            Update lien
                Set nom = :nom, url = :url, logo = :logo
            where id = :id
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('nom', $nom);
        $curseur->bindParam('url', $url);
        $curseur->bindParam('logo', $logo);
        $curseur->bindParam('id', $id);
        try {
            $curseur->execute();
            $ok = true;
            echo 1;
        } catch (Exception $e) {
            $reponse = substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
        }
        return $ok;
    }

    /**
     * Supprime le lien sélectionné
     * @param string $id
     * @param string $reponse
     * @return bool
     */
    public static function supprimer(string $id, string &$reponse): bool
    {
        $ok = false;
        $db = Database::getInstance();
        $sql = <<<EOD
            Delete from lien where id = :id
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('id', $id);
        try {
            $curseur->execute();
            $ok = true;
            echo 1;
        } catch (Exception $e) {
            $reponse = substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
        }
        return $ok;
    }
}