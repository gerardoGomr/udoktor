$(document).ready(function($) {
    // variables
    var $formOferta = $('#formOferta'),
        costRule    = '',
        costMessage = '';
        tipoR = 0;
        tipoE = 0;

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

    // crear rule que verifica el valor del costo
    $.validator.addMethod('lessThanEqual', function(value, element, param) {
        var i = parseFloat(value);
        var j = parseFloat(param);
        return (i < j) ? true : false;
    });

    // validar formulario
    $formOferta.validate({
        rules: {
            costoOferta: {
                required: true,
                number: true,
                min: 1
            },
            condiciones: {
                maxlength: 200
            },
            //currency: 'required',
            formaRecoleccion: 'required',
            formaEntrega: 'required'
        },
        messages: {
            costoOferta: {
                required: 'Ingrese el costo de la oferta',
                number: 'Ingrese solo números',
                min: 'Ingrese una cantidad mayor a S/ 0.00'
            },
            condiciones: {
                maxlength: 'Máximo 200 caracteres'
            },
            /*currency: {
                required: 'Seleccione el tipo de moneda'
            },*/
            formaRecoleccion: {
                required: 'Seleccione una forma de recolección'
            },
            formaEntrega: {
                required: 'Seleccione una forma de entrega'
            }
        }
    });

    // agregando validacion a costo fijo
    if ($('#costType').val() === '1') {
        $('#costoOferta').rules('add', {
            max: $('#costoAOfertar').val(),
            messages: {
                max: 'Ingrese una cantidad menor o igual a ' + $('#costoAOfertar').val()
            }
        });
    }

    // guardar form
    $('#publicarOferta').on('click', function (event) {
        event.preventDefault();
            
        
        if ($formOferta.valid()) {
            var precioOfertar=$("#precioPorOfertarOcultoFormato").val();
            if(precioOfertar==-1){
                swal("","No hay precio establecido para realizar la oferta, consulte al administrador.","warning");
                return;
            }
            swal({
             title: "",   
             text: "El precio por ofertar en este envío es : " + precioOfertar +" ¿Desea continuar?",
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#47A447",
             confirmButtonText: "Aceptar",
             cancelButtonText: "Cancelar", 
             closeOnConfirm: true,   
             closeOnCancel: true }, 
             function(isConfirm){   
                if (isConfirm) {
                    
                    // envío ajax
                        $.ajax({
                            url:      $formOferta.attr('action'),
                            type:     'post',
                            dataType: 'json',
                            data:     $formOferta.serialize(),
                            headers:  {'X-CSRF-TOKEN': $formOferta.find('input[name="_token"]').val() },
                            beforeSend: function() {
                                waitingDialog();
                            }
                        })
                        .done(function(respuesta) {
                            closeWaitingDialog();

                            if (respuesta.estatus === 'fail') {
                                swal('Error', "Ocurrió un error al publicar la oferta.\n" + respuesta.error, 'warning');
                            }

                            if (respuesta.estatus === 'ok') {
                                consultaSaldoTransportistaPrincipal();
                                swal({
                                    title: '',
                                    text:  'La oferta se publicó con éxito.',
                                    type:  'info',
                                    closeOnConfirm: true
                                }, function() {
                                    // redirect to prev view
                                    window.location.href = $('#cancelar').attr('href');
                                });
                            }
                        })
                        .fail(function(XmlHttpRequest, textStatus, errorThrown) {
                            closeWaitingDialog();
                            console.log(textStatus + ': ' + errorThrown);
                            swal('Error', 'Ocurrió un error al publicar la oferta.', 'warning');
                        });
                        
                }
            });
            
            
            
            
        } else {
            swal('', 'Tiene campos sin completar', 'warning');
        }
    });

    // datepicker
    $formOferta.find('input.fecha').datepicker({
        format:    'dd-mm-yyyy',
        autoclose: true
    });
    
    $formOferta.find('input.fecha2').datepicker({
        format:    'dd-mm-yyyy',
        autoclose: true
    });

    $formOferta.find('input.fecha').datepicker().on('changeDate',function(e){
        var startDate=new Date(e.date.getTime());
        var yy=startDate.getFullYear();
        var mm=startDate.getMonth()+1;
        var dd=startDate.getDate();
        if(mm<10)mm='0'+mm;
        var fechapick=yy+'-'+mm+'-'+dd;
        semaforo(fechapick,1);
    });
    
    $formOferta.find('input.fecha2').datepicker().on('changeDate',function(e){
        var startDate=new Date(e.date.getTime());
        var yy=startDate.getFullYear();
        var mm=startDate.getMonth()+1;
        var dd=startDate.getDate();
        if(mm<10)mm='0'+mm;
        var fechapick=yy+'-'+mm+'-'+dd;
        semaforo(fechapick,2);
    });

    $('#entreFechaRecoleccion').datepicker().on('changeDate',function(e){

            var startDate=new Date(e.date.getTime());
            $("#yFechaRecoleccion").datepicker('setStartDate',startDate);       
    });

    $('#entreFechaEntrega').datepicker().on('changeDate',function(e){
            var startDate=new Date(e.date.getTime());
            $("#yFechaEntrega").datepicker('setStartDate',startDate);       
    });

    $formOferta.find('input.fecha').datepicker('setStartDate',new Date(new Date().getTime()-86400000));

    // selección de currency
    // $('#currency').on('change', function () {
    //     if ($(this).val() !== '') {
    //         switch ($(this).val()) {
    //             case '1':
    //                 $('#faCurrency').addClass('fa-dollar');
    //                 break;
    //         }
    //     }
    // });

    // selección de recolección
    $formOferta.find('input.formaRecoleccion').on('click', function () {
        if ($(this).val() === '1') {
            // una sola fecha
            $('#contenedorFechasRecoleccion').show(300);
            $('#yFechaRecoleccion').hide(300);

            $('#entreFechaRecoleccion').attr('placeholder', 'Especifique la fecha');

            // agregar validacion a primer campo fecha y remover al segundo
            $('#entreFechaRecoleccion').rules('add', {
                required: true,
                messages: {
                    required: 'Ingrese la fecha de recolección'
                }
            });

            $('#yFechaRecoleccion').rules('remove');
            $('#yFechaRecoleccion').val('');
            $('#yFechaRecoleccion').siblings('p.has-error').remove();

            tipoR=1;
            $("#semaforo").html('');
        }

        if ($(this).val() === '2') {
            $('#entreFechaRecoleccion').val('');
            $('#yFechaRecoleccion').val('');
            $('#contenedorFechasRecoleccion').show(300);
            $('#yFechaRecoleccion').show(300);

            $('#entreFechaRecoleccion').attr('placeholder', 'Entre fecha');
            $('#yFechaRecoleccion').attr('placeholder', 'Y fecha');

            // agregar validacion a primer y segundo campo fecha
            $('#entreFechaRecoleccion').rules('add', {
                required: true,
                messages: {
                    required: 'Ingrese entre que fecha'
                }
            });

            $('#yFechaRecoleccion').rules('add', {
                required: true,
                messages: {
                    required: 'Ingrese hasta que fecha'
                }
            });

            tipoR=2;
            $("#semaforo").html('');
        }
    });

    // selección de entrega
    $formOferta.find('input.formaEntrega').on('click', function () {
        if ($(this).val() === '1') {
            // una sola fecha
            $('#contenedorFechasEntrega').show(300);
            $('#yFechaEntrega').hide(300);

            $('#entreFechaEntrega').attr('placeholder', 'Especifique la fecha');

            // agregar validacion a primer campo fecha y remover al segundo
            $('#entreFechaEntrega').rules('add', {
                required: true,
                messages: {
                    required: 'Ingrese la fecha de recolección'
                }
            });

            $('#yFechaEntrega').rules('remove');
            $('#yFechaEntrega').val('');
            $('#yFechaEntrega').siblings('p.has-error').remove();

            tipoE=1;
            $("#semaforo2").html('');
        }

        if ($(this).val() === '2') {
            $('#entreFechaEntrega').val('');
            $('#yFechaEntrega').val('');
            $('#contenedorFechasEntrega').show(300);
            $('#yFechaEntrega').show(300);

            $('#entreFechaEntrega').attr('placeholder', 'Entre fecha');
            $('#yFechaEntrega').attr('placeholder', 'Y fecha');

            // agregar validacion a primer y segundo campo fecha
            $('#entreFechaEntrega').rules('add', {
                required: true,
                messages: {
                    required: 'Ingrese entre que fecha'
                }
            });

            $('#yFechaEntrega').rules('add', {
                required: true,
                messages: {
                    required: 'Ingrese hasta que fecha'
                }
            });

            tipoE=2;
            $("#semaforo2").html('');
        }
    });

    $('#cancelar').on('click', function(event) {
        event.preventDefault();

        swal({
            title: '',
            text:  '¿Desea cancelar la publicación de la oferta y volver a la pantalla anterior?',
            type:  'warning',
            showCancelButton: true,
            closeOnConfirm: true
        }, function() {
            window.location.href = $('#cancelar').attr('href');
        });
    });
});

