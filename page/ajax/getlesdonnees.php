<?php

/**
 *  Retourner le contenu des pages statiques
 * Appel : page/index.js
 * Résultat : le contenu de la table page dans le format JSON
 */


require '../../include/initialisation.php';
require '../../include/controleacces.php';


// envoi de l'ensemble des pages
$sql = <<<EOD
          Select id, nom, contenu 
          from page
          order by nom;
EOD;
$db = Database::getInstance();
$curseur = $db->query($sql);
$lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
$curseur->closeCursor();

echo json_encode($lesLignes);
