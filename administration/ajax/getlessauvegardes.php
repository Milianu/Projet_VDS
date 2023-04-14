<?php
$lesExtensions = ["sql"];

$lesFichiers = [];

$repertoire = opendir(  '../../data/sauvegarde/');
$fichier = readdir($repertoire);
while ($fichier !== false) {
    $extension = pathinfo($fichier)['extension'];
    if ($fichier !== "." && $fichier !== ".." &&  in_array(strtolower($extension), $lesExtensions)) {
        $lesFichiers[] = $fichier;
    }
    $fichier = readdir($repertoire);
}
closedir($repertoire);

// inversion de l'ordre des fichiers
rsort($lesFichiers);

echo json_encode($lesFichiers);