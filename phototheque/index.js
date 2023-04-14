"use strict";

window.onload = init;

/**
 *  Récupération des photos
 *  Mise en place des gestionnaires d'événements pour l'upload
 */
function init() {

    getLesPhotos();

    // paramétrage de la zone d'upload
    new bootstrap.Popover(cible);
    cible.onclick = () => photo.click();
    cible.ondragover =  (e) => e.preventDefault();
    cible.ondrop = function (e) {
        e.preventDefault();
        controlerPhoto(e.dataTransfer.files[0]);
    }
    photo.onchange =  () => { if (photo.files.length > 0) controlerPhoto(photo.files[0]);};

    pied.style.visibility = 'visible';
}

function getLesPhotos() {
    $.ajax({
        url: 'ajax/getlesphotos.php',
        dataType: 'json',
        error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
        success: afficher
    });
}

/**
 * afficher les photos contenu dans le répertoire photo/information du site
 * @param {object}data liste des photos au format json
 */
function afficher(data) {
    lesPhotos.innerHTML = '';
    let row = document.createElement('div');
    row.classList.add("row");
    for (const nomFichier of data) {
        let col = document.createElement('div');
        col.classList.add("col-xl-3", "col-lg-4", "col-md-4", "col-sm-6", "col-12");

        let carte = document.createElement('div');
        carte.classList.add("card", "mb-3");

        let entete = document.createElement('div');
        entete.classList.add("card-header");

        // génération de l'icône de suppression avec un alignement à droite
        let i = document.createElement('i');
        i.classList.add("bi", "bi-x", "fs-2", "text-danger", "float-end");
        i.title = 'Supprimer le fichier'
        new bootstrap.Tooltip(i, {placement: 'bottom'});
        i.onclick = () => Std.confirmer(() => supprimer(nomFichier));
        entete.appendChild(i);

        i = document.createElement('i');
        i.classList.add("bi", "bi-clipboard", "fs-2", "float-end");
        i.title = "Copier l'url dans le press papier"
        new bootstrap.Tooltip(i, {placement: 'bottom'});
        i.onclick = () => {
            let chemin = "http://" + document.location.hostname + '/data/phototheque/';
            navigator.clipboard.writeText(chemin + nomFichier);
            i.classList.add('bi-clipboard-check');
            i.classList.remove('bi-clipboard');
        };
        entete.appendChild(i);


        let nom = document.createElement('div');
        nom.classList.add('float-start');
        nom.innerText = nomFichier;
        entete.appendChild(nom);

        carte.appendChild(entete);

        // contruction du corps du cadre contenant la photo
        let corps = document.createElement('div');
        corps.classList.add("card-body");
        corps.style.height = '180px';

        let img = document.createElement('img');
        img.src = "/data/phototheque/" + nomFichier;
        img.alt = "";
        img.style.maxWidth = '150px';
        img.style.maxHeight = '150px';
        corps.appendChild(img);
        carte.appendChild(corps);

        col.appendChild(carte);
        row.appendChild(col);
    }
    lesPhotos.appendChild(row);

}

/**
 * Lance la suppression côté serveur
 * @param {string} nomFichier  nom du pdf à supprimer
 */
function supprimer(nomFichier) {

    $.ajax({
        url: 'ajax/supprimer.php',
        type: 'POST',
        data: {nomFichier: nomFichier},
        dataType: 'json',
       error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
        success: getLesPhotos
    });
}

// ------------------------------------------------
// fonction de traitement concernant l'ajout
// ------------------------------------------------

/**
 * Contrôle sur le fichier téléversé
 * @param {file} file objet file à contrôler
 */
function controlerPhoto(file) {
    messagePhoto.innerHTML = "";
    let controle = { taille : 300 * 1024, lesExtensions: ["jpg", "png"]};
    if (Std.fichierValide(file, controle))
        ajouter(file)
    else
        messagePhoto.innerHTML = controle.reponse;
}


/**
 *
 * @param {file} file objet file à ajouter dans la photothèque
 */
function ajouter(file) {
    messagePhoto.innerHTML = "";
    let monFormulaire = new FormData();
    monFormulaire.append('fichier', file);
    $.ajax({
        url: 'ajax/ajouter.php',
        type: 'POST',
        data: monFormulaire,
        processData: false,
        contentType: false,
        dataType: 'json',
        error: reponse => { messagePhoto.innerHTML = reponse.responseText},
        success: function () {
            Std.afficherSucces("Photo ajoutée");
            // mise à jour de l'interface : on recharge les données
            getLesPhotos();

        }
    });
}



