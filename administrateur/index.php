<?php
/**
 *  Ajout ou suppression d'un administrateur : membre qui va recevoir des droits sur les fonctionnalités du menu gérer
 *  Attribution de ses droits
 * Appel : Cadre administration
 *
 */


require '../include/initialisation.php';
require '../include/controleacces.php';
$titreFonction = "Gestion des administrateurs du site et de leurs droits";
require RACINE . '/include/head.php';
?>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js"></script>
<script src="index.js"></script>
<div id="msg" class="m-3"></div>
<div class="row">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">
        <div class="input-group mb-3 col-6">
            <label class="input-group-text" for="idMmebre">Administrateur</label>
            <select class="form-select" id="idMembre"></select>
            <button id="btnSupprimerAdministrateur" class="btn btn-danger" title="Supprimer l'administrateur sélectionné">Supprimer</button>
            <a button id='ajoutAdministrateur' class="btn btn-success "
               data-bs-toggle="modal"
               data-bs-target="#frmAjout"
               data-bs-trigger="hover"
               data-bs-placement='bottom'
               title="Ajouter un administrateur">
                Ajouter
            </a>
        </div>
    </div>
</div>


<div id='droit' class="row border p-3">
    <div class="d-flex justify-content-between">
        <h4 class="">Les modules disponibles</h4>
        <button id='btnSupprimerTout' class="btn btn-sm  btn-danger pt-2">Retirer tout</button>
        <button id='btnAjouterTout' class="btn btn-sm  btn-danger pt-2">Ajouter tout</button>
    </div>
    <div id="lesModules"></div>
</div>
<?php require RACINE . '/include/pied.php'; ?>

<!-- Fenêtre nodale pour l'ajout d'un administrateur -->
<div class="modal fade" id="frmAjout"
     tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un nouvel administrateur</h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div id="msgFrmAjout"></div>
                <label for="nomPrenom" class="mt-1 pl-3">Membre administrateur</label>
                <input id="nomPrenom"
                       style="width: 250px"
                       type="text"
                       placeholder="Nom du membre"
                       class="form-control">
                <div id="messageNomPrenom" class="messageErreur"></div>
                <div class="text-center m-3">
                    <button id='btnAjouter' class="btn btn-sm btn-success">Ajouter un Administrateur</button>
                </div>
                <div class="col-auto messageErreur" id="msgAjout">
                </div>
            </div>
        </div>
    </div>
</div>
