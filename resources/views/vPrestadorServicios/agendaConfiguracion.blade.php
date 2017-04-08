@extends('layouts.prestadorServicios')
@section('titulo', trans('leng.Configurar agenda'))
@section('contenido')
<?php
$cadenaHorario="";
$reg=1;
$tiempo=0;
if($dataPerson->diarytype!=""){
    foreach($agendaConf as $dta){
        $vfinal=($dataPerson->diarytype=="1")?"-----":$dta->vend;
        $vlimite=($dataPerson->diarytype=="2")?"-----":$dta->vlimit;
        $vid=$dta->id;
        $cadenaHorario.="<tr>
                    <td>$dta->start</td>
                    <td>$vfinal</td>
                    <td>$vlimite</td>
                    <td><a href='javascript:;' class='elimina' title='Eliminar horario' onclick='eliminarHorario($vid)'><img src='/img/cancelado.png' width='22px;'></i></a></td>
                    </tr>
                    ";
    }
    $tiempo=  Udoktor\Funciones::formato_numeros($dataPerson->timeservice, ",", ".");
}

    if(count($dataPerson)==0){
        $cadenaHorario.="<tr>
             <td colspan='4' style='text-align:center'>".trans("leng.No hay horarios establecidos")."</td>
             </tr>
                    ";
    }

    
    
?>
<input type="hidden" value="<?php echo $dataPerson->diarytype;  ?>" id="tipoOculto">
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">

    <div class="row" style="font-size: 0.9em">
            <h2 class="heading content-header-title">{{ trans('leng.Configurar agenda') }}.</h2>
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-4">
                        <label for="text-input">{{ trans('leng.Tipo de agenda') }}</label>
                          <select class="form-control" id="tipoAgenda" onchange="tipoAgenda(this.value)">
                            <option value="0" selected>{{trans("leng.Seleccione")}}</option>
                            <option value="1" <?php if($dataPerson->diarytype=="1") echo "selected" ?> >{{trans("leng.Hora fija")}}</option>
                            <option value="2" <?php if($dataPerson->diarytype=="2") echo "selected" ?>>{{trans("leng.Rango de horas")}}</option>
                           </select>
                        <small id="texto1" style="display: none"><b>* {{trans("leng.Si selecciona hora fija los eventos se programarán a la(s) hora(s) que indique con un limite determinado de clientes")}}.</b></small>
                        <small id="texto2" style="display: none"><b>* {{trans("leng.Si selecciona Rango de horas,los eventos se programarán en los rangos especificados")}}.</b></small>
                    </div>
                    <div class="col-sm-3" id="duracionServicio" style="display: none">
                       <label for="text-input">{{ trans('leng.Duración del servicio (min)') }}</label>
                       <div class="form-group">
                            <div class="input-group">
                                <input maxlength="7" value="<?php echo $tiempo ?>" class="form-control" id="tiemposervicio" readonly> 
                                  <span class="input-group-btn">
                                      <button class="btn btn-secondary" onclick="cambiarTiempo();" type="button">{{trans("leng.Modificar")}}</button>
                                  </span>
                            </div>
                       </div>                        
                    </div>
                    <div class="col-sm-5"></div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2">
                        <button type="button" onclick="nuevoHorario();"  class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp;{{trans("leng.Agregar horario")}}</button>
                    </div>
                </div>
            </div>
            <br> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-8" id="tablaHorario">
                        <table class="table table-bordered table-highlight" id="listaHorario">
                            <thead>
                              <tr>
                                <th style="width: 150px;">{{trans("leng.Hora inicio")}}</th>
                                <th style="width: 150px;">{{trans("leng.Hora fin")}}</th>
                                <th style="width: 150px;">{{trans("leng.Número de clientes")}}</th>
                                <th style="width: 20px;"></th>
                              </tr>
                            </thead>
                            <tbody>
                                 <?php echo $cadenaHorario; ?>
                            </tbody>
                          </table>
                    </div>    
                    <div  class="col-sm-4"></div>
                </div>
            </div>
            <br> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-4">
                        <a type="button" href="{{url("/prestadorServicios")}}" style="width: 110px;" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i>&nbsp;{{trans("leng.Salir")}}</a>
                    </div>
                    <div class="col-sm-8"></div>
                </div>
            </div>
        
            <div id="divModal"></div>
            <div id="divNuevoHorario"></div>
      </div> 
        


@endsection

@section('piePagina')
        
{!!Html::script('js/prestadorServicio/agendaConfiguracion.js')!!}  
@endsection

