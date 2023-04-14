<?php
/**
 * Affichage du jourrnal dont l'id est passée dans l'url
 */


$resultat = "";

$id = $_GET['id'];
$file = "../data/log/$id.log";

if (file_exists($file)) {
    $contenu = file_get_contents($file);
    $lesLignes = explode('\n', $contenu);
    foreach ($lesLignes as $ligne) {
        $resultat .= '<tr>';
        $lesCellules = explode('\t', $ligne);
        foreach ($lesCellules as $cellule) {
            $resultat .= '<td>' . utf8_encode($cellule) . '</td>';
        }
        $resultat .= '</tr>';
    }
}

$titreFonction = "Journal des $id" . "s";
require 'include/head.php';
?>
<div class='table-responsive mt-3'>
    <table class="table table-sm table-bordered table-hover">
        <thead>
        <tr>
            <th>Date</th>
            <th>Url</th>
            <th>Paramètre</th>
        </tr>
        </thead>
        <tbody>
        <?= $resultat ?>
        </tbody>
    </table>
</div>
