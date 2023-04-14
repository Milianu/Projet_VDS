<?php

/**
 *  affichage de l'interface permettant à un membre de recevoir son login en cas d'oubli
 * Appel : Depuis la page d'accueil
 */

require '../include/initialisation.php';

// Contrôle d'accès
if (isset($_SESSION['membre'])) {
    Std::traiterErreur("Vous devez vous déconnecter pour accéder à cette fonctionnalité");
}
$titreFonction = "Login oublié";
require RACINE . '/include/head.php';
?>
<script src="oublilogin.js"></script>
<div class="m-3 d-flex justify-content-center">
    <div class="card mx-auto">
        <div class="card-header">
            Indiquez votre nom et prénom et nous vous enverrons par mail votre login.
        </div>
        <div class="card-body">
            <div id="msg" class="m-3"></div>
                <label class='obligatoire col-form-label' for="nom">Nom</label>
                <input id="nom" type="text" style="max-width: 350px"
                       required
                       pattern="^[A-Za-z][ A-Za-z-]*[A-Za-z]$"
                       maxlength="30"
                       class="form-control ctrl"
                       autocomplete=" off">
                <div class='messageErreur'></div>


                <label class='obligatoire col-form-label' for="prenom">Prénom</label>
                <input id="prenom" type="text" style="max-width: 350px"
                       required
                       pattern="^[A-Za-z][ A-Za-z-]*[A-Za-z]$"
                       maxlength="50"
                       class="form-control ctrl"
                       autocomplete=" off">
                <div class='messageErreur'></div>
        </div>
        <div class="text-center m-3">
            <button id='btnEnvoyer' class='btn btn-danger'>Envoyez-moi mon login</button>
        </div>
    </div>
</div>
</main>
<?php require RACINE . '/include/pied.php'; ?>
