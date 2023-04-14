<?php

/**
 *  Génération de l'entête de chaque script affichant une interface
 */

if (!isset($titreFonction))
    $titreFonction = "";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Amicale du Val de Somme</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="icon" type="image/png" href="/icone.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css">

    <link rel="stylesheet" href="/css/style.css">
    <script src="/composant/std.js"></script>

</head>
<div class="container-fluid d-flex flex-column p-0 h-100">
    <div style="background-color: #00469c" class="d-flex justify-content-between align-items-center">
        <a href="/index.php" title="Revenir sur la page d'accueil">
            <span class="text-white">Revenir au site</span>
        </a>
        <div class="text-white px-2">
            Administration du site
        </div>
    </div>
    <div class="d-flex  mt-3 mx-2 ">
        <h3 class="flex-grow-1 "><?= $titreFonction ?></h3>
        <a href="javascript:history.go(-1)"
           class="btn btn-outline-secondary"
           title='Revenir à la page prècédente'
           data-bs-toggle='tooltip'
           data-bs-placement='left'>
            <i class="bi bi-caret-left"></i>
        </a>
        <a href="/index.php" class="btn btn-outline-secondary ">
            <i class="bi bi-arrow-bar-up"
               class="btn btn-outline-secondary"
               title="Revenir sur la page d'accueil"
               data-bs-toggle="tooltip"
               data-bs-placement='left'></i>
        </a>
    </div>
    <main id="main" class="flex-grow-1 mx-3 ">


