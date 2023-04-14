"use strict";

/**
 * Gestion de la grille de connexion
 */

window.onload = init;

/**
 * Actions lancées après le chargement de la page
 */
function init() {
    btnValider.onclick = connecter;

    login.onkeypress = (e) => { if (e.key === 'Enter') password.focus(); }
    login.onfocus = () => { messageLogin.innerText = ""}

    password.onkeypress = (e) => { if (e.key === 'Enter') connecter(); }
    password.onfocus = () => { messagePassword.innerText = ""}

    pied.style.visibility = 'visible';
    login.focus();

    //
    voir.onclick = () => {
        password.type = "text"
        voir.style.display = 'none'
        cacher.style.display = 'block'
    }

    //
    cacher.onclick = () => {
        password.type = 'password'
        voir.style.display = 'block'
        cacher.style.display = 'none'
    }

    //
    let option = {
        trigger: "hover",
        placement: "right",
        html: true,
        content: 'Masquer le mot de passe'
    }
    new bootstrap.Popover(cacher, option)

    //
    option = {
        trigger: "hover",
        placement: "right",
        html: true,
        content: 'Afficher le mot de passe'
    }
    new bootstrap.Popover(voir, option)
}

/**
 * Vérifie que les champs de saisi sont bien renseignés
 * en absence d'erreur la demande de connexion est envoyée au serveur
 */
function connecter() {

    if (Std.donneesValides()) {
        // demande de connexion
        let memoriser = chkMemoriser.checked ? 1 : 0
        $.ajax({
            url: 'ajax/connecter.php',
            type: 'POST',
            data: {login: login.value, password: password.value, memoriser: memoriser},
            dataType: 'json',
            error: reponse => {
                msg.innerHTML = Std.genererMessage(reponse.responseText)
            },
            success: (url) => location.href = url
        })
    }
}
