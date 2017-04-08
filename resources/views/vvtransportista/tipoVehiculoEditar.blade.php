<br>
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<input type="hidden" name="idTipo" value="{{ $idTipoVehiculo }}" id="idTipo">

    <div class="col-md-12 flet-lab">
            <div class="row flet-lab">
                <div class="col-md-10">
                    <label>{{ trans('leng.Tipo de vehículo')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                          <input class="form-control" id="tipoVehiculo" type="text" value="{{$nombreVehiculo}}">
                </div>
            </div>
        <br><br>
            <div class="row">
                <div class="col-md-8">
                   <button type="button"  class="btn btn-success btn-sm" onclick="guardarCambiosTipoVehiculoTransportista();"><i class="fa fa-check"></i>&nbsp;{{ trans('leng.Aceptar')}}</button>
                   <button type="button" class="btn btn-danger btn-sm" onclick="$('#divElmentosListaTiposVehiculos').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{ trans('leng.Cancelar')}}</button>
                </div>
            </div>
                
        </div>

    
    
   
    