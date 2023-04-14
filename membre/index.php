<?php
/**
 * Affichage de l'ensemble des opérations de gestion conernant les membree
 */

require '../include/initialisation.php';
require '../include/controleacces.php';
$titreFonction = "Gestion des membres";
require RACINE . '/include/head.php';

?>
<script src="index.js"></script>
<div id="msg" class="m-3"></div>
<div class='row'>
    <div class="col-6 text-center">
        <div class="card">
            <div class="card-header">
                <a href="ajout.php"  class="btn btn btn-danger">Ajouter un nouveau membre</a>
            </div>
            <div class="card-body">
                3 informations uniquement à fournir : nom, prénom et adresse mail.
            </div>
        </div>
    </div>
    <div class="col-6 text-center ">
        <div class="card">
            <div class="card-header">
                <a href="consultation.php"  class="btn btn btn-danger">Liste des membres</a>
            </div>
            <div class="card-body">
                Affiche l'ensemble des membres
                Le tableau peut être ordonné sur certaines colonnes. il suffit de cliquer sur l'entête de la colonne.
            </div>
        </div>
    </div>
</div>


<?php require RACINE . '/include/pied.php'; ?>



