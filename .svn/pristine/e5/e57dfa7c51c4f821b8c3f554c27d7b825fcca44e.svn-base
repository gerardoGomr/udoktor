@extends('layouts.transportista')

@section('titulo', 'Mis calificaciones')

@section('contenido')
    <style>
        div.calificacion, div.row-stat {
            cursor: pointer;
        }
    </style>
    <h2 class="heading content-header-title">{{ trans('leng.calificaciones') }}</h2>

    <div class="row">
        <div class="col-md-3">
            <h2>{{ $shipper->person->company or '' }}</h2>
            <h4>{{ $shipper->person->firstname . ' ' . $shipper->person->lastname }}</h4>
            <hr>
            <table>
                <tbody>
                    <tr>
                        <td style="width:100px;"><strong>{{trans("leng.Promedio")}}:</strong> </td>
                        <td>
                            <h4 class="pull-left">
                                <?php 
                                for ($i = 1; $i <= 5; $i++) { 
                                    echo ($i <= $shipper->person->starrating) ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                                }
                                ?>
                            </h4>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-9">
            <div class="row" id="calificaciones">
                @if(count($shipmentRatings) > 0)
                    @foreach($shipmentRatings as $shipmentRating)
                        <div class="col-md-2 calificacion" data-id="{{ $shipmentRating->starrating }}">
                            <div class="row-stat">
                                <p class="row-stat-label">{{trans("leng.Calificaciones")}}: </p>
                                <p class="row-stat-label">
                                    <?php
                                        for ($i = 1; $i <= $shipmentRating->starrating; $i++) {
                                            echo '<i class="fa fa-star fa-2x"></i>';
                                        }
                                    ?>
                                </p>
                                <h3 class="row-stat-value text-primary">Total: {{ $shipmentRating->total }}</h3>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <hr>

    <div id="tablaResultados" data-url="{{ url('transportista/mis-calificaciones') }}" data-token="{!! csrf_token() !!}">

    </div>
@stop

@section('otrosScripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('js/transportista/calificaciones.js') }}"></script>
@stop