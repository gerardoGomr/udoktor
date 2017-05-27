@extends('layouts.master_small')

@section('body-class', 'signup-page')
@section('body-body', 'signup-box')

@section('content')
    <div id="divPrincipal">
        <div class="msg">¡Está a un paso de unirse al grupo Udoktor!</div>
        <form action="/crear-cuenta" id="formCrearCuenta" class="form-horizontal">
            <div id="informacionBasica">
                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="email" class="control-label">Correo electrónico:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-4">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="email" id="email" class="form-control required" data-rule-email="true" placeholder="ejemplo@ejemplo.com" autofocus>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="pass" class="control-label">Contraseña:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-4">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="password" name="pass" id="pass" class="form-control required" data-rule-minlength="8" placeholder="Longitud mínima de 8 caracteres">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="tipoCuenta" class="control-label">Cuenta a crear:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-9">
                        <div class="form-group">
                            <input type="radio" name="tipoCuenta" id="cuentaCliente" value="1" class="required radio-col-red">
                            <label for="cuentaCliente" data-toggle="tooltip" title="Cliente es la persona que solicita atención en el domicilio"> Cuenta cliente</label><br>
                            <input type="radio" name="tipoCuenta" id="cuentaPrestador" value="2" class="required radio-col-red">
                            <label for="cuentaPrestador" data-toggle="tooltip" title="Prestador de servicios es el profesional que puede brindar algún servicio"> Cuenta prestador de servicios</label>

                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="nombre" class="control-label">Nombre:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-4">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="nombre" id="nombre" class="form-control required" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="paterno" class="control-label">A. Paterno:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-4">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="paterno" id="paterno" class="form-control required" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="materno" class="control-label">A. Materno:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-4">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="materno" id="materno" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="pais" class="control-label">País de residencia:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-4">
                        <div class="form-group">
                            <select class="form-control show-tick required aUnit" name="pais" id="pais" data-live-search="true" data-target="estado">
                                <option value="" selected>Seleccione</option>
                                <option value="-1">Otro</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->getId() }}">{{ $country->getName() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="estado" class="control-label">Estado de residencia:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-4">
                        <div class="form-group">
                            <select class="form-control show-tick required aUnit" name="estado" id="estado" data-live-search="true" data-target="municipio">
                                <option value="" selected>Seleccione</option>
                                <option value="1">demo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="municipio" class="control-label">Municipio de residencia:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-4">
                        <div class="form-group">
                            <select class="form-control show-tick required" name="municipio" id="municipio" data-live-search="true">
                                <option value="" selected>Seleccione</option>
                                <option value="1">demo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="telefono" class="control-label">¿Algún número de contacto?:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-4">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="telefono" id="telefono" class="form-control required">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-md-offset-3 col-sm-offset-4">
                        <div class="form-group">
                            <input type="checkbox" name="aceptaTerminos" id="aceptaTerminos" class="filled-in chk-col-red">
                            <label for="aceptaTerminos">&nbsp; Estoy de acuerdo con los términos y condiciones de Udoktor</label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row clearfix">
                    <div class="col-md-offset-3 col-sm-offset-4">
                        <button id='paso2' type="button" class="btn btn-success btn-lg waves-effect">Siguiente&nbsp;<i class="fa fa-arrow-circle-right"></i></button>
                    </div>
                </div>
            </div>

            <div id="informacionPrestador" style="display: none;">
                <div class="msg">Por favor, complete su clasificación y servicios que brinda</div>
                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="clasificacion" class="control-label">Clasificación:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-4">
                        <div class="form-group">
                            <select name="clasificacion" id="clasificacion" class="form-control show-tick" data-live-search="true" required>
                                <option value="" selected="">Seleccione</option>
                                <option value="-1">Otro</option>
                                @foreach($classifications as $classification)
                                    <option value="{{ $classification->getId() }}">{{ $classification->getName() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                        <label for="servicios" class="control-label">Servicios que brinda:</label>
                    </div>
                    <div class="col-sm-8 col-xs-12 col-md-9">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="servicios" id="servicios" class="form-control required">
                            </div>

                            <p class="form-control-static text-muted">Escriba algún servicio que usted brinde. Ejemplo: aplicación de inyecciones</p>
                            <input type="hidden" id="serviceTypes" value="{{ base64_encode($serviceTypesJson) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-md-offset-3 col-sm-offset-4">
                    <button id='pasoAnterior' type="button" class="btn btn-danger btn-lg waves-effect" style="display: none">Anterior&nbsp;<i class="fa fa-arrow-circle-left"></i></button>
                    <button id='crearCuenta' type="button" class="btn btn-primary btn-lg waves-effect" style="display: none">Crear cuenta&nbsp;<i class="fa fa-save"></i></button>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="modalMapa" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" id="mapa" style="width: 100%; height: 480px;">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.loader')
@stop

@section('js')
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5UyM5feL_oL7pwodFUKGagZQieNXy3Ps"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}
    <script src="{{ mix('js/accounts/sign_up.js') }}"></script>
@stop