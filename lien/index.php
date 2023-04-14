<?php
/**
 * Affichage de l'ensemble des opérations de gestion conernant les membree
 */

require '../include/initialisation.php';
require '../include/controleacces.php';
$titreFonction = "Gestion des liens";
require RACINE . '/include/head.php';

?>
<script src="index.js"></script>
<div id="msg" class="m-3"></div>
<div class='row'>
    <div class="col-6 text-center">
        <div class="card">
            <div class="card-header">
                <a href="ajout.php" class="btn btn btn-danger">Ajouter un nouveau lien</a>
            </div>
            <div class="card-body">
                5 informations à fournir : nom, url, image, le nom l'image et si le lien sera actif.
            </div>
        </div>
    </div>
    <div class="col-6 text-center">
        <div class="card">
            <div class="card-header">
                <a href="modif.php" class="btn btn-success">Modifier un lien</a>
            </div>
            <div class="card-body">
                4 informations à fournir : nom, url, image, le nom l'image.
            </div>
        </div>
    </div>
</div>
<div id='lesCartes'></div>


<?php require RACINE . '/include/pied.php'; ?>



