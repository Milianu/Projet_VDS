<?php
/**
 * ajout ou modification d'un module qui doit subir un contrôle d'accès
 * Appel : administration/script.js
 */

// chargement des ressources
define('RACINE', $_SERVER['DOCUMENT_ROOT']);
require '../../class/class.database.php';
require '../../class/class.controle.php';


// contrôle des paramètres attendus
if (!Controle::existe('repertoire', 'nom', 'description')) {
    echo "Données manquantes";
    exit;
}

// récupération des paramètres
$nom =   $_POST['nom'];
$repertoire= $_POST['repertoire'];
$description= $_POST['description'];

// contrôle de l'existence physique du répertoire
if (!is_dir(RACINE  . '/' . $repertoire)) {
    echo "Ce répertoire n'existe pas";
    exit;
}

// S'agit-il d'une ajout ou d'une modification
$sql = <<<EOD
   Select 1
   from module
   where repertoire = :repertoire;
EOD;
$db = Database::getInstance();
$curseur = $db->prepare($sql);
$curseur->bindParam('repertoire', $repertoire);
$curseur->execute();
$ligne = $curseur->fetch();
$curseur->closeCursor();
if ($ligne) {
    $reponse = "Modification prise en compte";
    // modification
    $sql = <<<EOD
    update module 
        set nom = :nom, description = :description 
        where repertoire = :repertoire;
EOD;
} else {
    // ajout
    $reponse = "Module ajouté";
    $sql = <<<EOD
    insert into module(nom, repertoire, description) 
        values (:nom, :repertoire, :description);
EOD;
}
$curseur = $db->prepare($sql);
$curseur->bindParam('repertoire', $repertoire);
$curseur->bindParam('nom', $nom);
$curseur->bindParam('description', $description);
try {
    $curseur->execute();
    echo json_encode($reponse);
} catch (Exception $e) {
    echo substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
}
