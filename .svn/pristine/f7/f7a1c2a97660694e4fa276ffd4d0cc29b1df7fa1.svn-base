$(document).ready(function($) {
    var estatus = [];

    // datatable
    dataTable('tablaOfertas', '');

    // click en cancelar
    $('#tablaOfertas').on('click', 'button.cancelarOferta', function (event) {
        var serviceOfferId = $(this).data('id');
        swal({
            title: '',
            text:  '¿Confirma que desea cancelar esta oferta?',
            type:  'warning',
            closeOnConfirm: true,
            showCancelButton: true
        }, function() {
            // eliminar
            $.ajax({
                url:      $('#tablaOfertas').data('url'),
                type:     'post',
                dataType: 'json',
                data:     { serviceOfferId: serviceOfferId },
                headers:  { 'X-CSRF-TOKEN': $('#token').children('input[name="_token"]').val() },
                beforeSend: function() {
                    waitingDialog();
                }
            })
            .done(function(respuesta) {
                closeWaitingDialog();

                if (respuesta.estatus === 'fail') {
                    swal('Error', 'Ocurrió un error al cancelar la oferta.', 'warning');
                }

                if (respuesta.estatus === 'ok') {
                    consultaSaldoTransportistaPrincipal();
                    swal({
                        title: '',
                        text:  'La oferta se canceló con éxito.',
                        type:  'info',
                        closeOnConfirm: true
                    }, function() {
                        window.location.reload(true);
                    });
                }
            })
            .fail(function(XmlHttpRequest, textStatus, errorThrown) {
                closeWaitingDialog();
                console.log(textStatus + ': ' + errorThrown);
                swal('Error', 'Ocurrió un error al cancelar la oferta.', 'warning');
            });
        });
    });

    $('#formBusqueda').on('click', 'input.estatus', function() {
        $.ajax({
            url:      $('#formBusqueda').attr('action'),
            type:     'post',
            dataType: 'json',
            data:     $('#formBusqueda').serialize(),
            headers:  { 'X-CSRF-TOKEN': $('#token').children('input[name="_token"]').val() },
            beforeSend: function() {
                waitingDialog();
            }
        })
        .done(function(respuesta) {
            closeWaitingDialog();

            if (respuesta.estatus === 'fail') {
                swal('Error', 'Ocurrió un error al buscar ofertas.', 'warning');
            }

            if (respuesta.estatus === 'ok') {
                $('#resultadoOfertas').html(respuesta.html);
            }
        })
        .fail(function(XmlHttpRequest, textStatus, errorThrown) {
            closeWaitingDialog();
            console.log(textStatus + ': ' + errorThrown);
            swal('Error', 'Ocurrió un error al buscar ofertas.', 'warning');
        });
    });

    // buscar ofertas por el estatus especificado
    $('#estatus').on('change', function () {
        $.ajax({
            url:      $('#selectEstatus').data('url'),
            type:     'post',
            dataType: 'json',
            data:     { estatus: $('#estatus').val() },
            headers:  { 'X-CSRF-TOKEN': $('#token').children('input[name="_token"]').val() },
            beforeSend: function() {
                waitingDialog();
            }
        })
        .done(function(respuesta) {
            closeWaitingDialog();

            if (respuesta.estatus === 'fail') {
                swal('Error', 'Ocurrió un error al buscar ofertas.', 'warning');
            }

            if (respuesta.estatus === 'ok') {
                $('#resultadoOfertas').html(respuesta.html);
            }
        })
        .fail(function(XmlHttpRequest, textStatus, errorThrown) {
            closeWaitingDialog();
            console.log(textStatus + ': ' + errorThrown);
            swal('Error', 'Ocurrió un error al buscar ofertas.', 'warning');
        });
    });
});