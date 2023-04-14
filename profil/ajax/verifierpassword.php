<?php
/**
 *  Vérification du mot de passe actuel dans le cadre de la procédure de changement du mot de passe
 * Appel : password.js fonction init.js
 */

require '../../include/initialisation.php';
if (!isset($_SESSION['membre'])) exit;

// contrôle de l'existence des données transmises
if (!isset($_POST['password'])) {
    echo "Paramètre manquant.";
    exit();
}

// récupérer les données transmises
$password = $_POST['password'];
$login = $_SESSION['membre']['login'];

// réalisation de la connexion
$ligne = Profil::getMembreByLogin($login);
// vérification du mot de passe
if ($ligne && $ligne['password'] === hash('sha256', $password)) {
    echo 1;
} else {
    echo "Mot de passe actuel erroné";
}
