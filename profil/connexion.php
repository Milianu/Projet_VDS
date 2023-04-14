<?php
/**
 *  Affichage de l'interface de connexion
 */

require '../include/initialisation.php';
$titreFonction = "Connexion";
require RACINE . '/include/head.php';
?>
<script src="connexion.js"></script>
<div class=" d-flex align-content-center">
    <div class="card mx-auto ">
        <div class="card-header">
            Connexion à l'espace membre
        </div>
        <div class="card-body">
            <div id="msg" class="mt-3"></div>
            <div class="d-flex flex-column">
                <label for="login">
                    <input id="login" type="text" required class="ctrl mb-3" placeholder="Login"
                           autocomplete="off">
                    <div id="messageLogin" class='messageErreur'></div>
                </label>
                <label class="mt-3">
                    <input id='password' type="password" required class="ctrl mb-3" placeholder="Mot de passe">
                    <div id="messagePassword" class='messageErreur'></div>
                    <div class="password-icon">
                        <i id='voir' class="bi bi-eye"></i>
                        <i id='cacher' class="bi bi-eye-slash off"></i>
                    </div>
                </label>
            </div>

            <input type="checkbox" id="chkMemoriser">
            <label for="chkMemoriser">Se souvenir de moi</label>

            <div class="text-center mt-3 pl-3 pr-3">
                <button id='btnValider' class="btn btn-danger">Se connecter</button>
            </div>
        </div>
    </div>
    <?php require RACINE . '/include/pied.php'; ?>




