<?php
/**
 * Fournit la liste des membres
 * Appel : membre/consultation.js
 * Résultat :liste des membres au format json
 */

require '../../include/initialisation.php';
require '../../include/controleacces.php';

const REP_PHOTO = RACINE . '/data/logolien/';

// vérification de la transmission des paramètres
// Vérification des paramètres attendus
if (!Controle::existe('id', 'nom', 'url', 'logo')) {
    echo "Paramètre manquant";
    exit;
}

// récupération des données transmises
$id = trim($_POST['id']);
$nom = trim($_POST['nom']);
$url = trim($_POST['url']);
$logo = trim($_POST['logo']) . trim($_POST['extension']);
$imgChange = trim($_POST['imgChange']);

$ancienNom = substr(json_encode(lien::getLeNomLogo($id)), 9, -2);

$reponse = "";

// Si le logo a changer
if ($imgChange == 1) {
    if (!isset($_FILES['fichier'])) {
        echo "Demande incomplète";
        exit;
    } else {
        $tmp = $_FILES['fichier']['tmp_name'];
        $nomFichier = $_FILES['fichier']['name'];
        $type = $_FILES['fichier']['type'];
        $taille = $_FILES['fichier']['size'];

        // Définition des contraintes à respecter
        $tailleMax = 30 * 1024;
        $lesExtensions = ["jpg", "png"];
        $lesTypes = ["image/pjpeg", "image/jpeg", "x-png", "image/png"];

        // vérification de la taille
        if ($taille > $tailleMax) {
            echo "La taille du fichier dépasse la taille autorisée";
            exit;
        }

        // vérification de l'extension
        $extension = pathinfo($nomFichier, PATHINFO_EXTENSION);
        if (!in_array($extension, $lesExtensions)) {
            echo "Extension du fichier non acceptée";
            exit;
        }

        // vérification du type MIME
        $type = mime_content_type($tmp);
        if (!in_array($type, $lesTypes)) {
            echo "Type de fichier non accepté";
            exit;
        }

        if (lien::modifier($id, $nom, $url, $logo, $reponse)) {
            // suppression de l'ancien logo
            unlink(REP_PHOTO . $ancienNom);
            // copie sur le serveur du nouveau logo
            copy($tmp, REP_PHOTO . $logo);
        }
    }
} else {
    lien::modifier($id, $nom, $url, $logo, $reponse);
    rename(REP_PHOTO . $ancienNom, REP_PHOTO . $logo);
}

// réponse du serveur
echo $reponse;
