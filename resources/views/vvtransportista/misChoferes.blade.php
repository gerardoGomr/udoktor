@extends('layouts.transportista')
@section('titulo', 'Mis choferes')

@section('contenido')

    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Mis choferes') }}</h2>
    <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        <label>{{ trans('leng.Chofer,Usuario,Teléfono')}}.</label>
                              <input class="form-control" id="buscaTitulo" type="text">
                    </div>
                    <div class="col-md-3">
                        <label>{{ trans('leng.DNI')}}.</label>
                              <input class="form-control" id="dniFiltro" type="text">
                    </div>
                    <div class="col-md-3">
                        <label>{{ trans('leng.Licencia de conducir')}}.</label>
                              <input class="form-control" id="licenciaFiltro" type="text">
                    </div>
                </div>
        
                <br>
                <div class="row">
                    <div class="col-md-10">
                        <button type="button"  class="btn btn-secondary btn-sm" onclick="buscarChoferes();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="restablecerFiltrosChoferes();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success btn-sm" onclick="nuevoChofer();"><i class="fa fa-plus"></i>&nbsp;{{ trans('leng.Agregar chofer')}}</button>
                    </div>
                </div>

    </div>
                <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="listaTablaChoferesTransportista" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th style="width: 30%">{{ trans("leng.Chofer")}}</th>
                                         <th style="width: 13%">{{ trans("leng.DNI")}}</th>
                                         <th style="width: 13%">{{ trans("leng.Licencia de conducir")}}</th>
                                         <th style="width: 13%">{{ trans("leng.Usuario")}}</th>
                                         <th style="width: 13%">{{ trans("leng.Telefono")}}</th>
                                         <th style="width: 12%">&nbsp;</th>
                                     </tr>
                                 </thead>
                                 <tbody>

                                 </tbody>
                             </table>
                        </div>
                       </div>
               </div> 
            
    
    <div id="divElmentosListaChofer"></div>
    

@endsection

@section('piePagina')
@endsection


@section('otrosScripts')
    {!!Html::script('js/listadoChoferesTransportista.js')!!}  
@endsection
    