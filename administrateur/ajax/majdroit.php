<?php
/**
 *  ajout ou suppression dans la table droit(administrateur, repertoire)
 * Appel : administration/index.js
 */



require '../../include/initialisation.php';
require '../../include/controleacces.php';

// contrôle de l'existence des paramètres attendus
if (!isset($_POST['idMembre']) || !isset($_POST['repertoire']) || !isset($_POST['ajout'])) {
    echo "Paramètre manquant";
    exit;
}

// récupération des données transmises
$ajout = $_POST["ajout"];
$idMembre = $_POST["idMembre"];
$repertoire = $_POST["repertoire"];

// requeête d'ajout ou de suppression d'un droit en fonction de la valeur de $ajout
if ($_POST["ajout"] == 1) {
    $sql = <<<EOD
	    insert into droit (repertoire, idMembre) values 
		    (:repertoire, :idMembre)
EOD;
} else {
    $sql = <<<EOD
        delete from droit
	    where repertoire = :repertoire
        and idMembre = :idMembre;
EOD;
}
$db = Database::getInstance();
$curseur = $db->prepare($sql);
$curseur->bindParam('idMembre', $idMembre);
$curseur->bindParam('repertoire', $repertoire);
try {
    $curseur->execute();
    echo 1;
} catch (Exception $e) {
     echo substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
}
