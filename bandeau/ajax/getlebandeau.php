<?php

/**
 *  Récupération de la valeur du champ bandeau de la table parametre
 */



require '../../include/initialisation.php';

$db = Database::getInstance();
$sql = <<<EOD
    Select contenu    
    FROM bandeau 
EOD;
$curseur = $db->query($sql);
$contenu = $curseur->fetchColumn();
$curseur->closeCursor();

echo json_encode($contenu);
