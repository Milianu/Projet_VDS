<?php
/**
 *  Ajout des modules de l'application
 *  Suppression des modules inexistants
 *  un module correspond à un répertoire
 * Appel : index.php
 */

$titreFonction = "Gestion des modules";
require  'include/head.php';
?>
<script src="module.js"></script>
<div id="msg" class="m-3"></div>
<div id="formulaire" class="border p-3 m-1">
    <div class="row mb-3">
        <div class="col-sm-6">
            <label for="nom" class="obligatoire col-form-label">Nom du module (ce qui sera affiché sur la page d'accueil)</label>
            <input id="nom"
                   type="text"
                   required
                   maxlength="40"
                   class="form-control ctrl"
                   autocomplete="off">
            <div class='messageErreur'></div>
        </div>
        <div class="col-sm-6">
            <label class="obligatoire col-form-label" for="repertoire">Répertoire</label>
            <input id="repertoire"
                   type="text"
                   required
                   maxlength="30"
                   pattern="^[a-z][0-9a-z]*$"
                   class="form-control ctrl"
                   autocomplete="off">
            <div class='messageErreur'></div>
        </div>
    </div>
    <label for="description" class="obligatoire col-form-label">Description</label>
    <textarea id="description" required class="form-control" style="resize:vertical; min-height:100px;"></textarea>
    <div id='erreurMessage' class='messageErreur'></div>
    <button id="btnAjouter" class="btn btn-danger d-block w-100 mt-3">Ajouter / Modifier</button>
</div>

<div id="contenu" class='table-responsive'>
    <table class='table table-sm table-borderless table-hover'>
        <thead>
        <tr>
            <th>Action</th>
            <th>Nom</th>
            <th>Répertoire</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody id="lesLignes"></tbody>
    </table>
</div>


