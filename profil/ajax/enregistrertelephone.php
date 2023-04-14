<?php
/**
 *  Enregistrement du téléphone du membre
 *  Appel : fiche.js
 */

require '../../include/initialisation.php';
if (!isset($_SESSION['membre'])) exit;

// vérification des paramètres
if (!isset($_POST['telephone'])) {
    echo "Le paramètre attendu n'est pas transmis";
    exit;
}

// récupération des données transmises
$telephone = $_POST['telephone'];
$id = $_SESSION['membre']['id'];

// contrôle des données
$valide =  Controle::formatValide($telephone, 'tel');

if (!$valide) {
    echo "La valeur d'un paramètre attendu n'est pas valide";
    exit;
}

// Demande de mise à jour
$erreur = "";
if (Profil::modifierColonne('telephone', $telephone, $id, $erreur))
    echo 1;
else
    echo $erreur;
