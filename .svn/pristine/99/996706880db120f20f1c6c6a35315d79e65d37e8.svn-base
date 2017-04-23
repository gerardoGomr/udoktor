<?php

?>
<br>
<div class="row" style="font-size: 0.9em">
            <div class="col-sm-12">
                <div class="col-sm-6">
                    <label for="text-input">{{ trans('leng.Hora inicial') }}</label>
                    <div class="input-group bootstrap-timepicker">
                       <input id="hora1" type="text" class="form-control input-group-addon">
                       <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                   </div>
                </div>
                <?php if($tipo=="2"){ ?>
                <div class="col-sm-6">
                    <label for="text-input">{{ trans('leng.Hora final') }}</label>
                    <div class="input-group bootstrap-timepicker">
                       <input id="hora2" type="text" class="form-control input-group-addon">
                       <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                   </div>
                </div>
                <?php } ?>
            </div>
 </div> 
<br>
<?php if($tipo=="1"){ ?>
<div class="row" style="font-size: 0.9em">
    <div class="col-sm-12">
       <div class="col-sm-6">
         <label for="text-input">{{ trans('leng.Limite de clientes') }}</label>
          <input id="limite" type="text" class="form-control" maxlength="10" onkeypress="return soloNumerosConDecimal(event);">
       </div>
    </div>
 </div> 
<?php } ?>
<br><br>
<div class="row" style="font-size: 0.9em">
   <div class="col-sm-12">
       <div class="col-sm-12">
        <button type="button" onclick="agregarHorarioTabla();" style="width: 110px;" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i>&nbsp;{{trans("leng.Agregar")}}</button>
        <button type="button" onclick="$('#divModal').dialog('close');" style="width: 110px;" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i>&nbsp;{{trans("leng.Cancelar")}}</button>
      </div>
   </div>
</div>

