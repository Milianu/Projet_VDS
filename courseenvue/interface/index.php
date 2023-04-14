
<div class="input-group mb-3 col-6">
    <a href="ajout.php" class="btn btn-success ">
        Ajouter
    </a>
    <button id="btnEpurer" class="btn btn-danger" style="visibility: hidden;">Supprimer les courses  passées</button>
</div>
<div class='table-responsive'>
    <div class='table-responsive'>
        <table class='table table-sm table-borderless table-hover tablesorter-bootstrap' id='leTableau'>
            <thead>
            <tr>
                <th style="width: 100px">Action</th>
                <th style="width: 100px">Date</th>
                <th>Nom</th>
                <th>Saison</th>
                <th>Description</th>
                <th>Distance</th>
                <th>Participants</th>
                <th>Lien</th>
            </tr>
            </thead>
            <tbody id="lesLignes"></tbody>
        </table>
    </div>
</div>
