<?php
/**
 *  Récupérer l'ensemble des photos présentes dans le répertoire data/phototheque
 * Appel : phototheque/index.js
 * Résultat : tableau au format json contenant le nom des fichiers ["nom1", "nom2" ...]
 */


require '../../include/initialisation.php';
require '../../include/controleacces.php';

const REP_PHOTO = RACINE . '/data/phototheque/';

// lecture du répertoire afin de récupérer les photos
$lesExtensions = ['png', 'jpg'];
$lesFichiers = [];
$repertoire = opendir(REP_PHOTO);
$fichier = readdir($repertoire);
while ($fichier !== false) {
    $extension = pathinfo($fichier, PATHINFO_EXTENSION);
    if ($fichier != "." && $fichier != ".." && (in_array(strtolower($extension), $lesExtensions))) {
        $lesFichiers[] = $fichier;
    }
    $fichier = readdir($repertoire);
}
closedir($repertoire);
echo json_encode($lesFichiers);

