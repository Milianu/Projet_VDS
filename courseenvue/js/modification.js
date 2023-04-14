"use strict"

// mémorisation de l'identifiant de l'enregistrement en cours de modification
let id;

window.onload = () => {

    CKEDITOR.replace('description');

    // alimentation des champs du formulaire
    date.value = data.date;
    nom.value = data.nom;
    // description.value = data.description;
    CKEDITOR.instances.description.setData(data.description);
    id = data.id;

    // lancement de la modification
    btnModifier.onclick = () => {
        Std.effacerLesErreurs()
        description.value = CKEDITOR.instances.description.getData();
        if (!Std.donneesValides()) return;
        let monFormulaire = new FormData();
        monFormulaire.append('id', id);
        monFormulaire.append('nom', nom.value);
        monFormulaire.append('date', date.value);
        if (description.value.length > 0) monFormulaire.append('description', description.value);
        $.ajax({
            url: "ajax/modifier.php",
            type: 'post',
            dataType: "json",
            data: monFormulaire,
            contentType: false,
            processData: false,
            success: (data) => {
                
				
				
            },
            error: (reponse) => {
                msg.innerHTML = Std.genererMessage("L'opération a échoué, contacter la maintenance")
                console.error(reponse.responseText)
            }
        });
    }
}