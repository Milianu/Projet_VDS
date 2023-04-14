<?php
/**
 *  Affichage de l'interface permettant la modification du contenu des pages gérées par un administrateur
 *            Sélection de la page concerné à l'aide d'une zone de liste
 * Remarque : pas de possibilité d'ajout
 *            pages concernées : Présentation du club, des 4 saisons, de l'adhésion, des formations, memtions légales, politique de confidentialité
 */

require '../include/initialisation.php';
require '../include/controleacces.php';
$titreFonction = "Gestion des pages statiques";
require RACINE . '/include/head.php'
?>
<script src="https://cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>
<script src="index.js"></script>
<div class="row mt-3 ml-1">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">
        <div class="input-group mb-3 col-6">
            <label class="input-group-text" for="liste">Page à modifier</label>
            <select class="form-select " id="liste"></select>
        </div>
    </div>
</div>
<div id="formulaire" class="border p-3">
    <div id="msg" class="m-3"></div>
    <label class='col-form-label' for="nom">Titre affiché dans le menu </label>
    <input type="text"
           id='nom'
           required
           minlength="10"
           maxlength="70"
           class="form-control ctrl">
    <div class="messageErreur"></div>

    <label class="col-form-label" for="contenu">Contenu de cette page (un copier/coller depuis word est
        possible) </label>
    <textarea id='contenu' required minlength="10" style="display:none"></textarea>
    <div id="messageContenu" class="messageErreur"></div>
    <button id='btnModifier' class="btn btn-danger d-block w-100 ">Modifier</button>
</div>

