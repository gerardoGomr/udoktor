<form id="generalDataForm">
    <div class="row clearfix">
        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
            <label for="email" class="control-label">Correo electrónico:</label>
        </div>
        <div class="col-sm-8 col-xs-12 col-md-4">
            <div class="form-group">
                <div class="form-line focused">
                    <input type="text" name="email" id="email" class="form-control required" data-rule-email="true" placeholder="ejemplo@ejemplo.com" autofocus value="{{ $user->getEmail() }}">
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
            <label for="name" class="control-label">Nombre:</label>
        </div>
        <div class="col-sm-8 col-xs-12 col-md-4">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" name="name" id="name" class="form-control required" placeholder="" value="{{ $user->getFullName()->getName() }}">
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
            <label for="middleName" class="control-label">A. Paterno:</label>
        </div>
        <div class="col-sm-8 col-xs-12 col-md-4">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" name="middleName" id="middleName" class="form-control required" placeholder=""  value="{{ $user->getFullName()->getLastName1() }}">
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
            <label for="lastName" class="control-label">A. Materno:</label>
        </div>
        <div class="col-sm-8 col-xs-12 col-md-4">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" name="lastName" id="lastName" class="form-control" value="{{ $user->getFullName()->getLastName2() }}">
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
            <label for="country" class="control-label">País de residencia:</label>
        </div>
        <div class="col-sm-8 col-xs-12 col-md-4">
            <div class="form-group">
                <select class="form-control show-tick required aUnit" name="country" id="country" data-live-search="true" data-target="estado">
                    <option value="">Seleccione</option>
                    <option value="-1">Otro</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->getId() }}" {{ $country->getId() === $state->getParentUnit()->getId() ? 'selected' : '' }}>{{ $country->getName() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
            <label for="state" class="control-label">Estado de residencia:</label>
        </div>
        <div class="col-sm-8 col-xs-12 col-md-4">
            <div class="form-group">
                <select class="form-control show-tick required aUnit" name="state" id="state" data-live-search="true" data-target="municipio">
                    <option value="">Seleccione</option>
                    <option value="{{ $state->getId() }}" selected>{{ $state->getName() }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
            <label for="municipality" class="control-label">Municipio de residencia:</label>
        </div>
        <div class="col-sm-8 col-xs-12 col-md-4">
            <div class="form-group">
                <select class="form-control show-tick required" name="municipality" id="municipality" data-live-search="true">
                    <option value="">Seleccione</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->getId() }}" {!! $city->getId() === $user->getAdministrativeUnit()->getId() ? 'selected' : '' !!}>{{ $city->getName() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
            <label for="phoneNumber" class="control-label">¿Algún número de contacto?:</label>
        </div>
        <div class="col-sm-8 col-xs-12 col-md-4">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" name="phoneNumber" id="phoneNumber" class="form-control required" value="{{ $user->getPhoneNumber() }}">
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-sm-8 col-xs-12 col-md-4 col-sm-offset-4 col-md-offset-3">
            <div class="form-group">
                <button type="button" class="btn bg-red waves-effect" id="updateProfile"><i class="fa fa-save"></i> Actualizar datos generales</button>

                <input type="hidden" name="_method" value="PUT">
            </div>
        </div>
    </div>
</form>