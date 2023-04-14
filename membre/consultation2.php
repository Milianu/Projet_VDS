<?php
/**
 * Consultation de la liste des membres
 */

require '../include/initialisation.php';
require '../include/controleacces.php';
$titreFonction = "Liste des membres";
require RACINE . '/include/head.php';

$lesCategories = Base::getLesMembres();

// instanciation d'un objet de la classe tableau pour générer un affichage dans un conteneur de type table

$lesColonnes = ['Login', 'Nom et Prénom', 'Mail', 'Aut.', 'Photo', 'Téléphone'];
$lesTailles = [30, 10, 20, 20, 20, 20];
$lesAlignements = ['L', 'C', 'C', 'C', 'C', 'C'];
$lesStyles = ['', '', '', '', '', ''];
$lesClasses = ['', '', '', '', '', ''];

$monTableau = new Tableau($lesColonnes, $lesTailles, $lesStyles, $lesClasses);

foreach ($lesCategories as $row) {
    $monTableau->ajouterLigne($row, $lesStyles, $lesClasses);
}
$monTableau->fermer();
?>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.bootstrap_4.min.css"/>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
    <div class="container-fluid d-flex flex-column p-0 h-100">
        <main id="main" class="flex-grow-1 mx-3 ">
            <div id='contenu' class="m-3">
                <?= $monTableau->getTableau(); ?>
            </div>
        </main>
    </div>
<?php require RACINE . '/include/pied.php'; ?>