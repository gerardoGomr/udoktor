'use strict';

jQuery(document).ready(function($) {
    var $paso2                = $('#paso2'),
        $pasoAnterior         = $('#pasoAnterior'),
        $crearCuenta          = $('#crearCuenta'),
        $mapa                 = $('#mapa'),
        $abrirMapa            = $('#abrirMapa'),
        $modalMapa            = $('#modalMapa'),
        $formCrearCuenta      = $('#formCrearCuenta'),
        $servicios            = $('#servicios'),
        $informacionBasica    = $('#informacionBasica'),
        $informacionPrestador = $('#informacionPrestador'),
        $captcha              = $("#g-recaptcha-response"),
        $loader               = $('#loader'),
        listaServicios        = JSON.parse(atob($('#services').val())),
        servicios             = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local:          listaServicios
        });

    servicios.initialize();
    $servicios.tagsinput({
        itemValue: 'value',
        itemText:  'text',
        typeaheadjs: {
            name:       'servicios',
            displayKey: 'text',
            source:     servicios.ttAdapter()
        }
    });

    // cargar estados - municipios
    $formCrearCuenta.on('change', 'select.aUnit', function(event) {
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

    // click para el paso 2
	$paso2.on('click', function () {
		validateForm();

        if ($formCrearCuenta.valid()) {

            if (!$('#aceptaTerminos').prop('checked')) {
                swal('Por favor, acepte los términos y condiciones antes de continuar.', 'warning');
                return false;
            }

            let tipoCuenta = null;

            if ($('#cuentaCliente').prop('checked')) {
                tipoCuenta = 1;
            }

            if ($('#cuentaPrestador').prop('checked')) {
                tipoCuenta = 2;
            }

            if (tipoCuenta === 1) {
                crearCuenta($formCrearCuenta.serialize());
                return false;
            }

            $informacionBasica.hide(300);
            $pasoAnterior.show(300);
            $crearCuenta.show(300);

            if (tipoCuenta === 2) {
                // show data for prestador
                $informacionPrestador.show(300);
            }
        }
	});

    // regresar un paso
    $pasoAnterior.on('click', function () {
        //$captcha.hide(300);
        $crearCuenta.hide(300);
        $pasoAnterior.hide(300);

        if ($informacionPrestador.is(':visible')) {
            $informacionPrestador.hide(300);
        }

        $informacionBasica.show(300);
    });

    // crear cuenta
    $crearCuenta.on('click', function () {
        validateForm();
        if ($formCrearCuenta.valid()) {
            crearCuenta($formCrearCuenta.serialize());
        }
    });

    /**
     * crear cuenta
     *
     * @param Object datos
     * @return Json
     */
    function crearCuenta(datos) {
        $.ajax({
            url:        $formCrearCuenta.attr('action'),
            type:       'POST',
            dataType:   'json',
            data:       datos,
            beforeSend: function () {
                $loader.modal('show');
            }
        })
        .done(function(respuesta) {
            $loader.modal('hide');

            if (respuesta.estatus === 'fail') {
                swal('¡Error!', 'Tuvimos un inconveniente al crear su cuenta. Por favor, intente de nuevo', 'warning');
            }

            if (respuesta.estatus === 'OK') {
                swal({
                    title:             '¡Cuenta creada!',
                    text:              'Muchas gracias por registrarse en Udoktor. Le hemos enviado un correo electrónico con instrucciones para que active su cuenta en la aplicación.<br> Revise en la sección de spam de su cuenta de correo electrónico en caso de que no pueda ver el correo enviado por Udoktor en la bandeja de entrada.',
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
            $loader.modal('hide');
            let errors = '';
            if(jqXHR.status === 422) {
                $.each(jqXHR.responseJSON, function(key, value) {
                    errors += '-' + value + "\n";
                });
            }
            swal('¡Error!', "Tuvimos un inconveniente al crear su cuenta:\n\n" + errors, 'warning');
        });
    }

    /**
     * inicializar validacion de form
     */
    function validateForm() {
        $formCrearCuenta.validate({
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
    }
});