/*
 * Scripts para agendar la cita a domicilio del cliente
 * 
 */

var geocoder = new google.maps.Geocoder();

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
    var idUbicacion=$('#idUbicacion').val();
    var masinfo=$('#masinfo').val();
    var latitudUbicacion=$('#latitudUbicacion').val();
    var longitudUbicacion=$('#longitudUbicacion').val();
    var serviciosSeleccionados="";
    
    if(idUbicacion.replace(/\s/g,"")==""){
          swal("","Indique la ubicación del servicio.","warning");
          return;
       }
    
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
              'serviciosSeleccionados':serviciosSeleccionados,'idUbicacion':idUbicacion,'masinfo':masinfo,
          'latitudUbicacion':latitudUbicacion,'longitudUbicacion':longitudUbicacion},
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


/*
     * Muestra el mapa para elegir la ubicacion
     * Fecha: 05-07-2017
     * Autor: OT
     */
    function mostrarMapaDomicilio(){
        var mapaEnvio="";
        
        $("#divModal").dialog({
            autoOpen: false,
            title: "Seleccione su ubicación",
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
                            
                             
                             if($("#latitudUbicacion").val()!=""){
                                var myLatlng = new google.maps.LatLng($("#latitudUbicacion").val(),$("#longitudUbicacion").val());
                                var mapOptions = {
                                  center: myLatlng,
                                  zoom: 10,
                                  mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                
                                var mapaEnvio = new google.maps.Map(document.getElementById("mapidUb"),mapOptions);
                                var marker = new google.maps.Marker({
                                         position: myLatlng,
                                         map: mapaEnvio,
                                         draggable: true
                                     });
                                     
                                     google.maps.event.addListener(marker, 'dragend', function(event) {
                                       $("#latitudUbicacion").val(event.latLng.lat());
                                       $("#longitudUbicacion").val(event.latLng.lng());
                                       obtenerDireccionPuntoDom(event.latLng);
                                   });

                                   google.maps.event.addListener(marker, 'click', function(event) {
                                       marker.setMap(null);
                                       $("#latitudUbicacion").val('');
                                       $("#longitudUbicacion").val('');
                                       $("#idUbicacion").val('');
                                   });
                                     
                              }else{
                                  mapaEnvio = new google.maps.Map(document.getElementById("mapidUb"),
                                    {
                                      center: {lat: 19.432608, lng: -99.133208},
                                      zoom: 7
                                    });
                              }
                             
                              google.maps.event.addListener(mapaEnvio, 'click', function(event) {
                                  
                                  if($("#latitudUbicacion").val()==""){
                                       var marker = new google.maps.Marker({
                                           position: event.latLng,
                                           map: mapaEnvio,
                                           draggable: true
                                       });
                                       $("#latitudUbicacion").val(event.latLng.lat());
                                       $("#longitudUbicacion").val(event.latLng.lng());
                                      obtenerDireccionPuntoDom(event.latLng);
                                  }
                                   google.maps.event.addListener(marker, 'dragend', function(event) {
                                       $("#latitudUbicacion").val(event.latLng.lat());
                                       $("#longitudUbicacion").val(event.latLng.lng());
                                       obtenerDireccionPuntoDom(event.latLng);
                                   });

                                   google.maps.event.addListener(marker, 'click', function(event) {
                                       marker.setMap(null);
                                       $("#latitudUbicacion").val('');
                                       $("#longitudUbicacion").val('');
                                       $("#idUbicacion").val('');
                                   });
                              });
                   }
                });
            }
	});
	$("#divModal").dialog('open');
    }

/*
 * Obtiene la direcion del punto recibido
 * Fecha: 05-01-2017
 * Autor: OT
 */

function obtenerDireccionPuntoDom(punto){
    geocoder.geocode({ 'latLng': punto }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
               if (results[1]) {
                   var addressComponent = results[0].address_components;
                    var aCompareAdress = {
                            'locality' : 'municipio',
                            'administrative_area_level_1':'estado',
                            'administrative_area_level_2' : 'estado2',
                            'country' : 'pais'
                    };
                    var cadenaOrigen1="";
                    for(var iAddress in addressComponent){
                            var type = aCompareAdress[addressComponent[iAddress].types[0]];
                            if(type != null){
                               if(type=="municipio") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].short_name.toUpperCase()+",";
                               if(type=="estado") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].short_name.toUpperCase()+",";
                               if(type=="estado2") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].short_name.toUpperCase()+",";
                               if(type=="pais") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].long_name.toUpperCase()+",";
                            }
                    }
                    $("#idUbicacion").val(cadenaOrigen1);
               }
            }
         });
    
}
