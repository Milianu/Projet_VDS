<?php

/**
 *  ajouter ou supprimer un administrateur
 * Appel : administration/index.js
 */


require '../../include/initialisation.php';
require '../../include/controleacces.php';

// contrôle de l'existence des paramètres attendus
if (!isset($_POST['idMembre']) || !isset($_POST['op'])) {
    echo "Paramètre manquant";
    exit;
}

// récupération des données transmises
$op = $_POST["op"];
$idMembre = $_POST["idMembre"];

// génération de la requête d'ajout d'un administrateur ou de suppression d'un administrateur op = 's'
if ($op === "A") {
    $sql = <<<EOD
	    insert into administrateur (idMembre) values (:idMembre)
EOD;
} else if ($op === "S") {
    $sql = <<<EOD
        delete from administrateur
	    where idMembre = :idMembre;
EOD;
} else {
    echo "Paramètre invalide";
    exit;
}
$db = Database::getInstance();
$curseur = $db->prepare($sql);
$curseur->bindParam('idMembre', $idMembre);
try {
    $curseur->execute();
    echo 1;
} catch (Exception $e) {
     echo substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
}