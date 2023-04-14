"use strict";

/**
 * Saisie d'un nouvel adhérent
 *     Contrôle des champs de saisie par expression régulière
 *         Tous les champs sont obligatoires
 */

window.onload = init;

/**
 * Mise en place des gestionnaire d'événement sur les différents composant de l'interface
 */
function init() {

    // limiter les caractères autorisés lors de la frappe sur le champ nom
    nom.onkeypress = (e) => /^[A-Za-z ]$/.test(e.key);
    nom.focus();

    // limiter les caractères autorisés lors de la frappe sur le champ prenom
    prenom.onkeypress = (e) => /^[A-Za-z ]$/.test(e.key);

    pied.style.visibility = 'visible';

    // traitement associé au bouton 'Ajouter'
    btnAjouter.onclick = () => {
        if (Std.donneesValides()) {
            // lancement de la demande d'ajout dans la base
            msg.innerHTML = "";
            $.ajax({
                url: 'ajax/ajouter.php',
                type: 'POST',
                data: {
                    nom: nom.value,
                    prenom: prenom.value,
                    email: email.value,
                },
                dataType: 'json',
                error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
                success: function (data) {
                    let parametre = {
                        type: 'success',
                        message: 'Ajout réalisé avec succès',
                        fermeture: 1,
                    }
                    Std.afficherMessage(parametre);
                    Std.viderLesChamps()
                }
            })
        }
    }
}

