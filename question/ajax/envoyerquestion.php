<?php
/**
 *  envoi du message déposé sur la boîte mail de contact
 */

require '../../include/initialisation.php';

// récupération des données transmises
$nomPrenom = $_POST["nomPrenom"];
$email = $_POST["email"];
$message = nl2br($_POST["message"]);

$sujet = "Nouvelle question posée sur le site du Val de Somme";
$contenu = <<<EOD
            Bonjour,
            <br>$nomPrenom a posé une question sur le site du Val de Somme : <br>
            <br>$message <br>
            <br><br>
            Le site du Val de Somme
EOD;

$mail = new Mail();
$mail->setExpediteur($email);
$reponse = $mail->envoyer('guy.verghote@free.fr', $sujet, $contenu);

if ($reponse === 0 )
    echo "Désolé, une erreur est survenu lors de l'envoi de votre message par mail";
else
    echo 1;


