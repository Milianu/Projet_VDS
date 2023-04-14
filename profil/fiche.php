<?php

/**
 *  Affiche de l'interface affichant les informations de profil dans le but de les compléter/modifier
 * Appel : Cadre Membre de la page d'accueil
 */

require '../include/initialisation.php';
Std::necessiteConnexion();
$titreFonction = "Renseigner ma fiche membre";
require RACINE . '/include/head.php';

// récupération des données à afficher du membre
$id = $_SESSION['membre']['id'];

$ligne = Profil::getMembreById($id);
if ($ligne) {
    // Vérification de la présence de la photo
    $ligne['present'] = 0;
    if ($ligne['photo'] && file_exists(RACINE . '/data/photomembre/' . $ligne['photo']))
        $ligne['present'] = 1;
    // récupération des informations à intégrer dans l'interface
    $nomPrenom = $ligne['prenom'] . ' ' . $ligne['nom'];
    $autMail = $ligne['autMail'] === 1 ? "checked" : "" ;
    $photo = "";
    if ($ligne['present'] === 1) {
        // pas de chemin absolu et réponse au problème de mise à jour
        $file = "../data/photomembre/" . $ligne['photo'];
        $src = $file . '?v=' . filemtime($file);
        $photo = '<img src="' . $src . '" alt="">';
    }
} else {

    Std::traiterErreur("Erreur innattendue, nous recherchons une solution au problème");
}


?>
<script src="https://unpkg.com/bootstrap-checkbox@2.0.0/dist/js/bootstrap-checkbox.js" defer></script>
<script src="fiche.js"></script>
<div id="msg" class="m-3"></div>
<div id="contenu" class="border mx-3">
    <div class="card">
        <div class="card-body"></div>
        <div class="row mx-1">
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="nom" class="col-sm-4 col-form-label">Nom et prénom</label>
                    <div class="col-sm-8">
                      <input id="nom" type="text" class="form-control-plaintext" readonly value="<?= $nomPrenom?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label">Email </label>
                    <div class="col-sm-8">
                        <input id="email" type="text" class="form-control-plaintext" readonly value="<?= $ligne['nom'];?>">
                    </div>
                </div>
                <h5 class="fst-italic text-decoration-underline">
                    Informations affichées dans l'annuaire, consultable uniquement par les membres
                </h5>
                <div class="form-group row mt-3">
                    <label for="telephone" class="col-sm-4 col-form-label">Téléphone <i id="effacerTelephone" class='bi bi-eraser text-danger' style = "cursor : pointer; display:none"></i> </label>
                    <div class="col-sm-8">
                        <input id="telephone"
                               value="<?=$ligne['telephone']?>"
                               class="form-control ctrl"
                               type="text"
                               placeholder="Non renseigné"
                               maxlength="10"
                               minlength="10"
                               pattern="^0[1-9]\d{8}$">
                        <div class="messageErreur"></div>
                    </div>
                </div>
                <div class="form-group row mt-3">
                    <label for="autMail" class="col-sm-4 col-form-label">Affichage de mon email</label>
                    <div class="col-sm-8">
                        <input type="checkbox" id="autMail"  <?= $autMail ?> data-group-cls="btn-group-sm"
                               data-off-label="Non"
                               data-on-label="Oui"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <input type="file" id="photo" accept=".jpg, .png, .gif" style='display:none'>
                Votre photo dans l'annuaire  <i id="effacer" class='bi bi-eraser text-danger fs-2' style = "cursor : pointer; display:none"></i>
                <div id="cible" class="upload" style="height: 210px; width: 210px; font-size: 6rem;" >
                    <?= $photo ?>
                </div>
                <div id="messagePhoto" class="messageErreur"></div>
                <div>
                    La photo sera automatiqument redimensionner en 200 * 200. Si le résultat n'est pas conforme à vos attentes, utilisez le lien suivant pour redimensionner préalablement votre photo :
                    <a href=\"https://resizeyourimage.com/FR/\">Redimensionner ma photo</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require RACINE . '/include/pied.php'; ?>

