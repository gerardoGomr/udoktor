 $(function() {
    //buscarNotificacionesNuevasPrincipal();
    //buscarMensajesNuevosPrincipal();
    //buscarWarningsPrincipal();
    //buscarNotificacionesAdmin();
 });

 
 
/*
 * Obtiene la lista de estados
 * Autor: OT
 * Fecha: 20-05-2016
 * 
 */
function buscarEstado(idPais){
    if(idPais=="")idPais=0;
    
    waitingDialog(); 
    var token=$("#token").val();
     $.ajax({
          type:"GET",
          headers:{'X-CSRF-TOKEN':token},
          data:{'idPais':idPais},
          url:"../general/estados",
          success:function(respuesta){
              setTimeout(function(){closeWaitingDialog();},100);
              $("#estadoCuenta").html(respuesta);
              $("#ciudadCuenta").html("<option value='0'>Seleccione ciudad..</option>");
          }
        });   
}


/*
 * Obtiene la lista de ciudades
 * Autor: OT
 * Fecha: 20-05-2016
 * 
 */
function buscarCiudad(idEstado){
    waitingDialog(); 
    var token=$("#token").val();
     $.ajax({
          type:"GET",
          headers:{'X-CSRF-TOKEN':token},
          data:{'idEstado':idEstado},
          url:"../general/ciudades",
          success:function(respuesta){
              setTimeout(function(){closeWaitingDialog();},100);
              $("#ciudadCuenta").html(respuesta);
          }
        });   
}


/*
 * Muestra el mensaje recibido en la pantalla principal
 * Autor: OT
 * Fecha: 04-07-2016
 * 
 */
