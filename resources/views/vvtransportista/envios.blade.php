@extends('layouts.transportista')

@section('titulo', 'Mis envíos')

@section('otrosStyles')
      {!! Html::style('css/star-ratings/star-rating.min.css') !!}
@stop

@section('contenido')
    <h2 class="heading content-header-title">{{ trans('leng.Mis Cargas') }}</h2>

    @if (count($serviceOffers) > 0)
        <form action="{{ route('envios-buscar') }}" id="formBusqueda">
            <div class="form-group" id="estatus" data-url="route('envios-buscar')">
                <div class="checkbox-inline">
                    <label>
                        <input type="checkbox" name="estatus[]" class="estatus" id="pendiente" value="2"> {{ trans('leng.Pendiente de asignación') }}
                    </label>
                </div>
                <div class="checkbox-inline">
                    <label>
                        <input type="checkbox" name="estatus[]" class="estatus" id="pendiente" value="7"> {{ trans('leng.Pendiente de recojo') }}
                    </label>
                </div>

                <div class="checkbox-inline">
                    <label>
                        <input type="checkbox" name="estatus[]" class="estatus" id="pendiente" value="3"> {{ trans('leng.Pendiente de entrega') }}
                    </label>
                </div>

                <div class="checkbox-inline">
                    <label>
                        <input type="checkbox" name="estatus[]" class="estatus" id="pendiente" value="4"> {{ trans('leng.Entregado') }}
                    </label>
                </div>
            </div>
        </form>

        <table class="table table-striped table-bordered table-hover table-highlight table-checkable" id="tablaEnvios" data-url="{{ route('envio-entrega') }}">
            <thead>
            <tr class="bg-danger">
                <th role="columnheader" style="width: 30%">{{ trans('leng.Envío') }}</th>
                <th role="columnheader" style="width: 30%">{{ trans('leng.Cliente') }}</th>
                <th role="columnheader" style="width: 10%">{{ trans('leng.Oferta') }}</th>
                <th role="columnheader" style="width: 20%">{{ trans('leng.Publicación') }}</th>
                <th role="columnheader" style="width: 25%">{{ trans('leng.Moneda') }}</th>
                <th role="columnheader" style="width: 30%">{{ trans('leng.Vehículo asignado') }}</th>
                <th role="columnheader" style="width: 30%">{{ trans('leng.Estatus') }}</th>
                <th role="columnheader" style="width: 35%">&nbsp;</th>
            </tr>
            </thead>
            <tbody id="resultadoOfertas">
                @include('vvtransportista.envios_resultados')
            </tbody>
        </table>
        <span id="token">{{ csrf_field() }}</span>
    @else
        <h4>{{ trans('leng.Aún no se le han aceptado ofertas') }}.</h4>
    @endif
@stop

