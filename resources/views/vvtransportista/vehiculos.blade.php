@extends('layouts.transportista')

@section('titulo', 'Vehículos')

@section('contenido')
    <h2 class="heading content-header-title">{{ trans('leng.misVehiculos') }}</h2>

    <button class="btn btn-primary" type="button" id="modalAgregarVehiculo"><i class="fa fa-plus-circle"></i> {{trans("leng.Agregar nuevo vehículo")}}</button>

    <div class="row">
        <div class="col-md-8 col-md-offset-2" id="listadoVehiculos" data-url="{{ route('mis-vehiculos-eliminar') }}">
            @include('vvtransportista.vehiculos_lista')
        </div>
    </div>
@stop

@section('piePagina')
    <div id="modalVehiculos" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formVehiculo" action="{{ route('mis-vehiculos') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title">{{ trans('leng.vehiculoAsigna') }}</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="vehiculo">{{trans("leng.Descripción de vehículo")}}:</label>
                            <div class="col-md-8">
                                <input type="text" name="descripcion" id="descripcion" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="tipoAccion" id="tipoAccion">
                        <input type="hidden" name="vehicleId" id="vehicleId">
                        <button type="button" id="asignarVehiculo" class="center btn btn-primary"><strong id="accion">{{trans("leng.Guardar")}}</strong></button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('otrosScripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('js/validator/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/transportista/vehiculos.js') }}"></script>
@stop