@extends('layouts.cliente')
@section('titulo', 'Agendar cita a domicilio')

@section('contenido')
<?php
$fechaActual=date("d-m-Y");
?>
<input type="hidden" id='tipohorario' value="{{$arregloDatos["tipohorario"]}}">
<input type="hidden" id='idPrestador' value="{{$arregloDatos["idPrestador"]}}">
<input type="hidden" id='fechaOculta' value="<?php echo $fechaActual; ?>">

  <h2 class="heading content-header-title">{{ trans('leng.Agendar cita a domicilio') }}</h2>
   <div class="row" style="font-size: 0.9em">    
      <div class="col-md-4">
            <div class="well">
                <h4><?php echo $arregloDatos["compania"]; ?></h4>
                <h5><?php echo $arregloDatos["nombre"]; ?></h5>
                <hr>

                <ul class="icons-list">
                  <li><i class="icon-li fa fa-envelope"></i> <?php echo $arregloDatos["correo"]; ?></li>
                  <li><i class="icon-li fa fa-phone"></i> <?php echo $arregloDatos["telefono"]; ?></li>
                  <li><i class="icon-li fa fa-map-marker"></i> <?php echo $arregloDatos["ubicacion"]; ?></li>
                </ul>
                <br>
                <h5>{{trans("leng.Seleccione una fecha para ver la disponibilidad")}}</h5>
                <div id="dp-ex-5" class="" data-date-format="dd-mm-yyyy"></div>
            </div>
          
      </div>
       <div class="col-md-5">
          <div id="divFechaCita">
              <table style="width: 400px;">
                  <tr>
                      <td style="width: 80px;"><b>{{trans("leng.Fecha")}}:</b></td>
                      <td><input type="text" class="form-control" readonly id="fechaEtiqueta" value="<?php echo $fechaActual ?>"></td>
                  </tr>
                  <tr>
                      <td colspan="2" style="width: 80px;">&nbsp;</td>
                  </tr>
                  <tr id="rowUbicacion">
                      <td style="width: 80px; vertical-align:top"><b>{{trans("leng.Ubicación")}}:</b></td>
                      <td>
                            <div class="input-group">
                                <input class="form-control" id="idUbicacion" type="text" readonly>
                                <span class="input-group-btn">
                                  <button class="btn btn-secondary" type="button" onclick="mostrarMapaDomicilio();"><i class="fa fa-map-marker"></i></button>
                                </span>
                            </div>
                      </td>
                  </tr>
                  <tr id="rowInfo">
                      <td style="width: 80px; vertical-align:top"><b>{{trans("leng.Más información (opcional)")}}:</b></td>
                      <td><textarea name="masinfo" id="masinfo" cols="10" maxlength="200" rows="3" class="form-control" placeholder="{{trans("leng.# Casa, Teléfono, etc..")}}"></textarea></td>
                  </tr>
              </table>
          <br>
          </div>
          <div id="divHorario">
          </div>
       </div>
        <div class="col-md-3">
          
            <h4>{{trans("leng.Servicios")}}</h4>
            <div class="panel-group accordion" id="accordion">
                <?php echo $arregloDatos["servicios"];?>
            </div>
          </div>
      </div>
       
  <div id="divModal"></div>
  
  <input type="hidden" id="latitudUbicacion" value="">
    <input type="hidden" id="longitudUbicacion" value="">

@endsection

@section('piePagina')
        
 {!!Html::script('js/cliente/agenderCitaDomicilio.js')!!}   
@endsection


