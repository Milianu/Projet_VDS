"use strict";

/**
 * Gestion des administrateurs et de leurs droits
 *    Ajout ou suppression d'un administrateur
 *    Mise en place d'une zone d'auto completion pour sélectionner le membre devant devenir administrateur
 *    Tables utilisées : membre, administrateur, module et droit
 */


// déclaration des variables globales

let id = null; // identifiant du membre sélectionné
let $nomPrenom  // champ associé à l'autocomplétion

window.onload = init;

/**
 *  initialisation des événements
 *  chargement des données
 *  Mise en place du système d'auto complétion
 */
function init() {

    // gestionnaire d'événement
    btnAjouter.onclick = ajouterAdministrateur;
    btnSupprimerAdministrateur.onclick = () => {
        Std.confirmer(supprimerAdministrateur);
    }
    btnSupprimerTout.onclick = supprimerTousLesDroits;
    btnAjouterTout.onclick = ajouterTousLesDroits;
    idMembre.onchange = chargerLesDroits;

    // activation des 2 tooltips : pas possible en une seule instruction car la première est liée à une fenêtre modale
    new bootstrap.Tooltip(ajoutAdministrateur);
    new bootstrap.Tooltip(btnSupprimerAdministrateur);

    // chargement des administrateurs et des modules
    $.ajax({
        url: 'ajax/getlesdonnees.php',
        dataType: 'json',
        error: reponse => {
            msg.innerHTML = Std.genererMessage(reponse.responseText)
        },
        success: remplirLesDonnees
    });

    // Paramétrage de la zone d'autocomplétion pour l'ajout d'un administrateur via la fenêtre modale
    // limiter aux valeurs de la zone
    // alimente la variable globale id afin de mémoriser l'identifiant du membre sélectionné
    $nomPrenom = $("#nomPrenom")
    let option = {
        url: "ajax/getlesmembres.php",
        getValue: function (element) {
            return element.nom;
        },
        list: {
            match: {
                enabled: true,
                method: function (element, phrase) {
                    return element.indexOf(phrase) === 0;
                }
            },
            onChooseEvent: function () {
                // récupération de l'id du nom sélectionné
                id = $nomPrenom.getSelectedItemData().id;
                messageNomPrenom.innerText = "";
            },
            onLoadEvent: function () {
                id = null;
                let lesValeurs = $nomPrenom.getItems();
                if (lesValeurs.length === 0) {
                    messageNomPrenom.innerText = "Aucun nom ne correspond";
                    id = null;
                }
            }
        }
    }
    $nomPrenom.easyAutocomplete(option);
}

// Remplir la zone de liste des administrateurs et les modules disponibles sous forme de case à  cocher
function remplirLesDonnees(data) {
    if (data.lesAdministrateurs.length > 0) {
        for (const element of data.lesAdministrateurs) {
            idMembre.add(new Option(element.nom, element.id));
        }
        droit.style.visibility = 'visible';
        btnSupprimerAdministrateur.style.visibility = "visible"
        // afficher les modules
        for (const element of data.lesModules) {
            let div = document.createElement('div');
            div.classList.add("d-flex", "m-3");
            let uneCase = document.createElement('input');
            uneCase.type = 'checkbox';
            uneCase.id = element.repertoire;
            uneCase.classList.add("form-check-input", "my-auto", "m-3");
            uneCase.style.width = '25px';
            uneCase.style.height = '25px';
            // pour permettre de sélectionner toutes les cases
            uneCase.name = 'module';
            // le clic sur une case à cocher déclenche la mise à jour des droits de l'administrateur (ajout ou suppression)

            uneCase.onclick = function () {
                $.ajax({
                    url: "ajax/majdroit.php",
                    type: 'POST',
                    data: {
                        idMembre: idMembre.value,
                        repertoire: element.repertoire,
                        ajout: uneCase.checked ? 1 : 0,
                    },
                    dataType: 'json',
                    error: reponse => {
                        msg.innerHTML = Std.genererMessage(reponse.responseText)
                    },
                });
            };
            div.appendChild(uneCase);
            let label = document.createElement('label')
            label.innerHTML = element.nom;
            label.classList.add("my-auto");
            // mise en place d'une infobulle de type popover
            new bootstrap.Popover(label, {trigger: "hover", placement: "right", content: element.description});
            div.appendChild(label)
            lesModules.appendChild(div)
        }
        chargerLesDroits();
    } else {
        $("#frmAjout").modal("show")
    }

    pied.style.visibility = 'visible';

}

