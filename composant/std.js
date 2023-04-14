// Classe static comprenant un ensemble de méthode standard au niveau affichage, conversion
// Version : 2023.2
// Nécessite Bootstrap 5.0 et sa bibliothèque d'icônes + composant noty et animate.css
// Date mise à jour : 09/02/2023

class Std {

// -----------------------------------------------------------
// Fonctions d'affichage
// ------------------------------------------------------------

    /**
     * Génération d'un message dans une mise en forme bootstrap (class='alert alert-dismissible')
     * Nécessite le composant bootstrap avec la partie js !!!
     * @param {string} texte à afficher.
     * @param {string} couleur de fond : vert, rouge ou orange
     * @return {string} Chaîne au format HTML
     */

    static genererMessage(texte, couleur = 'rouge') {
        // Ne pas transformer un message x_debug
        if (texte.startsWith('<br />')) return texte;
        // détermination de la classe bootstrap à utiliser en fonction de la couleur choisie
        let code;
        let icone;
        if (couleur === 'vert') {
            code = '#1FA055';
            icone = "bi-check-circle";
        } else if (couleur === 'rouge') {
            code = '#C60800';
            icone = "bi-x-circle";
        } else if (couleur === 'orange') {
            code = '#FF7415';
            icone = "bi-exclamation-triangle";
        }
        return `
            <div class="alert alert-dismissible fade show" 
                 style="color:white; background-color:${code}" 
                 role="alert">
                 <i class="bi ${icone}" ></i>
                 ${texte}
                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>`;
    }

    /**
     * Affiche un message dans une fenêtre modale 'Noty'
     * Nécessite le composant noty
     * @param {object} parametre doit contenir les propriétés suivantes
     * <br> message : message à afficher
     * <br>type : [facultatif] alert, success, error (défaut), warning, info
     * <br>position : [facultatif] top, topLeft, topCenter, topRight, center (center), centerLeft, centerRight, bottom, bottomLeft, bottomCenter, bottomRight
     * <br>fermeture :[facultatif] 0 (défaut) la fenêtre disparait automatiquement, 1 il faut cliquer dans la fenêtre pour la fermer
     * <br>surFermeture : [facultatif] fonction à lancer après l'affichage
     *  <br>delai : [facultatif] délai avant la fermeture automatique de la fenêtre
     */

    static afficherMessage(parametre) {
        let type = (parametre.type) ? parametre.type : 'error';
        let position = (parametre.position) ? parametre.position : 'center'
        let fermeture = (parametre.fermeture) ? parametre.fermeture : 0;
        let delai = (parametre.delai) ? parametre.delai : 500;
        if (fermeture === 1) {
            let n = new Noty({
                text: parametre.message,
                type: type,
                modal: true,
                killer: true,
                layout: position,
                theme: 'sunset',
                buttons: [
                    Noty.button('Ok', 'btn btn-sm btn-info float-end mt-0 mb-1', function () {
                        n.close();
                        if (parametre.surFermeture) parametre.surFermeture();
                    })],
                animation: {
                    open: 'animated lightSpeedI',
                    close: 'animated lightSpeedOut'
                },
            });
            n.show();
        } else {
            let n = new Noty({
                text: parametre.message,
                type: type,
                modal: true,
                layout: position,
                theme: 'sunset',
                animation: {
                    open: 'animated lightSpeedI',
                    close: 'animated lightSpeedOut'
                },
                callbacks: {
                    onClose: parametre.surFermeture
                }
            });
            n.show().setTimeout(delai);
            if (parametre.surFermeture) parametre.surFermeture();
        }
    }

    /**
     * Affiche le message d'erreur dans une boîte de dialogue
     * @param {string} message message à afficher
     */

    static afficherErreur(message) {
        let n = new Noty({
            text: message,
            type: 'error',
            modal: true,
            killer: true,
            layout: 'center',
            theme: 'sunset',
            buttons: [
                Noty.button('Ok', 'btn btn-sm btn-info float-end mt-0 mb-1', function () {
                    n.close();
                })],
            animation: {
                open: 'animated lightSpeedI',
                close: 'animated lightSpeedOut'
            },
        });
        n.show();
    }

