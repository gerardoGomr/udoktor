@extends('layouts.master')

@section('content')
    @if (session('verified'))
        <div class="alert alert-success">
            ¡Muchas gracias por verificar su cuenta!. <br>
            A partir de este momento puede hacer uso de Udoktor sin problemas.
        </div>
    @endif
@stop