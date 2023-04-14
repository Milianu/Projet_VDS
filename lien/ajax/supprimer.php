<?php

/**
 *  supprimer une photo présente dans la photothèque
 * Appel : phototheque/index.js
 * Résultat : 1 ou message d'erreur
 */


require '../../include/initialisation.php';
require '../../include/controleacces.php';

$db = Database::getInstance();
const REP_PHOTO = RACINE . '/data/logolien/';

// vérification de la transmission des paramètres
if (!isset($_POST['nomlogo'])) {
    echo "Le paramètre n'est pas transmis";
    exit;
}
if (!isset($_POST['id'])) {
    echo "Le paramètre n'est pas transmis";
    exit;
}

// vérification de la valeur
$id = trim($_POST['id']);
$nomlogo = $_POST['nomlogo'];
if (strlen($nomlogo) === 0) {
    echo "Le paramètre n'est pas renseigné";
    exit;
}

$fichier = REP_PHOTO . $nomlogo;

// vérification de l'extension
$lesExtensions = ["jpg", "png"];
$extension = pathinfo($fichier, PATHINFO_EXTENSION);
if (!in_array($extension, $lesExtensions)) {
    echo "Demande refusée, le fichier ne porte pas une extension autorisée : $extension";
    exit;
}

// vérification de l'existence du fichier
if (!file_exists($fichier)) {
    echo "Le fichier $fichier n'existe pas";
    exit;
}

// suppression du fichier
$reponse = "";
if (lien::supprimer($id, $reponse)) {
    unlink($fichier);
}
// réponse du serveur
echo $reponse;


