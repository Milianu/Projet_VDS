"use strict";

window.onload = () => {
    for (const element of data) {
        let id = element.id;
        let tr = lesLignes.insertRow();
        tr.id = id;
        tr.style.verticalAlign = 'middle';
        // on marque les lignes pour savoir si elles font parties des lignes à effacer ou pas

        // colonne contenant des boutons de suppression et de modification
        let td = tr.insertCell();
        let i = document.createElement('i');
        i.classList.add("bi", "bi-x", "text-danger", 'fs-2');
        i.style.cursor = "pointer";
        i.onclick = () => Std.confirmer(() => supprimer(id));
        td.appendChild(i);

        // icône de modification dans la même cellule
        i = document.createElement('i');
        i.classList.add('bi', 'bi-pencil-square', 'text-warning', 'm-1', 'fs-2');
        i.onclick = () => {
            location.href = "modification.php?id=" + element.id;
        }
        td.appendChild(i);

        // Colonne date
        tr.insertCell().innerText = element.dateFr;

        // colonne nom
        tr.insertCell().innerText = element.nom;

        // colonne type
        tr.insertCell().innerText = element.saison;

        tr.insertCell().innerText = element.description;
        tr.insertCell().innerText = element.distance;
        tr.insertCell().innerText = element.nbParticipant;
        tr.insertCell().innerText = element.lien;

        // colonne ajouterPar

    }
    $("#leTableau").tablesorter({ headers: { 0: { sorter: false } } });
    // si le drapeau est vrai il faut afficher le bouton et définir son événement click
}

/**
 *
 * @param id identifiant de l'événement à supprimer
 */
function supprimer(id) {
    msg.innerText = "";
    $.ajax({
        url: 'ajax/supprimer.php',
        type: 'POST',
        data: {id: id},
        dataType: "json",
        success: (data) => {
            if (data.success) {
                Std.afficherSucces(data.success);
                let ligne = document.getElementById(id);
                ligne.parentNode.removeChild(ligne);
            } else {
                msg.innerHTML = Std.genererMessage(data.error);
            }
        },
        error: (reponse) => {
            msg.innerHTML = Std.genererMessage("L'opération a échoué, contacter la maintenance")
            console.error(reponse.responseText)
        }
    })
}


/**
 * Demande de suppression dans l'agenda des courses en vue dont la date est dépassée
 */

function epurer() {
    $.ajax({
        url: 'ajax/epurer.php',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                msg.innerHTML =  Std.genererMessage(data.success, 'vert');
                // supprimer les lignes sur l'interface
                for(let tr of lesLignes.querySelectorAll('tr')) {
                    if (tr.dataset.old === '1') {
                        tr.style.display = 'none';
                    }
                }
            } else {
                msg.innerHTML = Std.genererMessage(data.error);
            }
			
        },
        error: (reponse) => {
            msg.innerHTML = Std.genererMessage("L'opération a échoué, contacter la maintenance")
            console.error(reponse.responseText)
        }
    })
}