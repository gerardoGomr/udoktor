<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<br>
      <font style="color: #b9261e"><h3 class="title">{{trans("leng.Cambiar contraseña")}}</h3></font>
      <br>
      <div class="col-md-12" style="font-size: 0.9em">
        <div class="row">
                <div class="col-sm-6">
                    <label for="text-input"><span style="color:#b9261e">*</span>&nbsp;{{trans('leng.Contraseña anterior')}}</label>
                    <input type="password" id="passAnterior" class="form-control" value="">
                </div>
        </div>
        <br>
        <div class="row">
                <div class="col-sm-6">
                    <label for="text-input"><span style="color:#b9261e">*</span>&nbsp;{{trans('leng.Nueva contraseña')}}</label>
                  <input type="password" id="passNueva" class="form-control" value="">
                </div>
        </div>
        <br>
        <div class="row">
                <div class="col-sm-6">
                    <label for="text-input"><span style="color:#b9261e">*</span>&nbsp;{{trans('leng.Confirmar contraseña')}}</label>
                  <input type="password" id="passNuevaConfirmar" class="form-control" value="">
                </div>
        </div>
        <br><br>
        <div class="row">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-success btn-sm" onclick="guardarCambioContrasena()"><i class="fa fa-check-circle"></i>&nbsp;{{trans("leng.Guardar cambios")}}</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="$('#elementosPerfil').html('');$('#back-to-top').click();"><i class="fa fa-times-circle"></i>&nbsp;{{trans("leng.Cancelar")}}</button>
                </div>

                

        </div>
     </div>
              
        


    
