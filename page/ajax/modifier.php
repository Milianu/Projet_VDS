<?php

/**
 *  mise à jour d'un enregistrement de la table page(id, nom, contenu)
 * Appel : page/index.js
 * Résultat : 1 ou message d'erreur
 */

require '../../include/initialisation.php';
require '../../include/controleacces.php';

// vérification des paramètres
if (!isset($_POST['id']) || !isset($_POST['contenu']) || !isset($_POST['nom'])) {
    echo "Données manquantes";
    exit;
}

// récupération des paramètres
$id = $_POST['id'];
$nom = $_POST['nom'];
$contenu = $_POST['contenu'];

// mise à jour du champ contenu
$sql = <<<EOD
          UPDATE page
          SET nom =:nom, contenu = :contenu 
          WHERE id = :id;
EOD;
$db = Database::getInstance();
$curseur = $db->prepare($sql);
$curseur->bindParam('contenu', $contenu);
$curseur->bindParam('nom', $nom);
$curseur->bindParam('id', $id);
try {
    $curseur->execute();
    echo 1;
} catch (Exception $e) {
     echo substr($e->getMessage(),strrpos($e->getMessage(), '#') + 1);
}