function semaforo(pick,op){
    var fr1=$("#entreFechaRecoleccion").val();
    var fr2=$("#yFechaRecoleccion").val();
    var fr3=$("#entreFechaEntrega").val();
    var fr4=$("#yFechaEntrega").val();

    if(op==1){
        if(fechaR2==""){//se valida solo con la primer fecha de recolección
            if(tipoR==1){//si selecciono solo una fecha
                if(fechaR1==pick){
                    $("#semaforo").html('<img src="/img/calendario_verde.png">');
                }
                else{
                $("#semaforo").html('<img src="/img/calendario_rojo.png">');
                }
            }else{//si selecciono un periodo de fechas
                if(fechaR1 >= parseFecha(fr1) && fechaR1 <= parseFecha(fr2)){
                    $("#semaforo").html('<img src="/img/calendario_verde.png">');
                }
                else{
                    $("#semaforo").html('<img src="/img/calendario_rojo.png">');
                }
            }
        }
        else{//se valida que esten dentro del periodo de recoleccion
            if(tipoR==1){//si selecciono solo una fecha
                if(pick >= fechaR1 && pick <= fechaR2){
                    $("#semaforo").html('<img src="/img/calendario_verde.png">');
                }
                else{
                    $("#semaforo").html('<img src="/img/calendario_rojo.png">');
                }
            }else{//si selecciono un periodo de fechas
                if((parseFecha(fr1) >= fechaR1 && parseFecha(fr1) <=fechaR2) && (parseFecha(fr2) >= fechaR1 && parseFecha(fr2) <= fechaR2)){
                    $("#semaforo").html('<img src="/img/calendario_verde.png">');
                }
                else{
                    $("#semaforo").html('<img src="/img/calendario_rojo.png">');
                }
            }
        }
    }
    

    if(op==2){
            //entrega
            if(fechaE2==""){//se valida solo con la primer fecha de recolección
                if(tipoE==1){//si selecciono solo una fecha
                    if(fechaE1==pick){
                         $("#semaforo2").html('<img src="/img/calendario_verde.png">');
                    }
                    else{
                        $("#semaforo2").html('<img src="/img/calendario_rojo.png">');
                    }
                }else{//si selecciono un periodo de fechas
                    if(fechaE1 >= parseFecha(fr3) && fechaE1 <= parseFecha(fr4)){
                         $("#semaforo2").html('<img src="/img/calendario_verde.png">');
                    }
                    else{
                         $("#semaforo2").html('<img src="/img/calendario_rojo.png">');
                    }
                }
            }
            else{//se valida que esten dentro del periodo de recoleccion
                if(tipoE==1){//si selecciono solo una fecha
                    if(pick >= fechaE1 && pick <= fechaE2){
                         $("#semaforo2").html('<img src="/img/calendario_verde.png">');
                    }
                    else{
                        $("#semaforo2").html('<img src="/img/calendario_rojo.png">');
                    }
                }else{//si selecciono un periodo de fechas
                    if((parseFecha(fr3) >= fechaE1 && parseFecha(fr3) <= fechaE2) && (parseFecha(fr4) >= fechaE1 && parseFecha(fr4) <= fechaE2)){
                         $("#semaforo2").html('<img src="/img/calendario_verde.png">');
                    }
                    else{
                        $("#semaforo2").html('<img src="/img/calendario_rojo.png">');
                    }
                }
            }
        }
}


