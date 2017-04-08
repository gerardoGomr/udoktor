<br>
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<input type="hidden" name="claveCompany" value="{{ $clave}}" id="claveCompany">

    <div class="col-md-12 flet-lab">
            <div class="row flet-lab">
                <div class="col-md-6">
                    <label>{{ trans('leng.Primer nombre')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                          <input class="form-control" id="primerNombre" type="text">
                </div>
                <div class="col-md-6">
                    <label>{{ trans('leng.Segundo nombre')}}</label>
                          <input class="form-control" id="segundoNombre" type="text">
                    
                </div>
            </div>
        <br>
        <div class="row flet-lab">
                <div class="col-md-6">
                    <label>{{ trans('leng.Primer apellido')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                          <input class="form-control" id="primerApellido" type="text">
                </div>
                <div class="col-md-6">
                    <label>{{ trans('leng.Segundo apellido')}}</label>
                          <input class="form-control" id="segundoApellido" type="text">
                    
                </div>
            </div>
        <br>
        <div class="row flet-lab">
                <div class="col-md-6">
                    <label>{{ trans('leng.DNI')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                    <input class="form-control" id="dniChofer" type="text" onblur="generarUsuario();return;"  onkeyup="generarUsuario();return;" onkeypress="generarUsuario();">
                </div>
            
                <div class="col-md-6">
                    <label>{{ trans('leng.Licencia de conducir')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                    <input class="form-control" id="licenciaChofer" type="text" maxlength="20">
                </div>
            
            </div>
        <br>
        <div class="row flet-lab">
            <div class="col-md-6">
                    <label>{{ trans('leng.Telefono')}}</label>
                          <input class="form-control" id="telefonoChofer" type="text">
                    
                </div>
                <div class="col-md-6">
                    <label>{{ trans('leng.Usuario')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                    <input class="form-control" id="usuarioChofer" type="text" value="{{$clave}}" readonly>
                </div>
            </div>
        <br>
        <div class="row flet-lab">
                <div class="col-md-6">
                    <label>{{ trans('leng.Contraseña')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                    <input class="form-control" id="passChofer" type="password">
                    
                </div>
        </div>
        
        <br><br>
            <div class="row">
                <div class="col-md-8">
                            <button type="button"  class="btn btn-success btn-sm" onclick="guardarChoferTransportista();"><i class="fa fa-check"></i>&nbsp;{{ trans('leng.Aceptar')}}</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="$('#divElmentosListaChofer').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{ trans('leng.Cancelar')}}</button>
                </div>
            </div>
                
        </div>

    
    
   
    