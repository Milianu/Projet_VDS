<?php
/**
 *  ajouter un nouvel adhérent
 * Appel : membre/ajout.js
 * Résultat : 1 ou message d'erreur
 * Remarque : en cas d'ajout dans la table membre, un mail au nouvel adhérent est envoyé
 */


require '../../include/initialisation.php';
require '../../include/controleacces.php';

// Vérification des paramètres attendus
if (!Controle::existe('nom', 'prenom', 'email')) {
    echo "Paramètre manquant";
    exit;
}

// récupération des valeurs transmises
$nom = strtoupper($_POST["nom"]);
$prenom = strtoupper($_POST["prenom"]);
$email = $_POST["email"];

// on vérifie si l'adhérent n'est pas déjà présent dans la table membre

// Création du membre

$reponse = "";
if (Membre::ajouter($nom, $prenom, $email, $reponse)) {

// envoi d'un mail pour lui indiquer la procédure d'activation de son compte

    $sujet = "Bienvenue à l'Amicale de Val de Somme";
    $message = <<<EOD
            Bonjour $prenom,
            <br>Vous venez d'adhérer à l'association sportive de l'Amicale du Val de Somme et nous vous en remercions.
            <br>L'association dispose d'un site sur lequel vous avez la possibilité de retrouver de nombreuses informations : <a href="https://www.valdesomme.net/">Le site</a> <br>
            <br>Le site propose un espace réservé aux membres où vous trouverez des informations aux adhérents (séances d’entrainement, rendez-vous et sorties diverses…). 
            <br>Pour y accéder, vous disposez d'un compte dont les paramètres de connexion sont les suivants : 
            <br>Login : $reponse
            <br>Mot de passe provisoire : 0000  
            <br>
            <br>Une fois la première connexion réalisée, vous serez automatiquement redirigé vers une page vous permettant de personnaliser votre mot de passe.
            <br>Ce mot de passe devra se composer d'au moins 8 caractères dont au moins une minuscule, une majuscule, un chiffre et un symbole
            <br>
            <br>L'association est aussi présente sur les réseaux sociaux : 
            <br>&nbsp;&nbsp;<a href="https://www.facebook.com/amicalevds">page Facebook du club</a>
            <br>&nbsp;&nbsp;<a href="https://www.facebook.com/Coursesdes4saisons/">page Facebook dédiée à l'organisation des 4 saisons d'Amiens Métropole</a>
            <br>
            <br>Vous pouvez aussi vous abonner au groupe Facebook de l'association <a href="https://www.facebook.com/groups/amicaleduvaldesomme">Groupe Facebook</a> 
            <br>
            <br>En pièce jointe, vous trouverez un document au format PDF vous indiquant la marche à suivre pour votre première connexion ainsi que les différentes fonctionnalités de l'espace membre.
            <br>
            <br>Bonne visite sur le site du Val de Somme.
            <br>
            <br>Le webmaster du site
EOD;

    $mail = new Mail();
    $reponse = $mail->envoyerAvec($email, $sujet, $message, RACINE . '/membre/ajax/site val de somme.pdf');
}
echo $reponse;


