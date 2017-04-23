@extends('layouts.principal')
@section('titulo', 'Mis preguntas')

@section('contenido')

    
    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Mis preguntas') }}</h2>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-5">
                <label>{{ trans('leng.Transportista, Envio, Pregunta')}}.</label>
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
            <div class="col-md-12">
                <div class="form-group">
                   <label class="checkbox-inline">
                      <input type="checkbox" id="respondidas"><b> {{ trans('leng.Respondidas') }}</b>
                   </label>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <button type="button"  class="btn btn-primary btn-sm" onclick="buscarPreguntasTransportista();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="restablecerFiltrosPreguntaTransportista();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
            </div>
        </div>
       
    </div>       
       <div class="row">
       <div class="col-md-12">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="listaPreguntasGeneral" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width: 23%">{{ trans("leng.Cliente")}}</th>
                            <th style="width: 23%">{{ trans("leng.Envío")}}</th>
                            <th style="width: 23%">{{ trans("leng.Pregunta")}}</th>
                            <th style="width: 16%">{{ trans("leng.Fecha")}}</th>
                            <th style="width: 6%">{{ trans("leng.Ver")}}</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
           </div>
          </div>
         </div>
        </div> 
    

    <div id="divMostrarPreguntaTransportista"></div>

@endsection

@section('piePagina')
@endsection


@section('otrosScripts')
    {!!Html::script('js/misPreguntasTransportista.js')!!}  
@endsection
    