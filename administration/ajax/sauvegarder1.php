<?php
$racine = $_SERVER['DOCUMENT_ROOT'];

$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbBase = 'vds';

$date = date('Y-m-d');
$fichier = "$racine/data/sauvegarde/$date.sql";

$cmd = "J:\wamp64\bin\mysql\mysql8.0.29\bin\mysqldump --host=$dbHost --user=$dbUser --password=$dbPassword $dbBase --databases --add-drop-database -R >$fichier";

// lancer la commande mysqldump contenu dans $cmd
system($cmd);

// le fichier est vide (taille 0) si la commande n'a pas fonctionné
if(filesize($fichier) === 0) {
    echo 'La sauvegarde a échoué : ';
    @unlink($fichier);
} else {
    echo json_encode($fichier);
}

