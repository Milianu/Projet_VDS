<?php
/**
 * Page d'accueil affichant toutes les fonctionnalités offertes
 */

require 'include/initialisation.php';

/*
 * Si la variable de session 'membre' n'existe pas mais que le cookie 'seSouvenir' existe
 * Il faut récupérer la valeur du cookie
 * On vérifie qu'elle correspond à un membre
 * Si oui : on crée la variable de session 'membre'
 * Si non : on supprime le cookie
*/

if (!isset($_SESSION['membre']) && isset($_COOKIE['seSouvenir'])){
    $seSouvenir = $_COOKIE['seSouvenir'];

    // Vérification du login
    $db = Database::getInstance();
    $sql = <<<EOD
        Select id, login, nom, prenom
        from membre
        where :empreinte = sha2(concat(prenom, login, nom, :agent), 256);
EOD;
    $curseur = $db->prepare($sql);
    $curseur -> bindParam('empreinte', $seSouvenir);
    $curseur -> bindParam('agent', $_SERVER['HTTP_USER_AGENT']);
    $curseur -> execute();
    $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
    $curseur -> closeCursor();
    if ($ligne) {
        $_SESSION['membre']['id'] = $ligne['id'];
        $_SESSION['membre']['login'] = $ligne['login'];
        $_SESSION['membre']['nomPrenom'] = $ligne['prenom'] . ' ' . $ligne['nom'];
        $option['path'] = '/';
        $option['httponly'] = true;
        // Réinitialiser la date d'expiration du cookie
        $option['expires'] = time() + 3600 * 24 * 7;
        $empreinte = hash('sha256', $ligne['prenom'] . $ligne['login'] . $ligne['nom'] . $_SERVER['HTTP_USER_AGENT']);
        setcookie('seSouvenir', $empreinte, $option);
    } else {
        // Expirer le cookie
        $option['expires'] = time() - 1;
        $option['path'] = '/';
        $option['httponly'] = true;
        setcookie('seSouvenir', 0, $option);
    }
}

// Génération du contenu du cadre membre
$cadreMembre = "";
if (isset($_SESSION['membre'])) {

    // option statique du menu 'Espace membres
    $cadreMembre .= <<<EOD
            <a class="btn btn-sm btn-outline-dark m-2 shadow-sm" href="/page/formation.php">Formation</a>

            <a class="btn btn-sm btn-outline-dark m-2 shadow-sm" href="/profil/annuaire.php">Annuaire</a>
            <a class="btn btn-sm btn-outline-dark m-2 shadow-sm" href="/profil/fiche.php">Ma fiche membre
                <i class="bi bi-info-circle text-info"
                   data-bs-toggle="popover"
                   data-bs-content="Complétez si vous le souhaitez vos informations personnelles : téléphone, photo.<br>Abonnez vous à la newletters ">
                </i>
            </a>
            <a class="btn btn-sm btn-outline-dark m-2 shadow-sm" href="/profil/modificationpassword.php">Modifier mon mot de passe</a>
            <a class='btn btn-sm btn-danger m-2 shadow-sm' href='/profil/deconnexion.php'>Se déconnecter</a>
EOD;
} else {
    $cadreMembre = "<a class='btn btn-sm btn-danger m-2 shadow-sm' href='/profil/connexion.php'>Se connecter</a>";
    $cadreMembre .= "<a class='btn btn-sm btn-outline-dark m-2 shadow-sm' href='/profil/oublipassword.php'>J'ai oublié mon mot de passe</a>";
    $cadreMembre .= "<a class='btn btn-sm btn-outline-dark m-2 shadow-sm' href='/profil/oublilogin.php'>J'ai oublié mon login</a>";
}

// Génération des options du menu Administration

$cadreAdmin = "";
if (isset($_SESSION['membre'])) {
    $id = $_SESSION['membre']['id'];
    // la classe base est chargée dynamiquement
    $lesLignes = Base::getLesModules($id);

    if ($lesLignes) {
        foreach ($lesLignes as $ligne) {
            $nom = $ligne['nom'];
            $description = $ligne['description'];
            $repertoire = $ligne['repertoire'];
            $cadreAdmin .= <<<EOD
            <a class='btn btn-sm btn-outline-dark mt-2 shadow-sm' href='/$repertoire/index.php'>
              $nom
              <i class="bi bi-info-circle text-info"
                   data-bs-toggle="popover"
                   data-bs-content="$description">
                </i>
            </a>
EOD;
        }
    }
}

$titreFonction = "Site de l'Amicale du Val de Somme";
require RACINE . '/include/head.php';
?>
<script src="index.js"></script>
<div id="msg" class="m-3"></div>
<div class="card border-dark mx-2 mb-2">
    <div class="card-header text-white" style="background-color: #343a40">
        <span style="" class="card-text">Le Club</span>
    </div>
    <div class="card-body">
        <a class="btn btn-sm btn-outline-dark m-2 shadow-sm " href="/page/club.php">
            Présentation
        </a>
        <a class="btn btn-sm btn-outline-dark m-2 shadow-sm " href="/page/adhesion.php">
            Adhésion
        </a>

        <a class="btn btn-sm btn-outline-dark m-2 shadow-sm " href="/page/formation.php">
            Formation
        </a>

        <a class="btn btn-sm btn-outline-dark m-2 shadow-sm " href="/agenda/consultation.php">
            Agenda
        </a>
        <div class="marquee-rtl">
            <div id='detailBandeau' class=" article fst-italic"></div>
        </div>
    </div>
</div>

<div class="card border-dark mx-2 mb-2">
    <div class="card-header text-white" style="background-color: #343a40">
        <span style="" class="card-text">Les 4 saisons</span>
    </div>
    <div class="card-body">
        <a class="btn btn-sm btn-outline-dark m-2 shadow-sm " href="/page/4saisons.php">
            Présentation
            <i class="bi bi-info-circle text-info"
               data-bs-toggle="popover"
               data-bs-content="Calendrier, horaires et autres informations à connaitre">
            </i>
        </a>
    </div>
</div>


<div class="card border-dark mx-2 mb-2">
    <div class="card-header text-white" style="background-color: #343a40">
        <span style="" class="card-text">Espace Membre</span>
    </div>
    <div class="card-body">
        <?= $cadreMembre ?>
    </div>
</div>

<div id="msg" class="m-3"></div>
<div class="card border-dark mx-2 mb-2">
    <div class="card-header text-white" style="background-color: #343a40">
        <span style="" class="card-text">Quelques liens utiles concernant la course à pied</span>
    </div>
    <div class="card-body">
        <div id='liens' class=""></div>
    </div>
</div>

<?php
if ($cadreAdmin !== '') {
    ?>
    <div class="card border-dark mx-2 mb-2">
        <div class="card-header text-white" style="background-color: #343a40">
            <span style="" class="card-text">Espace Administration</span>
        </div>
        <div class="card-body">
            <?= $cadreAdmin ?>
        </div>
    </div>
    <?php
}
?>
<?php require RACINE . '/include/pied.php'; ?>
