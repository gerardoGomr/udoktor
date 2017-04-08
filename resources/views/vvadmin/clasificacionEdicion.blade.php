<br>
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<input type="hidden" name="idClasificacion" id="idClasificacion" value="{{$datosVehiculo["id"]}}">

    <div class="col-md-12 flet-lab">
            <div class="row flet-lab">
                <div class="col-sm-9">
                    <div class="form-group">
                        <label>{{ trans('leng.Nombre')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                        <input type="text" id="nombreClasificacion" maxlength="100" class="form-control" value="{{$datosVehiculo["name"]}}">
                    </div>

                    <div class="form-group">
                        <label>{{ trans('leng.Descripción')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                        <textarea name="descripcionClasificacion" id="descripcionClasificacion" maxlength="200" cols="10" rows="3" class="form-control">{{$datosVehiculo["description"]}}</textarea>
                    </div>
                </div>
            </div>
        <br>
            
        
            <div class="row flet-lab">
                <div class="col-md-9">
                   <button type="button"  class="btn btn-success btn-sm" onclick="guardarClasificacion();"><i class="fa fa-check"></i>&nbsp;{{ trans('leng.Aceptar')}}</button>
                   <button type="button" class="btn btn-danger btn-sm" onclick="$('#divElmentosClasificacion').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{ trans('leng.Cancelar')}}</button>
                </div>
            </div>
        
    </div>    

    
    
   
    