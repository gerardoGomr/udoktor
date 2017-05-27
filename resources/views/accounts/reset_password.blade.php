@extends('layouts.master_small')

@section('body-class', 'signup-page')
@section('body-body', 'signup-box')

@section('content')
    <form id="formPassword" class="form-horizontal">
        <div class="row clearfix">
            <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                <label for="email" class="control-label">Correo electrónico:</label>
            </div>
            <div class="col-sm-8 col-xs-12 col-md-4">
                <div class="form-group">
                    <p class="form-control-static">{{ $email }}</p>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                <label for="email" class="control-label">Nueva contraseña:</label>
            </div>
            <div class="col-sm-8 col-xs-12 col-md-4">
                <div class="form-group">
                    <div class="form-line">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Mínimo 8 caracteres">
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-offset-3 col-sm-offset-4">
                <button id="reset" type="button" class="btn btn-success btn-lg waves-effect">Guardar nueva contraseña&nbsp;<i class="fa fa-arrow-circle-right"></i></button>
                <input type="hidden" name="userId" value="{{ $userId }}">
            </div>
        </div>
    </form>
@stop

@section('js')
    <script src="{{ mix('js/accounts/reset_password.js') }}"></script>
@stop