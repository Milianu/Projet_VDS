<div class="row">
    <div class="col-md-4 col-12">
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
    <div class="col-md-8 col-12">
        <label class="col-form-label" for="type">Type</label>
        <select class="form-select" id="type" style="width: 150px;">
            <option value="Public">Public</option>
            <option value="Privé">Privé</option>
        </select>
        <div id='msgtype' class='messageErreur'></div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-12">
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
</div>


<label for="description" class="col-form-label">Description</label>
<textarea id='description' style="min-height: 350px" class="form-control"></textarea>
<div id="msgdescription" class='messageErreur'></div>
