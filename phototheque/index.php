<?php
/**
 * Interface de gestion des photos placées dans la photothèque
 */

require '../include/initialisation.php';
require '../include/controleacces.php';
$titreFonction = "Gestion des photos permanentes utilisables dans les informations publiées";
require RACINE . '/include/head.php';
?>
<script src="index.js"></script>
<input type="file" id="photo" accept=".jpg, .png, .gif" style='display:none'>
<label class="col-form-label">Photo à àjouter</label>
<div id="cible" class="upload">
    <i class="bi bi-cloud-upload m-1" style="font-size:2rem"></i>
    Déposer la photo (png ou jpg) dans ce cadre (taille limitée à 300 Ko) ou
    <span class="btn btn-danger m-2" title="Importez un fichier depuis votre appareil">
        <i class="bi bi-search m-1"></i>Sélectionner la photo
    </span>
</div>
<span id="messagePhoto" class="messageErreur"></span>
<div id='lesPhotos' class="text-center mt-3"></div>
<?php require RACINE . '/include/pied.php'; ?>
