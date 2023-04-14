"use strict";

/**
 * Gestion de l'interface d'oubli du login
 */

window.onload = init;

/**
 * Initialisation des événements sur le formultaire
 */
function init() {
    // filtrer les caractères sur les champs nom et prénom
    nom.onkeypress = (e) => /[a-zA-z -]/.test(e.key);
    prenom.onkeypress = (e) => /[a-zA-z -]/.test(e.key);

    nom.focus();
    pied.style.visibility = "visible";

    // demande après contrôle des données saisies de l'envoi par mail du login
    btnEnvoyer.onclick = () =>
    {
        msg.innerHTML = "";
        if (Std.donneesValides()) {
            btnEnvoyer.disabled = true;
            $.ajax({
                url: 'ajax/getlogin.php',
                type: 'POST',
                data: {nom: nom.value, prenom: prenom.value},
                dataType: "json",
                error: (reponse) => {
                    msg.innerHTML = Std.genererMessage(reponse.responseText, 'rouge')
                    btnEnvoyer.disabled = false
                },
                success: () => {
                    msg.innerHTML = Std.genererMessage("Votre login vient de vous êtes envoyé par mail", 'vert')
                }
            })
        }
    }
}



