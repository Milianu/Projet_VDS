"use strict";

/**
 * Mise à jour du bandeau d'information
 */

window.onload = init;
/**
 * Initialisation du composant CkEditor
 * Récupération de la valeur du champ contenu de la table bandeau
 * Définition des gestionnaires d'événements
 */
function init() {

    CKEDITOR.replace( 'bandeau');

    $.ajax({
        url: 'ajax/getlebandeau.php',
        dataType: 'json',
       error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
        success: (data) => {
            CKEDITOR.instances.bandeau.setData(data);
        }

    });

    // demande de modification sur le champ bandeau
    btnEnregistrer.onclick = function () {
        bandeau.value = CKEDITOR.instances.bandeau.getData();
        $.ajax({
            url: 'ajax/modifier.php',
            type: 'POST',
            data: {valeur: bandeau.value},
            dataType: 'json',
            success: function () {
                Std.afficherSucces("Modification enregistrée");
            },
            error: function (request) {
                Std.afficherErreur("Modification non enregistrée");
            }
        })
    }

    
    pied.style.visibility = 'visible';
}
