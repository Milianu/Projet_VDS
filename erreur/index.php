<?php

/**
 * Affichage des erreurs détectées par le code de l'application
 * Le libellé de l'erreur est transmis dans une variable de session qui est immédiatement détruite
 */

// ce script n'appelle pas initialisation.php il doit charger ses propres ressources
session_start();

$contenu = "Erreur non référencée";
// récupération du nom de l'erreur
if (isset($_SESSION['erreur'])) {
    $contenu = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Amicale du Val de Somme</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="icon" type="image/png" href="/icone.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid d-flex flex-column p-0 h-100">
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <h2 class="text-center"><?= $contenu ?></h2>
        <div class="d-flex justify-content-between">
            <a href="/index.php" class="btn btn-primary mx-3">
                <img class="img-fluid" src="/img/logo.gif" alt="Val de Somme">
                Revenir à la page d'accueil
            </a>
            <a href="javascript:history.go(-1)" class="btn btn-primary mx-3">
                <img class="img-fluid" src="/img/logo.gif" alt="Val de Somme">
                Revenir à la page précédente
            </a>
        </div>
    </div>
</div>
</body>
