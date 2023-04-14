<?php
/**
 * Fournit la liste des membres
 * Appel : membre/consultation.js
 * Résultat :liste des membres au format json
 */

require '../../include/initialisation.php';
require '../../include/controleacces.php';

// vérification de la transmission des paramètres
if (!isset($_POST['lien'])) {
    echo "Le paramètre n'est pas transmis";
    exit;
}

$lien = trim($_POST['lien']);

// récupération des informations sur les liens
echo json_encode(Lien::remplirLien($lien));