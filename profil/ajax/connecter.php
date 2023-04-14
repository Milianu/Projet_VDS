<?php
/**
 *  Vérification des identifiants de connexion
 *            Mémorisation des coordonnées du membre dans des variables de session
 *            Ajout d'un cookie pour rester connecté
 *            Comptabilisation de la connexion dans la table membre et dans le fichier connexion.log ou  erreur.log
 * Appel     : profil/connexion.php
 * Résultat  : 1 ou message d'erreur
 */

require '../../include/initialisation.php';
// Vérification des données attendues
if (!Controle::existe('login', 'password', 'memoriser')) {
    echo "Paramètres manquants";
}

// récupération des données
$login = $_POST["login"];
$password = $_POST["password"];
$memoriser = $_POST["memoriser"];

if ($password === '0000') {
    $_SESSION['personnaliser'] = 1;
}

// contrôle
// vérification du login
$ligne = Profil::getMembreByLogin($login);

if ($ligne && $ligne['password'] === hash('sha256', $password)) {
    // création de la session
    $_SESSION['membre']['id'] = $ligne['id'];
    $_SESSION['membre']['login'] = $ligne['login'];
    $_SESSION['membre']['nomPrenom'] = $ligne['prenom'] . ' ' . $ligne['nom'];

    if ($memoriser === '1') {
        // $empreinte = hash('sha256', $ligne['prenom'] . $login . $ligne['nom']);
        $empreinte = hash('sha256', $ligne['prenom'] . $login . $ligne['nom'] . $_SERVER['HTTP_USER_AGENT']);
        $option['expires'] = time() + 3600 * 24 * 7;
        $option['path'] = '/';
        $option['httponly'] = true;
        setcookie('seSouvenir', $empreinte, $option);
    }

    if ($password === '0000') {
        echo json_encode('personnalisationpassword.php');
    } elseif (isset($_SESSION['url'])) {
        echo json_encode($_SESSION['url']);
        unset($_SESSION['url']);
    } else {
        echo json_encode('/index.php');
    }
} else {
    echo "Il y a une erreur dans votre saisie. <br> Veuillez vérifier les informations.";
}