// ------------------------------------------------------
// Traitement concernant l'ajout d'un administrateur
// ------------------------------------------------------

function ajouterAdministrateur() {
    msgFrmAjout.innerText = "";
    messageNomPrenom.innerText = "";
    if (id == null) {
        messageNomPrenom.innerText = "Il faux sélectionner un membre dans la liste à partir de la saisie de son nom"
    } else {
        // demande d'ajout
        $.ajax({
            url: "ajax/majadministrateur.php",
            type: 'POST',
            data: {idMembre: id, op: 'A'},
            dataType: 'json',
            success: function () {
                // ajout dans la zone de liste
                idMembre.add(new Option(nomPrenom.value, id));
                // afficher le nouveau administrateur dans la zone de liste
                idMembre.selectedIndex = idMembre.length - 1;
                // décocher les cases
                decocherCase();
                // fermeture de la fenêtre modale
                $("#frmAjout").modal("hide");

                let parametre = {
                    message: "<div class='m-3' style='text-align: justify' >" + nomPrenom.value + "fait maintenant partie des administrateurs.Il vous reste à sélectionner les modules qu'il peut gérer " + "</div>",
                    type: 'success',
                    fermeture: 1,
                    surFermeture: function () {
                        $nomPrenom.val('');
                        id = null;
                    }
                }
                Std.confirmerSucces(parametre);
            },
            error: reponse => msgFrmAjout.innerHTML = Std.genererMessage(reponse.responseText),
        });
    }
}


/**
 * demande de Suppression de l'administrateur actuellement sélectionné sur l'interface
 */
function supprimerAdministrateur() {
    $.ajax({
        url: "ajax/majadministrateur.php",
        type: 'POST',
        data: {idMembre: idMembre.value, op: 'S'},
        dataType: 'json',
        success: function () {
            // un rafraichissement de la page parait la solution la plus simple
            document.location.reload(true);
        },
        error: reponse => {
            msg.innerHTML = Std.genererMessage(reponse.responseText)
        },
    });
}


/**
 * Récupération des droits de l'administrateur sélectionné afin de cocher les bonnes cases
 */
function chargerLesDroits() {
    $.ajax({
        url: "ajax/getlesdroits.php",
        type: 'POST',
        data: {
            idMembre: idMembre.value,
        },
        dataType: 'json',
        error: reponse => {
            msg.innerHTML = Std.genererMessage(reponse.responseText)
        },
        success: (data) => {
            decocherCase();
            // mise à jour de l'interface en cochant les cases correspondant aux droits de l'administrateur
            for (const element of data)
                document.getElementById(element.repertoire).checked = true;
        }
    })
    ;
}

/**
 * Suppression de tous les droits de l'administrateur actuellement sélectionné sur l'interface
 * Toutes les cases sont décochées sur l'interface
 */
function supprimerTousLesDroits() {
    $.ajax({
        url: "ajax/supprimertouslesdroits.php",
        type: 'POST',
        data: {
            idMembre: idMembre.value,
        },
        dataType: 'json',
        error: reponse => {
            msg.innerHTML = Std.genererMessage(reponse.responseText)
        },
        success: () => decocherCase()
    });
}

/**
 * Ajouter tous les droits
 * Toutes les cases sont cochées sur l'interface
 */
function ajouterTousLesDroits() {
    $.ajax({
        url: "ajax/ajoutertouslesdroits.php",
        type: 'POST',
        data: {
            idMembre: idMembre.value,
        },
        dataType: 'json',
        error: reponse => {
            msg.innerHTML = Std.genererMessage(reponse.responseText)
        },
        success: () => cocherCase()
    })
    ;
}


function decocherCase() {
    for (const input of document.getElementsByName("module"))
        input.checked = false;
}

function cocherCase() {
    for (const input of document.getElementsByName("module"))
        input.checked = true;
}
