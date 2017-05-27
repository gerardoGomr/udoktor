@php
    $cuenta = '';
    if ($user->isServiceProvider()) {
        $cuenta = 'Prestador de Servicios';
    }

    if ($user->isClient()) {
        $cuenta = 'Cliente';
    }
@endphp

@component('mail::message')
# Reseteo de contraseña

Se ha solicitado un reseteo de contraseña para la cuenta {{ $user->getEmail() }}. Si usted no solicitó este reseteo, por favor, ignore este correo electrónico.

Para resetear su contraseña, dé click en el siguiente botón, el cual lo redireccionará a la aplicación.
@component('mail::button', ['url' => url('cuentas/nueva-contrasenia/' . base64_encode($user->getId()) . '/' . $user->getRequestToken()]))
Resetear mi contraseñan
@endcomponent

Muchas gracias, <br>
{{ config('app.name') }}
@endcomponent
