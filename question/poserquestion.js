"use strict";

/**
 * Gestion du formulaire de contact
 */

window.onload = init

function init() {
    // traitements associés au champ nom
    nomPrenom.onkeypress = (e) => /^[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ '-]$/.test(e.key);
    nomPrenom.focus();
    
    
    pied.style.visibility = "visible";


    btnEnvoyer.onclick = () => {
        if(Std.donneesValides()) {
            btnEnvoyer.disabled = true;
            $.ajax({
                url: 'ajax/envoyerquestion.php',
                type: 'POST',
                data: {
                    nomPrenom: nomPrenom.value,
                    email: email.value,
                    message: message.value,
                },
                dataType: 'json',
               error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
                success: function (id) {
                    Std.viderLesChamps();
                    Std.afficherMessage({
                        message: "Votre message a été envoyé sur notre adresse de contact ; nous y répondrons très rapidement",
                        type: "success",
                        fermeture: 1,
                    });
                    btnEnvoyer.disabled = false;
                }
            })
        }
    }
}



