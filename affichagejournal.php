<?php
/**
 * Affichage du jourrnal dont l'id est passée dans l'url
 */


$resultat = "";

$titreFonction = "";
require  'include/head.php';
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
