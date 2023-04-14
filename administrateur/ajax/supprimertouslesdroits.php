<?php
/**
 *  suppression de tous les droits de l'administrateur dont l'id est passé en paramètre
 * Appel : administration/index.js
 */

require '../../include/initialisation.php';
require '../../include/controleacces.php';

// contrôle de l'existence des paramètres attendus
if (!isset($_POST['idMembre'])) {
    echo "Paramètre manquant";
    exit;
}

// suppression de tous les droits de l'administrateur, il reste cependant considéré comme administrateur
$sql = <<<EOD
	delete from droit
	where idMembre = :idMembre
EOD;
$db = Database::getInstance();
$curseur = $db->prepare($sql);
$curseur->execute($_POST);
echo 1;
