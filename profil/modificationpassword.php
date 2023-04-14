<?php

/**
 *  Affichage de l'interface permettant à l'utilisateur connecté de changer son mot de passe
 * Appel : Cadre Membre de la page d'accueil
 */

require '../include/initialisation.php';
if (!isset($_SESSION['membre'])) {
    Std::traiterErreur("Vous devez vous connecter pour accéder à cette fonctionnalité");
}
$titreFonction = "Modifier mon mot de passe";
require RACINE . '/include/head.php';
?>
<script src="modificationpassword.js"></script>
<div id="msg" class="m-3"></div>
<div id="contenu" class="m-3 d-flex justify-content-center">
    <div class="card mt-5">
        <div class=" card-body">
            Votre mot de passe doit respecter les régles de sécurité suivantes :
            <div style="padding-left: 20px">
                Au moins 8 caractères
                <br> Au moins une lettre minuscule
                <br> Au moins une lettre majuscule
                <br> Au moins un chiffre
                <br> Au moins un symbole parmis : ( ) = + ? ! ' $ . % ; : @ & * # / \ - _
            </div>
            <label for="passwordActuel" class="col-form-label">Mot de passe actuel</label>
            <input type="password" id="passwordActuel" style="max-width: 350px"
                   required
                   class="form-control ctrl">
            <div class='messageErreur'></div>

            <div class="form-group">
                <label for="password" class="obligatoire col-form-label">Nouveau mot de passe</label>
                <input type="password" id="password" style="max-width: 350px"
                       required
                       pattern="(?=.*[a-z]+)(?=.*[A-Z]+)(?=.*[0-9]+)(?=.*[()=+?!'$.%;:@&*#/\\-]+).{8,}$"
                       class="form-control ctrl"
                       autocomplete="off">
                <div class='messageErreur'></div>
            </div>
            <div class="form-group">
                <label for="confirmation" class="obligatoire col-form-label">Confirmation du mot de
                    passe</label>
                <input type="password" id="confirmation" style="max-width: 350px"
                       class="form-control ctrl"
                       autocomplete="off">
                <div class='messageErreur'></div>
            </div>

            <div class="row justify-content-center  mt-3 pl-3 pr-3">
                <button id='btnValider' class="btn btn btn-danger text-white ">Modifier</button>
            </div>
        </div>

        <?php require RACINE . '/include/pied.php'; ?>
