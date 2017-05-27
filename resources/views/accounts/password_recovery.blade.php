@extends('layouts.master_small')

@section('body-class', 'signup-page')
@section('body-body', 'signup-box')

@section('content')
    <div class="msg">Por favor, especifique un correo electrónico para enviarle las instrucciones para recuperar su contraseña</div>
    <form id="formPassword" class="form-horizontal">
        <div class="row clearfix">
            <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                <label for="email" class="control-label">Correo electrónico:</label>
            </div>
            <div class="col-sm-8 col-xs-12 col-md-4">
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="email" id="email" class="form-control" data-rule-required="true" data-rule-email="true" placeholder="ejemplo@ejemplo.com" autofocus>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-offset-3 col-sm-offset-4">
                <button id="enviar" type="button" class="btn btn-success btn-lg waves-effect">Enviar&nbsp;<i class="fa fa-arrow-circle-right"></i></button>
            </div>
        </div>
    </form>
@stop

@section('js')
    <script src="{{ mix('js/accounts/password_recovery.js') }}"></script>
@stop