    /**
     * Affiche le message de succès de l'opération dans une boîte de dialogue avec fermeture automatique
     * la boîte sera affichée dans le coin supérieur droit
     * @param {string} message message à afficher
     */
    static afficherSucces(message) {
        let n = new Noty({
            text: message,
            type: 'success',
            modal: true,
            killer: true,
            layout: 'topRight',
            theme: 'sunset',
            animation: {
                open: 'animated lightSpeedI',
                close: 'animated lightSpeedOut'
            },
        });
        n.show().setTimeout(500);
    }

    /**
     * Affiche le message d'erreur dans une boîte de dialogue
     * @param {string} message message à afficher
     */

    static confirmerSucces(message) {
        let n = new Noty({
            text: message,
            type: 'success',
            modal: true,
            killer: true,
            layout: 'center',
            theme: 'sunset',
            buttons: [
                Noty.button('Ok', 'btn btn-sm btn-info float-end mt-0 mb-1', function () {
                    n.close();
                })],
            animation: {
                open: 'animated lightSpeedI',
                close: 'animated lightSpeedOut'
            },
        });
        n.show();
    }

    static retournerVers(message, page) {
        let n = new Noty({
            text: message,
            type: 'success',
            modal: true,
            layout: 'center',
            theme: 'sunset',
            animation: {
                open: 'animated lightSpeedI',
                close: 'animated lightSpeedOut'
            },
            callbacks: {
                onClose: () => {
                    location.href = page
                }
            }
        });
        n.show().setTimeout(500);
    }


    /**
     * Demande de confirmation avant de lancer un traitement
     * @param {object} action pointeur sur la fonction à lancer
     */
    static confirmer(action) {
        let n = new Noty({
            text: 'Confirmer votre demande ',
            layout: 'center',
            theme: 'sunset',
            modal: true,
            type: 'info',
            animation: {
                open: 'animated lightSpeedIn',
                close: 'animated lightSpeedOut'
            },
            buttons: [
                Noty.button('Oui', 'btn btn-sm btn-success marge ', function () {
                    action();
                    n.close();
                }),
                Noty
                    .button('Non', 'btn btn-sm btn-danger', function () {
                        n.close();
                    })
            ]
        }).show();
    }

// ------------------------------------------------------------
// fonctions de contrôle sur les données saisies
// ------------------------------------------------------------

    /**
     * Contrôle la valeur d'un champ
     * En cas d'erreur afficher un message d'erreur sous le champ
     * Attribue la classe 'erreur' au champ ce qui ajoute une image à la fin du champ
     * condition : balise div après le champ possédant, présence style input.erreur
     * @param { input} input doit pointer la balise input
     * @return {boolean}
     */
    static controler(input) {
        input.nextElementSibling.innerText = input.validationMessage;
        if (input.checkValidity()) {
            input.classList.remove("erreur");
            return true;
        } else {
            input.classList.add("erreur");
            return false;
        }
    }

    /**
     * Contrôle la valeur d'un champ
     * En cas d'erreur change la couleur du texte et  de la bordure
     * @param input
     * @return {boolean}
     */
    static verifier(input) {
        if (input.checkValidity()) {
            input.classList.remove("erreur");
            return true;
        } else {
            input.classList.add("erreur");
            return false;
        }
    }

