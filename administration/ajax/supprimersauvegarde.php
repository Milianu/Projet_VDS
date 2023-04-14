<?php
if (!isset($_POST["nomFichier"])) {
    echo "Demande incomplète";
    exit;
}

$nomFichier =  "../../data/sauvegarde/" . $_POST['nomFichier'];

if (unlink($nomFichier))
    echo "1";
else
    echo "la suppression a échoué";




