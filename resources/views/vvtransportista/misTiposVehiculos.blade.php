@extends('layouts.transportista')
@section('titulo', 'Tipo de vehículo')

@section('contenido')

    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Tipos de vehículo') }}</h2>
    <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        <label>{{ trans('leng.Nombre')}}.</label>
                              <input class="form-control" id="buscaTitulo" type="text">
                    </div>
                    <div class="col-md-6">
                        <label>{{ trans('leng.Estado del tipo de vehículo')}}</label>
                        <div class="form-group">
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
                    <div class="col-md-7">
                        <button type="button"  class="btn btn-secondary btn-sm" onclick="buscarTipoVehiculo();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="restablecerFiltrosTipoVehiculos();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success btn-sm" onclick="nuevoTipoVehiculo();"><i class="fa fa-plus"></i>&nbsp;{{ trans('leng.Agregar tipo de vehículo')}}</button>
                    </div>
                </div>

    </div>
                <div class="row">
                     <div class="col-md-10">
                         <div class="table-responsive">
                             <table id="listaTablaTiposVehiculosTransportista" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th style="width: 25%">{{ trans("leng.Nombre")}}</th>
                                         <th style="width: 18%">{{ trans("leng.Estado")}}</th>
                                         <th style="width: 15%">&nbsp;</th>
                                     </tr>
                                 </thead>
                                 <tbody>

                                 </tbody>
                             </table>
                        </div>
                       </div>
               </div> 
            
    
    <div id="divElmentosListaTiposVehiculos"></div>
    

@endsection

@section('piePagina')
@endsection


@section('otrosScripts')
    {!!Html::script('js/listadoTipoVehiculosTransportista.js')!!}  
@endsection
    