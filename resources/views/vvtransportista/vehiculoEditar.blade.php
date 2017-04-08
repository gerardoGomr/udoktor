<br>
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<input type="hidden" name="idVehiculo" value="<?php echo $datos["idVehiculo"] ?>" id="idVehiculo">

    <div class="col-md-12 flet-lab">
            <div class="row flet-lab">
                <div class="col-md-6">
                    <label>{{ trans('leng.Tipo')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                          <select class="form-control" id="idTipo">
                            <option value="0" selected>{{trans("leng.Seleccione")}}</option>
                            <?php echo $datos["listaTipos"]; ?>
                        </select>
                </div>
                <div class="col-md-6">
                    <label>{{ trans('leng.Placa')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                    <input class="form-control" id="placa" type="text" value="<?php echo $datos["placa"]; ?>">
                    
                </div>
            </div>
        <br>
        <div class="row flet-lab">
                <div class="col-md-6">
                    <label>{{ trans('leng.Chofer')}}</label>
                          <select class="form-control" id="idChofer">
                            <option value="0" selected>{{trans("leng.Seleccione")}}</option>
                            <?php echo $datos["listaChoferes"]; ?>
                        </select>
                </div>
                <div class="col-md-6">
                    <label>{{ trans('leng.Descripción')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                    <textarea name="textarea-input" id="descripcion" cols="10" rows="3" class="form-control" maxlength="200"><?php echo $datos["descripcion"]; ?></textarea>
                </div>
            </div>
        <br><br>
            <div class="row">
                <div class="col-md-8">
                            <button type="button"  class="btn btn-success btn-sm" onclick="guardarCambiosVehiculoTransportista();"><i class="fa fa-check"></i>&nbsp;{{ trans('leng.Aceptar')}}</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="$('#divElmentosListaVehiculos').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{ trans('leng.Cancelar')}}</button>
                </div>
            </div>
                
        </div>

    
    
   
    