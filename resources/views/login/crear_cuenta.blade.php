@extends('layouts.master_small')

@section('body-class', 'signup-page')
@section('body-body', 'signup-box')

@section('content')
    <div class="msg">Crear una cuenta de usuario</div>
    <div id="divPrincipal">
        <h5 class="text-left text-danger small">Los campos marcados con * son obligatorios</h5>
        <form action="/crear-cuenta" id="formCrearCuenta" class="form-horizontal">
            <div id="informacionBasica">
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="email" class="control-label">*Correo electrónico:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="email" id="email" class="form-control required" placeholder="ejemplo@ejemplo.com" autofocus>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="pass" class="control-label">*Contraseña:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="password" name="pass" id="pass" class="form-control required">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="tipoCuenta" class="control-label">*Tipo de cuenta:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="radio" name="tipoCuenta" id="cuentaCliente" value="1" class="required radio-col-red">
                                <label for="cuentaCliente"> Cuenta cliente</label><br>
                                <input type="radio" name="tipoCuenta" id="cuentaPrestador" value="2" class="required radio-col-red">
                                <label for="cuentaPrestador"> Cuenta prestador de servicios</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="nombre" class="control-label">*Nombre:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="nombre" id="nombre" class="form-control required" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="paterno" class="control-label">*A. Paterno:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="paterno" id="paterno" class="form-control required" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="materno" class="control-label">A. Materno:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="materno" id="materno" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="compania" class="control-label">Compañía:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="compania" id="compania" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="pais" class="control-label">*País:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <select class="selectpicker show-tick required" name="pais" id="pais" data-live-search="true">
                            <option value="" selected>{{trans("leng.Seleccione el pais")}}</option>
                            <option value="1">demo</option>
                        </select>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="estado" class="control-label">*Estado:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <select class="selectpicker required" name="estado" id="estado" data-live-search="true">
                                    <option value="" selected>{{trans("leng.Seleccione el estado")}}</option>
                                    <option value="1">demo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="municipio" class="control-label">*Municipio:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <select class="selectpicker required" name="municipio" id="municipio" data-live-search="true">
                                    <option value="" selected>{{trans("leng.Seleccione el municipio")}}</option>
                                    <option value="1">demo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="telefono" class="control-label">*Teléfono:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="telefono" id="telefono" class="form-control required">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="checkbox" name="aceptaTerminos" id="aceptaTerminos" class="filled-in chk-col-red">
                                <label for="aceptaTerminos">&nbsp; Acepto los términos y condiciones</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                        <button id='paso2' type="button" class="btn btn-success btn-lg waves-effect">Siguiente&nbsp;<i class="fa fa-arrow-circle-right"></i></button>
                    </div>
                </div>
            </div>

            <div id="informacionPrestador" style="display: none;">
                <p class="strong"><b>Prestador de servicios</b></p>
                <br>
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="clasificacion" class="control-label">Clasificación:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <select name="clasificacion" id="clasificacion" class="form-control selectpicker" data-live-search="true" required>
                                    <option value="" selected="">{{trans("leng.Seleccione clasificación")}}</option>
                                    <option value="-1">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="servicios" class="control-label">Servicios:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <select name="servicios" id="servicios" class="form-control selectpicker" data-live-search="true" required>
                                <option value="" selected="">{{trans("leng.Seleccione clasificación")}}</option>
                                <option value="-1">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label class="control-label">Ubicación:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="input-group">
                            <div class="form-line">
                                <input class="form-control" id="ubicacion" type="text" readonly placeholder="{{trans("leng.Ubicaciónl")}}" required>
                            </div>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-info waves-effect" title="Abrir ventana para selección de ubicación" id="abrirMapa" data-toggle="tooltip"><i class="material-icons">pin_drop</i></button>
                            </div>
                        </div>
                        <input type="hidden" name="latitud" id="latitud">
                        <input type="hidden" name="longitud" id="longitud">
                    </div>
                </div>
            </div>

            <div id="captcha" style="display: none">
                <div class="row clearfix">
                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                        <div class="g-recaptcha" data-sitekey="6Lc9piUTAAAAAFBNrYcFr0-Tukw2GWBcr88sHxSy"></div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
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
                    <button type="button" class="btn btn-link waves-effect">Aceptar</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5UyM5feL_oL7pwodFUKGagZQieNXy3Ps"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="/js/cuentas/crear_cuenta.js"></script>
@stop