<?php

/**
 *  Envoie par mail du login de l'utilisateur
 *  Appel : oublilogin.js
 */

require '../../include/initialisation.php';

// vérification des données attendues
if (!controle::existe('nom', 'prenom')) {
    echo "Paramètre manquant";
    exit;
}

// récupération des données transmises
$nom = $_POST["nom"];
$prenom = $_POST["prenom"];

// contrôle des données
$erreur = false;
if (empty($nom)) {
    echo "\nLe nom doit être renseigné.";
    $erreur = true;
} elseif (!preg_match("/[A-Za-z][ A-Za-z-]*[A-Za-z]$/", $nom)) {
    echo "\nLe nom ne doit comporter que des lettres non accentuées et des espaces";
    $erreur = true;
} elseif ( mb_strlen($nom) > 30) {
    echo "\nLe nom ne peut dépasser 30 caractères";
    $erreur = true;
}

if (empty($prenom)) {
    echo "\nLe prénom doit être renseigné.";
    $erreur = true;
} elseif (!preg_match("/^[A-Za-z][ A-Za-z-]*[A-Za-z]$/", $nom)) {
    echo "\nLe prénom ne doit comporter que des lettres non accentuées et des espaces";
    $erreur = true;
} elseif ( mb_strlen($nom) > 50) {
    echo "\nLe prénom ne peut dépasser 50 caractères";
    $erreur = true;
}

if($erreur) exit;

// Récupération des informations sur le membre : login et email
$ligne = Profil::getMembreByNomPrenom($nom, $prenom);
if($ligne) {
   $login = $ligne['login'];
   $email = $ligne['email'];
   // envoi du mail
    $sujet = "Votre login sur le site de l'Amicale du Val de Somme";
    $contenu = <<<EOD
            Bonjour $prenom $nom, 
            <br> Nous avons reçu une demande concernant l'oubli de votre login sur notre site .
            <br>
            <br> Votre login est le suivant :  <strong>$login</strong>
            <br>
            <br>  
            <br>Si vous n'êtes pas à l'origine de cette demande, veuillez prévenir rapidement le webmaster du site.
            <br><br>
            Cordialement
            <br>Amicale du Val de Somme
EOD;

    $mail = new Mail();
    $mail->envoyer($email, $sujet, $contenu);

    echo 1;
} else {
    echo "Ce membre n'existe pas";
}