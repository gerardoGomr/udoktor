/*
 * script para el listado de envios de cliente
 */
var oTable;

$(function() {
    $('#fexMod').datepicker();
        $('#horaExpiracionMod').timepicker();
        $('#horaExpiracionMod').val("");
        
    $('#cal1').datepicker();
    $('#cal2').datepicker();
   oTable= $('#listadoEnvios').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        ajax: {
            url: '/cliente/listadoEnviosCliente',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var fecha1=$("#fecha1").val();
                var fecha2=$("#fecha2").val();
                var activo=false;
                var reservado=false;
                var recogido=false;
                var entregado=false;
                var expirado=false;
                var eliminado=false;
                var asignado=false;
                
                if($('#activo').is(':checked')){
                    activo=true;
                }

                if($('#reservado').is(':checked')){
                    reservado=true;
                }
                
                if($('#asignado').is(':checked')){
                    asignado=true;
                }

                if($('#recogido').is(':checked')){
                    recogido=true;
                }

                if($('#entregado').is(':checked')){
                    entregado=true;
                }

                if($('#expirado').is(':checked')){
                    expirado=true;
                }

                if($('#eliminado').is(':checked')){
                    eliminado=true;
                }
                d.buscaTitulo = buscaTitulo;
                d.fecha1 = fecha1;
                d.fecha2 = fecha2;
                d.activo = activo;
                d.reservado = reservado;
                d.recogido = recogido;
                d.entregado = entregado;
                d.expirado = expirado;
                d.eliminado = eliminado;
                d.asignado=asignado;
            }
        },
        columns: [
            {data: 'titulofecha', name: 'titulofecha',orderable: false, searchable: false},
            {data: 'recogerentrega', name: 'recogerentrega',orderable: false, searchable: false},
            {data: 'estadoenvio', name: 'estadoenvio',orderable: false, searchable: false},
            {data: 'acciones', name: 'acciones',orderable: false, searchable: false},
        ],
       language:{
            "decimal":        "",
            "emptyTable":     "<center><h4><b>Ningún resultado encontrado.</b></h4><h5 class='text-muted'>Revisa tu búsqueda e inténtalo de nuevo.</h5></center>",
            "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "_MENU_",
            "loadingRecords": "Cargando...",
            "processing":     "<h4>Procesando...</h4>",
            "search":         "",
            "zeroRecords":    "No se encontraron resultados",
            "paginate": {
                "first":      "Primero",
                "last":       "Último",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    });

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

    // calificaciones
    $('#calificacion').rating({
        language: 'es',
        min: 0,
        max: 5,
        step: 1,
        size: 'xs'
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

    $('#listadoEnvios').on('click', 'button.calificar', function () {
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
            headers:  { 'X-CSRF-TOKEN': $('#formCalificacion').find('input[name="_token"]').val() },
        }).done(function(respuesta) {
            if (respuesta.estatus === 'fail') {
                swal('Error', 'Ocurrió un error al calificar al cliente.', 'warning');
            }

            if (respuesta.estatus === 'OK') {
                swal({
                    title: '',
                    text:  'Se calificó al cliente de manera exitosa.',
                    type:  'info'
                }, function() {
                    buscarEnvios();
                    $('#modalCalificacion').modal('hide');
                    $('#calificacion').val('0');
                    $('#comentario').val('');
                });
            }

        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            swal('Error', 'Ocurrió un error al calificar al cliente.', 'warning');
        });
    }
 });


    /*
     * Hace la llamada a la funcion para buscar los envios del cliente.
     *
   */
   function buscarEnvios(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltros(){
         $("#buscaTitulo").val("");
         $("#fecha1").val("");
         $("#fecha2").val("");

         $('#activo').iCheck('uncheck');
         $('#activo, #reservado, #recogido,#entregado,#expirado,#eliminado,#asignado').iCheck('uncheck');
         buscarEnvios();
     }

    /*
     * Muestra el detalle del envio seleccionado
     *
     */
    function verEnvio(idEnvio){
        location.href="/cliente/detalleEnvio2/"+idEnvio;
    }


    /*
     * Muestra el mapa con el detalle del envio
     * Autor: OT
     * Fecha 04-06-2016
     *
     */
    var mapaDetalle;

    function cargarMapaDetalleListado(){
         var directionsDisplay = new google.maps.DirectionsRenderer;
         var directionsService = new google.maps.DirectionsService;

        mapaDetalle = new google.maps.Map(document.getElementById("mapid"),
	{
	  center: {lat: 19.432608, lng: -99.133208},
          zoom: 8
 	});

         var puntoRecoger = new google.maps.LatLng($("#latitudRecoger").val(),$("#longitudRecoger").val());
         var puntoEntregar = new google.maps.LatLng($("#latitudEntregar").val(),$("#longitudEntregar").val());
         directionsDisplay.setMap(mapaDetalle);
         calculateAndDisplayRoute(directionsService, directionsDisplay,puntoRecoger,puntoEntregar);
     }

     function calculateAndDisplayRoute(directionsService, directionsDisplay,puntoRecoger,puntoEntregar) {
          directionsService.route({
            origin: puntoRecoger,
            destination: puntoEntregar,
            travelMode: google.maps.TravelMode["DRIVING"]
          },
          function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(response);
            } else {
                verMapaNormal(puntoRecoger,puntoEntregar);
                setTimeout(function(){swal("","No existe una ruta entre los puntos de recolección y entrega, no fue posible calcular el tiempo y la distancia.","error");},100);
            }
          });
        }

	/*
         * Muestra los puntos de recolección y entrega en un mapa cuando no existe ruta entre los puntos
         * Autor: OT
         * Fecha: 02-07-2016
         */
	function verMapaNormal(puntoRecoger,puntoEntregar){
           var bounds  = null;
           bounds  = new google.maps.LatLngBounds();
           marcadorRecoger = new google.maps.Marker({
               map: mapaDetalle,
               position: puntoRecoger,
               icon:'/img/dot-green.png'
            });

            marcadorEntregar = new google.maps.Marker({
               map: mapaDetalle,
               position: puntoEntregar,
               icon:'/img/dot-red.png'
            });

            bounds.extend(marcadorRecoger.position);
            bounds.extend(marcadorEntregar.position);
            mapaDetalle.panToBounds(bounds);
            mapaDetalle.fitBounds(bounds);
        }


    /*
     * Muestra el detalle del envio a eliminar
     * 21-06-2016
     * OT
     */
    function eliminarEnvio(idEnvio,opcion){
        $("#divListadoEliminar").dialog({
            autoOpen: false,
            title: "Eliminar envío",
            modal:true,
            width: 650,
            height: 300,
            close: function(event,ui){
                 $("#divListadoEliminar").html('');
                 $("#divListadoEliminar").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/cliente/elimiarEnvioFormulario/"+idEnvio+"/"+opcion,
                   success:function(respuesta){
                       $("#divListadoEliminar").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divListadoEliminar").dialog('open');
    }

    /*
     * Eliminar el envio seleccinado
     * 21-06-2016
     * OT
     */
    function eliminarEnvioAccion(idEnvio,opcion){
        waitingDialog();
        $.ajax({
            type:"GET",
            url:"/cliente/elimiarEnvioAccion/"+idEnvio,
            success:function(respuesta){
                if(respuesta=="ok"){
                    if(opcion==1){
                        $('#divListadoEliminar').dialog('close');
                        setTimeout(function(){closeWaitingDialog();},100);
                        setTimeout(function(){swal("","Envió eliminado correctamente.","success");},100);
                        buscarEnvios();
                    }else{
                        $('#divListadoEliminar').dialog('close');
                        setTimeout(function(){closeWaitingDialog();},100);
                        location.href="/cliente/detalleEnvio2/"+idEnvio;
                    }
                }else{
                   setTimeout(function(){closeWaitingDialog();},100);
                   setTimeout(function(){swal("","Ocurrio un error en el proceso, consulte al administrador del sistema.","error");},100);
                }
            }
       });
    }

    /*
     * Muestra las ofertas del envio seleccionado
     * Fecha 2016-06-27
     * Autor OT
     */
    function verOfertasCliente(idEnvio){
        location.href="/cliente/verOfertasEnvio/"+idEnvio;
    }

    /*
    * Muestra el formulario para aceptar la oferta
    * Autor: OT
    * Fecha: 28-06-2016
    *
    */
   
   function aceptarOferta(idOferta){
       $("#divListadoEliminar").dialog({
            autoOpen: false,
            title: "Aceptar oferta",
            modal:true,
            width: 700,
            height: 300,
            close: function(event,ui){
                 $("#divListadoEliminar").html('');
                 $("#divListadoEliminar").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/cliente/validarOfertaAceptar/"+idOferta,
                   success:function(respuesta){
                       $("#divListadoEliminar").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divListadoEliminar").dialog('open');
   }
   
   
   
   /*
    * Aceptar la oferta del transportista
    * Autor: OT
    * Fecha: 19-07-2016
    *
    */
   function aceptarOfertaAccion(){
       var idOferta= $("#idOferta").val();
       waitingDialog();
        $.ajax({
           type:"GET",
	   url:"/cliente/aceptarOferta/"+idOferta,
           success:function(respuesta){
               $('#divListadoEliminar').dialog('close');
               $("#contenidoPaginaLay").html(respuesta);
               $("#back-to-top").click();
               setTimeout(function(){closeWaitingDialog();},100);
           }
       });
       
   }

   

   /*
     * Muestra El formulario para rechazar la oferta
     * 28-06-2016
     * OT
     */
    function rechazarOferta(idOferta){
        $("#divListadoEliminar").dialog({
            autoOpen: false,
            title: "Rechazar oferta",
            modal:true,
            width: 650,
            height: 400,
            close: function(event,ui){
                 $("#divListadoEliminar").html('');
                 $("#divListadoEliminar").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/cliente/rechazarOfertaFormulario/"+idOferta,
                   success:function(respuesta){
                       $("#divListadoEliminar").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#motivoRechazo").focus();
                   }
                });
            }
	});
	$("#divListadoEliminar").dialog('open');
    }

    /*
    * Rechazar oferta
    * Autor: OT
    * Fecha: 28-06-2016
    *
    */
   function rechazarOfertaAccion(){
       var motivo=$("#motivoRechazo").val();
       var idOferta=$("#idOferta").val();
       var token=$("#token").val();

       if(motivo.replace(/\s/g,"")==""){
          swal("","Escriba el motivo de rechazo de la oferta.","warning");
          return;
       }

       waitingDialog();
        $.ajax({
           headers:{'X-CSRF-TOKEN':token},
           type:"POST",
           data:{'idOferta':idOferta,'motivo':motivo,},
	   url:"/cliente/rechazarOferta",
           success:function(respuesta){
                $("#contenidoPaginaLay").html(respuesta);
                $("#back-to-top").click();
                setTimeout(function(){$('#divListadoEliminar').dialog('close');},100);
                setTimeout(function(){closeWaitingDialog();},100);
           }
       });
   }


    /*
     * Muestra El formulario de respuesta a la pregunta
     * 28-06-2016
     * OT
     */
    function responderPregunta(idPregunta){
        $("#divRespuesta").dialog({
            autoOpen: false,
            title: "Responder pregunta",
            modal:true,
            width: 650,
            height: 400,
            close: function(event,ui){
                 $("#divRespuesta").html('');
                 $("#divRespuesta").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/cliente/responderPreguntaFormulario/"+idPregunta,
                   success:function(respuesta){
                       $("#divRespuesta").html(respuesta);
                       $("#textoRespuesta").focus();
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divRespuesta").dialog('open');
    }

    /*
    * Responder pegunta del transportista
    * Autor: OT
    * Fecha: 29-06-2016
    *
    */
   function responderPreguntaAccion(){
       var respuesta=$("#textoRespuesta").val();
       var idPregunta=$("#idPregunta").val();
       var token=$("#token").val();

       if(respuesta.replace(/\s/g,"")==""){
          swal("","Escriba la respuesta.","warning");
          return;
       }

       waitingDialog();
        $.ajax({
           datatype:JSON,
           headers:{'X-CSRF-TOKEN':token},
           type:"POST",
           data:{'idPregunta':idPregunta,'respuesta':respuesta,},
	   url:"/cliente/responderPregunta",
           success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta.error==0){
                    swal("","Respuesta guardada correctamente.","success");
                    $("#listaPreguntas").html(respuesta.preguntas);
                    $("#cuentaPregunta2").text(respuesta.sinrespuesta);
                    $("#cuentaPregunta1").text(respuesta.sinrespuesta);
                    setTimeout(function(){$('#divRespuesta').dialog('close');},100);
                }else{
                    swal("","Ocurrio un error al guardar la respuesta.","error");
                }
           }
       });
   }

   /*
     * Muestra el formulario para enviar mensaje al transportista recibiendo el envio
     * Fecha: 04-07-2016
     * Autor:OT
     */
    function enviarMensajeCliente(idEnvio){
        $("#divEnviarMensaje").dialog({
            autoOpen: false,
            title: "Enviar mensaje",
            modal:true,
            width: 650,
            height: 380,
            close: function(event,ui){
                 $("#divEnviarMensaje").html('');
                 $("#divEnviarMensaje").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/cliente/enviarMensajeClienteFormulario/"+idEnvio,
                   success:function(respuesta){
                       $("#divEnviarMensaje").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#textoMensaje").focus();
                   }
                });
            }
	});
	$("#divEnviarMensaje").dialog('open');
    }


    /*
    * Envia el mensaje
    * Autor: OT
    * Fecha: 04-07-2016
    *
    */
   function enviarMensajeAccion(){
       var mensaje=$("#textoMensaje").val();
       var token=$("#token").val();
       var idPersonaEnvia=$("#idPersonaEnvia").val();
       var idPersonaRecibe=$("#idPersonaRecibe").val();
       var idEnvio=$("#idEnvio").val();
       var idOferta=$("#idOferta").val();

       if(mensaje.replace(/\s/g,"")==""){
          swal("","Escriba el mensaje a enviar.","warning");
          return;
       }

       if(idPersonaEnvia.replace(/\s/g,"")==""){
          swal("","Ocurrio un error al enviar el mensaje, intente de nuevo.","warning");
          setTimeout(function(){$('#divEnviarMensaje').dialog('close');},100);
          return;
       }

       if(idPersonaRecibe.replace(/\s/g,"")==""){
          swal("","Ocurrio un error al enviar el mensaje, intente de nuevo.","warning");
          setTimeout(function(){$('#divEnviarMensaje').dialog('close');},100);
          return;
       }

       if(idEnvio.replace(/\s/g,"")==""){
          swal("","Ocurrio un error al enviar el mensaje, intente de nuevo.","warning");
          setTimeout(function(){$('#divEnviarMensaje').dialog('close');},100);
          return;
       }

       waitingDialog();
        $.ajax({
           type:"POST",
           headers:{'X-CSRF-TOKEN':token},
           data:{'mensaje':mensaje,'idPersonaEnvia':idPersonaEnvia,'idPersonaRecibe':idPersonaRecibe,'idEnvio':idEnvio,'idOferta':idOferta},
           url:"/general/enviarMensaje",
           success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                    swal("","Mensaje enviado correctamente.","success");
                    setTimeout(function(){$('#divEnviarMensaje').dialog('close');},100);
                }else{
                    swal("","Ocurrio un error en el proceso.","error");
                }
           }
       });
   }

    /*
     * Muestra el formulario para enviar la pregunta al transportista recibiendo la oferta
     * Fecha: 06-07-2016
     * Autor: OT
     */
    function preguntaClienteOferta(idOferta){
        $("#divEnviarMensaje").dialog({
            autoOpen: false,
            title: "Enviar Mensaje",
            modal:true,
            width: 650,
            height: 380,
            close: function(event,ui){
                 $("#divEnviarMensaje").html('');
                 $("#divEnviarMensaje").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/cliente/enviarPreguntaClienteOfertaFormulario/"+idOferta,
                   success:function(respuesta){
                       $("#divEnviarMensaje").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#textoMensaje").focus();
                   }
                });
            }
	});
	$("#divEnviarMensaje").dialog('open');
    }


    /*
     * Muestra el mapa del envio
     * Fecha: 06-07-2016
     * Autor: OT
     */
    function vermapaEnvio(latitud,longitud,tipo){
        var mapaEnvio="";
        
        var titulo="";
        if(tipo==1)titulo="Punto de recolección";
        if(tipo==2)titulo="Punto de entrega";
        $("#divMapaEnvio").dialog({
            autoOpen: false,
            title: titulo,
            modal:true,
            width: 700,
            height: 580,
            close: function(event,ui){
                 $("#divMapaEnvio").html('');
                 $("#divMapaEnvio").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/cliente/verMapaEnvio",
                   success:function(respuesta){
                       $("#divMapaEnvio").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       mapaEnvio = new google.maps.Map(document.getElementById("mapidEnvio"),
                        {
                          center: {lat: 19.432608, lng: -99.133208},
                          zoom: 10
                        });
                        var bounds  = null;
                            bounds  = new google.maps.LatLngBounds();
                            var puntoMapa = new google.maps.Marker({
                                map: mapaEnvio,
                                position: {lat: latitud, lng: longitud},
                                icon:'/img/dot-green.png'
                             });


                             bounds.extend(puntoMapa.position);
                             
                             mapaEnvio.panToBounds(bounds);
                             mapaEnvio.fitBounds(bounds);
                        
                   }
                });
            }
	});
	$("#divMapaEnvio").dialog('open');
    }



