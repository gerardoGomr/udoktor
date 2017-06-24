@extends('layouts.master_small')

@section('body-class', 'login-page')
@section('body-body', 'login-box')

@section('content')
    <form action="/login" method="POST">
        {{ csrf_field() }}
        <div class="msg text-success">Escriba sus datos para ingresar al sistema</div>

        @if(session('error'))
            <p class="alert alert-danger">{{ session('error') }}</p>
        @endif

        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">person</i>
            </span>
            <div class="form-line focused">
                <input type="text" class="form-control" name="correo" placeholder="Correo electrónico" required autofocus>
            </div>
        </div>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">lock</i>
            </span>
            <div class="form-line">
                <input type="password" class="form-control" name="pass" placeholder="Contraseña" required>
            </div>
        </div>

        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">&nbsp;</i>
            </span>
            <div class="form-group">
                <input type="checkbox" name="rememberMe" id="rememberMe" class="filled-in chk-col-red">
                <label for="rememberMe">Mantener mi sesión activa</label>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 p-t-5">
                <button class="btn btn-block bg-pink waves-effect" type="submit">INGRESAR</button>
            </div>
        </div>

        <div class="row m-t-15 m-b--20">
            <div class="col-xs-6">
                <a href="/crear-cuenta">¡Crear una cuenta!</a>
            </div>
            <div class="col-xs-6 align-right">
                <a href="/cuentas/recuperar-contrasenia">¿Olvidó su contraseña?</a>
            </div>
        </div>
    </form>
@stop