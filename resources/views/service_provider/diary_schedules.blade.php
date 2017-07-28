@extends('layouts.service_provider.master')

@section('content')
    <div class="row clearfix">
        <div class="col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>CONFIGURACIÓN DE AGENDA</h2>
                </div>
                <div class="body table-responsive" id="schedules">
                    @include('service_provider.diary_schedules_list')
                </div>
            </div>
        </div>
    </div>

    @include('layouts.loader')
@stop

@section('js')
    <script src="{{ mix('js/service_provider/diary_schedules.js') }}"></script>
@stop