function leerMensajeClienteGeneral(idMensaje){
    $("#leerMensajeClientePrincipal").dialog({
            autoOpen: false,
            title: "Mensaje",
            modal:true,
            width: 650,
            height: 430,
            close: function(event,ui){
                 $("#leerMensajeClientePrincipal").html('');
                 $("#leerMensajeClientePrincipal").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/general/leerMensajeFormulario/"+idMensaje,
                   success:function(respuesta){
                       $("#leerMensajeClientePrincipal").html(respuesta);
                       buscarMensajesNuevosPrincipal();
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#leerMensajeClientePrincipal").dialog('open');
}
    
    /*
    * Envia el mensaje
    * Autor: OT
    * Fecha: 04-07-2016
    * 
    */
   function responderMensajePrincipal(){
       var mensaje=$("#textoMensaje").val();
       var token=$("#token").val();
       var idPersonaEnvia=$("#idPersonaEnvia").val();
       var idPersonaRecibe=$("#idPersonaRecibe").val();
       var idEnvio=$("#idEnvio").val();
       
       if(mensaje.replace(/\s/g,"")==""){
          swal("","Ocurrio un error al enviar el mensaje, intente de nuevo.","warning");
          setTimeout(function(){$('#divEnviarMensaje').dialog('close');},100);
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
           data:{'mensaje':mensaje,'idPersonaEnvia':idPersonaEnvia,'idPersonaRecibe':idPersonaRecibe,'idEnvio':idEnvio},
           url:"/general/enviarMensaje",
           success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                    swal("","Mensaje enviado correctamente.","success");
                    setTimeout(function(){$('#leerMensajeClientePrincipal').dialog('close');},100);
                }else{
                    swal("","Ocurrio un error en el proceso.","error");
                }
           }
       });
   }
   
   /*
    * Muestra los mensajes pendientes de leer
    * Autor: OT
    * Fecha: 04-07-2016
    * 
    */
   function buscarMensajesNuevosPrincipal(){
       $.ajax({
           type:"GET",
           url:"/general/buscarMensajesNuevos",
           success:function(respuesta){
               $("#menuMensajesCabera").html(respuesta);
           }
       });
   }
   
   /*
    * Muestra la caja de texto de respuesta
    * Autor: OT
    * Fecha 04-07-2016
    * 
    */
   function mostrarCamposCaptura(){
       $("#divCajaRespuesta").removeAttr("style");
       $("#divBotonesEnviarRespuesta").removeAttr("style");
       $("#divBotonesResponder").attr("style","display:none");
       $("#textoMensaje").focus();
   }
   
   /*
    * Muestra las notificaciones pendiente de leer
    * Autor: OT
    * Fecha: 05-07-2016
    * 
    */
   function buscarNotificacionesNuevasPrincipal(){
       $.ajax({
           type:"GET",
           url:"/general/buscarNotificacionesNuevos",
           success:function(respuesta){
               $("#menuNotifiacionesCabecera").html(respuesta);
           }
       });
   }
   
   /*
    * Muestra el mensaje recibido en la pantalla principal
    * Autor: OT
    * Fecha: 04-07-2016
    * 
    */
   function leerNotificacionesGeneral(idNotificacion){
       $("#leerMensajeClientePrincipal").dialog({
               autoOpen: false,
               title: "Notificación",
               modal:true,
               width: 750,
               height: 350,
               close: function(event,ui){
                    $("#leerMensajeClientePrincipal").html('');
                    $("#leerMensajeClientePrincipal").dialog('destroy');
               },
               open:function(event,ui){
                   waitingDialog();
                   $.ajax({
                      type:"GET",
                      url:"/general/mostrarNotificacion/"+idNotificacion,
                      success:function(respuesta){
                          $("#leerMensajeClientePrincipal").html(respuesta);
                          buscarNotificacionesNuevasPrincipal();
                          setTimeout(function(){closeWaitingDialog();},100);
                      }
                   });
               }
           });
           $("#leerMensajeClientePrincipal").dialog('open');

   }
   
   /*
    * Muestra los warnings 
    * Autor: OT
    * Fecha: 12-07-2016
    * 
    */
   function buscarWarningsPrincipal(){
       $.ajax({
           type:"GET",
           url:"/general/buscarWarningsNuevos",
           success:function(respuesta){
               $("#menuWarningsCabecera").html(respuesta);
           }
       });
   }
   
   
   /*
    * Cambiar la contraseña del usuario
    * Autor: OT
    * Fecha: 15-07-2016
    * 
    */
   function guardarCambioContrasena(){
       var token=$("#token").val();
       var passAnterior=$("#passAnterior").val();
       var passNueva=$("#passNueva").val();
       var passNuevaConfirmar=$("#passNuevaConfirmar").val();
       
    
        passAnterior=passAnterior.replace(/\s/g,"");
        passNueva=passNueva.replace(/\s/g,"");
        passNuevaConfirmar=passNuevaConfirmar.replace(/\s/g,"");


    if(passAnterior==''){
        swal("","Escriba la contraseña anterior.","warning");
        return;
    }
    
    if(passNueva==''){
        swal("","Escriba la nueva contraseña.","warning");
        return;
    }
    
    if(passNuevaConfirmar==''){
        swal("","Confirme la nueva contraseña.","warning");
        return;
    }
    
    
    var longitudPass= passNueva.length;
    
    if(parseFloat(longitudPass)<8){
      swal("","La contraseña debe tener al menos 8 caracteres sin espacios.","warning");
      return false;
    }
    
    
    if(passNueva!=passNuevaConfirmar){
      swal("","Error al confirmar la nueva contraseña, asegurese que sean iguales.","warning");
      return false;
    }
    
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
             data:{'passAnterior':passAnterior,'passNueva':passNueva,'passNuevaConfirmar':passNuevaConfirmar},
            url:"/general/guardarCambioContrasena",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                    swal("","La contraseña se cambió correctamente.","success");
                    $('#elementosPerfil').html('');
                    $('#back-to-top').click();
                }else if(respuesta=="errorpassanterior"){
                    swal("","La contraseña anterior es incorrecta, verifique de nuevo.","error");
                }else if(respuesta=="nocoincidenpass"){
                    swal("","Erro al confirmar la contraseña, asegurese que sean iguales.","error");
                }else{
                    swal("","Error al cambiar la contraseña.","error");
                }
            }
       });
   }

