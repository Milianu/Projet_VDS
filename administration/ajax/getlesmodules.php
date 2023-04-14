<?php

/**
 *  Récupération des fonctions
 * Appel : script.js
 */

// chargement des ressources
require '../../class/class.database.php';

$sql = <<<EOD
        Select nom, repertoire, description
        From module
        Order by nom;
EOD;
$db = Database::getInstance();
$curseur = $db->query($sql);
$curseur->execute();
$lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
$curseur->closeCursor();

// Vérification de l'existence du fichier
$nb = count($lesLignes);
for ($i = 0; $i < $nb; $i++) {
    $repertoire = "../../" . $lesLignes[$i]['repertoire'];
    $lesLignes[$i]['present'] = is_dir($repertoire) ? 1 : 0;
}

echo json_encode($lesLignes);