@section('piePagina')
    <div id="modalVehiculos" class="modal fade" style="font-size: 0.9em;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formVehiculo" action="{{ route('envio-vehiculo') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title">{{ trans('leng.Recoger envío') }}</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="divVehiculoEnvio">
                            <div>
                                <label class="control-label col-md-3" for="vehiculo">{{ trans('leng.Vehículo que trasladará el envío') }}:</label>
                                <div class="col-md-8">
                                    <p>
                                        <select name="vehiculo" id="vehiculo" class="form-control">
                                            <option value="0">{{ trans('leng.Seleccione') }}</option>
                                            <option value="-1">{{ trans('leng.Otro') }}</option>
                                            @foreach($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}">{{ $vehicle->plate }}</option>
                                            @endforeach
                                        </select>
                                    </p>
                                    <p>
                                        <input type="text" id="otroVehiculo" name="otroVehiculo" class="hide form-control" placeholder="Escriba la descripción">
                                    </p>
                                    <p>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label class="control-label col-md-3" for="tipotracking">{{ trans('leng.Tipo de tracking') }}</label>
                                <div class="col-md-8">
                                    <select name="tipotracking" id="tipotracking" class="form-control">
                                        <option value="">{{ trans('leng.Seleccione') }}</option>
                                        <option value="0">Real</option>
                                        <option value="1">Check</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>    
                    
                    <div class="modal-footer">
                        <input type="hidden" id="shipmentId" name="shipmentId">
                        <button type="button" id="asignarVehiculo" class="center btn btn-success">{{trans("leng.Asignar vehículo")}}&nbsp;&nbsp;<i class="fa fa-check-circle"></i></button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
            
            
            
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div id="modalRecoleccion" class="modal fade" style="font-size: 0.9em;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formRecoger" action="{{ route('envio-recoger') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title">{{ trans('leng.Recoger envío') }}</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="divDatosRecoleccion">
                            <div class="col-md-8">
                                <div class="col-md-12">
                                        <label for="text-input"><font style="size: 0.7em;color: red">*</font>{{trans("leng.Contacto")}}</label>
                                        <input type="text" id="nombreContacto" maxlength="100" class="form-control">
                                </div>
                            </div>
                            <br><br><br><br>
                            <div class="col-md-8">
                                <div class="col-md-12">
                                        <label for="text-input">{{trans("leng.DNI")}}</label>
                                        <input type="text" id="dni" maxlength="100" class="form-control">
                                </div>
                            </div>
                            <br><br><br><br>
                            <div class="col-md-8">
                                <div class="col-md-12">
                                        <label for="text-input">{{trans("leng.Comentarios")}}</label>
                                        <textarea name="comentarios" id="comentarios" cols="10" rows="3" maxlength="200" class="form-control"></textarea>
                                </div>
                            </div><br><br><br><br><br><br><br>
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <label for="text-input"><font style="size: 0.7em;color: red">*</font>{{trans("leng.Firma")}}</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload" id="divImagenFirma"><input type="hidden">
                                          <div class="fileupload-new thumbnail" style="width: 50px; height: 50px;">
                                          </div>

                                          <div id="divImagen2" class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px; line-height: 50px;">
                                          </div>
                                          <span class="btn btn-default btn-file">
                                            <span class="fileupload-new">{{ trans('leng.Buscar imagen') }}</span>
                                            <span class="fileupload-exists">{{ trans('leng.Cambiar') }}</span>
                                            <input type="file" accept="image/*">
                                          </span>

                                          <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">{{ trans('leng.Quitar') }}</a>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <label for="text-input">{{trans("leng.Imagen")}}</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload" id="divImagenRecoleccion"><input type="hidden">
                                          <div class="fileupload-new thumbnail" style="width: 50px; height: 50px;">
                                          </div>

                                          <div id="divImagen1" class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px; line-height: 50px;">
                                          </div>
                                          <span class="btn btn-default btn-file">
                                            <span class="fileupload-new">{{ trans('leng.Buscar imagen') }}</span>
                                            <span class="fileupload-exists">{{ trans('leng.Cambiar') }}</span>
                                            <input type="file" accept="image/*">
                                          </span>

                                          <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">{{ trans('leng.Quitar') }}</a>
                                    </div>

                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <input type="hidden" id="shipmentId" name="shipmentId">
                        <button type="button" id="btnRecolectar" class="center btn btn-success">{{trans("leng.Recoger envío")}}&nbsp;&nbsp;<i class="fa fa-check-circle"></i></button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
            
            
            
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="modalCalificacion" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formCalificacion" action="{{ route('mis-envios-calificar') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title">{{ trans('leng.calificacion') }}</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="vehiculo">{{trans("leng.Asigne una calificación al cliente")}}:</label>
                            <div class="col-md-8">
                                <input id="calificacion" name="calificacion" type="number" value="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="vehiculo">{{trans("leng.Comentarios")}}:</label>
                            <div class="col-md-8">
                                <textarea name="comentario" id="comentario" class="form-control" rows="7"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="shipmentIdCalificacion" name="shipmentId">
                        <button type="button" id="asignarCalificacion" class="center btn btn-primary"><strong>{{trans("leng.Asignar calificación")}}</strong></button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
    
    <div id="modalEntrega" class="modal fade" style="font-size: 0.9em;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEntregaEnvio" action="{{ route('envio-entrega') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title">{{ trans('leng.Entregar envío') }}</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="divDatosRecoleccion">
                            <div class="col-md-8">
                                <div class="col-md-12">
                                        <label for="text-input"><font style="size: 0.7em;color: red">*</font>{{trans("leng.Contacto")}}</label>
                                        <input type="text" id="nombreContactoEntrega" maxlength="100" class="form-control">
                                </div>
                            </div>
                            <br><br><br><br>
                            <div class="col-md-8">
                                <div class="col-md-12">
                                        <label for="text-input">{{trans("leng.DNI")}}</label>
                                        <input type="text" id="dniEntrega" maxlength="100" class="form-control">
                                </div>
                            </div>
                            <br><br><br><br>
                            <div class="col-md-8">
                                <div class="col-md-12">
                                        <label for="text-input">{{trans("leng.Comentarios")}}</label>
                                        <textarea name="comentarios" id="comentariosEntrega" cols="10" rows="3" maxlength="200" class="form-control"></textarea>
                                </div>
                            </div><br><br><br><br><br><br><br>
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <label for="text-input"><font style="size: 0.7em;color: red">*</font>{{trans("leng.Firma")}}</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload" id="divImagenFirmaEntrega"><input type="hidden">
                                          <div class="fileupload-new thumbnail" style="width: 50px; height: 50px;">
                                          </div>

                                          <div id="divImagen2Entrega" class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px; line-height: 50px;">
                                          </div>
                                          <span class="btn btn-default btn-file">
                                            <span class="fileupload-new">{{ trans('leng.Buscar imagen') }}</span>
                                            <span class="fileupload-exists">{{ trans('leng.Cambiar') }}</span>
                                            <input type="file" accept="image/*">
                                          </span>

                                          <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">{{ trans('leng.Quitar') }}</a>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <label for="text-input">{{trans("leng.Imagen")}}</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload" id="divImagenEntrega"><input type="hidden">
                                          <div class="fileupload-new thumbnail" style="width: 50px; height: 50px;">
                                          </div>

                                          <div id="divImagen1Entrega" class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px; line-height: 50px;">
                                          </div>
                                          <span class="btn btn-default btn-file">
                                            <span class="fileupload-new">{{ trans('leng.Buscar imagen') }}</span>
                                            <span class="fileupload-exists">{{ trans('leng.Cambiar') }}</span>
                                            <input type="file" accept="image/*">
                                          </span>

                                          <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">{{ trans('leng.Quitar') }}</a>
                                    </div>

                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <input type="hidden" id="shipmentId" name="shipmentId">
                        <button type="button" id="entregarEnvioBtn"  class="center btn btn-success">{{trans("leng.Entregar envío")}}&nbsp;&nbsp;<i class="fa fa-check-circle"></i></button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
            
            
            
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
    
@stop

@section('otrosScripts')
    <script src="{{ asset('js/star-ratings/star-rating.min.js') }}"></script>
    <script src="{{ asset('js/star-ratings/locales/es.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('js/validator/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/transportista/envios.js') }}"></script>
@stop