@extends('layouts.admin')
@section('titulo', 'Servicios')

@section('contenido')

    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    
    <h2 class="heading content-header-title">{{ trans('leng.Servicios') }}</h2>
    <div class="col-md-12">
                <div class="row" style="font-size: 0.9em">
                    <div class="col-md-5">
                        <label>{{ trans('leng.Nombre, descripción')}}.</label>
                              <input class="form-control" id="buscaTitulo" type="text">
                    </div>
                    <div class="col-md-5">
                        <label>{{ trans('leng.Estado')}}.</label>
                        <div class="form-group">
                            <label class="checkbox-inline">
                              <input type="checkbox" id="activo"> {{ trans('leng.Activo') }}
                           </label>
                           <label class="checkbox-inline">
                              <input type="checkbox" id="inactivo"> {{ trans('leng.Inactivo') }}
                           </label>
                        </div>
                    </div>
                </div>
                <br>
                
                <br>
                <div class="row">
                    <div class="col-md-9">
                        <button type="button"  class="btn btn-secondary btn-sm" onclick="buscarSErvicios();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="restablecerFiltrosServicios();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success btn-sm" onclick="nuevoServicioAdmin();"><i class="fa fa-plus"></i>&nbsp;{{ trans('leng.Agregar servicios')}}</button>
                    </div>
                </div>
        </div>
                <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="listaTablaServicios" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th style="width: 20%">{{ trans("leng.Nombre")}}</th>
                                         <th style="width: 20%">{{ trans("leng.Descripción")}}</th>
                                         <th style="width: 12%">{{ trans("leng.Precio sugerido")}}</th>
                                         <th style="width: 12%">{{ trans("leng.Precio mínimo")}}</th>
                                         <th style="width: 12%">{{ trans("leng.Precio máximo")}}</th>
                                         <th style="width: 10%">{{ trans("leng.Estado")}}</th>
                                         <th style="width: 10%">&nbsp;</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                 </tbody>
                             </table>
                        </div>
                       </div>
               </div> 
            
    
    <div id="divModal"></div>
    

@endsection

@section('piePagina')
@endsection


@section('otrosScripts')
    {!!Html::script('js/admin/servicios.js')!!}  
@endsection
    