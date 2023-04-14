<?php

/**
 *  Interface affichant l'annuaire des membres
 * Appel : Cadre MEmbre de la page d'accueil
 */

require '../include/initialisation.php';
Std::necessiteConnexion();
$titreFonction = "Annuaire des membres";
require RACINE . '/include/head.php';
?>
    <script src="annuaire.js"></script>

    <div class="row">
        <div class="col-12 col-sm-10 col-md-8 ">
            <div class="input-group mb-3 ">
                <label class="input-group-text" for="liste">Rechercher</label>
                <input type='text' pattern="^[A-Za-z ]*$" class="form-control" id="nomR" style="width:250px">
                <button id='btnFiltrer' class="m-3 btn btn-sm  btn-success my-auto">Filtrer</button>
                <div id="nb" class="btn btn-sm text-info"></div>
            </div>
        </div>
    </div>
    <div id="msg" class="m-3"></div>
    <div class="mt-3" id="listeMembres"></div>
<?php require RACINE . '/include/pied.php'; ?>