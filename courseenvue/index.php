<?php
// chargement des ressources
require '../include/initialisation.php';

// contrôle d'accès
require RACINE . '/include/controleacces.php';

// chargement des données
$lesLignes = CourseEnVue::getLesCourses();
if ($lesLignes === -1) {
    Std::traiterErreur("Échec lors de la lecture des données : " . CourseEnVue::getError());
}

// chargement de l'interface
$titreFonction = "Gestion des courses en vue";
require RACINE . '/include/interface.php';

// Chargement des composants spécifiques
require RACINE . '/include/tablesorter.php';

// transfert des données côté client
$data = json_encode($lesLignes);

echo $data;

echo "<script>let data = $data</script>";
