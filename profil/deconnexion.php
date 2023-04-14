<?php
session_start();
// supprimer le contenu du tableau $_SESSION
session_unset();
// supprimer le tableau $_SESSION
session_destroy();
// supprimer le cookie si il existe
if (isset($_COOKIE['seSouvenir'])) setcookie('seSouvenir', '', time() - 1, '/');
header("location:../index.php");
