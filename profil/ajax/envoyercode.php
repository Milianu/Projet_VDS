<?php

/**
 *  Envoyer par mail un code à 6 chiffre afin de valider la demande de réinitialisation du mot de passe
 * Appel : profil
 * Résultat : 1 ou message d'erreur
 */

require '../../include/initialisation.php';

// vérification des données attendues
if (!isset($_POST['login'])) {
    echo "Paramètre manquant";
    exit;
}

// récupération des données transmises
$login = $_POST["login"];

// Récupération du membre correspondant
$sql = <<<EOD
    SELECT id, nom, prenom, email  
    FROM membre
    Where login = :login
EOD;
$db = Database::getInstance();
$curseur = $db->prepare($sql);
$curseur->bindParam('login', $login);
$curseur->execute();
$ligne = $curseur->fetch();
$curseur->closeCursor();
if (!$ligne) {
    echo "Ce login n'existe pas";
    exit;
}

$id = $ligne['id'];
$prenom = $ligne['prenom'];
$nom = $ligne['nom'];
$email = $ligne['email'];

// génération et sauvegarde du code de réinitialisation dans une variable de session
$code = "";
for ($i = 1; $i <= 6; $i++){
    $code .= rand(0,9);
}
$_SESSION['code'] = $code;

// la variable de session est associée à un cookie afin de limiter sa durée de vie à 5 minutes
setcookie(/*Son nom*/ "code", /*Sa valeur*/1, /*Son temps de vie*/ time() + 300, /*De où il est disponible*/ '/');

// envoi du mail
$sujet = "Réinitialisation de votre mot de passe ";
$contenu = <<<EOD
            Bonjour $prenom $nom, 
            <br> Nous avons reçu une demande de réinitialisation du mot de passe réalisée depuis notre site.
           
            <br>Votre code de confirmation se trouve ci-dessous, saisissez-le dans la fenêtre ouverte de votre navigateur et 
            indiquez votre nouveau de passe en le confirmant.
             <br>
            Votre code de confirmation  :  <strong>$code</strong>
            <br>
            Attention : ce code n'est valable que pendant 5 minutes.
            <br>  
            <br>Si vous n'avez pas fait cette demande, vous pouvez ignorer cet e-mail.
            <br><br>
            Cordialement
            <br>Amicale du Val de Somme
EOD;

$mail = new Mail();
$mail->envoyer($email, $sujet, $contenu);

echo json_encode($code);