/*
     * Muestra la imagen en un div
     * Autor: OT
     * Fecha 17-07-2016
     * 
     */
       
    function mostrarImagenGeneral(img64){
        $("#mostrarImagenArticuloPrincipal").dialog({
		autoOpen: false,
		title: "Imagen artículo",
		modal:true,
		width: 890,
		height: 550,
		close: function(event,ui){
                    $("#mostrarImagenArticuloPrincipal").html('');
                    $("#mostrarImagenArticuloPrincipal").dialog('destroy');
		},
		open:function(event,ui){
                      $("#mostrarImagenArticuloPrincipal").html("<img src='"+img64+"' style='max-width:850px; max-height: 480px;'></img>");
		}
	});
	$("#mostrarImagenArticuloPrincipal").dialog('open');
    }
    
    /*
    * Muestra las notificaciones  del admin
    * Autor: OT
    * Fecha: 05-07-2016
    * 
    */
   function buscarNotificacionesAdmin(){
       $.ajax({
           type:"GET",
           url:"/general/buscarNotificacionesAdmin",
           success:function(respuesta){
               $("#menuNotifiacionesAdmin").html(respuesta);
           }
       });
   }
   
   /*
    * Muestra la notificacion del administrador
    * Autor: OT
    * Fecha: 04-07-2016
    * 
    */
   function leerNotificacionesGeneralAdmin(idPrincipal,tipo){
       $("#leerMensajeClientePrincipal").dialog({
               autoOpen: false,
               title: "Notificación",
               modal:true,
               width: 750,
               height: 300,
               close: function(event,ui){
                    $("#leerMensajeClientePrincipal").html('');
                    $("#leerMensajeClientePrincipal").dialog('destroy');
               },
               open:function(event,ui){
                   waitingDialog();
                   $.ajax({
                      type:"GET",
                      url:"/general/mostrarNotificacionAdmin/"+idPrincipal+"/"+tipo,
                      success:function(respuesta){
                          $("#leerMensajeClientePrincipal").html(respuesta);
                          setTimeout(function(){closeWaitingDialog();},100);
                      }
                   });
               }
           });
           $("#leerMensajeClientePrincipal").dialog('open');
   }
   
   
   /* Activa la cuenta del usuario
     * Autor: OT
     * Fecha:01-08-2016
     */
    
    function activarCuenta(idUsuario,idPerson,tipo){
        swal({
             title: "",   
             text: "¿Está seguro de activar la cuenta del usuario?",
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#47A447",
             confirmButtonText: "Aceptar",
             cancelButtonText: "Cancelar", 
             closeOnConfirm: true,   
             closeOnCancel: true }, 
             function(isConfirm){   
                if (isConfirm) {
                    waitingDialog();
                        $.ajax({
                           type:"GET",
                           data:{'idUsuario':idUsuario,'idPerson':idPerson,'verificada':0},
                           url:"/admin/activarCuenta",
                           success:function(respuesta){
                               setTimeout(function(){closeWaitingDialog();},100);
                               if(respuesta=="ok"){
                                   swal('', 'Cuenta activada correctamente.', 'success');
                                   if(tipo==1){
                                       buscarCliente();
                                   }else if(tipo==2){
                                       buscarTransporte();
                                   }
                               }else{
                                   swal('', 'Ocurrio un error al realizar el proceso.', 'error');
                               }
                           }
                        }); 
                }
            });
    }
    
    /* Desactiva la cuenta del usuario
     * Autor: OT
     * Fecha:01-08-2016
     */
    
    function desactivarCuenta(idUsuario,idPerson,tipo){
        swal({
             title: "",   
             text: "¿Está seguro de desactivar la cuenta?, al realizar esto el usuario no podra entrar sistema.",
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#47A447",
             confirmButtonText: "Aceptar",
             cancelButtonText: "Cancelar", 
             closeOnConfirm: true,   
             closeOnCancel: true }, 
             function(isConfirm){   
                if (isConfirm) {
                    waitingDialog();
                        $.ajax({
                           type:"GET",
                           data:{'idUsuario':idUsuario,'idPerson':idPerson,'verificada':0},
                           url:"/admin/desActivarCuenta",
                           success:function(respuesta){
                               setTimeout(function(){closeWaitingDialog();},100);
                               if(respuesta=="ok"){
                                   swal('', 'Cuenta desactivada correctamente.', 'success');
                                   if(tipo==1){
                                       buscarCliente();
                                   }else if(tipo==2){
                                       buscarTransporte();
                                   }
                                   
                               }else{
                                   swal('', 'Ocurrio un error al realizar el proceso.', 'error');
                               }
                           }
                        }); 
                }
            });
    }
    
    /*
     * Muestra los datos de la cuenta
     * Autor:OT
     * Fecha 04-08-2016
     */
    
   function verificarCuentaUsuario(idUsuario){
         location.href="/admin/verificarCuenta/"+idUsuario;
   }
   
   
   
   
    /* Activar - Verificar cuenta
     * Autor: OT
     * Fecha:04-08-2016
     */
    
    function verficarCuentaActivar(){
        var usuarioid=$("#usuarioid").val();
        var personid=$("#personid").val();
        var tipo=$("#tipo").val();
        var mensaje="";
        if(tipo==0){
            mensaje="Al activar la cuenta el usuario podrá publicar envíos, responder preguntas, etc. ¿Quiere continuar?"
        }else{
            mensaje="Al activar la cuenta el usuario podrá ofertar, preguntar,etc, ¿Quiere continuar?";
        }
        swal({
             title: "",   
             text: mensaje,
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#47A447",
             confirmButtonText: "Aceptar",
             cancelButtonText: "Cancelar", 
             closeOnConfirm: true,   
             closeOnCancel: true }, 
             function(isConfirm){   
                if (isConfirm) {
                    waitingDialog();
                        $.ajax({
                           type:"GET",
                           data:{'idUsuario':usuarioid,'idPerson':personid,'verificada':1},
                           url:"/admin/activarCuenta",
                           success:function(respuesta){
                               setTimeout(function(){closeWaitingDialog();},100);
                               if(respuesta=="ok"){
                                   $("#divElmentosListaGrupo").dialog('close');
                                   buscarNotificacionesAdmin();
                                   
                                   swal({
                                        title: "",   
                                        text: "Proceso realizado correctamente",
                                        type: "success",   
                                        showCancelButton: false,   
                                        confirmButtonColor: "#47A447",
                                        confirmButtonText: "OK",
                                        cancelButtonText: "Cancelar", 
                                        closeOnConfirm: true,   
                                        closeOnCancel: true }, 
                                        function(isConfirm){   
                                            if (isConfirm) {
                                                waitingDialog();
                                                if(tipo==0){
                                                    location.href="/admin/clientes";
                                                }else if(tipo==1){
                                                    location.href="/admin/transportistas";
                                                }
                                                
                                            }
                                        });
                               }else{
                                   swal('', 'Ocurrio un error al realizar el proceso.', 'error');
                               }
                           }
                        }); 
                }
            });
    }
    
    /* Desactiva - Verifica la cuenta  del usuario
     * Autor: OT
     * Fecha:04-08-2016
     */
    
    function verificarCuentaDesactivar(){
        var usuarioid=$("#usuarioid").val();
        var personid=$("#personid").val();
        var tipo=$("#tipo").val();
        
        swal({
             title: "",   
             text: "Al desactivar la cuenta el usuario no podrá ingresar al sistema, ¿Quiere continuar?",
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#47A447",
             confirmButtonText: "Aceptar",
             cancelButtonText: "Cancelar", 
             closeOnConfirm: true,   
             closeOnCancel: true }, 
             function(isConfirm){   
                if (isConfirm) {
                    waitingDialog();
                        $.ajax({
                           type:"GET",
                           data:{'idUsuario':usuarioid,'idPerson':personid,'verificada':1},
                           url:"/admin/desActivarCuenta",
                           success:function(respuesta){
                               setTimeout(function(){closeWaitingDialog();},100);
                               if(respuesta=="ok"){
                                   $("#divElmentosListaGrupo").dialog('close');
                                   buscarNotificacionesAdmin();
                                   swal({
                                        title: "",   
                                        text: "Proceso realizado correctamente",
                                        type: "success",   
                                        showCancelButton: false,   
                                        confirmButtonColor: "#47A447",
                                        confirmButtonText: "OK",
                                        cancelButtonText: "Cancelar", 
                                        closeOnConfirm: true,   
                                        closeOnCancel: true }, 
                                        function(isConfirm){   
                                            if (isConfirm) {
                                                waitingDialog();
                                                if(tipo==0){
                                                    location.href="/admin/clientes";
                                                }else if(tipo==1){
                                                    location.href="/admin/transportistas";
                                                }
                                                
                                            }
                                        });
                               }else{
                                   swal('', 'Ocurrio un error al realizar el proceso.', 'error');
                               }
                           }
                        }); 
                }
            });
    }
   
   
    function soloNumerosConDecimal(e)
        {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
        return true;
         
        return /\d/.test(String.fromCharCode(keynum));
        }   
        
        
   function validarNro(e) {
    var key;
    if(window.event){ // IE
       key = e.keyCode;
    }
    else if(e.which){ // Netscape/Firefox/Opera
       key = e.which;
    }

    if (key < 48 || key > 57){
      if(key == 8){ // Detectar  backspace (retroceso)
        return true; 
      }
        else { 
            return false; 
        }
    }
        
    return true;
}