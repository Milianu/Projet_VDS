<div class="row">
    <div class="col-md-3 col-12">
        <label for="date" class="col-form-label obligatoire">Date </label>
        <input id="date"
               type='date'
               style="width: 160px;"
               required
               value='<?= isset($date) ? $date : "" ?>'
               min='<?= isset($min) ? $min : "" ?>'
               max='<?= isset($max) ? $max : "" ?>'
               class="form-control ctrl ">
        <div id='msgdate' class='messageErreur'></div>
    </div>
    <div class="col-md-5 col-12">
        <label for="nom" class="col-form-label obligatoire">Nom </label>
        <input id="nom"
               type="text"
               required
               minlength="10"
               maxlength="70"
               pattern="^[0-9A-Za-zÀÇÈÉÊàáâçèéêëî]((.)*[0-9A-Za-zÀÇÈÉÊàáâçèéêëî!])*$"
               class="form-control ctrl"
               autocomplete="off">
        <div id='msgnom' class='messageErreur'></div>
    </div>
    <div class="col-md-3 col-12">
        <label for="distance" class="col-form-label obligatoire">Distance (en Km) </label>
        <input id="distance"
               type="number"
               required
               minlength="1"
               maxlength="5"
               pattern="^\d+$/"
               class="form-control ctrl"
               autocomplete="off">
        <div id='msgdistance' class='messageErreur'></div>
    </div>
    <div class="col-md-3 col-12">
        <label for="nbParticipant" class="col-form-label obligatoire">Nombre de Participants </label>
        <input id="nbParticipant"
               type="number"
               required
               pattern="^\d+$/"
               class="form-control ctrl"
               autocomplete="off">
        <div id='msgnbParticipant' class='messageErreur'></div>
    </div>
    <div class="col-md-8 col-12">
        <label for="lien" class="col-form-label obligatoire">Lien </label>
        <input id="lien"
               type="text"
               required
               maxlength="100"
               pattern="^[0-9A-Za-zÀÇÈÉÊàáâçèéêëî]((.)*[0-9A-Za-zÀÇÈÉÊàáâçèéêëî!])*$"
               class="form-control ctrl"
               autocomplete="off">
        <div id='msglien' class='messageErreur'></div>
    </div>
</div>
<label for="description" class="col-form-label">Description</label>
<textarea id='description' style="min-height: 350px" class="form-control"></textarea>
<div id="msgdescription" class='messageErreur'></div>
