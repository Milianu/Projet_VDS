<?php

$titreFonction = "Sauvegarde de la base de données";
require  'include/head.php';
?>
<script src="sauvegarde.js"></script>
<div id="msg" class="m-3"></div>

<button id='btnSauvegarder' class="btn btn btn-success mt-2 d- ">  Nouvelle sauvegarde</button>

<div class="table-responsive col-md-10 col-lg-9 col-xl-8 col-xxl-7 ">
    <table class='table table-sm table-borderless'>
        <thead>
        <tr>
            <th style='width: 100px'></th>
            <th style='width: 400px'>Sauvegarde</th>
        </tr>
        </thead>
        <tbody id="lesLignes"></tbody>
    </table>
</div>
