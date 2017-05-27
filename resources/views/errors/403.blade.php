@extends('layouts.master_small')

@section('body-class', 'signup-page')
@section('body-body', 'signup-box')

@section('content')
    @if (isset($error))
        <div class="msg">{{ $error }}</div>
    @endif
    <div class="center">
        <a href="/" class="waves-effect btn btn-primary">Volver al inicio</a>
    </div>
@stop