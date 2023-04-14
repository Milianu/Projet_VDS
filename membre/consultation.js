"use strict";

window.onload = init;

/**
 * Initialisation du composant table sorter
 * Récupération des membres pour un affichage en mode tableau
 */
function init() {
    $('[data-toggle="tooltip"]').tooltip();
    $("#leTableau").tablesorter({
        headers: {
            5: {sorter: false}
        }
    });


    $.ajax({
        url: 'ajax/getlesmembres.php',
        dataType: 'json',
       error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
        success: function (data) {
            for (const membre of data) {
                let tr = lesLignes.insertRow();

                tr.insertCell().innerText = membre.login;
                tr.insertCell().innerText = membre.nomPrenom;
                tr.insertCell().innerText = membre.email;
                tr.insertCell().innerText = membre.autMail === '1' ? 'Oui' : 'Non';
                tr.insertCell().innerText = membre.photo ? 'Oui' : 'Non';
                tr.insertCell().innerText = membre.telephone;
            }
            $("#leTableau").trigger('update');
            
            pied.style.visibility = 'visible';
        }

    });
}


