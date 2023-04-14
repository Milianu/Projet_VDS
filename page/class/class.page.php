<?php

class Page
{

    public static function getPageById(int $id): array
    {
        $db = Database::getInstance();
        $sql = <<<EOD
            SELECT contenu, nom  
            FROM page
            WHERE id = :id; 
EOD;
        $curseur = $db->prepare($sql);
        $curseur->bindParam('id', $id);
        $curseur->execute();
        $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        return $ligne;
    }

    public static function getLesPages(): array
    {
        $db = Database::getInstance();
        $sql = <<<EOD
          Select id, nom, contenu 
          from page
          order by nom;
EOD;
        $curseur = $db->query($sql);
        $lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        return $lesLignes;
    }

    public static function update(int $id, string $nom, string $contenu,  string &$erreur): bool {
        $db = Database::getInstance();
        $ok = true;
        $erreur = "";
        $sql = <<<EOD
          UPDATE page
          SET nom =:nom, contenu = :contenu 
          WHERE id = :id;
EOD;
        $db = Database::getInstance();
        $curseur = $db->prepare($sql);
        $curseur->bindParam('contenu', $contenu);
        $curseur->bindParam('nom', $nom);
        $curseur->bindParam('id', $id);
        try {
            $curseur->execute();
        } catch (Exception $e) {
            $ok = false;
            $erreur = substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
        }
        return $ok;

    }

}
