"use strict";

/**
 * Gestion de l'interface de réinitialisation du mot de passe
 */

window.onload = init;

/**
 *  Définition des gestionnaires d'événement
 */
function init() {
    // traitement sur le clic des boutons
    btnEnvoyer.onclick = envoyer;
    btnValider.onclick = initialiser;

    // filtrer les caractères sur le champ code
    code.onkeypress = (e) => /[0-9]/.test(e.key);

    // filtrer les caractères sur le champ login et déclencher automatiquement l'envoi du code sur la touche Entrée
    login.onkeypress = (e) => {
        if (e.key === 'Enter') envoyer();
        else if (!/[a-zA-z0-9]/.test(e.key)) return false;
    }

    // interdire le coller au niveau de la confirmation
    confirmation.onpaste = () => false;


    pied.style.visibility = "visible";

    // Choix de la phase à afficher : étape 1 : pas de cookie 'code', étape 2 : présence d'un cookie code
    // mais comment récupérer le login : le stocker à l'issue de la phase 1
    if (document.cookie.indexOf('code') === -1) {
        zone1.style.display = 'block';
        zone2.style.display = 'none';
    } else {
        zone1.style.display = 'none';
        zone2.style.display = 'block';
        login.value = sessionStorage['login'];
    }
}

/**
 * demande envoi du code
 */
function envoyer() {
    // contrôle des données saisies dans l'étape 1 : login
    login.nextElementSibling.innerText = login.validationMessage
    if (login.checkValidity()) {

        msg.innerHtml = "";
        $.ajax({
            url: 'ajax/envoyercode.php',
            type: 'POST',
            data: {login: login.value},
            dataType: "json",
            error: (request) => {
                msg.innerHTML = Std.genererMessage(request.responseText, 'rouge')
            },
            success: (data) => {
                let rep = data + `Un code pour réinitialiser votre mot de passe vient de vous être envoyé,
                       veuillez consulter votre boîte mail et le saisir dans le champ code de vérification afin de valider votre nouveau mot de passe                       
                       <br>Ne tardez pas, ce code possède une durée de validité limitée à 5 minutes.
                       <br> Votre mot de passe doit respecter les régles de sécurité suivantes :
                        <div style="padding-left: 20px">
                            Au moins 8 caractères
                            <br> Au moins une lettre  minuscule
                            <br> Au moins une lettre majuscule
                            <br> Au moins un chiffre
                            <br> Au moins un symbole parmis : ( ) = + ? ! ' $ . % ; : @ & * # / \\ - _
                        </div>`;
                msg.innerHTML = Std.genererMessage(rep, 'vert');
                // passer à la phase 2
                zone1.style.display = 'none';
                zone2.style.display = 'block';
                sessionStorage['login'] = login.value;
            }
        })
    }
}

// phase 2

/**
 *  demande de réinitialisation après contrôle des données saisies
 */
function initialiser() {
    let valide = Std.donneesValides();

    // la confirmation doit être identique au nouveau mot de passe
    confirmation.nextElementSibling.innerText = "";
    if (password.value !== confirmation.value) {
        confirmation.nextElementSibling.innerText = "La confirmation n'est pas identique !"
        valide = false;
    }
    if (valide) {
        msg.innerHtml = "";
        messagePassword.innerText = "";
        messageCode.innerText = "";
        $.ajax({
            url: "ajax/initialiserpassword.php",
            data: {login: login.value, password: password.value, code: code.value},
            type: 'post',
            dataType: "json",
            error: reponse => msg.innerHTML = Std.genererMessage(reponse.responseText),
            success: () => {
                let parametre = {
                    message: "Votre mot de passe à été mis à jour",
                    type: 'success',
                    fermeture: 1,
                    surFermeture: function () {
                        document.location.href = "connexion";
                        sessionStorage.removeItem("login");
                    }
                }
                Std.afficherMessage(parametre);
            }
        });
    }
}