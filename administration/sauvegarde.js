"use strict"


window.onload = init;

function init() {
    getLesSauvegardes();
    btnSauvegarder.onclick  = sauvegarder;
}

function getLesSauvegardes() {
    $.ajax({
        url: 'ajax/getlessauvegardes.php',
        dataType: "json",
        error: reponse => {
            Std.afficherErreur("Erreur lors de la lecture des données");
            console.error(reponse.responseText)
        },
        success: afficher
    });
}

// affichage des données contenu dans le tableau
function afficher(data) {
    lesLignes.innerHTML = "";
    for (let j in data) {
        let element = data[j];
        let tr = lesLignes.insertRow();
        tr.style.verticalAlign = 'middle'
        tr.id = j;

        // colonne contenant un lien vers un fichier pdf
        let td = tr.insertCell()
        td.style.textAlign = 'center';

        let a = document.createElement('a');
        a.href = '/data/sauvegarde/' + element;
        a.download = element;
        let i = document.createElement('i');
        i.classList.add("bi", "bi-cloud-download", "text-danger", "fs-2");
        a.appendChild(i);

        td.appendChild(a);

        i = document.createElement('i');
        i.classList.add('bi', 'bi-x', 'fs-2', 'text-danger');
        i.style.cursor = "pointer";
        new bootstrap.Popover(i, {placement: 'bottom', content: 'Supprimer la sauvegarde', trigger: 'hover'});
        i.onclick = () => Std.confirmer(() => supprimer(j, element));
        td.appendChild(i);

        tr.insertCell().innerText = element
    }
}

function supprimer(j, nomFichier) {

    $.ajax({
        url: 'ajax/supprimersauvegarde.php',
        type: 'POST',
        data: {nomFichier: nomFichier},
        dataType: "json",
        error: reponse => {
            Std.afficherErreur(reponse.responseText)
        },
        success: () => {
            // suppression sur l'interface : l'utilisation du nom du fichier avec l'extension comme id n'est pas fonctionnelle
            $('#' + j).remove();
        }
    });
}




function sauvegarder() {
    msg.innerHTML = Std.genererMessage("Sauvegarde en cours, veuillez patienter...", 'orange');
    $.ajax({
        url: "ajax/sauvegarder.php",
        dataType: "json",
        success: function () {
            // recharger les données
            getLesSauvegardes();
            msg.innerHTML = Std.genererMessage("Sauvegarde terminée", 'vert');

        },
        error: function (data) {
            msg.innerHTML = Std.genererMessage(data.responseText, 'rouge');
        }
    });
}