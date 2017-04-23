/* 
 * script para el perfil del prestador de servicios
 */
var oTable;

$(function() {
 });
 


    /*
    * Muestra la informacion general del usuario para su modificacon
    * Autor: OT
    * Fecha: 13-07-2016
    * 
    */
   function cargaInformacionUsuario(){
       waitingDialog();
       $.ajax({
            type:"GET",
            url:"/miPerfil/datosPrestador/",
            success:function(respuesta){
               $("#elementosPerfil").html(respuesta);
               setTimeout(function(){closeWaitingDialog();},100);
            }
       });
   }
   
   /*
     * Quita la imagen del div
     * Autor: OT
     * Fecha: 14-07-2016
     */
    
    function quitarImagenPerfil(){
        $("#i1").attr('src','');
        $("#imagenModificada").val("1");
    }
   
   
   /*
    * Guarda la información personal del usuario
    * Autor: OT
    * Fecha: 29-12-2016
    * 
    */
   function guardarPerfilPrestador(){
       var token=$("#token").val();
       var imagenPerfil="";
       
       $("#imgPerfil img").each(function(key, element){ 
           imagenPerfil=$(element).attr('src');
        }); 
        
       var primerNombre=$("#primerNombre").val();
       var telefono=$("#telefono").val();
       var paisT=$("#paisT").val();
       var estadoT=$("#estadoCuenta").val();
       var ciudad=$("#ciudadCuenta").val();
       var imagenModificada=$("#imagenModificada").val();
       
       if(primerNombre.replace(/\s/g,"")==""){
          swal("","Ingrese el nombre.","warning");
           return;
       }
       
       if(telefono.replace(/\s/g,"")==""){
          swal("","Ingrese el telefono.","warning");
           return;
       }
       
       if(paisT==""){
          swal("","Seleccione el país.","warning");
           return;
       }
       
       if(estadoT=="0"){
          swal("","Seleccione el estado.","warning");
           return;
       }
       
       if(ciudad=="0"){
          swal("","Ingrese la ciudad.","warning");
           return;
       }
       
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
             data:{'imagenPerfil':imagenPerfil,'primerNombre':primerNombre,'telefono':telefono,'paisT':paisT,'estadoT':estadoT,
                 'ciudad':ciudad,'imagenModificada':imagenModificada},
            url:"/miPerfil/guardarInfoPrestador",
            success:function(respuesta){
                if(respuesta=="ok"){
                    location.href="/miPerfil";
                }else if(respuesta=="errorimagen"){
                    setTimeout(function(){closeWaitingDialog();},100);
                    swal("","El formato de la imagen debe ser (.jpg,.png o .gif)).","error");
                }else{
                    setTimeout(function(){closeWaitingDialog();},100);
                    swal("","Ocurrio un error al guardar los datos.","error");
                }
                
            }
       });
   }
   
   /*
    * Muestra el formulario para cambiar de contraseña
    * Autor: OT
    * Fecha: 15-07-2016
    * 
    */
   function formularioContrasenaUsuario(){
       waitingDialog();
       $.ajax({
            type:"GET",
            url:"/miPerfil/formularioContrasenaPerfil/",
            success:function(respuesta){
               $("#elementosPerfil").html(respuesta);
               $("#passAnterior").focus();
               setTimeout(function(){closeWaitingDialog();},100);
            }
       });
   }
   
   /*
    * Muestra el formulario de notificaciones
    * Autor: OT
    * Fecha: 29-12-2016
    * 
    */
   function formularioNotificacionesPrestador(){
       waitingDialog();
       $.ajax({
            type:"GET",
            url:"/miPerfil/formularioNotificacionesPrestador/",
            success:function(respuesta){
               $("#elementosPerfil").html(respuesta);
               setTimeout(function(){closeWaitingDialog();},100);
            }
       });
   }
   
   
   
   /*
    * Guarda las notificaciones del prestador de servicios
    * Autor: OT
    * Fecha: 29-12-2016
    * 
    */
   function guardarNotificacionesPrestador(){
       var token=$("#token").val();
       var nuevaCita=false;
       var citaCancelada=false;
       
       if($('#nuevaCita').is(':checked')){
            nuevaCita=true;
        }
        
        if($('#citaCancelada').is(':checked')){
            citaCancelada=true;
        }
        
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
             data:{'nuevaCita':nuevaCita,'citaCancelada':citaCancelada},
            url:"/miPerfil/guardarNotificacionesPrestador",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                    swal("","Notificaciones guardadas correctamente.","success");
                    $('#elementosPerfil').html('');
                    $('#back-to-top').click();
                }else{
                    swal("","Error al guardar las notificaciones.","error");
                }
                
            }
       });
   }
   
   /*
    * Muestra el formulario de preferencias del transportista
    * Autor: OT
    * Fecha: 15-07-2016
    * 
    */
   function formularioPreferenciasTransportista(){
       waitingDialog();
       $.ajax({
            type:"GET",
            url:"/miPerfil/formularioPreferenciasTransportista/",
            success:function(respuesta){
               $("#elementosPerfil").html(respuesta);
               setTimeout(function(){closeWaitingDialog();},100);
            }
       });
   }
   
   /*
    * Guarda las preferencias del transportista
    * Autor: OT
    * Fecha: 15-07-2016
    * 
    */
   function guardarPreferenciasTransportista(){
       var token=$("#token").val();
       var contadorEstados=0;
       
       var cadenaEstados="";
       var chEstado = document.getElementsByName("chEstado");
       for (var i = 0; i < chEstado.length; i++) {
           if(chEstado[i].checked==true){
               cadenaEstados=cadenaEstados+chEstado[i].id+',';
               contadorEstados++;
            }
        }
        
        if(contadorEstados==0){
            swal("","Seleccione al menos un Estado.","warning");
            return;
        }
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
             data:{'cadenaEstados':cadenaEstados},
            url:"/miPerfil/guardarEstadosTransportista",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                    swal("","Preferencias guardadas correctamente.","success");
                    $('#elementosPerfil').html('');
                    verPreferenciasTransportista();
                    $('#back-to-top').click();
                }else{
                    swal("","Error al guardar las preferencias.","error");
                }
                
            }
       });
   }
   
   
   /*
    * Muestra las  preferencias del transportista
    * Autor: OT
    * Fecha: 16-07-2016
    * 
    */
   function verPreferenciasTransportista(){
       waitingDialog();
       $.ajax({
            type:"GET",
            url:"/miPerfil/verPreferenciasTransportista/",
            success:function(respuesta){
               $("#elementosPerfil").html(respuesta);
               setTimeout(function(){closeWaitingDialog();},100);
            }
       });
   }
   
   /*
    * Eliminar las preferencias del transportista
    * Autor: OT
    * Fecha: 15-07-2016
    * 
    */
   function eliminarPreferenciasTransportista(){
       var token=$("#token").val();
       
       
       swal({
           title: "",   
           text: "¿Desea eliminar la cobertura? esta acción borrará sus paises y estados favoritos.",   
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
                             type:"POST",
                             headers:{'X-CSRF-TOKEN':token},
                             url:"/miPerfil/eliminarEstadosTransportista",
                             success:function(respuesta){
                                 setTimeout(function(){closeWaitingDialog();},100);
                                 if(respuesta=="ok"){
                                     swal("","Preferencias eliminadas correctamente.","success");
                                     $('#elementosPerfil').html('');
                                     verPreferenciasTransportista();
                                     $('#back-to-top').click();
                                 }else{
                                     swal("","Error al eliminar las preferencias.","error");
                                 }

                             }
                        });
                   } 
                });

   }