$(document).ready(function($) {
    // datatable
    dataTable('tablaEnvios', '');

    // overriding default values for form validation
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

    // validar formulario vehículos
    $('#formVehiculo').validate({
        rules: {
            vehiculo: 'required',
        },
        messages: {
            vehiculo: {
                required: 'Seleccione un vehículo'
            }
        }
    });

    $('#formCalificacion').validate({
        rules: {
            comentario: 'required'
        },
        messages: {
            comentario: {
                required: 'Ingrese un comentario'
            }
        }
    });

    // calificaciones
    $('#calificacion').rating({
        language: 'es',
        min: 0,
        max: 5,
        step: 1,
        size: 'xs'
    });

    // click en recolectar
    $('#tablaEnvios').on('click', 'button.asignar', function (event) {
        var shipmentId = $(this).data('id');
        $('#shipmentId').val(shipmentId);
        $('#otroVehiculo').val("");
        $('#otroVehiculo').rules('remove');
        $('#otroVehiculo').removeClass('has-error');
        $('#otroVehiculo').addClass('hide');
        $('#vehiculo').val(0);
        
        $("#nombreContacto").val("");
        $("#comentarios").val("");
        $("#divImagenRecoleccion").attr("class","fileupload fileupload-new");
        $("#divImagenFirma").attr("class","fileupload fileupload-new");
        
        $("#divImagen1 img").each(function(key, element){ 
           $(element).attr("src","");
        }); 
        $("#divImagen2 img").each(function(key, element){ 
           $(element).attr("src","");
        }); 
        
        $('#modalVehiculos').modal('show');
        
        capturaVehiculo();
    });

    // click en recolectar
    $('#tablaEnvios').on('click', 'button.recolectar', function (event) {
        var shipmentId = $(this).data('id');
        $('#shipmentId').val(shipmentId);
        
        $("#nombreContacto").val("");
        $("#comentarios").val("");
        $("#divImagenRecoleccion").attr("class","fileupload fileupload-new");
        $("#divImagenFirma").attr("class","fileupload fileupload-new");
        
        $("#divImagen1 img").each(function(key, element){ 
           $(element).attr("src","");
        }); 
        $("#divImagen2 img").each(function(key, element){ 
           $(element).attr("src","");
        }); 

        $("#dni").val('');
        
        $('#modalRecoleccion').modal('show');
    });
    
    
    // click en entregar
    $('#tablaEnvios').on('click', 'button.entregar', function (event) {
        var shipmentId = $(this).data('id');
        $('#shipmentId').val(shipmentId);
        $("#nombreContactoEntrega").val("");
        $("#comentariosEntrega").val("");
        $("#divImagenFirmaEntrega").attr("class","fileupload fileupload-new");
        $("#divImagenEntrega").attr("class","fileupload fileupload-new");
        
        $("#divImagen1Entrega img").each(function(key, element){ 
           $(element).attr("src","");
        }); 
        $("#divImagen2Entrega img").each(function(key, element){ 
           $(element).attr("src","");
        }); 
        $("#dniEntrega").val('');
        
        $('#modalEntrega').modal('show');
        
    });
    
    // Guadar entrega
    $('#entregarEnvioBtn').on('click', function () {
       var shipmentId=$('#shipmentId').val();
        
        var nombreContacto=$('#nombreContactoEntrega').val();
        var comentarios=$('#comentariosEntrega').val();
        var firma="";
        var imagen="";
        var dni=$("#dniEntrega").val();
        
        if(nombreContacto.replace(/\s/g,"")==""){
            swal('', 'El nombre del contacto es obligatorio.', 'warning');
            $("#nombreContacto").focus();
            return;
        }
        
        $("#divImagen1Entrega img").each(function(key, element){ 
           imagen=$(element).attr("src");
        }); 
        
        $("#divImagen2Entrega img").each(function(key, element){ 
           firma=$(element).attr("src");
        }); 
        
        if(firma==""){
            swal('', 'La imagen de la firma es obligatorio.', 'warning');
            return;
        }
        
        
            $.ajax({
                url:      $('#formEntregaEnvio').attr('action'),
                type:     'post',
                dataType: 'json',
                data:{'shipmentId':shipmentId,'nombreContacto':nombreContacto,'comentarios':comentarios,'imagen':imagen,'firma':firma,'dni':dni},
                headers:  { 'X-CSRF-TOKEN': $('#token').children('input[name="_token"]').val() },
                beforeSend: function() {
                    waitingDialog();
                }
            })
            .done(function(respuesta) {
                closeWaitingDialog();

                if (respuesta.estatus === 'fail') {
                    if(respuesta.error=="errorimagenFirma"){
                        swal('', 'La imagen de la firma es inválida, debe ser png o jpg.', 'warning');
                    }else if(respuesta.error=="errorimagen"){
                        swal('', 'La imagen es inválida, debe ser png o jpg.', 'warning');
                    }else{
                        swal('', 'Ocurrió un error al entregar el envío.', 'warning');
                    }
                    
                }

                if (respuesta.estatus === 'ok') {
                    $('#resultadoOfertas').html(respuesta.html);

                    swal({
                        title: '',
                        text:  'Envío entragado correctamente.',
                        type:  'info',
                        closeOnConfirm: true
                    }, function() {
                        // cerrar modal y reiniciar campo de pregunta
                        $('#modalEntrega').modal('hide');
                    });
                }
            })
            .fail(function(XmlHttpRequest, textStatus, errorThrown) {
                closeWaitingDialog();
                console.log(textStatus + ': ' + errorThrown);
                swal('', 'Ocurrió un error al entregar el envío.', 'warning');
            });
    });
    

    // buscar ofertas por el estatus especificado
    $('#estatus').on('click', 'input.estatus', function() {
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
                swal('Error', 'Ocurrió un error al buscar envios.', 'warning');
            }

            if (respuesta.estatus === 'ok') {
                $('#resultadoOfertas').html(respuesta.html);
            }
        })
        .fail(function(XmlHttpRequest, textStatus, errorThrown) {
            closeWaitingDialog();
            console.log(textStatus + ': ' + errorThrown);
            swal('Error', 'Ocurrió un error al buscar envios.', 'warning');
        });
    });

    // selección de vehículo
    $('#vehiculo').on('change', function () {
        if ($(this).val() === '-1') {
            $('#otroVehiculo').removeClass('hide');
            $('#otroVehiculo').rules('add', {
                required: true,
                messages: {
                    required: 'Escriba la descripción del vehículo'
                }
            });
            $('#otroVehiculo').focus();
        } else {
            $('#otroVehiculo').rules('remove');
            $('#otroVehiculo').removeClass('has-error');
            $('#otroVehiculo').addClass('hide');
        }
    });

    // asignar vehículo
    $('#asignarVehiculo').on('click', function () {
        
        var shipmentId=$('#shipmentId').val();
        
        var nombreContacto=$('#nombreContacto').val();
        var comentarios=$('#comentarios').val();
        var firma="";
        var imagen="";

        var vehiculo=$("#vehiculo").val();
        var otroVehiculo=$("#otroVehiculo").val();
        var tracking=$("#tipotracking").val();
        
        if(vehiculo==0){
            swal('', 'Seleccione un vehículo', 'warning');
            return;
        }
        
        if(vehiculo==-1 && otroVehiculo.replace(/\s/g,"")==""){
            swal('', 'Escriba la descripción', 'warning');
            $("#otroVehiculo").focus();
            return;
        }

        if(tracking==""){
            swal('', 'Seleccione un tipo de tracking', 'warning');
            return;
        }
        
            $.ajax({
                url:      $('#formVehiculo').attr('action'),
                type:     'post',
                dataType: 'json',
                //data:     $('#formVehiculo').serialize(),
                data:{'shipmentId':shipmentId,'vehiculo':vehiculo,'otroVehiculo':otroVehiculo,'tracking':tracking},
                headers:  { 'X-CSRF-TOKEN': $('#token').children('input[name="_token"]').val() },
                beforeSend: function() {
                    waitingDialog();
                }
            })
            .done(function(respuesta) {
                closeWaitingDialog();

                if (respuesta.estatus === 'fail') {
                    if(respuesta.error=="errorimagenFirma"){
                        swal('', 'La imagen de la firma es inválida, debe ser png o jpg.', 'warning');
                    }else if(respuesta.error=="errorimagen"){
                        swal('', 'La imagen es inválida, debe ser png o jpg.', 'warning');
                    }else{
                        swal('', 'Ocurrió un error al asignar el vehículo al envío.', 'warning');
                    }
                    
                }

                if (respuesta.estatus === 'ok') {
                    $('#resultadoOfertas').html(respuesta.html);

                    swal({
                        title: '',
                        text:  'Vehículo asignado con éxito.',
                        type:  'info',
                        closeOnConfirm: true
                    }, function() {
                        // cerrar modal y reiniciar campo de pregunta
                        $('#otroVehiculo').rules('remove');
                        $('#otroVehiculo').removeClass('has-error');
                        $('#otroVehiculo').addClass('hide');
                        $('#vehiculo option[value="Seleccione"]').attr('selected', true);
                        $('#modalVehiculos').modal('hide');
                    });
                }
            })
            .fail(function(XmlHttpRequest, textStatus, errorThrown) {
                closeWaitingDialog();
                console.log(textStatus + ': ' + errorThrown);
                swal('Error', 'Ocurrió un error al asignar el vehículo al envío.', 'warning');
            });
    });

    // recoger envio
    $('#btnRecolectar').on('click', function () {
        
        var shipmentId=$('#shipmentId').val();
        
        var nombreContacto=$('#nombreContacto').val();
        var comentarios=$('#comentarios').val();
        var firma="";
        var imagen="";
        var dni=$("#dni").val();
        
        if(nombreContacto.replace(/\s/g,"")==""){
            swal('', 'El nombre del contacto es obligatorio.', 'warning');
            $("#nombreContacto").focus();
            return;
        }
        
        $("#divImagen1 img").each(function(key, element){ 
           imagen=$(element).attr("src");
        }); 
        
        $("#divImagen2 img").each(function(key, element){ 
           firma=$(element).attr("src");
        }); 
        
        if(firma==""){
            swal('', 'La imagen de la firma es obligatorio.', 'warning');
            return;
        }

        
        
            $.ajax({
                url:      $('#formRecoger').attr('action'),
                type:     'post',
                dataType: 'json',
                //data:     $('#formVehiculo').serialize(),
                data:{'shipmentId':shipmentId,'nombreContacto':nombreContacto,'comentarios':comentarios,'imagen':imagen,'firma':firma,'dni':dni},
                headers:  { 'X-CSRF-TOKEN': $('#token').children('input[name="_token"]').val() },
                beforeSend: function() {
                    waitingDialog();
                }
            })
            .done(function(respuesta) {
                closeWaitingDialog();

                if (respuesta.estatus === 'fail') {
                    if(respuesta.error=="errorimagenFirma"){
                        swal('', 'La imagen de la firma es inválida, debe ser png o jpg.', 'warning');
                    }else if(respuesta.error=="errorimagen"){
                        swal('', 'La imagen es inválida, debe ser png o jpg.', 'warning');
                    }else{
                        swal('', 'Ocurrió un error al asignar el vehículo al envío.', 'warning');
                    }
                    
                }

                if (respuesta.estatus === 'ok') {
                    $('#resultadoOfertas').html(respuesta.html);

                    swal({
                        title: '',
                        text:  'Envío recogido',
                        type:  'info',
                        closeOnConfirm: true
                    }, function() {
                        // cerrar modal y reiniciar campo de pregunta
                        $('#modalRecoleccion').modal('hide');
                    });
                }
            })
            .fail(function(XmlHttpRequest, textStatus, errorThrown) {
                closeWaitingDialog();
                console.log(textStatus + ': ' + errorThrown);
                swal('Error', 'Ocurrió un error al asignar el vehículo al envío.', 'warning');
            });
    });

    // calificar cliente
    $('#tablaEnvios').on('click', 'button.calificar', function (event) {
        var shipmentId = $(this).data('id');
        $('#shipmentIdCalificacion').val(shipmentId);
        $('#modalCalificacion').modal('show');
    });

    // guardar calificación
    $('#asignarCalificacion').on('click', function(event) {
        if (!$('#formCalificacion').valid()) {
            return false;
        }

        if ($('#calificacion').val() === '0') {
            swal({
                title: '',
                text:  'Se asignará una calificación de 0 al cliente, ¿desea continuar?',
                type:  'warning',
                closeOnConfirm: true,
                showCancelButton: true
            }, function() {
                // guardar  calificación
                guardarCalificacion();
            });

        } else {
            guardarCalificacion();
        }

    });

    /**
     * guardar la calificacion
     * @return json repuesta
     */
    function guardarCalificacion() {
        $.ajax({
            url: $('#formCalificacion').attr('action'),
            type: 'post',
            dataType: 'json',
            data: $('#formCalificacion').serialize(),
            headers:  { 'X-CSRF-TOKEN': $('#token').children('input[name="_token"]').val() },
            beforeSend: function() {
                waitingDialog();
            }
        }).done(function(respuesta) {
            closeWaitingDialog();

            if (respuesta.estatus === 'fail') {
                swal('Error', 'Ocurrió un error al calificar al cliente.', 'warning');
            }

            if (respuesta.estatus === 'OK') {
            	$('#resultadoOfertas').html(respuesta.html);
                swal({
                    title: '',
                    text:  'Se calificó al cliente de manera exitosa.',
                    type:  'info'
                }, function() {
                    $('#modalCalificacion').modal('hide');
                    $('#calificacion').val('0');
                    $('#comentario').val('');
                });
            }

        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
            closeWaitingDialog();
            console.log(textStatus + ': ' + errorThrown);
            swal('Error', 'Ocurrió un error al calificar al cliente.', 'warning');
        });
    }
});

  
    
