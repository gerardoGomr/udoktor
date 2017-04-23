jQuery(document).ready(function($) {
    var $paso2           = $('#paso2'),
        $formCrearCuenta = $('#formCrearCuenta');

	$paso2.on('click', function (event) {
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
        }).settings.ignore = ':disabled,:hidden';
        if ($formCrearCuenta.valid()) {
            var tipoCuenta;

            if ($('#cuentaCliente').attr('checked')) {
                tipoCuenta = 1;
            }

            if ($('#cuentaPrestador').attr('checked')) {
                tipoCuenta = 2;
            }

            if (tipoCuenta === 1) {
                // show data for cliente
            }

            if (tipoCuenta === 2) {
                // show data for prestador
            }

            $('#informacionBasica').hide(300);
            $('#informacionPrestador').show(300);
        }
	});
});