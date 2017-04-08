@extends('layouts.transportista')
@section('titulo', 'Mis vehículos')

@section('contenido')

    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Mis vehículos') }}</h2>
    <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        <label>{{ trans('leng.Descripción,placa,tipo,chofer')}}.</label>
                              <input class="form-control" id="buscaTitulo" type="text">
                    </div>
                    <div class="col-md-6">
                        <label>{{ trans('leng.Estado del vehículo')}}</label>
                        <div class="form-group">
                           <label class="checkbox-inline">
                              <input type="checkbox" id="asignado"> {{ trans('leng.Asignado') }}
                           </label>
                           <label class="checkbox-inline">
                              <input type="checkbox" id="sinAsignar"> {{ trans('leng.Sin asignar') }}
                           </label>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="activo"> {{ trans('leng.Activo') }}
                           </label>
                           <label class="checkbox-inline">
                              <input type="checkbox" id="inactivo"> {{ trans('leng.Inactivo') }}
                           </label>
                    </div>
                </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                
                </div>
            </div>
        </div>
        
                <br>
                <div class="row">
                    <div class="col-md-9">
                        <button type="button"  class="btn btn-secondary btn-sm" onclick="buscarVehiculo();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="restablecerFiltrosVehiculos();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success btn-sm" onclick="nuevoVehiculo();"><i class="fa fa-plus"></i>&nbsp;{{ trans('leng.Agregar vehículo')}}</button>
                    </div>
                </div>

    </div>
                <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="listaTablaVehiculosTransportista" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th style="width: 18%">{{ trans("leng.Placa")}}</th>
                                         <th style="width: 17%">{{ trans("leng.Tipo")}}</th>
                                         <th style="width: 25%">{{ trans("leng.Chofer")}}</th>
                                         <th style="width: 27%">{{ trans("leng.Descripción")}}</th>
                                         <th style="width: 13%">&nbsp;</th>
                                     </tr>
                                 </thead>
                                 <tbody>

                                 </tbody>
                             </table>
                        </div>
                       </div>
               </div> 
            
    
    <div id="divElmentosListaVehiculos"></div>
    

@endsection

@section('piePagina')
@endsection


@section('otrosScripts')
    {!!Html::script('js/listadoVehiculosTransportista.js')!!}  
@endsection
    