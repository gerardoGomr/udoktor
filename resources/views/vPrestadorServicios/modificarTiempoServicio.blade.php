<?php

?>
<br>
<div class="row" style="font-size: 0.9em">
                <div class="col-sm-12">
                <div class="col-sm-12">
                    {{trans("leng.Especifique el tiempo de duración del servicio (min)")}}.
                    <input id="vtiempoServicio" value="<?php echo $tiempo; ?>" type="text" class="form-control" style="width: 200px;" onkeypress="return validarNro(event);">
                </div>
            </div>
 </div> 
<br><br>
<div class="row" style="font-size: 0.9em">
   <div class="col-sm-12">
       <div class="col-sm-12">
        <button type="button" onclick="guardarTiempoServicio();" style="width: 110px;" class="btn btn-success btn-sm"><i class="fa fa-check-circle"></i>&nbsp;{{trans("leng.Guardar")}}</button>
        <button type="button" onclick="$('#divModal').dialog('close');" style="width: 110px;" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i>&nbsp;{{trans("leng.Cancelar")}}</button>
      </div>
   </div>
</div>

