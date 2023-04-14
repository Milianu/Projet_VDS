<?php
/**
 *  Modification de l'autorisation de l'affichage de son email dans l'annuaire
 * Appel : fiche.js
 */


require '../../include/initialisation.php';
if (!isset($_SESSION['membre'])) exit;

// vérification des paramètres
if (!isset($_POST['autMail'])) {
    echo "Le paramètre attendu n'est pas transmis";
    exit;
}

// récupération des données transmises
$id = $_SESSION['membre']['id'];
$autMail = $_POST["autMail"];

// contrôle des données
$valide =  $autMail == 1 || $autMail == 0;

if (!$valide) {
    echo "La valeur d'un paramètre attendu n'est pas valide";
    exit;
}

// Demande de mise à jour
$erreur = "";
if (Profil::modifierColonne('autMail', $autMail, $id, $erreur))
    echo 1;
else
    echo $erreur;


