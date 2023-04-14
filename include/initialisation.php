<?php
/**
 *  Mise en place de l'accès aux ressources
 * Appel depuis tous les scripts sauf ceux concernant la personnalisation du mot de passe.
 */

// définition du décalage horaire par défaut
date_default_timezone_set('Europe/Paris');

// Définition de la constante RACINE pour permettre un accès aux ressources par un adressage absolu
define('RACINE', $_SERVER['DOCUMENT_ROOT']);

// Chargement dynamique des classes
spl_autoload_register(function ($name) {
    $name = strtolower($name);
    if (file_exists(RACINE . "/class/class.$name.php"))
        require RACINE . "/class/class.$name.php";
    elseif (file_exists(RACINE . "/$name/class/class.$name.php"))
        require RACINE . "/$name/class/class.$name.php";
});

// Accès aux variables de session
session_start();

if (!isset($Ajax)) {
// contrôle prioritaire : si le mot de passe n'est pas personnalisé, l'utilisateur doit être redirigé vers le module de personnalisation
    if (isset($_SESSION['personnaliser'])) {
        header('location:/profil/personnalisationpassword.php');
        exit;
    }
}

// journalisations des requêtes
$nom = 'visiteur';
if (isset($_SESSION['membre'])) {
    $nom = $_SESSION['membre']['nomPrenom'];
}
//Std::tracerDemande('requete', $nom);
