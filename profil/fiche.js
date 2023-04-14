"use strict";

/**
 * Consultation des informations
 * Possibilité de renseigner son téléphone et l'autorisation d'affichage de son email dans l'annuaire du club
 * Possibilité d'ajouter sa photo dans les dimensions demandées : 200 * 200
 */

window.onload = init;

function init() {
    pied.style.visibility = 'visible';

    // demande de modification de l'autorisation d'affichage de l'email dans l'annuaire du club
    autMail.onchange = () => {
        $.ajax({
            url: 'ajax/modifierautmail.php',
            type: 'POST',
            data: {
                autMail: autMail.checked ? 1 : 0,
            },
            dataType: 'json',
            error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
            success: () => {
                Std.afficherSucces('Modification enregistrée')
            }
        })
    }

    // Traitement sur le champ téléphone
    // Si la touche Entrée est enfoncée on lance l'enregistrement sinon on ne laisse passer que les chiffres (sauf coier/coller)
    telephone.onkeypress = (e) => {
        if (e.key === 'Enter') {
            enregistrerTelephone()
        } else {
            return /[0-9]/.test(e.key)
        }
    }

    // Après chaque frappe, si les 10 chiffres ont été saisis on lance l'enregistrement
    telephone.oninput = () => {
        // effacement de l'éventuel message d'erreur après chaque frappe
        telephone.nextElementSibling.innerText = "";
        if (telephone.value.length === 10) enregistrerTelephone()
    }

    // si la valeur a été modifiée on lance l'enregistrement ou l'effacement
    telephone.onchange = () => {
        // la valeur a t'elle subit une modification
        if (telephone.value !== telephone.dataset.old) {
            if (telephone.value.length === 0)
                Std.confirmer(supprimerTelephone)
            else
                enregistrerTelephone()
        }
    }

    // demande de suppression du téléphone
    effacerTelephone.onclick = () => Std.confirmer(supprimerTelephone);

    // demande de suppression de la photo
    effacer.onclick = () => Std.confirmer(supprimerPhoto);

    // pour le glisser déposer d'une photo
    cible.onclick = function () {
        photo.click();
    };
    cible.ondragover = function (e) {
        e.preventDefault();
    };
    cible.ondrop = function (e) {
        e.preventDefault();
        modifierPhoto(e.dataTransfer.files[0]);
    };

    // pour la sélection à partir d'un champ file nommé ici photo
    photo.onchange = function () {
        if (this.files.length > 0) modifierPhoto(this.files[0]);
    };


    // popover associée à la div cible
    let option = {
        trigger: "hover",
        placement: "right",
        html: true,
        content:
            `
              Faites glisser la photo dans ce cadre ou double-cliquer à l'intérieur pour en sélectionner une depuis votre appareil 
              <br>Taille limitée à 300 Ko
              <br>Extensions acceptées : jpg et png
            `
    }
    new bootstrap.Popover(cible, option)

    // popover associée à l'icône 'effacer' visible uniquement si le membre possède une photo
    option = {
        trigger: "hover",
        placement: "right",
        content: "Supprimer votre photo"
    }
    new bootstrap.Popover(effacer, option)

    // popover associée à l'icône 'effacerTelephone' visible uniquement si le membre a indiqué son téléphone
    option = {
        trigger: "hover",
        placement: "right",
        content: "Effacer votre numéro de téléphone"
    }
    new bootstrap.Popover(effacerTelephone, option)

    pied.style.visibility = 'visible';

    // mise à jour de la partie dynamique de l'interface (icône)

    // on conserve les valeurs pour détecter toute modification

    // le téléphone n'est pas forcément renseigné
    if (telephone.value !== '') {
        // l'icône d'effacement peut être affiché
        effacerTelephone.style.display = "inline";
        // on conserve la valeur pour détecter toute modification
        telephone.dataset.old = telephone.value
    } else {
        telephone.value = '';
        telephone.dataset.old = '';
        effacerTelephone.style.display = "none";
    }

    // mise en forme de la case à cocher à l'aide du composant bootstrap-checkbox
    $(':checkbox').checkboxpicker();

    // Si la photo existe on l'affiche et on ajoute une icône de suppression
    // Sinon on retire la poubelle

    if (cible.innerHTML.trim().length === 0) {
        initialiserPhoto();
    } else {
        effacer.style.display = "inline";
    }

}




/**
 * Affiche l'image reçue en paramètre dans la div cible
 * Affiche l'icône permettant de supprimer cette photo
 * Fonction appelée au moment de l'affichage des informations du membre et sur le téléversement de la photo du membre
 * @param img
 */
function afficherPhoto(img) {
    cible.innerHTML = '';
    cible.appendChild(img);
    effacer.style.display = "inline";
}

/**
 * Replace l'icône par défaut dans la div cible
 * Masque l'icône permettant la suppression de la photo
 * Fonction appelée au moment de l'affichage des informations du membre ou suite à la suppression de la photo du membre
 */
function initialiserPhoto() {
    cible.innerHTML = "<i class='bi bi-person-circle'></i>";
    effacer.style.display = "none";
}

/**
 * Demande de suppression de la photo
 */
function supprimerPhoto() {
    $.ajax({
        url: 'ajax/supprimerphoto.php',
        dataType: 'json',
        success: initialiserPhoto,
        error: reponse => msg.innerHTML = Std.genererMessage(reponse.responseText)
    })
}

/**
 * Demande de suppression du numéro de téléphone
 */
function supprimerTelephone() {
    $.ajax({
        url: 'ajax/supprimertelephone.php',
        dataType: 'json',
        success: () => {
            telephone.value = '';
            effacerTelephone.style.display = "none";
            Std.afficherMessage({
                message: "Votre numéro de téléphone a été effacé, il n'apparaitra plus dans l'annuaire du club.",
                fermeture: 1,
                type: 'success'
            });
        },
        error: reponse => msg.innerHTML = Std.genererMessage(reponse.responseText)
    })
}

/**
 * Demande d'enregistrement du téléphone
 */
function enregistrerTelephone() {
    if (telephone.value !== telephone.dataset.old && Std.donneesValides()) {
        msg.innerHTML = "";
        // mise à jour de la base de données
        $.ajax({
            url: 'ajax/enregistrertelephone.php',
            type: 'POST',
            data: {
                telephone: telephone.value,
            },
            dataType: 'json',
            error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
            success: () => {
                Std.afficherSucces('Modification enregistrée')
                effacerTelephone.style.display = "inline";
                // On mémorise la nouvelle valeur pour détecter un nouveau changement
                telephone.dataset.old = telephone.value
            }
        })
    }
}


/**
 * contrôle et enregistrement de la photo du membre
 * @param   {object} file objet de type file contenant l'image à contrôler
 */
function modifierPhoto(file) {
    messagePhoto.innerText = "";
    let controle = {taille: 300 * 1024, lesExtensions: ["jpg", "png"]}
    if (Std.fichierValide(file, controle)) {
        let monFormulaire = new FormData();
        monFormulaire.append('fichier', file);
        $.ajax({
            url: 'ajax/modifierphoto.php',
            type: 'POST',
            data: monFormulaire,
            processData: false,
            contentType: false,
            dataType: 'json',
            error: reponse => {
                messagePhoto.innerHTML = reponse.responseText
            },
            success: function (data) {
                let date  = new Date();
                let time = date.getTime();
                let img = document.createElement('img');
                img.src = "/data/photomembre/" + data + '?v=' + time;
                img.alt = "";
                afficherPhoto(img);
            }
        })
    } else {
        messagePhoto.innerHTML = controle.reponse
    }
}
