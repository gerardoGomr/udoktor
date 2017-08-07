@extends('layouts.clients.master')

@section('content')
    @php
        use Illuminate\Support\Facades\Auth;
    @endphp
    <div class="block-header">
        <h1>BÚSQUEDA DE SERVICIOS</h1>
    </div>
    <div class="row clearfix">
        <div class="col-lg-3 col-sm-4 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>FILTROS</h2>
                </div>
                <div class="body">
                    <form id="search-services-form">
                        <div class="form-group">
                            <label for="country" class="control-label">País:</label>
                            <select class="form-control show-tick required aUnit" name="country" id="country" data-live-search="true" data-target="country">
                                <option value="">Seleccione</option>
                                <option value="-1">Otro</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->getId() }}">{{ $country->getName() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="state" class="control-label">Estado:</label>
                            <select class="form-control show-tick required aUnit" name="state" id="state" data-live-search="true" data-target="state">
                                <option value="">Seleccione</option>
                                <option value="-1">Otro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="municipality" class="control-label">Municipio:</label>
                            <select class="form-control show-tick required aUnit" name="municipality" id="municipality" data-live-search="true" data-target="municipality">
                                <option value="">Seleccione</option>
                                <option value="-1">Otro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="classifications" class="control-label">Especialidad:</label>
                            <select class="form-control show-tick required aUnit" name="classifications" id="classifications" data-live-search="true" data-target="classifications">
                                <option value="">Seleccione</option>
                                <option value="-1">Otro</option>
                                @foreach($classifications as $classification)
                                    <option value="{{ $classification->getId() }}">{{ $classification->getName() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="offeredServices" class="control-label">Servicios:</label>
                        <div class="input-group">
                            <span class="input-group-addon instructions" id="help-text" data-toggle="tooltip" data-placement="top" title="{!! $servicesString !!}">
                                <i class="material-icons">search</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="offeredServices" id="offeredServices" class="form-control required">
                                <input type="hidden" id="services" value="{{ base64_encode($servicesJson) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <button id='btn-filter-services' type="button" class="btn bg-teal btn-block waves-effect"><i class="fa fa-search"></i> Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-sm-8 col-sm col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>MAPA</h2>
                </div>
                <div class="body">
                    <div id="map" style="width: 100%; height: 350px;"></div>
                </div>
            </div>

            <div class="card">
                <div class="header">
                    <h2>SERVICIOS ENCONTRADOS</h2>
                </div>
                <div class="body table-responsive">
                    @unless (count($serviceProviders) > 0)
                        <h4>No existen proveedores de servicios para la ubicación {{ Auth::user()->getAdministrativeUnit()->getName() }}</h4>
                        <input type="hidden" id="data-service-providers" value="0">
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Compañía</th>
                                    <th>Prestador de servicios</th>
                                    <th>Ubicación</th>
                                    <th>Contacto</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceProviders as $serviceProvider)
                                    <tr>
                                        <td>{{ $serviceProvider->getFullName()->fullName() }}</td>
                                        <td>{{ $serviceProvider->getFullName()->fullName() }}</td>
                                        <td>{{ $serviceProvider->getAdministrativeUnit()->getName() }}</td>
                                        <td>{{ $serviceProvider->getPhoneNumber() }}</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <input type="hidden" id="data-service-providers" value="1">
                        <input type="hidden" name="locations" id="locations" value="{{ $locations }}">
                    @endunless
                </div>
            </div>
        </div>
    </div>

    @include('layouts.loader')
@stop

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5UyM5feL_oL7pwodFUKGagZQieNXy3Ps"></script>
    <script src="{{ mix('js/clients/services.js') }}"></script>
    <script src="{{ mix('js/clients/map.js') }}"></script>
@stop