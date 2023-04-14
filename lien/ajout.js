"use strict";

/**
 * Saisie d'un nouveau lien
 *     Contrôle des champs de saisie par expression régulière
 *         Tous les champs sont obligatoires
 */

window.onload = init;

// fichier téléversé
let leFichier = null;

/**
 * Mise en place des gestionnaire d'événement sur les différents composant de l'interface
 */
function init() {

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

    // Le bouton ajouter
    btnAjouter.onclick = ajouter;

    pied.style.visibility = 'visible';
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
    } else
        messageCible.innerHTML = controle.reponse;
}

/**
 * Ajout du lien
 */
function ajouter() {
    if (Std.donneesValides()) {
        // lancement de la demande d'ajout dans la base
        msg.innerHTML = "";
        let monFichier = new FormData();
        monFichier.append('fichier', leFichier);
        monFichier.append('nom', nom.value);
        monFichier.append('logo', nomlogo.value);
        monFichier.append('url', url.value);
        monFichier.append('actif', actif.checked ? 1 : 0);

        // Demande d'ajout dans la base
        $.ajax({
            url: 'ajax/ajouter.php',
            type: 'POST',
            data: monFichier,
            processData: false,
            contentType: false,
            dataType: 'json',
            error: reponse => {
                msg.innerHTML = Std.genererMessage(reponse.responseText)
            },
            success: function () {
                let parametre = {
                    type: 'success',
                    message: 'Ajout réalisé avec succès',
                    fermeture: 1,
                }
                Std.afficherMessage(parametre);
                Std.viderLesChamps();
                messageCible.innerText = "";
                leFichier = null;
                init();
            }
        });
    }
}

