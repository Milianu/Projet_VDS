<?php
/**
 *  Retourner au format JSON les informations sur les membres
 * Appel : profil/liste.js function init()
 */

require '../../include/initialisation.php';
if (!isset($_SESSION['membre'])) exit;

$lesLignes = Profil::getLesMembres();

// vérification de l'existence des photos sinon
for ($i = 0; $i < count($lesLignes); $i++) {
   $lesLignes[$i]['present'] = 0;
    if ($lesLignes[$i]['photo'] !== "Non renseignée") {
        if (file_exists(RACINE . '/data/photomembre/' . $lesLignes[$i]['photo'])) {
           $lesLignes[$i]['present'] = 1;
        } else {
            $lesLignes[$i]['photo'] !== "Non trouvée";
        }
    }
}

echo json_encode($lesLignes);