<?php
/**
 * Mise à jour de la valeur du champ bandeau de la table paramètre
 */



require '../../include/initialisation.php';

if (!isset($_POST['valeur'])) {
    echo "Paramètre manquant";
    exit;
}

// récupération des paramètres
$valeur = $_POST['valeur'];

// modification de la valeur du bandeau
$db = Database::getInstance();
if (empty($valeur)) {
    $sql = <<<EOD
        UPDATE bandeau SET contenu = null 
EOD;
    try {
        $db->exec($sql);
        echo 1;
    } catch (Exception $e) {
         echo substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
    }
} else {
    $sql = <<<EOD
        UPDATE bandeau SET contenu = :valeur 
EOD;
    $curseur = $db->prepare($sql);
    $curseur->bindParam('valeur', $valeur);
    try {
        $curseur->execute();
        echo 1;

    } catch (Exception $e) {
         echo substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
    }
}

