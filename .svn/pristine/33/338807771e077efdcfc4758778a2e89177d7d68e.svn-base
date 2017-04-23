$(document).ready(function() {
	// cargar mapa
	var puntoRecoger      = new google.maps.LatLng($("#latitudRecoger").val(), $("#longitudRecoger").val()),
		puntoEntregar     = new google.maps.LatLng($("#latitudEntregar").val(),$("#longitudEntregar").val()),
		markerRojo,
		markerVerde,
		verde             = $('#green').val(),
		rojo			  = $('#red').val(),
		mapa;
        bounds 			  = new google.maps.LatLngBounds(),
		directionsService = new google.maps.DirectionsService(),
		directionsDisplay = new google.maps.DirectionsRenderer;


	// generar mapa
    mapa = new google.maps.Map(document.getElementById("mapaEnvio"), {
	  	center: {
	  		lat: 19.432608,
	  		lng: -99.133208
	  	},
        zoom: 8
 	});

    // markers
 	/*markerRojo = new google.maps.Marker({
        position: puntoRecoger,
        map: mapa,
        icon: verde,
        title: 'Punto recoger'
    });

    markerVerde = new google.maps.Marker({
        position: puntoEntregar,
        map: mapa,
        icon: rojo,
        title: 'Punto entregar'
    });*/

    bounds.extend(puntoRecoger);
    bounds.extend(puntoEntregar);

    mapa.fitBounds(bounds);

	directionsDisplay.setMap(mapa);

	// draw route
	directionsService.route({
		origin:      puntoRecoger,
		destination: puntoEntregar,
		travelMode:  google.maps.TravelMode.DRIVING

	}, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);

		}
	});
});

    /*
     * Muestra el detalle de la recoleccion del envio
     * Autor: OT
     * Fecha: 30-07-2016
     */
    function verDetalleEnvioLog(idEnvio,tipo){
        var titulo="";
        if(tipo==1)titulo="Datos de recolección";
        if(tipo==2)titulo="Datos de entrega";
        $("#divDetalleEnvioDatos").dialog({
            autoOpen: false,
            title: titulo,
            modal:true,
            width: 700,
            height: 480,
            close: function(event,ui){
                 $("#divDetalleEnvioDatos").html('');
                 $("#divDetalleEnvioDatos").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{'tipo':tipo,'idEnvio':idEnvio},
                   url:"/transportista/verDetalleLog/",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                      $("#divDetalleEnvioDatos").html(respuesta);
                   }
                });
            }
	});
	$("#divDetalleEnvioDatos").dialog('open');
        
    }
    
    /*
     * Muestra el mapa del envio
     * Fecha: 30-07-2016
     * Autor: OT
     */
    function vermapaEnvio(latitud,longitud,tipo){
        var mapaEnvio="";
        
        var titulo="";
        if(tipo==1)titulo="Punto de recolección";
        if(tipo==2)titulo="Punto de entrega";
        $("#divDetalleEnvioDatos").dialog({
            autoOpen: false,
            title: titulo,
            modal:true,
            width: 700,
            height: 580,
            close: function(event,ui){
                 $("#divDetalleEnvioDatos").html('');
                 $("#divDetalleEnvioDatos").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/transportista/verMapaEnvio",
                   success:function(respuesta){
                       $("#divDetalleEnvioDatos").html(respuesta);
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
	$("#divDetalleEnvioDatos").dialog('open');
    }
    
    