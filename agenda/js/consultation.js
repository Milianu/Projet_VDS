"use strict";

window.onload = () => {
    for (const element of data) {
        // création du conteneur de type card
        let div = document.createElement('div');
        div.classList.add("col-md-12", "col-sm-12", "mt-1");
        let carte = document.createElement('div');
        carte.classList.add("card");
        let entete = document.createElement('div');
        entete.classList.add("card-header", "titreCadre2");
        let titre = document.createTextNode(element.dateFr + ' : ' + element.nom)
        entete.appendChild(titre);

        // ajouter une icône pour indiquer l'état du corps du cadre : fermé ou ouvert
        let i = document.createElement('i');
        i.classList.add("bi", "bi-arrow-bar-up", "float-end");

        entete.appendChild(i);

        let conteneur = document.createElement('div');
        conteneur.classList.add("card-body", "corpsCadre");
        // conteneur.style.display = "none"

        let p = document.createElement('p');
        p.style.paddingLeft = '10px';
        p.innerHTML = element.description
        conteneur.appendChild(p);


        // mise en place du système d'ouverture et fermeture sur le cadre
        entete.onclick = function () {
            Std.onOff(conteneur, i)
        };

        carte.appendChild(entete);
        carte.appendChild(conteneur);
        div.appendChild(carte);
        contenu.appendChild(div)
    }
}





