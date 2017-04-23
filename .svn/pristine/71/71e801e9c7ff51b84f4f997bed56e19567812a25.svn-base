/*
 * Scripts para agendar la cita del cliente
 * 
 */

$(function() {
    $('#dp-ex-5').datepicker({
            startDate: 'd'
    });
    $('#dp-ex-5').on("changeDate", function() {
        $('#fechaOculta').val($('#dp-ex-5').datepicker('getFormattedDate'));
        verDisponible();
    });
    verDisponible();	

});


/*
 * Guarda la cita del cliente
 * Autor: OT
 * Fecha: 26-12-2016
 */

function cambiarCita(){
    var horario=$('input:radio[name=hora_rio]:checked').val();
    var tipohorario=$('#tipohorario').val();
    var idPrestador=$('#idPrestador').val();
    var fechaCita=$('#fechaOculta').val();
    var idCita=$("#idCita").val();
    var serviciosSeleccionados="";
    
    if(horario==undefined){
       swal("","Seleccione un horario.","warning");
       return;
    }
    
    $('input:checkbox:checked').each(function(){
        serviciosSeleccionados=serviciosSeleccionados+$(this).val()+",";
      });
    
    if(serviciosSeleccionados==""){
        swal("","Seleccione los servicios requeridos.","warning");
       return;
    }
    
    swal({
       title: "",   
       text: "Está seguro de cambiar la cita?",
       type: "warning",   
       showCancelButton: true,   
       confirmButtonColor: "#47A447",
       confirmButtonText: "Aceptar",
       cancelButtonText: "Cancelar", 
       closeOnConfirm: true,   
       closeOnCancel: true
    }, 
    function(isConfirm){   
       if (isConfirm) {
           
           waitingDialog();
            $.ajax({
              type:"GET",
              data:{'horario':horario,'tipohorario':tipohorario,'idPrestador':idPrestador,'fechaCita':fechaCita,'idCita':idCita,
              'serviciosSeleccionados':serviciosSeleccionados},
              url:"/cliente/guardarCambioCita/",
              success:function(respuesta){
                 setTimeout(function(){closeWaitingDialog();},100);
                 if(respuesta=="ok"){
                     swal({
                            title: "",   
                            text: "Cambio de cita realizado correctamente",
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
                               location.href="/cliente/misCitas";
                            }
                        });
                 }else{
                     swal("","Ocurrio un error al realizar la cita, consulte al administrador del servicio.","error");
                 }
              }
            });
    
       }
    });
    
    
    
}


/*
 * Obtener la disponibilidad de la fecha enviada
 * Autor: OT
 * Fecha: 26-12-2016
 */
function verDisponible(){
    var fechaCita=$('#fechaOculta').val();
    var idPrestador=$('#idPrestador').val();
    var tipohorario=$('#tipohorario').val();
    var idCita=$("#idCita").val();
    $("#fechaEtiqueta").val(fechaCita);
    
    waitingDialog();
    $.ajax({
      type:"GET",
      dataType: 'json',
      data:{'fechaCita':fechaCita,'idPrestador':idPrestador,'tipohorario':tipohorario,'idCita':idCita},
      url:"/cliente/obtenerDisponibleCita2/",
      success:function(respuesta){
         setTimeout(function(){closeWaitingDialog();},100);
         $("#divHorario").html(respuesta.horas);
         
      }
    });
    
}
