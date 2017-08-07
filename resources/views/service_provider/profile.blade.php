@extends('layouts.service_provider.master')

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
                    <h2>PRESTADOR DE SERVICIOS</h2>
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
                    <input type="checkbox" name="notifications[]" id="newDate" {{ in_array('newDate', $notifications) ? 'checked' : '' }} class="filled-in" value="newDate">
                    <label for="newDate">&nbsp; Nueva cita</label>
                    <br>
                    <input type="checkbox" name="notifications[]" id="dateCancelled" {{ in_array('dateCancelled', $notifications) ? 'checked' : '' }} class="filled-in" value="dateCancelled">
                    <label for="dateCancelled">&nbsp; Cita cancelada</label>
                    <br>
                    <button type="button" id="notifications" class="btn btn-sm bg-red waves-effect align-center"><i class="material-icons">cached</i> Actualizar</button>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
            <div class="card">
                <div class="body">
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" class="active"><a href="#generalData" data-toggle="tab">Datos Generales</a></li>
                        <li role="presentation"><a id="mapLocationLink" href="#mapLocation" data-toggle="tab">Ubicación</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="generalData">
                            @include('common._profile_form')
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="mapLocation">
                            <form id="locationForm">
                                <div class="row clearfix">
                                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                                        <label for="location" class="control-label">Ubicación:</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-12 col-md-9">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="location" id="location" class="form-control required" placeholder="Establezca su ubicación usando el mapa" value="{{ $user->getLocation()->getLocation() }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                                        <label for="" class="control-label">Mapa:</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-12 col-md-9">
                                        <div id="map"></div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-8 col-xs-12 col-md-4 col-sm-offset-4 col-md-offset-3">
                                        <div class="form-group">
                                            <button type="button" class="btn bg-red waves-effect" id="updateLocation"><i class="fa fa-save"></i> Actualizar ubicación</button>

                                            <input type="hidden" name="_method" value="PUT">
                                            <input type="hidden" name="longitude" id="longitude" value="{{ $user->getLocation()->getLongitude() }}">
                                            <input type="hidden" name="latitude" id="latitude" value="{{ $user->getLocation()->getLatitude() }}">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.loader')
@stop

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5UyM5feL_oL7pwodFUKGagZQieNXy3Ps"></script>
    <script src="{{ mix('js/service_provider/profile.js') }}"></script>
    <script src="{{ mix('js/service_provider/map.js') }}"></script>
@stop