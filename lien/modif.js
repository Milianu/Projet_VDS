"use strict";

/**
 * Modifier le lien
 */

window.onload = init

// fichier téléversé
let leFichier = null;

/**
 * initialisation des gestionnaire d'événement
 */
function init() {
    // chargement des clubs
    $.ajax({
        url: "ajax/getlesliens.php",
        type: 'post',
        dataType: "json",
        success: function (data) {
            for (const element of data) {
                lien.add(new Option(element.nom, element.id));
            }
            remplir();
        },
        error: reponse => console.error(reponse.responseText)
    });

    // limiter les caractères autorisés lors de la frappe sur le champ nom
    nom.onkeypress = (e) => /^[A-Za-z0-9 ]$/.test(e.key);
    nom.focus();

    url.onkeypress = (e) => /^[A-Za-z0-9/:.]$/.test(e.key);

    nomlogo.onkeypress = (e) => /^[A-Za-z0-9_]$/.test(e.key);

    // paramétrage de la zone d'upload
    cible.onclick = () => logo.click();
    cible.ondragover = (e) => e.preventDefault();
    cible.ondrop = function (e) {
        e.preventDefault();
        controlerLogo(e.dataTransfer.files[0]);
    }
    logo.onchange = () => {
        if (logo.files.length > 0) controlerLogo(logo.files[0]);
    };

    // il faut récupérer les catégorie lorsqu'on sélectionne une catégorie
    lien.onchange = remplir;

    btnModifier.onclick = modifier;

    pied.style.visibility = 'visible';
}

/**
 * Rempli le nom, l'url et le logo selon le lien sélectionné
 */
function remplir() {
    cible.innerHTML = "";
    // chargement des données pour les sous-catégories
    $.ajax({
        url: 'ajax/remplirlesliens.php',
        type: 'POST',
        data: {lien: lien.value},
        dataType: 'json',
        success: function (data) {
            nom.value = data.nom;
            url.value = data.url;
            nomlogo.value = data.logo.substring(0, data.logo.indexOf('.'));
            let img = document.createElement('img');
            img.src = '../data/logolien/' + data.logo;
            img.style.width = "auto";
            img.style.height = "100px";
            cible.appendChild(img);
            messageCible.innerText = data.logo;
        },
        error: (reponse) => console.error(reponse.responseText)
    });
    nom.focus();
}

/**
 * Contrôle sur le fichier téléversé
 * @param file  objet file à contrôler
 */
function controlerLogo(file) {
    messageCible.innerHTML = "";
    let controle = {taille: 30 * 1024, lesExtensions: ["jpg", "png"]};
    if (Std.fichierValide(file, controle)) {
        cible.innerHTML = "";
        messageCible.innerText = file.name;
        leFichier = file;
        let img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.width = "auto";
        img.style.height = "100px";
        cible.appendChild(img);
        // Déclaration de cible.value à 1 quand il y a un changement de logo
        cible.value = 1;
    } else
        messageCible.innerHTML = controle.reponse;
}

/**
 * Modifie le lien
 */
function modifier() {
    if (Std.donneesValides()) {
        let extension = messageCible.innerText.substr(-4);
        let monFichier = new FormData();
        monFichier.append('fichier', leFichier);
        monFichier.append('id', lien.value);
        monFichier.append('nom', nom.value);
        monFichier.append('logo', nomlogo.value);
        monFichier.append('url', url.value);
        monFichier.append('imgChange', cible.value);
        monFichier.append('extension', extension);

        $.ajax({
            url: 'ajax/modifier.php',
            type: 'POST',
            data: monFichier,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function () {
                let parametre = {
                    type: 'success',
                    message: 'Modification réalisé avec succès',
                    fermeture: 1,
                }
                Std.afficherMessage(parametre);
            },
            error: (reponse) => console.error(reponse.responseText)
        });
    }
}