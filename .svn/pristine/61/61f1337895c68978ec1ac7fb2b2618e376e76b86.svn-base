@extends('layouts.transportista')
@section('titulo', 'Tracking')

@section('contenido')
    <input type="hidden" id="green" value="{{ url('/img/dot-green.png') }}">
    <input type="hidden" id="red" value="{{ url('/img/dot-red.png') }}">
    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Tracking') }}</h2>

    <form action="{{ route('buscar-tracking') }}" id="formTracking">
    </form>

    <div id="contenedorMapa" style="height:500px">
        <div id="mapaOfertas" style="height:100%"></div>
    </div>
                    
              

    

@endsection


@section('piePagina')
        pie de pagina
        <div id="modalCargando" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <span><i class="fa fa-spinner fa-spin fa-5x"></i></span>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
@endsection


@section('otrosScripts')
    {!!Html::script('js/transportista/tracking.js')!!}  
@endsection