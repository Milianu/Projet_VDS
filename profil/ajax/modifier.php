<?php
/**
 *  Modification par le membre des informations personnelles : telephone et autorisation
 * Appel : fiche.js
 */


require '../../include/initialisation.php';
if (!isset($_SESSION['membre'])) exit;

// vérification des paramètres
if (!Controle::existe('telephone', 'autMail')) {
    echo "Données manquantes";
    exit;
}

// récupération des données transmises
$telephone = $_POST['telephone'];
$id = $_SESSION['membre']['id'];
$autMail = $_POST["autMail"];

// contrôle des données
$valide =  (empty($telephone) || Controle::formatValide($telephone, 'tel'))
    && ($autMail == 1 || $autMail == 0);

if (!$valide) {
    echo "La valeur d'un des paramètres n'est pas valide";
    exit;
}

// requête de mise à jour
$sql = <<<EOD
    Update membre 
        set autMail = :autMail
EOD;

$sql .= empty($telephone) ? ",telephone = null" : ",telephone = :telephone";
$sql .= " where id = :id";

$db = Database::getInstance();
$curseur = $db->prepare($sql);
$curseur->bindParam('id', $id);
$curseur->bindParam('autMail', $autMail);
if (!empty($telephone)) $curseur->bindParam('telephone', $telephone);
try {
    $curseur->execute();
    echo 1;
} catch (Exception $e) {
     echo substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
}


