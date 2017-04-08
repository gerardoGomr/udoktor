@extends('layouts.admin')
@section('titulo', 'Clasificación')

@section('contenido')

    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    
    <h2 class="heading content-header-title">{{ trans('leng.Clasificación') }}</h2>
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
                        <button type="button"  class="btn btn-secondary btn-sm" onclick="buscarClasificacion();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="restablecerFiltrosClasificacion();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success btn-sm" onclick="nuevoClasificacion();"><i class="fa fa-plus"></i>&nbsp;{{ trans('leng.Agregar clasificación')}}</button>
                    </div>
                </div>
        </div>
                <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="listaTablaClasificacion" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th style="width: 25%">{{ trans("leng.Nombre")}}</th>
                                         <th style="width: 15%">{{ trans("leng.Descripción")}}</th>
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
            
    
    <div id="divElmentosClasificacion"></div>
    

@endsection

@section('piePagina')
@endsection


@section('otrosScripts')
    {!!Html::script('js/admin/clasificacion.js')!!}  
@endsection
    