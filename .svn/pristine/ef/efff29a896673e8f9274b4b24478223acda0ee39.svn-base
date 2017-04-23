var map;
var geocoder;
var bounds = new google.maps.LatLngBounds();
var oTable;

	misMarkers = [],
	infoWindow = new google.maps.InfoWindow({
		content: ''
	});
var markerCluster;


$(function() {
	 map = new google.maps.Map(document.getElementById("mapaOfertas"), {
		center: {
			lat: -12.0553419,
			lng: -77.0802054
		},
		zoom: 8
	});
	 
	 
	geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-12.0553419, -77.0802054);
    var mapOptions = {
      zoom: 8,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    var options = {
            	imagePath: '../img/clusterer/m'
        	};
    markerCluster = new MarkerClusterer(map,null,options);


    // buscar ofertas al cargar la página
	buscarTracking();
});

/**
 * buscar las ofertas del sistema
 * por default todas
 */
function buscarTracking() {
	var token     = $("#token").val(),
		verde     = $("#green").val(),
		rojo      = $("#red").val(),
		out       = "",
		contenido = [];

	$.ajax({
		type:     'post',
		headers:  {'X-CSRF-TOKEN': token},
		data:     $('#formTracking').serialize(),
		dataType: 'json',
		url:      $('#formTracking').attr('action'),
		beforeSend: function () {
			$('#modalCargando').modal('show');
		}
	}).done(function (respuesta) {
		$('#modalCargando').modal('hide');
		
		//remove markers
		clearOverlays();
		markerCluster.clearMarkers();
		bounds  = new google.maps.LatLngBounds(); //reseteamos los puntos
		var infowindow = new google.maps.InfoWindow();

		if (respuesta.tracking.length > 0) {
			var markers     = [],
				contenido   = [];
				arrayPuntos = [];

			for (i = 0; i < respuesta.tracking.length; i++) {
				var vlatLng = new google.maps.LatLng(respuesta.tracking[i]['latitude'], respuesta.tracking[i]['longitude']);
                                 
                arrayPuntos.push(vlatLng);

			 	bounds.extend(vlatLng);
                                  
            }

            var ruta = new google.maps.Polyline({
                    path: arrayPuntos,
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                  });

            ruta.setMap(map);
            map.panToBounds(bounds);     // auto-center
            map.fitBounds(bounds);

            for(i = 0; i < respuesta.shippings.length; i++){
            	if(respuesta.shippings[i]['status']==3){
            			var vlatLng = new google.maps.LatLng(respuesta.shippings[i]['latitude'], respuesta.shippings[i]['longitude']);
                        marker = new google.maps.Marker({
                           position: vlatLng,
                           map: map,
                           icon: "/img/dot-green.png",
                        });
                         
                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {
                                infowindow.setContent(respuesta.shippings[i]['createdat']);
                                infowindow.open(map, marker);
                            }
                        })(marker, i));
                    
                }
                if(respuesta.shippings[i]['status']==4){
        			var vlatLng = new google.maps.LatLng(respuesta.shippings[i]['latitude'], respuesta.shippings[i]['longitude']);
                    marker = new google.maps.Marker({
                       position: vlatLng,
                       map: map,
                       icon: "/img/dot-red.png",
                    });
                     
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infowindow.setContent(respuesta.shippings[i]['createdat']);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                    
                }
            }

            for(i = 0; i < respuesta.checks.length; i++){
            			var vlatLng = new google.maps.LatLng(respuesta.checks[i]['latitude'], respuesta.checks[i]['longitude']);
                        marker = new google.maps.Marker({
                           position: vlatLng,
                           map: map,
                           icon: "/img/truckMap.png",
                        });
                         
                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {
                                infowindow.setContent(respuesta.checks[i]['fecha']);
                                infowindow.open(map, marker);
                            }
                        })(marker, i));
            }

		}

	}).fail(function (XMLHttpRequest, textStatus, errorThrown) {
		$('#modalCargando').modal('hide');
	});
}

function clearOverlays() {
	for (var i = 0; i < misMarkers.length; i++ ) {
		misMarkers[i].setMap(null);
		misMarkers[i] = null;


	}
	misMarkers.length = 0;
}

function mostrarMapa(){
	if($("#ocultarMapa").is(':checked')){
		$("#contenedorMapa").show();
	}
	else{
		$("#contenedorMapa").hide();
	}
}
