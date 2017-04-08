@extends('layouts.cliente')
@section('titulo', 'Agendar cita')

@section('contenido')
<?php
$fechaActual=date("d-m-Y");
?>
<input type="hidden" id='tipohorario' value="{{$arregloDatos["tipohorario"]}}">
<input type="hidden" id='idPrestador' value="{{$arregloDatos["idPrestador"]}}">
<input type="hidden" id='fechaOculta' value="<?php echo $fechaActual; ?>">


  <h2 class="heading content-header-title">{{ trans('leng.Agendar cita') }}</h2>
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
              <table>
                  <tr>
                      <td style="width: 80px;"><b>{{trans("leng.Fecha")}}:</b></td>
                      <td><input type="text" class="form-control" readonly id="fechaEtiqueta" value="<?php echo $fechaActual ?>"></td>
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
       
   </div>
                
<div id="divModal"></div>
@endsection

@section('piePagina')
        
 {!!Html::script('js/cliente/agenderCita.js')!!}   
@endsection

