<?php

/**
 *  Suppression d'un module dans le contrôle d'accès
 *  Appel : module.js
 * Paramètre : repertoire
 */


// vérification des paramètres attendus
if (!isset($_POST['repertoire']) ) {
    echo "Demande incomplète";
    exit;
}
$repertoire = $_POST['repertoire'];

// chargement des ressources
require '../../class/class.database.php';


// suppression
$sql = <<<EOD
   DELETE FROM module
   WHERE repertoire = :repertoire;
EOD;
$db = Database::getInstance();
$curseur = $db->prepare($sql);
$curseur->bindParam('repertoire', $repertoire);
try {
    $curseur->execute();
    echo 1;
} catch (Exception $e) {
    echo substr($e->getMessage(), strrpos($e->getMessage(), '#') + 1);
}

