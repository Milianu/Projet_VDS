<?php
/**
 *  ajouter une photo dans le répertoire data/phototheue
 * Appel : phototheque/index.js
 * Résultat : 1 ou message d'erreur
 */



require '../../include/initialisation.php';
require '../../include/controleacces.php';

const REP_PHOTO = RACINE . '/data/phototheque/';

// contrôle de l'existence des paramètres attendus
if (!isset($_FILES['fichier'])) {
    echo "Demande incomplète";
    exit;
}

// récupération des données transmises
$tmp = $_FILES['fichier']['tmp_name'];
$nomFichier = $_FILES['fichier']['name'];
$type = $_FILES['fichier']['type'];
$taille = $_FILES['fichier']['size'];


// Définition des contraintes à respecter
$tailleMax = 300 * 1024;
$lesExtensions = ["jpg", "png"];
$lesTypes = ["image/pjpeg", "image/jpeg", "x-png", "image/png"];

// vérification de la taille
if ($taille > $tailleMax) {
    echo "La taille du pdf dépasse la taille autorisée";
    exit;
}

// vérification de l'extension
$extension = pathinfo($nomFichier, PATHINFO_EXTENSION);
if (!in_array($extension, $lesExtensions)) {
    echo "Extension du pdf non acceptée";
    exit;
}

// vérification du type MIME
$type = mime_content_type($tmp);
if (!in_array($type, $lesTypes)) {
    echo "Type de pdf non accepté";
    exit;
}


// Ajout éventuel d'un suffixe sur le nom de la nouvelle photo en cas de doublon
$nom = pathinfo($nomFichier, PATHINFO_FILENAME);
$i = 1;
while (file_exists(REP_PHOTO . $nomFichier)) $nomFichier = "$nom(" . $i++ . ").$extension";

// copie sur le serveur
copy($tmp, REP_PHOTO . $nomFichier);
echo 1;


