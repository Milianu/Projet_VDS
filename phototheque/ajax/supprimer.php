<?php

/**
 *  supprimer une photo présente dans la photothèque
 * Appel : phototheque/index.js
 * Résultat : 1 ou message d'erreur
 */


require '../../include/initialisation.php';
require '../../include/controleacces.php';

$db = Database::getInstance();
const REP_PHOTO = RACINE . '/data/phototheque/';

// vérification de la transmission des paramètres
if (!isset($_POST['nomFichier'])) {
    echo "Le paramètre n'est pas transmis";
    exit;
}

// vérification de la valeur
$nomFichier = $_POST['nomFichier'];
if (strlen($nomFichier) === 0) {
    echo "Le paramètre n'est pas renseigné";
    exit;
}

$fichier = REP_PHOTO . $nomFichier;

// vérification de l'extension
$lesExtensions = ["jpg", "png"];
$extension = pathinfo($fichier, PATHINFO_EXTENSION);
if (!in_array($extension, $lesExtensions)) {
    echo "Demande refusée, le fichier ne porte pas une extension autorisée : $extension";
    exit;
}

// vérification de l'existence du fichier

if (!file_exists($fichier)) {
    echo "Le fichier $nomFichier n'existe pas";
    exit;
}

// suppression du fichier
unlink ($fichier);

// réponse du serveur
echo 1;


