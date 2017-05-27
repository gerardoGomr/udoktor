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
# Cuenta creada

Muchas gracias por registrarse en Udoktor.

Sus datos de acceso son: <br>
Usuario:    {{ $user->getEmail() }} <br>
Contraseña: {{ $user->getTempPassword() }} <br>
Cuenta:     {{ $cuenta }}

Para poder ingresar a Udoktor, es necesario que active su cuenta. Para activarla, dé click en el siguiente botón, el cual lo redireccionará a la aplicación.
@component('mail::button', ['url' => url('cuentas/activar/' . base64_encode($user->getId()) . '/' . $user->getVerificationToken())])
Activar mi cuenta de usuario
@endcomponent

Muchas gracias, <br>
{{ config('app.name') }}
@endcomponent
