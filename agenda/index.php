<?php
// chargement des ressources
require '../include/initialisation.php';

// contrôle d'accès
require RACINE . '/include/controleacces.php';

// chargement des données
$lesLignes = Agenda::getLesEvenements();
if ($lesLignes === -1) {
    Std::traiterErreur("Échec lors de la lecture des données : " . Agenda::getError());
}

// chargement de l'interface
$titreFonction = "Gestion de l'agenda";
require RACINE . '/include/interface.php';


// Chargement des composants spécifiques
require RACINE . '/include/tablesorter.php';

// transfert des données côté client
$data = json_encode($lesLignes);
echo "<script>let data = $data</script>";