<?php
$paisArreglo=Array();
$estadoArreglo=Array();
$ciudadArreglo=Array();
$paisSeleccionado=$datosUsuario['idPais'];
$estadoSeleccionado=$datosUsuario['idEstado'];
$ciudadSeleccionada=$datosUsuario['idCiudad'];

foreach($pais as $rowPais){
      $paisArreglo[$rowPais->id]=$rowPais->name;
  }

foreach($estado as $rowEstado){
      $estadoArreglo[$rowEstado->id]=$rowEstado->name;
  }

  foreach($ciudad as $rowCiudad){
      $ciudadArreglo[$rowCiudad->id]=$rowCiudad->name;
  }


?>
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<input type="hidden" name="imagenModificada" value="0" id="imagenModificada">
<br>
      <font style="color: #b9261e"><h3 class="title">{{trans("leng.Información del usuario")}}</h3></font>
      <br>
      <div class="col-md-12" style="font-size: 0.9em">
          <label for="text-input">&nbsp;{{trans('leng.Foto de perfil')}}</label>
          <div class="row">
            <div class="col-sm-6">
                <?php
                    $vDiv="fileupload fileupload-new";
                    $imagenPer="";
                    if(trim($datosUsuario["imagen"])!=""){
                      $vDiv="fileupload fileupload-exists";
                      $imagenPer=asset($datosUsuario["imagen"]);
                    }
                ?>
                
                <div class="<?php echo $vDiv; ?>" data-provides="fileupload"><input type="hidden">
                      <div class="fileupload-new thumbnail" style="width: 150px; height: 150px;">
                      </div>

                      <div id="imgPerfil" class="fileupload-preview fileupload-exists thumbnail" style="width: 150px; height: 150px; line-height: 50px;">
                          <?php if(trim($datosUsuario["imagen"])!=""){ ?>
                                <img id="i1" style="width: 150px;" src="{{$datosUsuario["imagen"]}}">
                          <?php } ?>
                      </div>
                      <span class="btn btn-default btn-file">
                        <span class="fileupload-new">{{ trans('leng.Buscar imagen') }}</span>
                        <span class="fileupload-exists">{{ trans('leng.Cambiar') }}</span>
                        <input type="file" accept="image/*">
                      </span>

                    <a href="#" class="btn btn-default fileupload-exists" onclick="quitarImagenPerfil()" data-dismiss="fileupload">{{ trans('leng.Quitar') }}</a>
                </div>
                
                
                </div> 
              
            </div>
              
        <div class="row">
                <div class="col-sm-6">
                    <label for="text-input"><span style="color:#b9261e">*</span>&nbsp;{{trans('leng.Nombre')}}</label>
                  <input type="text" id="primerNombre" class="form-control" value="{{$datosUsuario['nombre1']}}">
                </div>
                <div class="col-sm-6">
                  <label for="text-input"><span style="color:#b9261e">*</span>&nbsp;{{trans('leng.Telefono')}}</label>
                  <input type="text" id="telefono" class="form-control" value="{{$datosUsuario['telefono']}}">
                </div>
        </div>
        <br>
        <div class="row">
                <div class="col-sm-6">
                  <label for="text-input"><span style="color:#b9261e">*</span>&nbsp;{{trans('leng.Pais')}}</label>
                  {!! Form::select('paisT', $paisArreglo, $paisSeleccionado, ['id'=>'paisT','placeholder' => 'Seleccione', 'class'=>'form-control','onchange'=>'buscarEstado(this.value);']); !!}
                </div>

                <div class="col-sm-6">
                  <label for="text-input"><span style="color:#b9261e">*</span>&nbsp;{{trans('leng.Estado')}}</label>
                  {!! Form::select('estadoCuenta',$estadoArreglo, $estadoSeleccionado, ['id'=>'estadoCuenta','placeholder' => 'Seleccione','tabindex'=>'9','class'=>'form-control','onchange'=>'buscarCiudad(this.value);']); !!}
                </div>
        </div>
        <br>
        <div class="row">
                <div class="col-sm-6">
                  <label for="text-input"><span style="color:#b9261e">*</span>&nbsp;{{trans('leng.Ciudad')}}</label>
                  {!! Form::select('ciudadCuenta',$ciudadArreglo, $ciudadSeleccionada, ['id'=>'ciudadCuenta','placeholder' => 'Seleccione','tabindex'=>'10','class'=>'form-control']); !!}
                </div>
        </div>
        <br><br>
        <div class="row">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-success btn-sm" onclick="guardarPerfilPrestador()"><i class="fa fa-check-circle"></i>&nbsp;{{trans("leng.Guardar cambios")}}</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="$('#elementosPerfil').html('');$('#back-to-top').click();"><i class="fa fa-times-circle"></i>&nbsp;{{trans("leng.Cancelar")}}</button>
                </div>
        </div>
     </div>
