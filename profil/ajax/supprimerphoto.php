<?php

/**
 *  Suppression de la photo du membre
 * Appel : fiche.js fonction supprimerPhoto()
 */


require '../../include/initialisation.php';
if (!isset($_SESSION['membre'])) exit;

$id = $_SESSION['membre']['id'];



// récupérer le nom de la photo pour le supprimer physiquement
$db = Database::getInstance();
$sql = <<<EOD
   Select photo
   FROM membre
   WHERE id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('id', $id);
$curseur->execute();
$photo = $curseur->fetchColumn();
$curseur->closeCursor();

// Effacer la valeur du champ photo dans la table membre
$sql = <<<EOD
    update membre
        set photo = null
    where id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('id', $id);
try {
    $curseur->execute();
    // suppression de la photo
    @unlink(RACINE . '/data/photomembre/' . $photo);
    echo 1;
} catch (Exception $e) {
     echo substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
}