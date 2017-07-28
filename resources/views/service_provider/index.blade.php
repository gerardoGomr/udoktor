@extends('layouts.service_provider.master')

@section('content')
    @php
    $user = Illuminate\Support\Facades\Auth::user();
    @endphp
    @if (!$user->hasCompletedProfile())
        <div class="alert bg-pink">
            <p class="lead">Hemos detectado que no ha completado su perfil. Por favor, completa los siguientes datos:</p>
            @if(!$user->hasServices())
                <a href="{{ url('prestador-servicios/servicios') }}" class="btn btn-default waves-effect">Mis servicios</a>
            @endif

            @if(!$user->hasSchedules())
                <a href="{{ url('prestador-servicios/agenda/configuracion') }}" class="btn btn-default waves-effect">Mis horarios</a>
            @endif
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