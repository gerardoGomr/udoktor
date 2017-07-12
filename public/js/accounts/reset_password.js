'use strict';

jQuery(document).ready(function($) {
    var $formPassword = $('#formPassword'),
        $email        = $('#email'),
        $loader       = $('#loader');

    $formPassword.validate({
        highlight: function (input) {
            console.log(input);
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
            $(element).parents('.form-group').append(error);
        }
    })
        .settings.ignore = ':disabled,:hidden';

    $('#reset').on('click', procesarForm);

    function procesarForm() {
        if ($formPassword.valid()) {
            $.ajax({
                url:        '/cuentas/nueva-contrasenia',
                type:       'POST',
                dataType:   'json',
                data:       $formPassword.serialize(),
                beforeSend: function () {
                    $loader.modal('show');
                }
            })
            .done(function(result) {
                if (result.status === 'error') {
                    swal('warning', 'Ocurrió un error al resetear la contraseña para la cuenta de ' + $email.val() + '. Por favor, intente de nuevo.');
                }

                if (result.status === 'success') {
                    swal({
                        title:             '¡Contraseña cambiada!',
                        text:              'Su contraseña ha sido cambiada exitosamente.',
                        type:              'success',
                        showCancelButton:  false,
                        confirmButtonText: 'Continuar',
                        closeOnConfirm:    false,
                        html:              true
                    }, function () {
                        window.location.href = '/';
                    });
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ': ' + errorThrown);

                swal('warning', 'Ocurrió un error al resetear la contraseña para la cuenta de ' + $email.val() + '. Por favor, intente de nuevo.');
            })
            .always(function () {
                $loader.modal('hide');
            });
        }
    }
});