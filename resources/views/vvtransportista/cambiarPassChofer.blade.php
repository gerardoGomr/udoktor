<br>
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<input type="hidden" name="idChofer" value="{{ $idChofer}}" id="idChofer">

    <div class="col-md-8 flet-lab">
            <div class="row flet-lab">
                <div class="col-md-12">
                    <label>{{ trans('leng.Nueva contraseña')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                    <input class="form-control" id="nuevoPass" type="password">
                </div>
            </div>
        <br><br>
            <div class="row">
                <div class="col-md-12">
                    <button type="button"  class="btn btn-success btn-sm" onclick="guardarNuevoPassChofer();"><i class="fa fa-check"></i>&nbsp;{{ trans('leng.Aceptar')}}</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="$('#divElmentosListaChofer').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{ trans('leng.Cancelar')}}</button>
                </div>
            </div>
                
        </div>

    
    
   
    