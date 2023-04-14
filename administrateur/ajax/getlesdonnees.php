<?php
/**
 *  renvoie l'id et le nom prénom des administrateur et l'id et la description des fonctions
 * Appel : administration/index.js
 */

require '../../include/initialisation.php';
require '../../include/controleacces.php';
$db = Database::getInstance();

// récupération des administrateurs
$sql = <<<EOD
        Select membre.id, concat(nom, ' ', prenom) as nom
        from membre join administrateur on membre.id = administrateur.idMembre
        order by nom; 
EOD;
$curseur = $db->query($sql);
$lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
$curseur->closeCursor();
$lesDonnees['lesAdministrateurs'] = $lesLignes;

// récupération des modules
$sql = <<<EOD
        Select repertoire,  nom, description
        from module
        order by nom;
EOD;
$curseur = $db->query($sql);
$lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
$curseur->closeCursor();
$lesDonnees['lesModules'] = $lesLignes;

echo json_encode($lesDonnees);
