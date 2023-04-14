<?php

/**
 *  Contrôle et enregistrement de la photo du membre
 * Appel :  profil.js fonction modifierPhoto
 */

require '../../include/initialisation.php';
require RACINE . '/vendor/autoload.php';

use \Gumlet\ImageResize;

if (!isset($_SESSION['membre'])) exit;

$id = $_SESSION['membre']['id'];
// contrôle de l'existence des paramètres attendus
if (!isset($_FILES['fichier'])) {
    echo "Demande incomplète";
    exit;
}

// récupération des données transmises
$tmp = $_FILES['fichier']['tmp_name'];
$nomFichier = $_FILES['fichier']['name'];
$type = $_FILES['fichier']['type'];

// Définition des contraintes à respecter
$lesExtensions = ["jpg", "png", "gif"];
$lesTypes = ["image/pjpeg", "image/jpeg", "x-png", "image/gif", "image/png"];
$type = mime_content_type($tmp);

// vérification de l'extension
$extension = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));
if (!in_array($extension, $lesExtensions)) {
    echo "Extension du fichier non acceptée";
    exit;
}

// vérification du type MIME
if (!in_array($type, $lesTypes)) {
    echo "Type de fichier non accepté";
    exit;
}

// récupération de l'ancienne photo pour la supprimer

$id = $_SESSION['membre']['id'];

$ligne = Profil::getMembreById($id);
if ($ligne) {
    if ($ligne['photo']) @unlink(RACINE . '/data/photomembre/' . $ligne['photo']);
    // Prise en compte dans la table membre de la nouvelle photo
    // Demande de mise à jour
    $nomPhoto = strtolower($_SESSION['membre']['nomPrenom']) . '.' . $extension;
    $erreur = "";
    if (Profil::modifierColonne('photo', $nomPhoto, $id, $erreur)) {
        // le nom de la photo : nom + prenom + extension
        // Redimensionner l'image en 200 * 200 si dimension supérieure
        $lesDimensions = getimagesize($tmp);
        if ($lesDimensions[0] > 200 || $lesDimensions[1] > 200) {
            $image = new ImageResize($tmp);
            $image->crop(200, 200, true, ImageResize::CROPCENTER);
            $image->save(RACINE . '/data/photomembre/' . $nomPhoto);
        } else {
            copy($tmp, RACINE . '/data/photomembre/' . $nomPhoto);
        }
        echo json_encode($nomPhoto);
    } else {
        echo $erreur;
    }
} else {

    echo "Erreur innattendue, nous recherchons une solution au problème";
}
