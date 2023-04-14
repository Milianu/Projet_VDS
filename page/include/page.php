<?php
if(!isset($id)) $id=1;
require '../include/initialisation.php';
// Récupération du contenu de la page dont l'id est contenu dans la varaible $id
// récupération du contenu de la page
// Tous les appels à cette méthode sont réalisés dans le code de l'application
// le paramètre $id est donc forcément correct

$ligne =  Page::getPageById($id);

$titreFonction = $ligne['nom'];
$contenu = $ligne['contenu'];
require RACINE . '/include/head.php';
echo <<<EOD
    <div class="border p-3">
         $contenu 
    </div>
EOD;
require RACINE . '/include/pied.php';
echo "<script>pied.style.visibility = 'visible';</script>";
