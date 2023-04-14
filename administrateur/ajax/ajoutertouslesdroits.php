<?php

/**
 *  ajouter tous les droits pour l'administrateur dont l'id est transmis
 * Appel : index.js
 */


require '../../include/initialisation.php';
require '../../include/controleacces.php';

// contrôle de l'existence des paramètres attendus
if (!isset($_POST['idMembre'])) {
    echo "Paramètre manquant";
    exit;
}

$idMembre = $_POST['idMembre'];

// ajout de l'ensemble des droits : cela passe par la suppression des droits actuels
$db =  Database::getInstance();
$sql = <<<EOD
	delete from droit
	where idMembre = :idMembre;
	insert into droit(idMembre, repertoire) select :idMembre, repertoire from module;
EOD;
$curseur = $db->prepare($sql);
$curseur->execute($_POST);
echo 1;
