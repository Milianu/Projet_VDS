"use strict";

let confirmationIdentique = false;
let passwordValide = false; // utilisé pour la vérification du mot de passe actuel réalisé par un appel Ajax

window.onload = init;

/**
 * Initialisation des événements
 * vérification après chaque caractère saisi
 * Vérification asynchrome du mot de passe actuel
 */
function init() {

    pied.style.visibility = 'visible';

    // activation des infobulles
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(element => new bootstrap.Popover(element));

    btnValider.onclick = modifier;

    // Traitement événementiel sur le champ passwordActuel
    passwordActuel.oninput = function () {
        this.nextElementSibling.innerText = "";
        this.classList.remove('correct');
    }

    passwordActuel.onchange = function () {
        if (Std.controler(this)) {
            $.ajax({
                url: 'ajax/verifierpassword.php',
                type: 'POST',
                data: {password: this.value},
                dataType: 'json',
                error: function (request) {
                    passwordActuel.nextElementSibling.innerText = request.responseText;
                    passwordActuel.classList.remove('correct');
                    passwordValide = false;
                },
                success: function () {
                    passwordActuel.classList.add('correct');
                    passwordActuel.innerText = '';
                    passwordValide = true;
                }
            });
        }
    };

    // traitement événementiel sur le champ confirmation
    confirmation.onpaste = () => false;

    
    pied.style.visibility = 'visible';
}

/**
 * Contrôle des données saisies et demande de modification du mot de passe envoyée
 */
function modifier() {
    let valide = Std.donneesValides();

    // la confirmation doit être renseignée et identique au nouveau mot de passe
    if (password.value !== confirmation.value) {
        confirmation.nextElementSibling.innerText = "La confirmation n'est pas identique !"
        valide = false;
    }
    // l'ancien mot de passe est-il correct
    if (!passwordValide) {
        passwordActuel.nextElementSibling.innerText = 'Mot de passe actuel erroné';
        valide = false;
    }

    // le nouveau mot de passe doit être différent
    if (password.value === passwordActuel.value) {
        password.nextElementSibling.innerText = "Le nouveau mot de passe est identique à l'actuel mot de passe";
        valide = false;
    }
    // demande de modification
    if (valide) {
        $.ajax({
            url: "ajax/modifierpassword.php",
            data: {password: password.value, passwordActuel: passwordActuel.value},
            type: 'post',
            dataType: 'json',
           error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
            success: function (data) {
                let parametre = {
                    message: "Votre nouveau mot de passe est pris en compte.",
                    type: 'success',
                    fermeture: 1,
                    surFermeture: function () {
                        document.location.href = "/index.php"
                    }
                }
                Std.afficherMessage(parametre);
            }
        });
    }
}

