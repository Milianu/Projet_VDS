<?php

/**
 *  personnalisation du mot de passe du membre connecté avec un password à 0000
 * Appel : personnaliserpassword.js - fonction modifie()
 * Résultat : 1 ou message d'erreur
 * Remarque : Ce script ne doit pas utiliser initialisation.php
 */

// attention : la classe Mail utilise la constante racine


session_start();
define('RACINE', $_SERVER['DOCUMENT_ROOT']);

include '../../class/class.database.php';


// contrôle des données attendues
if(!isset($_POST['password'])) {
    echo "Donnée manquante";
    exit;
}
// récupération des données
$id = $_SESSION['membre']['id'];
$password = $_POST["password"];

// vérification : le membre est bien connecté et il doit bien personnaliser son mot de passe


// contrôle du respect des règles de sécurité sur le mot de passe
if (!preg_match('#^(?=.*[a-z]+)(?=.*[A-Z]+)(?=.*[0-9]+)(?=.*[()=+?!\'$.%;:@&*\#/\\-]+).{8,}$#', $password)) {
    echo "Le mot de passe doit comporter 8 caractères minimum, dont une minuscule, une majuscule un chiffre et un caractère spécial. ";
    exit;
}

// Mise à jour du mot de passe
$sql = <<<EOD
   update membre
    	set password = sha2(:password, 256)
   where id = :id;
EOD;
$db = Database::getInstance();
$curseur = $db->prepare($sql);
$curseur->bindParam('id', $id);
$curseur->bindParam('password', $password);

try {
    $curseur->execute();
    // suppression de l'obligation de personnaliser son mot de passe
    unset($_SESSION['personnaliser']);
    if (isset($_SESSION['url'])) {
        echo json_encode($_SESSION['url']);
        unset($_SESSION['url']);
    } else {
        echo json_encode('/index.php');
    }
} catch (Exception $e) {
    echo substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
}
