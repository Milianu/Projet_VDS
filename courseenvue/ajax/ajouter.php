<?php
$ajax = 1;
require '../../include/initialisation.php';
require RACINE . '/include/controleacces.php';

$table = new CourseEnVue();
echo $table->ajouter();

