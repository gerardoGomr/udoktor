/*
 * Scripts para agendar la cita del cliente
 * 
 */

$(function() {
    $('#serviciosid').multiselect({
         maxHeight: 200,
         includeSelectAllOption: false,
         selectAllJustVisible: false
    });
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
 * Fecha: 22-12-2016
 */

function agendarCita(){
    var horario=$('input:radio[name=hora_rio]:checked').val();
    var tipohorario=$('#tipohorario').val();
    var idPrestador=$('#idPrestador').val();
    var fechaCita=$('#fechaOculta').val();
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
    
    waitingDialog();
    $.ajax({
      type:"GET",
      data:{'horario':horario,'tipohorario':tipohorario,'idPrestador':idPrestador,'fechaCita':fechaCita,
          'serviciosSeleccionados':serviciosSeleccionados
      },
      url:"/cliente/guardarCita/",
      success:function(respuesta){
         setTimeout(function(){closeWaitingDialog();},100);
         if(respuesta=="ok"){
             swal({
                    title: "",   
                    text: "Proceso realizado correctamente, espere la confirmación de su cita",
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
                       location.href="/cliente/servicios";
                    }
                });
         }else{
             swal("","Ocurrio un error al realizar la cita, consulte al administrador del servicio.","error");
         }
      }
    });
}


/*
 * Obtener la disponibilidad de la fecha enviada
 * Autor: OT
 * Fecha: 22-12-2016
 */
function verDisponible(){
    var fechaCita=$('#fechaOculta').val();
    var idPrestador=$('#idPrestador').val();
    var tipohorario=$('#tipohorario').val();
    $("#fechaEtiqueta").val(fechaCita);
    
    waitingDialog();
    $.ajax({
      type:"GET",
      dataType: 'json',
      data:{'fechaCita':fechaCita,'idPrestador':idPrestador,'tipohorario':tipohorario},
      url:"/cliente/obtenerDisponibleCita/",
      success:function(respuesta){
         setTimeout(function(){closeWaitingDialog();},100);
         $("#divHorario").html(respuesta.horas);
         
      }
    });
    
}

/*
     * Muestra el mapa con la ubicacion del cliente
     * Fecha: 05-07-2017
     * Autor: OT
     */
    function mostrarUbicacionCliente(latitud,longitud){
        var mapaEnvio="";
        
        $("#divModal").dialog({
            autoOpen: false,
            title: "Ubicación",
            modal:true,
            width: 600,
            height: 550,
            close: function(event,ui){
                 $("#divModal").html('');
                 $("#divModal").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/cliente/verMapaUbicacion",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#divModal").html(respuesta);
                            var myLatlng = new google.maps.LatLng(latitud,longitud);
                            var mapOptions = {
                                  center: myLatlng,
                                  zoom: 9,
                                  mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                
                            var mapaEnvio = new google.maps.Map(document.getElementById("mapidUb"),mapOptions);
                            var marker = new google.maps.Marker({
                                   position: myLatlng,
                                   map: mapaEnvio,
                            });
                   }
                });
            }
	});
	$("#divModal").dialog('open');
    }