    /**
     * Vérifie si l'objet file possède une extension et un type mime autorisés
     * @param {object} file objet file à contrôler
     * @param {object} controle
     * controle peut contenir les propriétés suivantes :
     * <br> lesExtensions : [Facultatif] tableau contenant les extensions autorisées
     * <br> taille : [Facultatif] Taille maximale en octet du pdf
     * <br> reponse : message d'erreur
     * @returns {bool}
     */
    static fichierValide(file, controle) {
        controle.reponse = '';
        // vérification qu'un pdf est transmis
        if (file === undefined) {
            controle.reponse = 'Aucun pdf transmis'
            return false;
        }
        // si la taille à ne pas dépasser est précisée on contrôle la taille du pdf
        if (controle.taille && file.size > controle.taille) {
            let size = this.conversionOctet(file.size, "Ko");
            let taille = Std.conversionOctet(controle.taille, "Ko");
            controle.reponse = `La taille du fichier (${size}) dépasse la taille autorisée (${taille})`;
            return false;

        }
        // si les extensions sont précisées, on contrôle l'extension du pdf
        if (controle.lesExtensions) {
            // récupération de l'extension : découpons au niveau du '.' et prenons le dernière élement
            let eltFichier = file.name.split('.');
            let extension = eltFichier[eltFichier.length - 1].toLowerCase();
            if (controle.lesExtensions.indexOf(extension) === -1) {
                controle.reponse = `Extension ${extension} non acceptée`;
                return false;
            }
        }
        return true;
    }


    /**
     * Contrôle tous les champs associés à la classe 'ctrl'
     * champ champ doit être suivi d'une balise <div class='messageErreur'></div> pour afficher le message d'erreur
     * @returns {boolean} true si tous es champs respectent les contraintes définies dans leurs attributs pattern, minlength, maxlength, required, min, max ...
     */
    static donneesValides() {
        let valide = true
        for (const input of document.getElementsByClassName('ctrl')) {
            input.nextElementSibling.innerText = input.validationMessage
            if (!input.checkValidity()) valide = false;
        }
        return valide;
    }

    /**
     * Vide tous les champs associés à la classe 'ctrl'
     */
    static viderLesChamps() {
        for (const input of document.getElementsByClassName('ctrl')) {
            input.value = '';
        }
    }

    static effacerLesErreurs() {
        for (const div of document.getElementsByClassName('messageErreur')) {
            div.innerText = ''
        }
        msg.innerText = "";
    }


// ------------------------------------------------------------
// fonctions diverses de conversion et mise en forme
// ------------------------------------------------------------

    /**
     * Conversion d'une chaine de format jj/mm/aaaa au format aaaa-mm-jj
     * @param {string} date au format jj/mm/aaaa
     * @return {string} Chaîne au aaaa-mm-jj
     */

    static encoderDate(date) {
        return date.substring(6) + '-' + date.substring(3, 6) + '-' + date.substring(0, 2);
    }

    /**
     * Conversion d'une chaine de format aaaa-mm-jj  au format jj/mm/aaaa
     * @param {string} date au format aaaa-mm-jj
     * @return {string} Chaîne au jj/mm/aaaa
     */

    static decoderDate(date) {
        return date.substring(8) + '/' + date.substring(5, 8) + '/' + date.substring(0, 4);
    }

    /**
     * Retourne la chaine passée en paramètre avec la première lettre de chaque mot en majuscule
     * @param {string} nom
     * @return {string} avec la première lettre de chaque mot en majuscule
     */
    static ucWord(nom) {
        let resultat = "";
        if (nom.trim().length > 0) {
            let lesMots = nom.trim().split(" ");
            for (let mot of lesMots)
                if (mot.length >= 2)
                    resultat += mot[0].toUpperCase() + mot.substring(1).toLowerCase() + " ";
                else if (mot.length === 1)
                    resultat += mot[0].toUpperCase() + " ";
            resultat = resultat.substring(0, resultat.length - 1);
        }
        return resultat;
    }

//
    /**
     * Retourne la valeur passée en paramètre dans le format monétaire
     * @param {number} valeur
     * @return {string}
     */
    static formatMonetaire(valeur) {
        return new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(valeur);
    }

