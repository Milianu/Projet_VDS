<?php
/**
 * Consultation de la liste des membres
 */

require '../include/initialisation.php';
require '../include/controleacces.php';
$titreFonction = "Liste des membres";
require RACINE . '/include/head.php';
?>
    <script src="consultation.js"></script>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.bootstrap_4.min.css"/>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
    <div id="msg" class="m-3"></div>
    <div class='table-responsive mt-1'>
        <table id='leTableau' class='table table-sm table-borderless tablesorter-bootstrap'
               style="font-size: 0.8rem">
            <thead>
            <tr>
                <th style=''>Login</th>
                <th style=''>Nom et prénom</th>
                <th style=''>Mail</th>
                <th style=''>Aut.</th>
                <th style=''>Photo</th>
                <th style=''>Téléphone</th>
            </tr>
            </thead>
            <tbody id="lesLignes"></tbody>
        </table>
    </div>
<?php require RACINE . '/include/pied.php'; ?>