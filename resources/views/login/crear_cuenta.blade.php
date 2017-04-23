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
                        <label class="control-label">*Tipo de cuenta:</label>
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
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control required" name="pais" id="pais">
                                    <option value="" selected>{{trans("leng.Seleccione el pais")}}</option>
                                    <option value="1">demo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="estado" class="control-label">*Estado:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control required" name="estado" id="estado">
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
                                <select class="form-control required" name="municipio" id="municipio">
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
                        <button id='paso2' type="button" class="btn btn-success btn-lg">Siguiente&nbsp;<i class="fa fa-arrow-circle-right"></i></button>
                    </div>
                </div>
            </div>

            <div id="informacionPrestador" style="display: none">
                <h4>Prestador de servicios</h4>
                <div class="form-group">
                    <label class="control-label">Clasificación:</label>
                    <select class="form-control">
                        <option value="0" selected="">{{trans("leng.Seleccione clasificación")}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Servicios:</label>
                    <select class="form-control">
                        <option value="0" selected="">{{trans("leng.Seleccione clasificación")}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Ubicación:</label>

                </div>
            </div>

            <div class="row" id="divCaptcha" style="display: none">
                <br>
                <div class="g-recaptcha" data-sitekey="6Lc9piUTAAAAAFBNrYcFr0-Tukw2GWBcr88sHxSy"></div>
            </div>
        </form>
    </div>
@stop

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5UyM5feL_oL7pwodFUKGagZQieNXy3Ps"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="/js/cuentas/crear_cuenta.js"></script>
@stop