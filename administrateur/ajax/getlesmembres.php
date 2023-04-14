<?php

/**
 *  récupération des membres qui ne sont pas des administrateurs
 * Appel : administration/index.js
 */

require '../../include/initialisation.php';
require '../../include/controleacces.php';

// récupération des membres non administrateurs
$db = Database::getInstance();
$sql = <<<EOD
        Select id, concat(nom, ' ', prenom) as nom
        from membre where id not in (select idMembre from administrateur)  
        order by nom; 
EOD;
$curseur = $db->query($sql);
$lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
$curseur->closeCursor();

echo json_encode($lesLignes);