/*
     * Muestra el mapa con los checks del envio
     * Fecha: 12-08-2016
     * Autor: OT
     */
    function vermapaChecks(idEnvio,tipo){
        var tituloEnvio=$("#tituloEnvio").val();
        $("#divMapaEnvio").dialog({
            autoOpen: false,
            title: tituloEnvio,
            modal:true,
            width: 700,
            height: 580,
            close: function(event,ui){
                 $("#divMapaEnvio").html('');
                 $("#divMapaEnvio").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{'idEnvio':idEnvio,'tipo':tipo},
                   url:"/cliente/verMapaGeneralEnvio",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#divMapaEnvio").html(respuesta);
                   }
                });
            }
	});
	$("#divMapaEnvio").dialog('open');
    }
    
    
    
    /*
     * Muestra el formulario para modificar la fecha de expiracion del envio
     * Fecha: 16-08-2016
     * Autor:OT
     */
    function modificarExpiracion(idEnvio){
        location.href="/cliente/modificarExpiracionEnvio/"+idEnvio;
    }
    
    
    /*
    * Cambia la hora de expiracion
    * Autor: OT
    * Fecha: 17-08-2016
    *
    */
   function cambiarExpiracionAccion(){

       var token=$("#token").val();
       var fechaExpiracionMod=$("#fechaExpiracionMod").val();
       var horaExpiracionMod=$("#horaExpiracionMod").val();
       var idEnvio=$("#idEnvio").val();


       if(fechaExpiracionMod.replace(/\s/g,"")==""){
          swal("","Ingrese la fecha de expiración.","warning");
          return;
       }

       if(horaExpiracionMod.replace(/\s/g,"")==""){
          swal("","Ingrese la hora de expiración.","warning");
          return;
       }

       
       fechaExpiracionMod=fechaExpiracionMod.split("-");
       fechaExpiracionMod=fechaExpiracionMod[2]+'-'+fechaExpiracionMod[1]+'-'+fechaExpiracionMod[0];
       
       
       waitingDialog();
        $.ajax({
           type:"POST",
           headers:{'X-CSRF-TOKEN':token},
           data:{'idEnvio':idEnvio,'fechaExpiracionMod':fechaExpiracionMod,'horaExpiracionMod':horaExpiracionMod},
           url:"/cliente/cambiarExpiracionAccion",
           success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                       swal({
                        title: "",   
                        text: "Fecha modificada correctamente",
                        type: "success",   
                        showCancelButton: false,   
                        confirmButtonColor: "#47A447",
                        confirmButtonText: "Aceptar",
                        cancelButtonText: "Cancelar", 
                        closeOnConfirm: false,   
                        closeOnCancel: false
                        }, 
                        function(isConfirm){   
                            if (isConfirm) {
                                location.href="/cliente/misenvios";
                            }
                        });
                   }else if(respuesta=="1"){ 
                       swal("","La fecha de expiracion debe ser mayor a la fecha y hora actual.","error");
                   }else if(respuesta=="2"){ 
                       swal("","La fecha de expiracion debe ser menor a la fecha y hora de recojo.","error");
                   }else{
                       swal("","Ocurrio un error al realizar el proceso.","error");
                   }
           }
       });
   }

function soloLetrasE(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyz,.";
    
    tecla_especial = false
    

    if(letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}

function limpiaE() {
    var val = document.getElementById("textoRespuesta").value;
    var tam = val.length;
    for(i = 0; i < tam; i++) {
        if(!isNaN(val[i]))
            document.getElementById("textoRespuesta").value = '';
    }
}

