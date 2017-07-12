'use strict';

jQuery(document).ready(function($) {
    var $generalDataForm        = $('#generalDataForm'),
        $locationForm           = $('#locationForm'),
        $formPicture            = $('#formPicture'),
        $services               = $('#services'),
        $changeProfileImage     = $('#changeProfileImage'),
        $loadPicture            = $('#loadPicture'),
        $loader                 = $('#loader'),
        $notifications          = $('#notifications'),
        $updateProfile          = $('#updateProfile'),
        $updateServices         = $('#updateServices'),
        $updateLocation         = $('#updateLocation'),
        optionsPicture          = {
            dataType: 'json',
            beforeSubmit: function () {
                $loader.modal('show');
            },
            success: function (response, statusText, xhr, $form) {
                $loader.modal('hide');
                if (response.status === 'success') {
                    // load image
                    $('.user-picture').attr('src', response.imgUrl + '?' + Math.floor((Math.random() * 10000) + 1));
                }
                if (response.status === 'error') {
                    swal('Error', 'Ocurrió un error al actualizar la imagen de perfil del prestador de servicios: ' + response.error, 'error');
                }
            },
            error: function () {
                $loader.modal('hide');
                swal('Error', 'Ocurrió un error al actualizar la imagen de perfil del prestador de servicios', 'error');
            }
        };

    // setting ajax form for sending image
    $formPicture.ajaxForm(optionsPicture);

    // load states - municipality
    $generalDataForm.on('change', 'select.aUnit', function(event) {
        var aUnitId = $(this).val(),
            target  = '#' + $(this).data('target');

        // avoid change on empty
        if (aUnitId === '') {
            return false;
        }

        $.ajax({
            url:        '/crear-cuenta/a-units/search',
            type:       'POST',
            dataType:   'json',
            data:       {aUnitId: aUnitId},
            beforeSend: function () {
                $loader.modal('show');
            }
        })
        .done(function(result) {
            console.log(result.status);
            $loader.modal('hide');

            if (result.status === 'OK') {
                $(target).html(result.html);

                $(target).selectpicker('refresh');
            }

            if (result.status === 'fail') {
                swal(result.message);
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('error: ' + textStatus + ' -- ' + errorThrown);
            $loader.modal('hide');
        });
    });

    // validate form
    validateForm($generalDataForm);
    validateForm($locationForm);
    validateForm($formPicture);

    // profile image change
    $changeProfileImage.on('click', function () {
        $formPicture.find('input.loadPicture').click();
    });

    // upload image
    $formPicture.find('input.loadPicture').on('change', function() {
        if (!$formPicture.valid()) {
            swal('Error', 'Ingrese una imagen en formato .jpg, .png o .gif', 'error');
            return false;
        }
        $formPicture.submit();
        let $inputImage = $formPicture.find('input.loadPicture');
        $inputImage.replaceWith($inputImage.val('').clone(true));

        $inputImage.rules('add', {
            required: true,
            extension: 'jpg|png|gif'
        });
    });

    // click on notifications
    $notifications.on('click', function () {
        $.ajax({
            url:        '/prestador-servicios/perfil/notificaciones',
            type:       'POST',
            dataType:   'json',
            data:       collectDataFromNotifications(),
            beforeSend: function () {
                $loader.modal('show');
            }
        })
        .done(function(response) {
            $loader.modal('hide');

            if (response.status === 'success') {
                swal('¡Éxito!', 'Notificaciones actualizados con éxito', 'success');
            }

            if (response.status === 'error') {
                swal('Error', 'Ocurrió un error al actualizar las notificaciones del prestador de servicios: ' + response.error, 'error');
            }
        })
        .fail(function(jqXHR) {
            $loader.modal('hide');
            swal('Error', 'Ocurrió un error al actualizar las notificaciones del prestador de servicios', 'error');
        });
    });

    // update profile
    $updateProfile.on('click', function () {
        if ($generalDataForm.valid()) {
            $.ajax({
                url:        '/prestador-servicios/perfil',
                type:       'POST',
                dataType:   'json',
                data:       $generalDataForm.serialize(),
                beforeSend: function () {
                    $loader.modal('show');
                }
            })
            .done(function(response) {
                $loader.modal('hide');

                if (response.status === 'success') {
                    swal('¡Éxito!', 'Perfil actualizado con éxito', 'success');
                }

                if (response.status === 'error') {
                    swal('Error', 'Ocurrió un error al actualizar el perfil del prestador de servicios: ' + response.error, 'error');
                }
            })
            .fail(function(jqXHR) {
                $loader.modal('hide');
                swal('Error', 'Ocurrió un error al actualizar el perfil del prestador de servicios', 'error');
            });
        }
    });

    // update location
    $updateLocation.on('click', function () {
        if ($locationForm.valid()) {
            $.ajax({
                url:        '/prestador-servicios/perfil/ubicacion',
                type:       'POST',
                dataType:   'json',
                data:       $locationForm.serialize(),
                beforeSend: function () {
                    $loader.modal('show');
                }
            })
            .done(function(response) {
                $loader.modal('hide');

                if (response.status === 'success') {
                    swal('¡Éxito!', 'Ubicación actualizada con éxito', 'success');
                }

                if (response.status === 'error') {
                    swal('Error', 'Ocurrió un error al actualizar la ubicación del prestador de servicios: ' + response.error, 'error');
                }
            })
            .fail(function(jqXHR) {
                $loader.modal('hide');
                swal('Error', 'Ocurrió un error al actualizar la ubicación del prestador de servicios', 'error');
            });
        }
    });
});

/**
 * validates a form
 *
 * @param Object $form
 * @return void
 */
function validateForm($form) {
    $form.validate({
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
        ignore: []
    });
}

/**
 * collects data from notifications
 *
 * @return Object
 */
function collectDataFromNotifications () {
    return {
        newDate:       $('#newDate').prop('checked') ? 1 : 0,
        dateCancelled: $('#dateCancelled').prop('checked') ? 1 : 0,
        _method:       'PUT',
    };
}