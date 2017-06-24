@php
    $user = \Auth::user();
@endphp

<div class="row clearfix">
    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
        <label for="email" class="control-label">Correo electrónico:</label>
    </div>
    <div class="col-sm-8 col-xs-12 col-md-4">
        <div class="form-group">
            <p class="form-line form-control-static">{{ $user->getEmail() }}</p>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
        <label for="nombre" class="control-label">Nombre:</label>
    </div>
    <div class="col-sm-8 col-xs-12 col-md-4">
        <div class="form-group">
            <p class="form-line form-control-static">{{ $user->getFullName()->getName() }}</p>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
        <label for="paterno" class="control-label">A. Paterno:</label>
    </div>
    <div class="col-sm-8 col-xs-12 col-md-4">
        <div class="form-group">
            <p class="form-line form-control-static">{{ $user->getFullName()->getLastName1() }}</p>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
        <label for="materno" class="control-label">A. Materno:</label>
    </div>
    <div class="col-sm-8 col-xs-12 col-md-4">
        <div class="form-group">
            <p class="form-line form-control-static">{{ $user->getFullName()->getLastName2() }}</p>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
        <label for="municipio" class="control-label">Municipio de residencia:</label>
    </div>
    <div class="col-sm-8 col-xs-12 col-md-4">
        <div class="form-group">
            <p class="form-line form-control-static">{{ $user->getAdministrativeUnit()->getName() }}</p>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
        <label for="telefono" class="control-label">¿Algún número de contacto?:</label>
    </div>
    <div class="col-sm-8 col-xs-12 col-md-4">
        <div class="form-group">
            <p class="form-line form-control-static">{{ $user->getPhoneNumber() }}</p>
        </div>
    </div>
</div>