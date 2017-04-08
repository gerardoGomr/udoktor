@extends('layouts.transportista')

@section('titulo', 'Mis ofertas')

@section('contenido')
    <h2 class="heading content-header-title">{{trans("leng.Mis ofertas")}}</h2>

    @if (count($serviceOffers) > 0)
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('ofertas-buscar') }}" id="formBusqueda">
                    <label class="control-label">{{trans("leng.Estado oferta")}}:</label>
                    <div class="form-group" id="selectEstatus" data-url="">
                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" name="estatus[]" class="estatus" value="1"> {{trans("leng.Activa")}}
                            </label>
                        </div>

                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" name="estatus[]" class="estatus" value="2"> {{trans("leng.Aceptada")}}
                            </label>
                        </div>

                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" name="estatus[]" class="estatus" value="3"> {{trans("leng.Rechazada")}}
                            </label>
                        </div>

                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" name="estatus[]" class="estatus" value="4"> {{trans("leng.Cancelada")}}
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-striped table-bordered table-hover table-highlight table-checkable" id="tablaOfertas" data-url="{{ route('oferta-cancelar') }}">
            <thead>
                <tr class="bg-danger">
                    <th style="width: 200px;" role="columnheader">{{trans("leng.Envío")}}</th>
                    <th style="width: 200px;" role="columnheader">{{trans("leng.Cliente")}}</th>
                    <th style="width: 100px;" role="columnheader">{{trans("leng.Oferta")}}</th>
                    <th style="width: 150px;" role="columnheader">{{trans("leng.Publicación")}}</th>
                    <th style="width: 150px;" role="columnheader">{{trans("leng.Recoger")}}</th>
                    <th style="width: 150px;" role="columnheader">{{trans("leng.Entregar")}}</th>
                    <th role="columnheader">{{trans("leng.Estatus")}}</th>
                    <th role="columnheader">&nbsp;</th>
                </tr>
            </thead>
            <tbody id="resultadoOfertas">
                @include('vvtransportista.ofertas_listado_resultados')
            </tbody>
        </table>
        <span id="token">{{ csrf_field() }}</span>
    @else
        <h4>{{trans("leng.No ha publicado ofertas")}}</h4>
    @endif
@stop

@section('otrosScripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('js/transportista/ofertas_listado.js') }}"></script>
@stop