<?php

/**
 *  Affiche l'interface proposant les actions d'administration
 * Appel : Cadre 'Espace Administration'
 */

$titreFonction = "";
require 'include/head.php';

?>

<div id="msg" class="m-3"></div>
<div class="card border-dark mx-2 mb-2">
    <div class="card-header text-white" style="background-color: #343a40">
        <span style="" class="card-text">Administration du site</span>
    </div>
    <div class="card-body">
        <div class='row'>
            <div class="col-xl-3  col-md-4 col-sm-6 ">
                <a class="btn btn-sm btn-outline-dark m-2 shadow-sm " href="module.php"
                   style="display : block;  width:220px">
                    Contrôle d'accès module
                </a>
            </div>

            <div class="col-xl-3 col-md-4 col-sm-6 ">
                <a class="btn btn-sm btn-outline-dark m-2 shadow-sm " href="sauvegarde.php"
                   style="display : block; width:220px">
                    Sauvegarde de la base de données
                </a>
            </div>
        </div>
    </div>

</div>
