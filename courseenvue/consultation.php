<?php
// chargement des ressources
require '../include/initialisation.php';

// chargement des données
$lesLignes = CourseEnVue::getLesCoursesAVenir();

if ($lesLignes === -1) {
    Std::traiterErreur("Échec lors de la lecture des données : " . CourseEnVue::getError());
}

// chargement de l'interface
$titreFonction = "Consultation des courses à venir";
require RACINE . '/include/interface.php';

// Transfert des données côté client
$data = json_encode($lesLignes);
echo "<script>let data = $data</script>";
