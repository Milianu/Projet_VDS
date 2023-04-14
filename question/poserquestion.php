<?php
/**
 * Formulaire de contact
 */

require '../include/initialisation.php';
$titreFonction = "Nous contacter";
require RACINE . '/include/head.php'
?>
<script src="../composant/date.js"></script>
<script src="poserquestion.js"></script>
<div id="contenu" class="border p-3 m-2">

    <div class="form-row">
        <div class="form-group col-md-6 ">
            <label for="nomPrenom" class="col-form-label">Nom et prénom</label>
            <input id="nomPrenom"
                   type="text"
                   class="form-control ctrl"
                   required
                   pattern="^[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]([ '-]?[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*$"
                   maxlength='30'
                   autocomplete="off">
            <span class='messageErreur'></span>
        </div>
        <div class="form-group col-md-6 ">
            <label for="email" class="col-form-label">Email</label>
            <input id="email"
                   type="text"
                   class="form-control ctrl"
                   required
                   pattern="^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*[\.][a-zA-Z]{2,4}$"
                   autocomplete="off">
            <span class='messageErreur'></span>
        </div>
    </div>
    <div class="form-row">
        <label for="message" class="col-form-label">Ma question</label>
        <textarea id='message' class="form-control ctrl" rows="8" cols="70" minlength="10" required
                  style="resize:vertical; min-height:150px"></textarea>
        <span class='messageErreur'></span>
    </div>
    <button id='btnEnvoyer' class="btn btn btn-danger mt-2 d-block w-100">Envoyer</button>
    <div class="fst-italic ">Aucune donnée personnelle n’est conservée par le site internet via ce formulaire
    </div>
    <?php require RACINE . '/include/pied.php'; ?>
