<?php
/**
 * Interface d'ajout d'un membre
 */

require '../include/initialisation.php';
require '../include/controleacces.php';
$titreFonction =  "Nouveau membre";
require RACINE . '/include/head.php';
?>

<script src="ajout.js"></script>

<div class="border p-3 mt-3">
    <div id="msg" class="m-3"></div>
    <div class="row">
        <div class="col-md-6 col-12">
            <label for="nom" class="col-form-label">Nom </label>
            <input id="nom"
                   type="text"
                   class="form-control ctrl  "
                   required
                   maxlength='30'
                   pattern="^[A-Za-z]([A-Za-z ]*[A-Za-z])*$"
                   autocomplete="off">
            <div class='messageErreur'></div>
        </div>
        <div class="col-md-6 col-12">
            <label for="prenom" class="col-form-label">Prénom </label>
            <input id="prenom"
                   type="text"
                   class="form-control ctrl "
                   required
                   maxlength='50'
                   pattern="^[A-Za-z]([A-Za-z ]*[A-Za-z])*$"
                   autocomplete="off">
            <div class='messageErreur'></div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6 col-12">
            <label for="email" class="col-form-label">Adresse e-mail</label>
            <input type="text"
                   id="email"
                   required
                   pattern="^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*[\.][a-zA-Z]{2,4}$"
                   class="form-control ctrl "
                   autocomplete="off">
            <div class='messageErreur'></div>
        </div>
    </div>
    <div class="text-center">
        <button id='btnAjouter' class="btn btn btn-danger">Ajouter</button>
    </div>
</div>
<?php require RACINE . '/include/pied.php'; ?>


