@extends('layouts.service_provider.master')

@section('content')
    <div class="row clearfix">
        <div class="col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>CITAS</h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="#add-service" data-toggle="modal">Agregar nuevo servicio</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive" id="services">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-service" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Agregar nuevo servicio</h4>
                </div>
                <div class="modal-body">
                    <form id="add-service-form" class="form-horizontal">
                        <div class="row clearfix">
                            <div class="col-sm-8 col-xs-12 col-md-9">
                                <div class="form-group">
                                    <div class="form-line">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="_method" value="PUT">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-add-service" class="btn btn-link waves-effect">ASIGNAR SERVICIOS</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCELAR</button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.loader')
@stop

@section('js')
    <script src="{{ mix('js/service_provider/services.js') }}"></script>
@stop