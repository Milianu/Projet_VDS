<?php
/**
 *  Interface pour personnaliser son mot de passe quand ce dernier vaut 0000
 * Appel : include/initialisation.php ou  connecter.js
 * Remarque : N'utilise pas le script initialisation.php ou message d'erreur
 */
session_start();
define('RACINE', $_SERVER['DOCUMENT_ROOT']);
$titreFonction = 'Personnaliser son mot de passe';
require RACINE . '/include/head.php';

?>

<script src="personnalisationpassword.js"></script>
<div id="msg" class="m-3"></div>
<div class="card mt-5 col-sm-6" style="margin:auto">
    <div class="card-body ">
        Votre mot de passe doit être personnalisé. Il doit respecter les régles de sécurité suivantes :
        <div style="padding-left: 20px">
            Au moins 8 caractères
            <br> Au moins une lettre minuscule
            <br> Au moins une lettre majuscule
            <br> Au moins un chiffre
            <br> Au moins un symbole parmis : ( ) = + ? ! ' $ . % ; : @ & * # / \ - _
        </div>
    </div>
    <div class="card-body col-md-6">
        <div class="form-group">
            <label for="password" class="col-form-label">Mot de passe personnel </label>
            <input type="password" id="password"
                   required
                   pattern="(?=.*[a-z]+)(?=.*[A-Z]+)(?=.*[0-9]+)(?=.*[()=+?!'$.%;:@&*#/\\-]+).{8,}$"
                   class="form-control ctrl col-sm-6"
                   autocomplete="off">
            <div class='messageErreur'></div>
        </div>
        <div class="form-group">
            <label for="confirmation" class="obligatoire col-form-label">Confirmation du mot de
                passe</label>
            <input type="password"
                   id="confirmation"
                   class="form-control ctrl "
                   autocomplete="off">
            <div class='messageErreur'></div>
        </div>

        <div class="row justify-content-center  mt-3 pl-3 pr-3">
            <button id='btnValider' class="btn btn btn-danger text-white ">Personnaliser</button>
        </div>
    </div>
</div>
<?php require RACINE . '/include/pied.php'; ?>
