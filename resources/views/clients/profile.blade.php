@extends('layouts.clients.master')

@php
    $user = \Auth::user();
    $account = '';
    if ($user->isServiceProvider()) {
        $account = 'Prestador de Servicios';
    }

    if ($user->isClient()) {
        $account = 'Cliente';
    }
@endphp

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>CLIENTE</h2>
                </div>
                <div class="body">
                    <p class="font-18 col-teal align-center">{{ $user->getFullName()->fullName() }}</p>
                    <p class="align-center">{{ $account }}</p>
                    <div class="profile-image">
                        <img src="{{ $profilePictureUrl !== '' ? asset('storage/profile_pictures/' . $user->getProfilePicture()) : asset('images/user.png') }}" class="align-center user-picture" alt="User">
                        <button type="button" id="changeProfileImage" class="btn btn-sm bg-red waves-effect" data-toggle="tooltip" title="Formatos jpg, png o gif"><i class="material-icons">cached</i> Cambiar</button>
                        <form id="formPicture" action="/prestador-servicios/perfil/picture" method="POST" enctype="multipart/form-data">
                            <input type="file" name="loadPicture" id="loadPicture" class="loadPicture hide" data-rule-extension="jpg|png|gif" data-rule-required="true">
                            <input type="hidden" name="_method" value="PUT">
                        </form>
                    </div>
                    <br><br><hr>
                    <p class="font-14 col-teal">Notificaciones:</p>
                    <input type="checkbox" name="notifications[]" id="confirmedDate" {{ in_array('confirmedDate', $notifications) ? 'checked' : '' }} class="filled-in" value="confirmedDate">
                    <label for="confirmedDate">&nbsp; Cita confirmada</label>
                    <br>
                    <input type="checkbox" name="notifications[]" id="dateRejected" {{ in_array('dateRejected', $notifications) ? 'checked' : '' }} class="filled-in" value="dateRejected">
                    <label for="dateRejected">&nbsp; Cita rechazada</label>
                    <br>
                    <button type="button" id="notifications" class="btn btn-sm bg-red waves-effect align-center"><i class="material-icons">cached</i> Actualizar</button>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
            <div class="card">
                <div class="body">
                    @include('common._profile_form')
                </div>
            </div>
        </div>
    </div>

    @include('layouts.loader')
@stop

@section('js')
    <script src="{{ mix('js/service_provider/profile.js') }}"></script>
@stop