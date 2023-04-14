<?php
/**
 *  ajouter une photo dans le répertoire data/phototheue
 * Appel : phototheque/index.js
 * Résultat : 1 ou message d'erreur
 */


require '../../include/initialisation.php';
require '../../include/controleacces.php';

const REP_PHOTO = RACINE . '/data/logolien/';

// Vérification des paramètres attendus
if (!Controle::existe('nom', 'url', 'logo', 'actif')) {
    echo "Paramètre manquant";
    exit;
}
if (!isset($_FILES['fichier'])) {
    echo "Demande incomplète";
    exit;
}

// récupération des données transmises
$nom = trim($_POST['nom']);
$url = trim($_POST['url']);
$logo = trim($_POST['logo']);
$actif = trim($_POST['actif']);
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

// Ajout de l'extension au nom du logo
$logo = $logo . "." . $extension;
$logo = mb_strtolower($logo);

$reponse = "";
if (lien::ajouter($nom, $url, $logo, $actif, $reponse)) {
    // copie sur le serveur
    copy($tmp, REP_PHOTO . $logo);
}
echo $reponse;


