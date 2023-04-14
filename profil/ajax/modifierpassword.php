<?php

/**
 *  Enregistrement du nouveau  mot de passe pour le membre connecté
 * Appel :  profil/password.js  fonction modifier
 */


require '../../include/initialisation.php';
if (!isset($_SESSION['membre'])) exit;

// contrôle des données attendues
if(!Controle::existe('passwordActuel', 'password')) {
    echo "Données manquante";
    exit;
}
// récupération des données
$id = $_SESSION['membre']['id'];
$passwordActuel = $_POST["passwordActuel"];
$password = $_POST["password"];


// vérification du mot de passe actuel
$sql = <<<EOD
   Select 1 from membre 
   where password = sha2(:password, 256)
   and id = :id;
EOD;
$db = Database::getInstance();
$curseur = $db->prepare($sql);
$curseur->bindParam('id', $id);
$curseur->bindParam('password', $passwordActuel);
$curseur->execute();
$ligne = $curseur->fetch(PDO::FETCH_ASSOC);
$curseur->closeCursor();
 if (!$ligne) {
    echo "Le mot de passe actuel saisie n'est pas le bon $passwordActuel";
    exit;
}


// Mise à jour du mot de passe
$sql = <<<EOD
   update membre
    	set password = sha2(:password, 256)
   where id = :id;
EOD;
$curseur = $db->prepare($sql);
$curseur->bindParam('id', $id);
$curseur->bindParam('password', $password);

try {
    $curseur->execute();
    echo 1;
} catch (Exception $e) {
    echo substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
}
