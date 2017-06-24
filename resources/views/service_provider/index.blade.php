@extends('layouts.service_provider.master')

@section('content')
    @if (!\Auth::user()->hasCompletedProfile())
        <div class="alert bg-red">
            Hemos detectado que no ha completado su perfil. Antes de continuar, por favor, <a href="/perfil">complete su perfil</a>
        </div>
    @endif

    <!-- Basic Alerts -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>AGENDA</h2>
                </div>
                <div class="body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ mix('js/service_provider/index.js') }}"></script>
@stop