    /**
     *  Conversion d'un nombre exprimé en octet en Ko, Mo ou Go
     *  @param {number} nb nombre représentant un nombre d'octets
     *  @param {string} unite unité souhaitée : Ko Mo ou Go
     *  @return {string}  nombre exprimé dans l'unité avec une mise en forme par groupe de 3
     */
    static conversionOctet(nb, unite = 'o') {
        let diviseur = 1;
        if (unite === "Ko") diviseur = 1024;
        else if (unite === "Mo") diviseur = 1024 * 1024;
        else if (unite === "Go") diviseur = 1024 * 1024 * 1024;
        let str = Math.round(nb / diviseur).toString();
        let result = str.slice(-3);
        str = str.substring(0, str.length - 3);  // sans les trois derniers
        while (str.length > 3) {
            let elt = str.slice(-3);
            result = elt.concat(" ", result);
            str = str.substring(0, str.length - 3);
        }
        result = str.concat(result, " ", unite);
        return result;
    }

//
// https://stackoverflow.com/questions/18251399/why-doesnt-encodeuricomponent-encode-single-quotes-apostrophes

    /**
     *  Encoder les apostrophes dans une chaîne
     *  @param {string} str
     *  @return {string}
     */

    static encoder(str) {
        return encodeURIComponent(str).replace(/[!'()*]/g, function (c) {
            return '%' + c.charCodeAt(0).toString(16);
        });
    }

    /**
     *  récupérer les paramètres GET passées dans l'url de la page ?nom=valeur&nom=valeur...
     *  @return {array} Tableau associatif
     */
    static getLesParametresUrl() {
        // Search retourne ?nom=valeur&nom=valeur...
        let lesCouplesNomValeur = location.search.substring(1).split('&');
        let lesParametres = [];
        for (const i in lesCouplesNomValeur) {
            let lesCouples = lesCouplesNomValeur[i].split('=');
            lesParametres[lesCouples[0]] = lesCouples[1];
        }
        return lesParametres;
    }

    /**
     *  emission d'un bip sonore
     */

