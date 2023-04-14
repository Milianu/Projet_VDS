<?php

// Affichage de l'interface de modification
// donnée transmise par la méthode GET : id

// chargement des ressources
require '../include/initialisation.php';

// contrôle d'accès
require RACINE . '/include/controleacces.php';

// récupération de l'enregistrement correspondant
$courseenvue = new CourseEnVue();
$ligne = $courseenvue->rechercher();

if (!$ligne) {
    Std::traiterErreur($table->getValidationMessage());
}

// chargement de la page
// intervalle accepté pour la date de la course : dans l'annèe à venir
$min = date("Y-m-d");
$max = date("Y-m-d", strtotime("+ 1 year"));

$titreFonction = "Modification d'une course dans le registre";
require RACINE . '/include/interface.php';

// chargement des composants spécifiques
require RACINE . "/include/ckeditor.php";

// transfert des données côté client
$data = json_encode($ligne);
echo "<script>let data = $data </script>";
