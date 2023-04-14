"use strict";

/**
 * Gestion de l'interface de saisie du mot de passe personnalisé et de sa confirmation
 */
window.onload = init;

/**
 * Initialisation des événements
 */
function init() {

    btnValider.onclick = modifier;
    confirmation.onpaste = () => false;

    pied.style.visibility = 'visible';
}

/**
 * Contrôle des données saisies et demande de modification du mot de passe envoyée
 */
function modifier() {
    let valide = Std.donneesValides();

    // la confirmation doit être identique au nouveau mot de passe
    confirmation.nextElementSibling.innerText = "";
    if (password.value !== confirmation.value) {
        confirmation.nextElementSibling.innerText = "La confirmation n'est pas identique !"
        valide = false;
    }

    if (valide) {
        // demande de modification
        $.ajax({
            url: "ajax/personnaliserpassword.php",
            data: {password: password.value},
            type: 'post',
            dataType: 'json',
            error: reponse => {
                msg.innerHTML = Std.genererMessage(reponse.responseText)
            },
            success: function (url) {
                Std.viderLesChamps();
                let parametre = {
                    message: "Votre nouveau mot de passe est pris en compte.",
                    type: 'success',
                    fermeture: 1,
                    surFermeture: function () {
                        document.location.href = url;
                    }
                }
                Std.afficherMessage(parametre);
            }
        });
    }
}