    static beep() {
        let snd = new Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xA4Tvh9Rz/y8QADBwMWgQAZG/ILNAARQ4GLTcDeIIIhxGOBAuD7hOfBB3/94gcJ3w+o5/5eIAIAAAVwWgQAVQ2ORaIQwEMAJiDg95G4nQL7mQVWI6GwRcfsZAcsKkJvxgxEjzFUgfHoSQ9Qq7KNwqHwuB13MA4a1q/DmBrHgPcmjiGoh//EwC5nGPEmS4RcfkVKOhJf+WOgoxJclFz3kgn//dBA+ya1GhurNn8zb//9NNutNuhz31f////9vt///z+IdAEAAAK4LQIAKobHItEIYCGAExBwe8jcToF9zIKrEdDYIuP2MgOWFSE34wYiR5iqQPj0JIeoVdlG4VD4XA67mAcNa1fhzA1jwHuTRxDUQ//iYBczjHiTJcIuPyKlHQkv/LHQUYkuSi57yQT//uggfZNajQ3Vmz+Zt//+mm3Wm3Q576v////+32///5/EOgAAADVghQAAAAA//uQZAUAB1WI0PZugAAAAAoQwAAAEk3nRd2qAAAAACiDgAAAAAAABCqEEQRLCgwpBGMlJkIz8jKhGvj4k6jzRnqasNKIeoh5gI7BJaC1A1AoNBjJgbyApVS4IDlZgDU5WUAxEKDNmmALHzZp0Fkz1FMTmGFl1FMEyodIavcCAUHDWrKAIA4aa2oCgILEBupZgHvAhEBcZ6joQBxS76AgccrFlczBvKLC0QI2cBoCFvfTDAo7eoOQInqDPBtvrDEZBNYN5xwNwxQRfw8ZQ5wQVLvO8OYU+mHvFLlDh05Mdg7BT6YrRPpCBznMB2r//xKJjyyOh+cImr2/4doscwD6neZjuZR4AgAABYAAAABy1xcdQtxYBYYZdifkUDgzzXaXn98Z0oi9ILU5mBjFANmRwlVJ3/6jYDAmxaiDG3/6xjQQCCKkRb/6kg/wW+kSJ5//rLobkLSiKmqP/0ikJuDaSaSf/6JiLYLEYnW/+kXg1WRVJL/9EmQ1YZIsv/6Qzwy5qk7/+tEU0nkls3/zIUMPKNX/6yZLf+kFgAfgGyLFAUwY//uQZAUABcd5UiNPVXAAAApAAAAAE0VZQKw9ISAAACgAAAAAVQIygIElVrFkBS+Jhi+EAuu+lKAkYUEIsmEAEoMeDmCETMvfSHTGkF5RWH7kz/ESHWPAq/kcCRhqBtMdokPdM7vil7RG98A2sc7zO6ZvTdM7pmOUAZTnJW+NXxqmd41dqJ6mLTXxrPpnV8avaIf5SvL7pndPvPpndJR9Kuu8fePvuiuhorgWjp7Mf/PRjxcFCPDkW31srioCExivv9lcwKEaHsf/7ow2Fl1T/9RkXgEhYElAoCLFtMArxwivDJJ+bR1HTKJdlEoTELCIqgEwVGSQ+hIm0NbK8WXcTEI0UPoa2NbG4y2K00JEWbZavJXkYaqo9CRHS55FcZTjKEk3NKoCYUnSQ0rWxrZbFKbKIhOKPZe1cJKzZSaQrIyULHDZmV5K4xySsDRKWOruanGtjLJXFEmwaIbDLX0hIPBUQPVFVkQkDoUNfSoDgQGKPekoxeGzA4DUvnn4bxzcZrtJyipKfPNy5w+9lnXwgqsiyHNeSVpemw4bWb9psYeq//uQZBoABQt4yMVxYAIAAAkQoAAAHvYpL5m6AAgAACXDAAAAD59jblTirQe9upFsmZbpMudy7Lz1X1DYsxOOSWpfPqNX2WqktK0DMvuGwlbNj44TleLPQ+Gsfb+GOWOKJoIrWb3cIMeeON6lz2umTqMXV8Mj30yWPpjoSa9ujK8SyeJP5y5mOW1D6hvLepeveEAEDo0mgCRClOEgANv3B9a6fikgUSu/DmAMATrGx7nng5p5iimPNZsfQLYB2sDLIkzRKZOHGAaUyDcpFBSLG9MCQALgAIgQs2YunOszLSAyQYPVC2YdGGeHD2dTdJk1pAHGAWDjnkcLKFymS3RQZTInzySoBwMG0QueC3gMsCEYxUqlrcxK6k1LQQcsmyYeQPdC2YfuGPASCBkcVMQQqpVJshui1tkXQJQV0OXGAZMXSOEEBRirXbVRQW7ugq7IM7rPWSZyDlM3IuNEkxzCOJ0ny2ThNkyRai1b6ev//3dzNGzNb//4uAvHT5sURcZCFcuKLhOFs8mLAAEAt4UWAAIABAAAAAB4qbHo0tIjVkUU//uQZAwABfSFz3ZqQAAAAAngwAAAE1HjMp2qAAAAACZDgAAAD5UkTE1UgZEUExqYynN1qZvqIOREEFmBcJQkwdxiFtw0qEOkGYfRDifBui9MQg4QAHAqWtAWHoCxu1Yf4VfWLPIM2mHDFsbQEVGwyqQoQcwnfHeIkNt9YnkiaS1oizycqJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");
        snd.play();
    }

    /**
     * gestion de l'ouverture fermeture d'un conteneur
     * @param corps le conteneur à ouvrir/fermer
     * @param baliseI la balise i dont la classe doit être modifiée
     */
    static onOff(corps, baliseI) {
        if (corps.style.display === "none") {
            corps.style.display = "block"
            baliseI.classList.remove("bi-arrow-bar-down");
            baliseI.classList.add("bi-arrow-bar-up");
        } else {
            corps.style.display = "none"
            baliseI.classList.remove("bi-arrow-bar-up");
            baliseI.classList.add("bi-arrow-bar-down");
        }
    }
}
