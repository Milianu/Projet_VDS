<?php
/**
 *  Renvoyer dans le format json les coordonnées du membre connecté
 * Appel : profif/fiche.js  fonction init()
 */


require '../../include/initialisation.php';
if (!isset($_SESSION['membre'])) {
    Std::traiterErreurAjax("Vous devez vous connecter pour accéder à cette fonctionnalité");
}

$id = $_SESSION['membre']['id'];

$db = Database::getInstance();
$sql = <<<EOD
        Select id, nom, prenom, email, telephone, photo, autMail
        From membre
        Where id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('id', $id);
$curseur->execute();
$leMembre = $curseur->fetchObject();
$curseur->closeCursor();

// vérification de l'existence de la photo
if ($leMembre['photo']) {
    if (!file_exists( RACINE . '/data/photomembre/' . $leMembre['photo']))
        $leMembre['photo'] = null;
}
echo json_encode($leMembre);