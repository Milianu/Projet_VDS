<?php

/**
 *  Suppression du téléphone du membre
 * Appel : fiche.js fonction supprimerTelephone()
 */


require '../../include/initialisation.php';
if (!isset($_SESSION['membre'])) exit;

$id = $_SESSION['membre']['id'];

// Demande'éffacement
$erreur = "";
if (Profil::effacerColonne('telephone', $id, $erreur))
    echo 1;
else
    echo $erreur;