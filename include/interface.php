<?php


// Journalisation de la requête
$nom = '';
if (isset($_SESSION['membre'])) {
    $nom = $_SESSION['membre']['nomPrenom'];
}
// Std::tracerDemande('requete', $nom);

//  Comptabilisation de l'appel
if (!isset($titreFonction)) $titreFonction = $_SERVER['PHP_SELF'];
// vds::setStatistique($titreFonction);

// récupération du nom du fichier afin de charger le fichier js associé
$file = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
$js = "";
if (file_exists("$file.js")) $js = "<script src='$file.js'></script>";
if (file_exists("js/$file.js")) $js = "<script src='js/$file.js'></script>";

// personnalisation de l'entête de page : bouton Se connecter ou Se déconnecter
$barre = "<a class='btn btn-sm btn-danger m-2 shadow-sm' href='/profil/connexion.php'>Se connecter</a>";
if (isset($_SESSION['membre'])) {
    $barre = <<<EOD
        <i class='bi bi-person-circle m-1 masquer'></i>
        <span class="masquer">$nom</span>
        <a class='btn btn-sm btn-danger m-2 shadow-sm' href='/profil/deconnexion.php'>Se déconnecter</a>
EOD;


}

?>
<!DOCTYPE HTML>
<html lang="fr">
<body>
<head>
    <title>Amicale du Val de Somme</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description"
          content="Club de courses à pied affilié à la FFA, organisateur 4 fois par an de la course des 4 saisons">
    <link rel="icon" type="image/png" href="/icone.png">
    <!--
    <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="2439ee52-f0a7-489d-9f37-79a9cf7bac6a" data-blockingmode="auto" type="text/javascript"></script>
    -->

    <script
            src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
            crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css">

    <link rel="stylesheet" href="/css/style.css">
    <script src="/composant/std.js"></script>
    <?= $js ?>
    <script>
        window.addEventListener("load", miseEnFormePage, false);

        function miseEnFormePage() {
            // activation de toutes les popover et infobulle de la page
            document.querySelectorAll('[data-bs-toggle="popover"]').forEach(element => new bootstrap.Popover(element));
            // affichage de toutes les infobulles
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(element => new bootstrap.Tooltip(element));

            pied.style.visibility = 'visible';
            main.style.visibility = 'visible';

        }
    </script>
</head>
<body>
<div class="container-fluid d-flex flex-column p-0 h-100">
    <header>
        <a href="/index.php" title="Revenir sur la page d'accueil">
            <img class="img-fluid masquer" src="/img/logo.gif" alt="Val de Somme">
            <span class="text-white masquer ">Club de course à pied sur Amiens et ses environs</span>
        </a>
        <div class="text-white px-2">
            <?= $barre ?>
        </div>
    </header>
    <main id="main">
        <div class="d-flex  my-2 mx-4 ">
            <h3 class="flex-grow-1 animated fadeInLeft masquer"><?= $titreFonction ?></h3>
            <a href="javascript:history.go(-1)"
               class="btn btn-outline-secondary"
               title='Revenir à la page prècédente'
               data-bs-toggle='tooltip'
               data-bs-placement='left'>
                <i class="bi bi-caret-left"></i>
            </a>
            <a href="/index.php" class="btn btn-outline-secondary ">
                <i class="bi bi-arrow-bar-up"
                   title="Revenir sur la page d'accueil"
                   data-bs-toggle="tooltip"
                   data-bs-placement='left'></i>
            </a>
        </div>
        <div class="my-1" id="msg"></div>
        <?php require "interface/$file.php"; ?>
    </main>
    <footer id="pied">
        <div class="d-none d-sm-block">© 2023 Guy Verghote</div>

        <a style="text-decoration: none; color: white; font-size:1.2em; padding-left: 10px"
           href="/page/page.php?id=5">
            Mentions légales
        </a>
        <a style="text-decoration: none; color: white; font-size:1.2em; padding-left: 10px"
           class="text-left"
           href="/page/page.php?id=6">
            Politique de confidentialité
        </a>
        <!--
        <script id="CookieDeclaration" src="https://consent.cookiebot.com/2439ee52-f0a7-489d-9f37-79a9cf7bac6a/cd.js" type="text/javascript" async></script>
        -->
        <a style="text-decoration: none; color: white; font-size:1.2em; padding-left: 10px"
           class="text-left text-white"
           href="/question/poserquestion.php">
            <i class="bi bi-envelope-plus"></i>Nous contacter
        </a>

    </footer>
</div>
</body>
</html>
