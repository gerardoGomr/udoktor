<br>
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<input type="hidden" name="idChofer" value="<?php echo $datos["idChofer"] ?>" id="idChofer">

    <div class="col-md-12 flet-lab">
            <div class="row flet-lab">
                <div class="col-md-6">
                    <label>{{ trans('leng.Primer nombre')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                          <input class="form-control" id="primerNombre" type="text" value="<?php echo $datos["primerNombre"] ?>">
                </div>
                <div class="col-md-6">
                    <label>{{ trans('leng.Segundo nombre')}}</label>
                          <input class="form-control" id="segundoNombre" type="text" value="<?php echo $datos["segundoNombre"] ?>">
                    
                </div>
            </div>
        <br>
        <div class="row flet-lab">
                <div class="col-md-6">
                    <label>{{ trans('leng.Primer apellido')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                          <input class="form-control" id="primerApellido" type="text" value="<?php echo $datos["primerApellido"] ?>">
                </div>
                <div class="col-md-6">
                    <label>{{ trans('leng.Segundo apellido')}}</label>
                          <input class="form-control" id="segundoApellido" type="text" value="<?php echo $datos["segundoApellido"] ?>">
                    
                </div>
            </div>
        <br>
        <div class="row flet-lab">
                <div class="col-md-6">
                    <label>{{ trans('leng.DNI')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                    <input class="form-control" id="dniChofer" type="text" disabled value="<?php echo $datos["dni"] ?>">
                </div>
            
            <div class="col-md-6">
                    <label>{{ trans('leng.Licencia de conducir')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                    <input class="form-control" id="licenciaChofer" type="text" maxlength="20" value="<?php echo $datos["licencia"] ?>">
                </div>
                
            </div>
        <br>
        <div class="row flet-lab">
            <div class="col-md-6">
                    <label>{{ trans('leng.Telefono')}}</label>
                          <input class="form-control" id="telefonoChofer" type="text" value="<?php echo $datos["telefono"] ?>">
                    
                </div>
                <div class="col-md-6">
                    <label>{{ trans('leng.Usuario')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                    <input class="form-control" id="usuarioChofer" type="text" disabled value="<?php echo $datos["usuario"] ?>">
                </div>
            </div>
        <br><br>
            <div class="row">
                <div class="col-md-8">
                            <button type="button"  class="btn btn-success btn-sm" onclick="guardarCambiosChoferTransportista();"><i class="fa fa-check"></i>&nbsp;{{ trans('leng.Guardar cambios')}}</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="$('#divElmentosListaChofer').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{ trans('leng.Cancelar')}}</button>
                </div>
            </div>
                
        </div>

    
    
   
    