function parseFecha(fecha){
    var str=fecha.split("-");
    return str[2]+'-'+str[1]+'-'+str[0];
}

/* Obtiene el precio por ofertar
 * Autor: OT
 * FEcha: 29-09-2016
 */

function obtenerPrecioOferta(){
    var idEnvio=$("#idEnvioOculto").val();
    var oferta=$("#costoOferta").val();
    
    if(oferta=="" ||  !/^([0-9])*.([0-9])*$/.test(oferta)  || parseFloat(oferta)<0 || isNaN(oferta)){
        $("#precioPorOfertarOculto").val("-1");
        $("#precioPorOfertarOcultoFormato").val("0.00");
        $("#preioPorOfertar").val("0.00");
        swal("","El precio a ofertar no es válido.","warning");
        return;
    }
    
    waitingDialog();
        $.ajax({
               datatype:"JSON",
               type:"GET",
               data:{'idEnvio':idEnvio,'oferta':oferta},
               url:"/transportista/obtenerPrecioPorOfertar",
               success:function(data){
                    setTimeout(function(){closeWaitingDialog();},100);
                    if(data.error==0){
                        $("#precioPorOfertarOculto").val(data.precioOfertarV);
                        $("#preioPorOfertar").val(data.precioOfertarV);
                        $("#precioPorOfertarOcultoFormato").val(data.precioOfertarFormato);
                        
                    }else{
                        $("#precioPorOfertarOculto").val("-1");
                        $("#precioPorOfertarOcultoFormato").val("0.00");
                        $("#preioPorOfertar").val("0.00");
                        swal("",data.tituloError,"error");
                    }
                    
               }
        });
    
}

