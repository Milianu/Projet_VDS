<?php

/**
 *  Affichage de l'interface permettant à un  membre de réinitialiser son mot de passe en cas d'oubli
 * Appel : Depuis la page d'accueil Cadre Membrede connexion
 */

require '../include/initialisation.php';
if (isset($_SESSION['membre'])) {
    header("location:/index.php");
    exit;
}
$titreFonction = "Mot de passe oublié";
require RACINE . '/include/head.php';
?>
<script src="oublipassword.js"></script>
<div id="contenu" style="display:none" class="m-3 d-flex justify-content-center">
    <div class="card">
        <div class="card-header bg-light">
            Demande de réinitialisation de son mot de passe
        </div>
        <div class="card-body">
            <div id="msg" class="m-3"></div>
            <div id="zone1" style="display:none">
                <div class="form-group text-left">
                    <label class='' for="login">Entrez votre login, nous allons vous envoyer par mail un code pour
                        réinitialiser votre mot de passe.</label>
                    <input id="login" type="text" style="max-width: 350px"
                           required
                           pattern="^[a-zA-Z][a-zA-Z]{2,19}[0-9]?$"
                           class="form-control
                                   autocomplete=" off">
                    <div class='messageErreur'></div>
                </div>
                <div class="text-center mt-3">
                    <button id='btnEnvoyer' class='btn btn-danger mt-3'>Envoyer</button>
                </div>
            </div>
            <div id="zone2" style="display:none">

                <div class="form-group">
                    <label class='' for="code">Entrez le code de vérification reçu par mail</label>
                    <input type="text" id="code" style="max-width: 350px"
                           required
                           pattern="^[0-9]{6}$"
                           class="form-control ctrl ">
                    <div id='messageCode' class='messageErreur'></div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-form-label obligatoire">Nouveau mot de passe</label>
                    <input type="password" id="password" style="max-width: 350px"
                           required
                           pattern="(?=.*[a-z]+)(?=.*[A-Z]+)(?=.*[0-9]+)(?=.*[()=+?!'$.%;:@&*#/\\-]+).{8,}$"
                           class="form-control ctrl"
                           autocomplete="off">
                    <div id="messagePassword" class='messageErreur'></div>
                </div>
                <div class="form-group">
                    <label for="confirmation" class="obligatoire col-form-label">Confirmation du mot de
                        passe</label>
                    <input type="password" style="max-width: 350px"
                           id="confirmation"
                           class="form-control ctrl"
                           autocomplete="off">
                    <div class='messageErreur'></div>
                </div>
                <div class="text-center  mt-3 ">
                    <button id='btnValider' class="btn btn btn-danger text-white ">Réinitialiser</button>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
<?php require RACINE . '/include/pied.php'; ?>
