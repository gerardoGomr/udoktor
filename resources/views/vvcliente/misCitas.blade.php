@extends('layouts.cliente')
@section('titulo', 'Mis citas')

@section('contenido')

    
    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Mis citas') }}</h2>
    <div class="col-md-12" style="font-size: 0.9em">
        <div class="row">
            <div class="col-md-6">
                <label>{{ trans('leng.Compañia, médico, hora')}}.</label>
                <div class="row">
                    <div class="col-md-12">
                              <input class="form-control" id="buscaTitulo" type="text">
                    </div>
                  </div>
            </div>
            <div class="col-sm-6">
               <label>{{ trans('leng.Fecha')}}</label>
                <div class="row">
                    <div class="col-md-5">
                        <div id="cal1" class="input-group date" data-auto-close="true" data-date-format="yyyy-mm-dd" data-date-autoclose="true">
                            <input class="form-control" type="text" id="fecha1">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div> 
                    <div class="col-md-1"><b>{{ trans('leng.A')}}</b></div>
                    <div class="col-md-5">
                        <div id="cal2" class="input-group date" data-auto-close="true"  data-date-format="yyyy-mm-dd" data-date-autoclose="true">
                            <input class="form-control" type="text" id="fecha2">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
               </div>
           </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3">
                <label>{{ trans('leng.Servicios')}}</label>
                <div class="form-group">
                    <select id="serviciosConsulta" class="form-control" multiple="multiple">
                        <?php echo $cadenaServicios; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label>{{ trans('leng.Cita')}}</label>
                <div class="form-group">
                    <select id="tipoCita" class="form-control">
                        <option value="0">{{trans("leng.Seleccione")}}</option>
                        <option value="1">{{trans("leng.En consultorio")}}</option>
                        <option value="2">{{trans("leng.A domicilio")}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label>{{ trans('leng.Estado de la cita')}}</label>
                <div class="form-group">
                   <label class="checkbox-inline">
                      <input type="checkbox" id="pendiente"> {{ trans('leng.Pendiente') }}
                   </label>
                   <label class="checkbox-inline">
                      <input type="checkbox" id="confirmada"> {{ trans('leng.Confirmada') }}
                   </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="rechazada"> {{ trans('leng.Rechazada') }}
                   </label>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <button type="button"  class="btn btn-primary btn-sm" onclick="buscarCitasCliente();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="restablecerFiltrosCitaCliente();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
            </div>
        </div>
       
    </div>       
       <div class="row" style="font-size: 0.9em">
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="listaCitasCliente" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 25%">{{ trans("leng.Compañia / Médico")}}</th>
                                <th style="width: 32%">{{ trans("leng.Servicios / Domicilio")}}</th>
                                <th style="width: 13%">{{ trans("leng.Fecha")}}</th>
                                <th style="width: 10%">{{ trans("leng.Hora")}}</th>
                                <th style="width: 10%">{{ trans("leng.Estado")}}</th>
                                <th style="width: 10%"></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
         </div>
       </div> 
    

    <div id="divModal"></div>
    
    <style type="text/css">
    .multiselect-container {
        width: 100% !important;
    }
    </style>

@endsection

@section('piePagina')
@endsection


@section('otrosScripts')
    {!!Html::script('js/cliente/misCitas.js')!!}  
@endsection
    