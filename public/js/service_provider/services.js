'use strict';

jQuery(document).ready(function($) {
    let $addServiceForm = $('#add-service-form'),
        $btnAddService  = $('#btn-add-service'),
        $addService     = $('#add-service'),
        $services       = $('#services'),
        $loader         = $('#loader');

    // click on notifications
    $btnAddService.on('click', function () {
        $.ajax({
            url:        '/prestador-servicios/servicios',
            type:       'POST',
            dataType:   'json',
            data:       $addServiceForm.serialize(),
            beforeSend: beforeSend
        })
        .done(function(response) {
            $loader.modal('hide');

            if (response.status === 'success') {
                swal('¡Éxito!', 'Servicios asignados con éxito', 'success');
                $services.html(response.html);
                $addService.modal('hide');
                cleanForm($addServiceForm);
            }

            if (response.status === 'error') {
                swal('Error', 'Ocurrió un error al asignar los servicios al usuario: ' + response.message, 'error');
            }
        })
        .fail(function(jqXHR) {
            $loader.modal('hide');
            swal('Error', 'Ocurrió un error al asignar los servicios al usuario', 'error');
        });
    });

    // removing service
    $services.on('click', 'button.remove-service', function() {
        let serviceId = $(this).data('id');

        swal({
            title:             '¡Advertencia!',
            text:              'Se removerá el servicio seleccionado del prestador de servicios, ¿desea continuar?',
            type:              'warning',
            showCancelButton:  true,
            confirmButtonText: 'Continuar',
            cancelButtonText:  'Cancelar',
            closeOnConfirm:    false,
            html:              true
        }, function () {
            $.ajax({
                url:        '/prestador-servicios/servicios',
                type:       'POST',
                dataType:   'json',
                data:       {
                    id:      serviceId,
                    _method: 'DELETE'
                },
                beforeSend: beforeSend
            })
            .done(function(response) {
                $loader.modal('hide');

                if (response.status === 'success') {
                    swal('¡Éxito!', 'Servicio removido con éxito', 'success');
                    $services.html(response.html);
                }

                if (response.status === 'error') {
                    swal('Error', 'Ocurrió un error al remover el servicio del usuario: ' + response.message, 'error');
                }
            })
            .fail(function(jqXHR) {
                $loader.modal('hide');
                swal('Error', 'Ocurrió un error al remover el servicio del usuario', 'error');
            });
        });
    });

    // update the type of price
    $services.on('click', 'button.btn-change-service-type', function() {
        let priceType = $services.find('input[name="priceType"]:checked').val();

        $.ajax({
            url:        '/prestador-servicios/precios',
            type:       'POST',
            dataType:   'json',
            data:       {
                priceType: priceType,
                _method:   'PUT'
            },
            beforeSend: beforeSend
        })
        .done(function(response) {
            $loader.modal('hide');

            if (response.status === 'success') {
                $services.html(response.html);
            }

            if (response.status === 'error') {
                swal('Error', 'Ocurrió un error al actualizar el tipo de precio del proveedor de servicios: ' + response.message, 'error');
            }
        })
        .fail(function(jqXHR) {
            $loader.modal('hide');
            let errors = '';
            if(jqXHR.status === 422) {
                $.each(jqXHR.responseJSON, function(key, value) {
                    errors += value + '<br>';
                });
            }
            swal('Error', 'Ocurrió un error al actualizar el tipo de precio del proveedor de servicios: ' + errors, 'error');
        });
    });

    // save prices when fixed
    $services.on('click', 'button.btn-save-prices', function() {
        $.ajax({
            url:        '/prestador-servicios/precios',
            type:       'PATCH',
            dataType:   'json',
            data:       gatherPricesToSend(),
            beforeSend: beforeSend
        })
        .done(function(response) {
            $loader.modal('hide');

            if (response.status === 'success') {
                swal('¡Éxito!', 'Precios de servicios actualizados con éxito', 'success');
                $services.html(response.html);
            }

            if (response.status === 'error') {
                swal('Error', 'Ocurrió un error al actualizar los precios de los servicios: ' + response.message, 'error');
            }
        })
        .fail(function(jqXHR) {
            $loader.modal('hide');
            let errors = '';
            if(jqXHR.status === 422 || jqXHR.status === 500) {
                $.each(jqXHR.responseJSON, function(key, value) {
                    errors += value + '<br>';
                });
            }
            swal('Error', 'Ocurrió un error al actualizar los precios de los servicios: ' + errors, 'error');
        });
    });
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
 * cleans the form
 *
 * @param {Object} $form
 * @return {void}
 */
function cleanForm($form) {
    $form.find('input[type="checkbox"]').each(function (element) {
        let $element = $(this);

        if ($element.prop('checked')) {
            $element.attr('checked', false);
        }
    });
}

/**
 * gathers data for prices
 *
 * @return {Object}
 */
function gatherPricesToSend() {
    let $prices = $('#services').find('input.price'),
        prices  = $prices.map(function(i, el) {
            return el.value;
        })
        .get(),
        offeredServicesIds = $prices.map(function () {
            return $(this).data('id')
        })
        .get();

    return {
        prices:             prices,
        offeredServicesIds: offeredServicesIds,
        _method:            'PATCH',
    }
}