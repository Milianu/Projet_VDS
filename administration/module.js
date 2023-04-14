"use strict";

/**
 * Contrôle de l'existence des scripts nécessitant un contrôle d'accès
 */

window.onload = init

/**
 * Récupération des scripts
 */
function init() {

    getLesModules();

    btnAjouter.onclick = () => {
        msg.innerText = "";
        if (Std.donneesValides()) {
            $.ajax({
                url: 'ajax/ajoutermodule.php',
                method: 'POST',
                data: {nom: nom.value, repertoire: repertoire.value, description: description.value},
                dataType: "json",
                error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
                success: reponse => {
                    Std.afficherSucces(reponse);
                    // rechargement des modules
                    getLesModules();
                }
            })
        }
    }

}

function getLesModules() {
    msg.innerText = "";
    $.ajax({
        url: 'ajax/getlesmodules.php',
        dataType: "json",

        error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
        success: (data) => {
            lesLignes.innerHTML = "";
            for (const element of data) {
                let tr = lesLignes.insertRow();
                tr.id = element.repertoire;

                // première colonne : icône de modification et de suppression
                let td = tr.insertCell();
                // td.style.display = 'table-cell';
                // td.style.verticalAlign = 'middle';
                td.style.textAlign = 'center';

                // icone de modification
                let i = document.createElement('i');
                i.classList.add("bi", "bi-pencil", "text-warning");
                i.title = 'Modifier les informations de ce module'
                i.onclick = () => {
                    nom.value = element.nom;
                    description.value = element.description
                    repertoire.value = element.repertoire
                    msg.innerHTML = "";
                }
                new bootstrap.Tooltip(i, {placement: 'bottom'});
                td.appendChild(i);
                //  icône de suppression seulement si le répertoire n'existe pas

                i = document.createElement('i');
                i.classList.add('bi', 'bi-x', 'text-danger');
                i.style.cursor = "pointer";
                new bootstrap.Popover(i, {
                    placement: 'bottom',
                    content: 'Supprimer ce module dont le répertoire est inexistant',
                    trigger: 'hover'
                });
                i.onclick = () => Std.confirmer(() => supprimer(element.repertoire));
                td.appendChild(i);


                // seconde colonne le nom du module
                tr.insertCell().innerText = element.nom;

                // troisième colonne le répertoire contenant les fichiers du module
                tr.insertCell().innerText = element.repertoire;

                // quatrième colonne décrivant le module
                tr.insertCell().innerText = element.description;

                // cinquième colonne contrôle sur le répertoire
                if (element.present === 0) {
                    tr.insertCell().innerText = "Ce répertoire n'existe pas";
                }
            }
        }
    })
}


/**
 * Suppression d'un script de la table script
 * @param id {int} nom du script
 */
function supprimer(repertoire) {
    msg.innerText = "";
    $.ajax({
        url: 'ajax/supprimermodule.php',
        type: 'POST',
        data: {repertoire: repertoire},
        dataType: "json",
        success: function () {
            let ligne = document.getElementById(repertoire);
            ligne.parentNode.removeChild(ligne);
        },
        error: reponse => msg.innerHTML = Std.genererMessage(reponse.responseText)
    })
}