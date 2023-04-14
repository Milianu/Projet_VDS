<?php
/**
 * Fournit la liste des membres
 * Appel : membre/consultation.js
 * Résultat :liste des membres au format json
 */

require '../../include/initialisation.php';
require '../../include/controleacces.php';

// vérification de la transmission des paramètres
if (!isset($_POST['actif'])) {
    echo "Le paramètre n'est pas transmis";
    exit;
}
if (!isset($_POST['id'])) {
    echo "Le paramètre n'est pas transmis";
    exit;
}

// récupération des données transmises
$id = trim($_POST['id']);
$actif = trim($_POST['actif']);

if ($actif == 1)
    $actif = 0;
elseif ($actif == 0)
    $actif = 1;

$reponse = "";
lien::modifierActif($id, $actif, $reponse);

// réponse du serveur
echo $reponse;
