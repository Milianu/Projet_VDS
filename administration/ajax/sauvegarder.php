<?php
$racine = $_SERVER['DOCUMENT_ROOT'];

require "$racine/vendor/autoload.php";

use Ifsnop\Mysqldump as IMysqldump;

$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbBase = 'vds';

$date = date('Y-m-d');
$fichier = "$racine/data/sauvegarde/$date.sql";

$lesParametres = [
    'add-drop-database' => true,
    'add-drop-trigger' => false,
    'database' => true,
    'routines' => true,
    'skip-definer' => true
];

try {
    $dump = new IMysqldump\Mysqldump("mysql:host=$dbHost; dbname=$dbBase; $dbUser; $dbPassword; $lesParametres");
    $dump->start($fichier);
    echo 1;
} catch(Exception $e) {
    echo 'La sauvegarde a échoué: ' . $e->getMessage();
    exit;
}

$ftp = ftp_connect("ftp-delattre.alwaysdata.net", 22, 90);
ftp_login($ftp, "delattre", "SlamSr.2023");
ftp_pasv($ftp, true);
if (ftp_fput($ftp, "/sauvegarde/$date.sql", $fichier, FTP_ASCII)) {
    echo 1;
} else {
    echo "La sauvegarde a réussi mais son exportation a échoué";
}
ftp_close($ftp);

