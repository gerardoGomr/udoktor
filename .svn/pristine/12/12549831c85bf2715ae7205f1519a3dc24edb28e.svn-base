@extends('layouts.transportista')
@section('titulo', 'Mis mensajes')

@section('contenido')

    
    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Mensajes enviados') }}</h2>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-5">
                <label>{{ trans('leng.Receptor, mensaje')}}.</label>
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
                <button type="button"  class="btn btn-primary btn-sm" onclick="buscarMenajesEnviados();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="restablecerFiltrosMensajesEnviados();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
            </div>
        </div>
       
    </div>       
       <div class="row">
       <div class="col-md-12">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="listaMensajesEnviadosGeneral" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width: 25%">{{ trans("leng.Para")}}</th>
                            <th style="width: 50%">{{ trans("leng.Mensaje")}}</th>
                            <th style="width: 25%">{{ trans("leng.Fecha")}}</th>
                            <th style="width: 25%"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
           </div>
          </div>
         </div>
        </div> 
    </div>

    <div id="divListadoEliminar"></div>
    <div id="divEnviarMensaje"></div>
    

@endsection

@section('piePagina')
@endsection


@section('otrosScripts')
    {!!Html::script('js/misMensajesEnviados.js')!!}  
@endsection
    