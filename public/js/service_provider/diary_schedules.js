'use strict';

jQuery(document).ready(function($) {
    let $schedules = $('#schedules'),
        $loader    = $('#loader');

    // changing the schedule type - fixed / interval
    $schedules.on('click', 'button.btn-change-schedule-type', function(event) {
        let diaryScheduleType = $schedules.find('input[name="diary-schedule-type"]:checked').val();

        swal({
            title:             'Alerta',
            text:              'Si cambia el tipo de agenda se borrará la configuración actual. ¿Desea continuar?',
            type:              'warning',
            showCancelButton:  true,
            cancelButtonText:  'Cancelar',
            confirmButtonText: 'Continuar',
            closeOnConfirm:    true,
            html:              true
        }, function () {
            $.ajax({
                url:        '/prestador-servicios/agenda/tipo',
                type:       'PUT',
                dataType:   'json',
                data:       {
                    diaryScheduleType: diaryScheduleType
                },
                beforeSend: beforeSend()
            })
            .done(function(response) {
                $loader.modal('hide');

                if (response.status === 'success') {
                    swal('¡Éxito!', 'Tipo de agenda asignada con éxito', 'success');
                    $schedules.html(response.html);

                    initComponents();
                }

                if (response.status === 'error') {
                    swal('Error', 'Ocurrió un error al asignar el tipo de agenda al usuario: ' + response.error, 'error');
                }
            })
            .fail(function(jqXHR) {
                $loader.modal('hide');
                swal('Error', 'Ocurrió un error al asignar el tipo de agenda al usuario', 'error');
            });
        });
    });

    // modify service lasting
    $schedules.on('click', 'button.btn-modify-service-lasting', function() {
        let lasting = $schedules.find('input[name="service-lasting"]').val();

        $.ajax({
            url:        '/prestador-servicios/agenda/duracion-servicios',
            type:       'PUT',
            dataType:   'json',
            data:       {
                lasting: lasting
            },
            beforeSend: beforeSend()
        })
        .done(function(response) {
            $loader.modal('hide');

            if (response.status === 'success') {
                swal('¡Éxito!', 'Duración de servicios modificada con éxito', 'success');
                $schedules.html(response.html);
                initComponents();
            }

            if (response.status === 'error') {
                swal('Error', 'Ocurrió un error al modificar la duración de los servicios: ' + response.error, 'error');
            }
        })
        .fail(function(jqXHR) {
            $loader.modal('hide');
            swal('Error', 'Ocurrió un error al modificar la duración de los servicios', 'error');
        });
    });

    // add new schedule - open modal
    $schedules.on('click', '.btn-add-schedule', function() {
        $('#modal-add-schedule').modal('show');
    });

    // save new schedule
    $schedules.on('click', '#btn-save-schedule', function() {
        if ($('#add-schedule-form').valid()) {
            $.ajax({
                url:        '/prestador-servicios/agenda/agregar-horario',
                type:       'PUT',
                dataType:   'json',
                data:       $('#add-schedule-form').serialize(),
                beforeSend: beforeSend()
            })
            .done(function(response) {
                $loader.modal('hide');

                if (response.status === 'success') {
                    swal({
                        title:             '¡Éxito!',
                        text:              'Horario agregado con éxito',
                        type:              'success',
                        showCancelButton:  false,
                        confirmButtonText: 'Continuar',
                        closeOnConfirm:    true,
                        html:              true
                    }, function () {
                        $('#modal-add-schedule').modal('hide');

                        setTimeout(function () {
                            $schedules.html(response.html);
                            initComponents();
                        }, 2000);
                    });
                }

                if (response.status === 'error') {
                    swal('Error', 'Ocurrió un error al agregar el horario: ' + response.error, 'error');
                }
            })
            .fail(function(jqXHR) {
                $loader.modal('hide');
                swal('Error', 'Ocurrió un error al agregar el horario', 'error');
            });
        }
    });

    // init components
    initComponents();
});

/**
 * showing loader before send the request
 *
 * @return {void}
 */
function beforeSend() {
    $('#loader').modal('show');
}

/**
 * inits components after load
 *
 * @return {void}
 */
function initComponents() {
    // validate form
    $('#add-schedule-form').validate({
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
            $(element).parents('.form-group').append(error);
        },
    })
        .settings.ignore = '';

    /**
     * adds timepicker addon to inputs
     *
     * if the hour is less than 10, adds a leading zero
     *
     */
    $('input.hours').timepicker({
        template:     false,
        showInputs:   false,
        minuteStep:   1,
        showMeridian: false
    })
    .on('changeTime.timepicker', function(e) {
        let hours = e.time.hours, //Returns a string
            min   = e.time.minutes;

        if(hours < 10) {
            $(this).val('0' + hours + ':' + min);
        }
    });
}