<?php

/**
 *  renvoi les modules (repertoires) accessibles par l'administrateur dont l'id est transmis
 * Appel : administration/index.js
 */

require '../../include/initialisation.php';
require '../../include/controleacces.php';

// contrôle de l'existence des paramètres attendus
if (!isset($_POST['idMembre'])) {
    echo "Paramètre manquant";
    exit;
}

// récupération des droits (idFonction) de cet administrateur
$db = Database::getInstance();
$sql = <<<EOD
    Select  repertoire
    from droit 
    where idMembre = :idMembre
EOD;
$curseur = $db->prepare($sql);
$curseur->execute($_POST);
$lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
$curseur->closeCursor();
echo json_encode($lesLignes);
