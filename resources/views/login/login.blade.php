@extends('layouts.master_small')

@section('body-class', 'login-page')
@section('body-body', 'login-box')

@section('content')
    <form action="/login" method="POST">
        {{ csrf_field() }}
        <div class="msg">Escriba sus datos para ingresar al sistema</div>

        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">person</i>
            </span>
            <div class="form-line">
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

        <div class="row">
            <div class="col-xs-12 p-t-5">
                <button class="btn btn-block bg-pink waves-effect" type="submit">INGRESAR</button>
            </div>
        </div>

        <div class="row m-t-15 m-b--20">
            <div class="col-xs-6">
                <a href="/crear-cuenta">Crear una cuenta</a>
            </div>
            <div class="col-xs-6 align-right">
                <a href="/crear-cuenta/recuperar-contrasenia">¿Olvidó su contraseña?</a>
            </div>
        </div>
    </form>
@stop