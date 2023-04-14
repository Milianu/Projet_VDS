"use strict";

let editor;

window.onload = () => {
    CKEDITOR.replace('description');

    btnAjouter.onclick = () => {
        description.value = CKEDITOR.instances.description.getData();
        // if (!Std.donneesValides()) return;
        // effacer les anciens messages
        Std.effacerLesErreurs();
        // données transmises
        let monFormulaire = new FormData();
        monFormulaire.append('nom', nom.value);
        monFormulaire.append('date', date.value);
        if (description.value.length > 0) monFormulaire.append('description', description.value);

        // demande d'ajout
        $.ajax({
            url: 'ajax/ajouter.php',
            type: 'post',
            data: monFormulaire,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: (data) => {
               if (data.error) {
                   for(const erreur of data.error) {
                       if(erreur.champ === 'msg') {
                           msg.innerHtml = Std.genererMessage(erreur.message);
                       } else {
                           let champ = document.getElementById('msg' + erreur.champ);
                           champ.innerText = erreur.message;
                       }
                   }
               } else {
                   Std.retournerVers(data.success, 'index.php');
               }
            },
            error: (reponse) => {
                msg.innerHTML = Std.genererMessage("L'opération a échoué, contacter la maintenance")
                console.error(reponse.responseText)
            }
        })
    }
}
