<?php
/**
 * Interface d'ajout d'un lien vers un autre site
 */

require '../include/initialisation.php';
require '../include/controleacces.php';
$titreFonction = "Modifier un lien";
require RACINE . '/include/head.php';
?>

<script src="modif.js"></script>

<div class="border p-3 mt-3">
    <div class="">
        <label for="lien" class="col-form-label">Lien</label>
        <select class="form-select shadow-none" id="lien"></select>
    </div>

    <div id="msg" class="m-3"></div>
    <div class="row">
        <div class="col-md-6 col-12">
            <label for="nom" class="col-form-label">Nom </label>
            <input id="nom"
                   type="text"
                   class="form-control ctrl  "
                   required
                   maxlength='50'
                   pattern="^[A-Za-z]([A-Za-z0-9 ]*[A-Za-z0-9])*$"
                   autocomplete="off">
            <div class='messageErreur'></div>
        </div>
        <div class="col-md-6 col-12">
            <label for="url" class="col-form-label">URL </label>
            <input id="url"
                   type="text"
                   class="form-control ctrl"
                   required
                   maxlength='50'
                   pattern="^(http://|https://)?(www.)?(([a-zA-Z0-9-]){2,}\.){1,4}([a-zA-Z]){2,6}(/([a-zA-Z-_/.0-9#:?=&;,]*)?)?"
                   autocomplete="off">
            <div class='messageErreur'></div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-6 col-12">
            Logo
            <input type="file" id="logo" accept=".jpg, .png" style='display: none '>
            <div id="cible" class="upload"
                 data-bs-html="true"
                 style="height: 200px">
            </div>
            Fichier téléchargé : <span id='messageCible'></span>
        </div>

        <div class="col-md-6 col-12">
            <label for="nomlogo" class="col-form-label">Nom du logo</label>
            <input id="nomlogo"
                   type="text"
                   class="form-control ctrl  "
                   required
                   maxlength='30'
                   pattern="^[A-Za-z]([A-Za-z0-9])*$"
                   autocomplete="off">
            <div class='messageErreur'></div>
        </div>
    </div>

    <br>

    <div class="text-center">
        <button id='btnModifier' class="btn btn-success">Modifier</button>
    </div>
</div>

<?php require RACINE . '/include/pied.php'; ?>


