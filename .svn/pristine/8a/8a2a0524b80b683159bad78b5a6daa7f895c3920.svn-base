/* 
 * script para el perfil del cliente
 */
var oTable;

$(function() {
 //cargaInformacionUsuario();

 });
 


    /*
    * Muestra la informacion general del usuario para su modificacon
    * Autor: OT
    * Fecha: 14-07-2016
    * 
    */
   function cargaInformacionUsuarioCliente(){
       waitingDialog();
       $.ajax({
            type:"GET",
            url:"/miPerfil/datosCliente/",
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
    
    function quitarImagenPerfilCliente(){
        $("#i1").attr('src','');
        $("#imagenModificada").val("1");
    }
   
   
   /*
    * Guarda la información personal del usuario
    * Autor: OT
    * Fecha: 14-07-2016
    * 
    */
   function guardarPerfilPersonalCliente(){
       var token=$("#token").val();
       var imagenPerfil="";
       
       $("#imgPerfil img").each(function(key, element){ 
           imagenPerfil=$(element).attr('src');
        }); 
       
       var primerNombre=$("#primerNombre").val();
       var paisT=$("#paisT").val();
       var telefono=$("#telefono").val();
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
             data:{'imagenPerfil':imagenPerfil,'primerNombre':primerNombre,'paisT':paisT,'telefono':telefono,
                 'estadoT':estadoT,'imagenModificada':imagenModificada,'ciudad':ciudad},
            url:"/miPerfil/guardarInfoCliente",
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
   function formularioContrasenaCliente(){
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
    * Fecha: 15-07-2016
    * 
    */
   function formularioNotificacionesCliente(){
       waitingDialog();
       $.ajax({
            type:"GET",
            url:"/miPerfil/formularioNotificacionesCliente/",
            success:function(respuesta){
               $("#elementosPerfil").html(respuesta);
               setTimeout(function(){closeWaitingDialog();},100);
            }
       });
   }
   
   /*
    * Guarda las notificaciones
    * Autor: OT
    * Fecha: 15-07-2016
    * 
    */
   function guardarNotificacionesCliente(){
       var token=$("#token").val();
       var confirmacioncita=false;
       var citarechazada=false;
       
       if($('#confirmacioncita').is(':checked')){
            confirmacioncita=true;
        }
        
        if($('#citarechazada').is(':checked')){
            citarechazada=true;
        }
        
        
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
             data:{'confirmacioncita':confirmacioncita,'citarechazada':citarechazada},
            url:"/miPerfil/guardarNotificacionesCliente",
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
   