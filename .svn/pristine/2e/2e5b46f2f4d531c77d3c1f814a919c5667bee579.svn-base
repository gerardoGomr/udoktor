$(document).ready(function() {

    // ############################################################
    // overriding default values
    $.validator.setDefaults({
        showErrors: function(map, list) {
            this.currentElements.parents('label:first, div:first').find('.has-error').remove();
            this.currentElements.parents('.form-group:first').removeClass('has-error');

            $.each(list, function(index, error) {
                var ee = $(error.element);
                var eep = ee.parents('label:first').length ? ee.parents('label:first') : ee.parents('div:first');

                ee.parents('.form-group:first').addClass('has-error');
                eep.find('.has-error').remove();
                eep.append('<p class="has-error help-block">' + error.message + '</p>');
            });
        }
    });

    // validar formulario
    $('#formVehiculo').validate({
        rules: {
            descripcion: 'required'
        },
        messages: {
            descripcion: 'Escriba la descripción del vehículo.'
        }
    });

    // agregar nuevo vehículo
    $('#modalAgregarVehiculo').on('click', function() {
        //$('#accion').text('Agregar vehículo');
        $('#tipoAccion').val('agregar');

        $('#modalVehiculos').modal('show');
        $('#descripcion').focus();
    });

    // listado vehiculos editar
    $('#listadoVehiculos').on('click', 'button.editar', function() {
        var vehicleId = $(this).parents('td').siblings('td.id').text(),
            description = $(this).parents('td').siblings('td.description').text();

        //$('#accion').text('Guardar cambios');
        $('#tipoAccion').val('editar');

        $('#descripcion').val(description);
        $('#vehicleId').val(vehicleId);

        $('#modalVehiculos').modal('show');
        $('#descripcion').focus();
    });

    // eliminar vehiculo
    $('#listadoVehiculos').on('click', 'button.eliminar', function() {
        var vehicleId = $(this).parents('td').siblings('td.id').text(),
            url       = $('#url-eliminar').val();

        swal({
            title: '',
            text:  'Se eliminará al vehículo seleccionado, ¿Desea continuar?',
            type:  'warning',
            closeOnConfirm: true,
            showCancelButton: true
        }, function() {
            // eliminar
            $.ajax({
                url:      url,
                type:     'post',
                headers:  {'X-CSRF-TOKEN': $('#formVehiculo').find('input[name="_token"]').val() },
                dataType: 'json',
                data:     { vehicleId: vehicleId },
                beforeSend: function() {
                    waitingDialog();
                }
            })
            .done(function(respuesta) {
                closeWaitingDialog();

                if (respuesta.estatus === 'fail') {
                    swal('Error', "Ocurrió un error al eliminar el vehículo:\n" + respuesta.error, 'warning');
                }

                if (respuesta.estatus === 'ok') {
                    $('#listadoVehiculos').html(respuesta.html);

                    swal('', 'Vehículo eliminado con éxito.', 'info');
                }
            })
            .fail(function(XmlHttpRequest, textStatus, errorThrown) {
                closeWaitingDialog();
                console.log(textStatus + ': ' + errorThrown);
                swal('Error', 'Ocurrió un error al eliminar el vehículo.', 'warning');
            });
        });
    });

    // activar vehículo
    $('#listadoVehiculos').on('click', 'button.activar', function() {
        var vehicleId = $(this).parents('td').siblings('td.id').text(),
            url       = $('#url-activar').val();

        swal({
            title: '',
            text:  'Se activará al vehículo seleccionado, ¿Desea continuar?',
            type:  'warning',
            closeOnConfirm: true,
            showCancelButton: true
        }, function() {
            // eliminar
            $.ajax({
                url:      url,
                type:     'post',
                headers:  {'X-CSRF-TOKEN': $('#formVehiculo').find('input[name="_token"]').val() },
                dataType: 'json',
                data:     { vehicleId: vehicleId },
                beforeSend: function() {
                    waitingDialog();
                }
            })
            .done(function(respuesta) {
                closeWaitingDialog();

                if (respuesta.estatus === 'fail') {
                    swal('Error', "Ocurrió un error al activar el vehículo:\n" + respuesta.error, 'warning');
                }

                if (respuesta.estatus === 'ok') {
                    $('#listadoVehiculos').html(respuesta.html);

                    swal('', 'Vehículo activado con éxito.', 'info');
                }
            })
            .fail(function(XmlHttpRequest, textStatus, errorThrown) {
                closeWaitingDialog();
                console.log(textStatus + ': ' + errorThrown);
                swal('Error', 'Ocurrió un error al activar el vehículo.', 'warning');
            });
        });
    });


    // check if form is valid
    $('#asignarVehiculo').on('click', function() {
        if ($('#formVehiculo').valid()) {
            $.ajax({
                url:      $('#formVehiculo').attr('action'),
                type:     'post',
                headers:  {'X-CSRF-TOKEN': $('#formVehiculo').find('input[name="_token"]').val() },
                dataType: 'json',
                data:     $('#formVehiculo').serialize(),
                beforeSend: function() {
                    waitingDialog();
                }
            })
            .done(function(respuesta) {
                closeWaitingDialog();

                if (respuesta.estatus === 'fail') {
                    swal('Error', 'Ocurrió un error al guardar el vehículo.', 'warning');
                }

                if (respuesta.estatus === 'ok') {
                    $('#listadoVehiculos').html(respuesta.html);

                    swal({
                        title: '',
                        text:  'Vehículo registrado con éxito.',
                        type:  'info',
                        closeOnConfirm: true
                    }, function() {
                        // cerrar modal y reiniciar campos
                        $('#descripcion').val('');
                        $('#tipoAccion').val('');
                        $('#vehicleId').val('');
                        $('#modalVehiculos').modal('hide');
                    });
                }
            })
            .fail(function(XmlHttpRequest, textStatus, errorThrown) {
                closeWaitingDialog();
                console.log(textStatus + ': ' + errorThrown);
                swal('Error', 'Ocurrió un error al guardar el vehículo.', 'warning');
            });
        }
    });
});