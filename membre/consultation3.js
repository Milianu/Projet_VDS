"use strict";

window.onload = init;

/**
 * Initialisation du composant table sorter
 * Récupération des membres pour un affichage en mode tableau
 */
function init() {
    $('[data-toggle="tooltip"]').tooltip();
    for (const membre of data) {
        let tr = lesLignes.insertRow();

        tr.insertCell().innerText = membre.login;
        tr.insertCell().innerText = membre.nomPrenom;
        tr.insertCell().innerText = membre.email;
        tr.insertCell().innerText = membre.autMail === '1' ? 'Oui' : 'Non';
        tr.insertCell().innerText = membre.photo ? 'Oui' : 'Non';
        tr.insertCell().innerText = membre.telephone;
    }
    $("#leTableau").tablesorter({
        headers: {
            5: {sorter: false}
        }
    });

    pied.style.visibility = 'visible';


}


