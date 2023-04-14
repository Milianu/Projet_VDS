<?php
// chargement des ressources
require '../include/initialisation.php';

// contrôle d'accès
require RACINE . '/include/controleacces.php';

// chargement de l'interface
// intervalle accepté pour la date de a course : dans plus de 6 jours mais dans l'annèe à venir
$titreFonction = "Ajout d'une course dans le répertoire";
$min = date("Y-m-d", strtotime("+ 6 day"));
$date = $min;
$max = date("Y-m-d", strtotime("+ 1 year"));
require '../include/interface.php';

// chargement des composants spécifiques
require RACINE . "/include/ckeditor.php";




