"use strict";

// Gestions des pages dont le contenu est paramètrable par l'administrateur à l'aide du comosant CkEditor
// Les pages sont fixes : ajout et suppression ne sont pas possibles (cela demande des interventions dans le code)
// liste des pages
// 1 Présentation du club
// 2 Adhésion au club
// 3 Présentation des 4 saisons
// 4 Formation
// 5 Mentions légales
// 6 Politiques de confidentialité

// définition des variables globales
let lesPages = [];  //


// problème lié à CkEditor : le composant doit être chargé complémenent avant de pouvoir l'alimenter
window.onload = () => {
    CKEDITOR.replace('contenu', {uiColor: '#42a4b9', height: '300px'});
    setTimeout(init, 100);
}

/**
 * Chargement des enregistrements de la table page pour alimenter le tableau lesPages et la zone de liste
 * Définition des événements
 */
function init() {
    $.ajax({
        url: 'ajax/getlesdonnees.php',
        dataType: 'json',
        error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
        success: (data) => {
            lesPages = data;
            for (let page of lesPages)
                liste.add(new Option(page.nom, page.id));
            afficher();
        }
    });


    liste.onchange = afficher;

    // le bouton modifier doit contrôler la saisie et lancer la demande de modification dans la table page
    btnModifier.onclick = (data) => {
        // contrôle standard sur les champs possédant la classe ctrl
        let valide = Std.donneesValides();

        //  contrôle sur le champ géré par CkEditor
        let contenu = CKEDITOR.instances.contenu.getData();
        if (contenu.length === 0) {
            messageContenu.innerText = "Veuillez renseigner ce champ."
            valide = false;
        }
        if (valide) {
            $.ajax({
                url: 'ajax/modifier.php',
                type: 'POST',
                data: {nom: nom.value, contenu: contenu, id: liste.value},
                dataType: 'json',
                error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
                success: function () {
                    // mise à jour du tableau
                    let indice = liste.selectedIndex
                    let page = lesPages[indice];
                    page.contenu = contenu.value;
                    page.nom = nom.value;
                    // mise à jour de l'interface
                    Std.afficherSucces('Modification enregistrée');
                    liste[indice].text = nom.value;
                }
            })
        }
    }
}

/**
 * Affichage du contenu de la page actuellement sélectionnée dans la zone de liste
 * L'indice de l'élément sélectionné dans la zone de liste permet de pointer l'enregistrement correspondant dans le tableau lesPages
 */
function afficher() {
    let page = lesPages[liste.selectedIndex]
    nom.value = page.nom;
    CKEDITOR.instances.contenu.setData(page.contenu);
}

