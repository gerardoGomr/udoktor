@php
    use Udoktor\Domain\Users\User;
@endphp
<div class="row clearfix">
    <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
        <label for="diary-schedule-type" class="control-label">Tipo de agenda:</label>
    </div>
    <div class="col-sm-8 col-xs-12 col-md-5">
        <div class="form-group">
            <input type="radio" name="diary-schedule-type" id="fixed" value="1" class="filled-in" {{ Auth::user()->getDiaryScheduleType() === User::FIXED_SCHEDULE ? 'checked' : '' }}>
            <label for="fixed">Horarios fijos</label>
            &nbsp;&nbsp;
            <input type="radio" name="diary-schedule-type" id="interval" value="2" class="filled-in" {{ Auth::user()->getDiaryScheduleType() === User::INTERVAL_SCHEDULE ? 'checked' : '' }}>
            <label for="interval">Rango de horarios</label>
            &nbsp;&nbsp;
            <button type="button" class="btn btn-sm bg-teal waves-effect align-center btn-change-schedule-type"><i class="material-icons">cached</i> Asignar tipo</button>
        </div>
    </div>
</div>
@if(Auth::user()->getDiaryScheduleType() === User::INTERVAL_SCHEDULE)
    <div class="row clearfix">
        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
            <label for="service-lasting" class="control-label">Duración del servicio (min.):</label>
        </div>
        <div class="col-sm-8 col-xs-12 col-md-3">
            <div class="input-group">
                <div class="form-line">
                    <input type="number" name="service-lasting" id="service-lasting" class="form-control" value="{{ Auth::user()->getMinServiceDuration() }}">
                </div>
                <span class="input-group-btn">
                    <button type="button" class="btn bg-default waves-effect btn-modify-service-lasting">Modificar</button>
                </span>
            </div>
        </div>
    </div>
@endif
<p class="font-10 col-pink">* Si selecciona hora fija los eventos se programarán a la(s) hora(s) que indique con un limite determinado de clientes.</p>
<p class="font-10 col-pink">* Si selecciona Rango de horas,los eventos se programarán en los rangos especificados.</p>
<br>
<button type="button" class="btn bg-teal waves-effect btn-add-schedule"><i class="material-icons">add</i> Agregar un horario</button>
<hr>
@if(Auth::user()->hasSchedules())
    <table class="table table-condensed table-striped">
        <thead class="bg-red">
            <tr>
                <th>#</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Cantidad de clientes</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach (Auth::user()->getSchedules() as $schedule)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ date('H:i', $schedule->getStartHour()) }}</td>
                    <td>{{ !is_null($schedule->getEndHour()) ? date('H:i', $schedule->getEndHour()) : '-' }}</td>
                    <td>{{ $schedule->getClientsLimit() }}</td>
                    <td><button type="button" class="btn btn-sm bg-pink waves-effect remove-schedule" data-toggle="tooltip" title="Remover horario" data-id="{{ $schedule->getId() }}"><i class="material-icons">delete</i></button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <h5>No ha agregado algún horario. <a href="#" class="btn-add-schedule">Agregar un horario</a></h5>
@endif

<div class="modal fade" id="modal-add-schedule" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Agregar nuevo horario</h4>
            </div>
            <div class="modal-body">
                <form id="add-schedule-form" class="form-horizontal">
                    <div class="row clearfix">
                        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                            <label for="start-hour">Hora de inicio:</label>
                        </div>
                        <div class="col-sm-8 col-xs-12 col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="start-hour" id="start-hour" class="form-control hours" data-rule-required="true" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->hasFixedSchedules())
                        <div class="row clearfix">
                            <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                                <label for="clients-limit">Límite de clientes:</label>
                            </div>
                            <div class="col-sm-8 col-xs-12 col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" name="clients-limit" id="clients-limit" class="form-control" data-rule-required="true" data-rule-digits="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="diary-schedule-type" value="fixed">
                    @endif
                    @if(Auth::user()->hasIntervalSchedules())
                        <div class="row clearfix">
                            <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
                                <label for="end-hour">Hora de término:</label>
                            </div>
                            <div class="col-sm-8 col-xs-12 col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="end-hour" id="end-hour" class="form-control hours" data-rule-required="true" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="diary-schedule-type" value="interval">
                    @endif
                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-save-schedule" class="btn btn-link waves-effect">AGREGAR HORARIO</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCELAR</button>
            </div>
        </div>
    </div>
</div>