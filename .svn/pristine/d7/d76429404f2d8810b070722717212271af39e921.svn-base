$(document).ready(function() {

    // calificaciones
    $('#calificaciones').on('click', 'div.calificacion', function() {
        var calificacion = $(this).data('id');

        $.ajax({
            url: $('#tablaResultados').data('url'),
            type: 'post',
            dataType: 'json',
            data: { calificacion: calificacion },
            headers:  { 'X-CSRF-TOKEN': $('#tablaResultados').data('token') },
            beforeSend: function() {
                waitingDialog();
            }
        }).done(function(respuesta) {
            closeWaitingDialog();

            if (respuesta.estatus === 'fail') {
                swal('Error', 'Ocurrió un error al obtener las calificaciones.', 'warning');
            }

            if (respuesta.estatus === 'OK') {
                $('#tablaResultados').html(respuesta.html);

                dataTable('tablaCalificaciones');
            }

        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
            closeWaitingDialog();
            console.log(textStatus + ': ' + errorThrown);
            swal('Error', 'Ocurrió un error al obtener las calificaciones.', 'warning');
        });
    });
});