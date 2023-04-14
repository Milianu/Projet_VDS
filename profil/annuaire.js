"use strict";

// tableau mémorisant les membres afin de permettre de filtrer côté client les données sur la saison
let lesMembres;

window.onload = init;

/**
 * Chargement des membres
 */
function init() {
    $.ajax({
        url: 'ajax/getlesmembres.php',
        dataType: 'json',
        error: reponse => { msg.innerHTML = Std.genererMessage(reponse.responseText)},
        success: remplirLesDonnees
    });
    nomR.onkeypress = (e) => {
        if (e.key === 'Enter' && nomR.value.length > 0)
            afficher();
        else
            return /^[a-zA-Z ]$/.test(e.key);
    }
    btnFiltrer.onclick = afficher;
}

/**
 * Alimenter le tableau des membres et demander son affichage
 * @param data
 */
function remplirLesDonnees(data) {
    lesMembres = data;
    afficher();
    
    pied.style.visibility = 'visible';
}


/**
 * Affichage en mode carte avec prise en compte du filtre s'appliquant sur le nom ou  le prénom
 */
function afficher() {
    listeMembres.innerHTML = "";
    let row = document.createElement('div');
    row.classList.add("row");
    let n = 0;
    for (const element of lesMembres) {

        //filtre sur le nom recherché : si non renseigné la méthode include retourne true
        let valeur = nomR.value.toUpperCase();
        if (element.nom.includes(valeur) || element.prenom.includes(valeur)) {
            n++;
            let col = document.createElement('td');
            // col-xxl (1400) col-xl(1200) col-lg(992) col-md(768) col-sm(576) col
            col.classList.add("col-lg-2", "col-md-3", "col-sm-4", "col-6");

            let cadre = document.createElement('div');
            cadre.classList.add("card");

            let entete = document.createElement('div');
            entete.classList.add("card-header", 'text-center');
            entete.innerText = element.nomPrenom;

            // ajouter une icône pour indiquer l'état du corps du cadre : fermé ou ouvert
            let i = document.createElement('i');
            i.classList.add("bi", "bi-arrow-bar-down", "float-end");

            entete.appendChild(i);
            cadre.appendChild(entete);

            let conteneur = document.createElement('div');
            conteneur.classList.add("card-body", "corpsCadre");
            conteneur.style.display = "none";

            // si présence d'une photo
            if(element.present === 1) {

                let img = document.createElement('img');
                img.src = "/data/photomembre/" + element.photo;
                img.classList.add("card-img-top");
                img.style.maxHeight = '200px';
                img.style.maxWidth = '200px';
                conteneur.appendChild(img);
            }

            let p = document.createElement('p');
            p.style.paddingLeft = '10px';
            p.innerHTML = "Email : " + element.email + "<br>Téléphone : " + element.telephone
            conteneur.appendChild(p);
            cadre.appendChild(conteneur);

            // mise en place du système d'ouverture et fermeture sur le cadre
            entete.onclick = function () {
                Std.onOff(conteneur, i)
            };
            
            col.appendChild(cadre);
            row.appendChild(col);
        }
    }
    nb.innerText = n + " adhérent" + (n > 1 ? 's' : '');
    listeMembres.appendChild(row);
}