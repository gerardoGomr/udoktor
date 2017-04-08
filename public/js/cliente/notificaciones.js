 $(function() {
    buscarNotificacionesNuevas();
 });

  
   
   /*
    * Muestra las notificaciones pendiente de leer
    * Autor: OT
    * Fecha: 05-07-2016
    * 
    */
   function buscarNotificacionesNuevas(){
       $.ajax({
           type:"GET",
           url:"/cliente/buscarNotificacionesNuevas",
           success:function(respuesta){
               $("#menuNotifiacionesCliente").html(respuesta);
           }
       });
   }
   
   /*
    * Muestra la notificacion seleccionada
    * Autor: OT
    * Fecha: 27-12-2016
    * 
    */
   function leerNotificacionCliente(idNotificacion){
       $("#modalNotificacionesGeneral").dialog({
               autoOpen: false,
               title: "Notificación",
               modal:true,
               width: 600,
               height: 300,
               close: function(event,ui){
                    $("#modalNotificacionesGeneral").html('');
                    $("#modalNotificacionesGeneral").dialog('destroy');
               },
               open:function(event,ui){
                   waitingDialog();
                   $.ajax({
                      type:"GET",
                      url:"/cliente/mostrarNotificacionCliente/"+idNotificacion,
                      success:function(respuesta){
                          $("#modalNotificacionesGeneral").html(respuesta);
                          buscarNotificacionesNuevas();
                          setTimeout(function(){closeWaitingDialog();},100);
                      }
                   });
               }
           });
           $("#modalNotificacionesGeneral").dialog('open');

   }