@extends('layouts.transportista')
@section('titulo', 'Mis grupos')

@section('contenido')

    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Mis grupos') }}</h2>
    <div class="col-md-12">
            <div class="row">
                <label>{{ trans('leng.Grupo,Nombre del cliente')}}.</label>
                <div class="row">
                    <div class="col-md-6">
                              <input class="form-control" id="buscaTitulo" type="text">
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button"  class="btn btn-secondary btn-sm" onclick="buscarGrupos();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="restablecerFiltrosGrupos();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
                    </div>
                </div>
          </div>
    </div>
                <div class="row">
                     <div class="col-md-9">
                         <div class="table-responsive">
                             <table id="listaTablaGruposTransportista" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th style="width: 50%">{{ trans("leng.Nombre del grupo")}}</th>
                                         <th style="width: 50%">{{ trans("leng.Cliente")}}</th>
                                     </tr>
                                 </thead>
                                 <tbody>

                                 </tbody>
                             </table>
                        </div>
                       </div>
               </div> 

    <div id="divElmentosListaGrupo"></div>
    

@endsection

@section('piePagina')
@endsection


@section('otrosScripts')
    {!!Html::script('js/listadoGruposTransportista.js')!!}  
@endsection
    