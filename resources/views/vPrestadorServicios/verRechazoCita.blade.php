<?php

?>
<br>
<div class="row" style="font-size: 0.9em">
            <div class="col-sm-12">
                <div class="col-md-11">
                    <div class="form-group">
                        <label for="textarea-input">Motivo de rechazo de la cita</label>
                        <textarea maxlength="200" name="motivoid" id="motivoid" cols="10" rows="4" class="form-control" disabled>{{$motivo}}</textarea>
                  </div>
                </div>
            </div>
 </div> 

<br>
<div class="row" style="font-size: 0.9em">
   <div class="col-sm-12">
       <div class="col-sm-12">
        <button type="button" onclick="$('#divModal').dialog('close');" style="width: 110px;" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i>&nbsp;{{trans("leng.Cerrar")}}</button>
      </div>
   </div>
</div>

