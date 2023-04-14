<?php

/**
 * Vérification du droit d'accès
 * Appel depuis tous les sripts demandant une autorisation d'accès :
 *        il faut au minimum être connecté et éventuellement posséder le droit sur le répertoire contenant le script lancé
 * Résultat : Redirection vers une page d'erreur si les règles ne sont pas respectées
 * Remarque : le script initialisation est toujours appelé avant ce qui garantit l'existence de la constante RACINE
 */


// premier contrôle : le visiteur doit être connecté
Std::necessiteConnexion();

// le script appelé demande une autorisation d'accès
// le repertoire contenant ce script doit faire partir des répertoires accessibles par le membre connecté
// il faut donc vérifier qu'un enregistrement correspondant existe dans la table droit

//Comment récupérer le répertoire ?
// on peut connaitre le nom complet du script en cours avec $_SERVER['PHP_SELF'] : /repertoire/sousrepertoire/fichier.php
// il suffit de découper ce nom sur le caractère '/' et prendre le second élément





// récupération de l'id du membre
// L'id du membre est quant à lui stocké dans la variable de session membre



// il faut être autorisé : Vérification dans la table droit