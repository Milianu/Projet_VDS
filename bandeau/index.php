<?php
/**
 * Gérer le champ bandeau de la table paramètre qui contient les dernières nouvelles. Ce champ est affiché sur la page d'accueil dans le premier cadre
 * interface pour la modification du bandeau
 * utilisation du composant CkEditor.
 */

require '../include/initialisation.php';
require '../include/controleacces.php';
$titreFonction = "Mise à jour du bandeau des dernières informations";
require RACINE . '/include/head.php';
?>
    <script src="https://cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>
    <script src="index.js"></script>

    <div class="border p-3">
        <div id="msg" class="m-3"></div>
        <label for="bandeau" class="col-form-label">Dernières nouvelles </label>
        <textarea id="bandeau" class="form-control" style="resize:vertical; min-height:400px;"></textarea>
        <button id='btnEnregistrer' class="btn btn-danger d-block w-100 mt-3">Enregistrer les modifications</button>
    </div>
<?php require RACINE . '/include/pied.php'; ?>