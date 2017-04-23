var map;
var geocoder;
var bounds = new google.maps.LatLngBounds();
var oTable;
var urlDetalle = $('#rutaMapaDetalle').data('url'),
	misMarkers = [],
	infoWindow = new google.maps.InfoWindow({
		content: ''
	});
var markerCluster;


$(function() {

	// select 2
	$('#paisRecoleccion, #paisEntrega').select2();
        $('#ciudadRecoleccion,#estadoRecoleccion').select2();

	$('#cal1').datepicker();
	$('#cal2').datepicker();

	// overriding default values
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

	$('#formBusqueda').validate({
		rules: {
			precioDesde: {
				required: true,
				number: true
			},
			precioHasta: {
				required: true,
				number: true
			}
		},
		messages: {
			precioDesde: {
				required: 'Ingrese desde que precio',
				number: 'Ingrese sólo números'
			},
			precioHasta: {
				required: 'Ingrese hasta que precio',
				number: 'Ingrese sólo números'
			}	
		}
	});

	$('#paisRecoleccion').on('change', function() {
		if ($(this).val() !== '0') {
			var paisId = $(this).val(),origen = 'recoleccion';
			buscarEstados(paisId, origen);
		}else{
                    $('#estadoRecoleccion option').remove();
                    $('#estadoRecoleccion').html("<option value='0' selected>Todos</option>");
                    $('#ciudadRecoleccion option').remove();
                    $('#ciudadRecoleccion').html("<option value='0' selected>Todos</option>");
                    $('#ciudadRecoleccion,#estadoRecoleccion').select2();
                }
	});

        
        $('#estadoRecoleccion').on('change', function() {
		if ($(this).val() !== '0') {
			var estadoId = $(this).val();
			buscarCiudades(estadoId);
		}else{
                    $('#ciudadRecoleccion option').remove();
                    $('#ciudadRecoleccion').html("<option value='0' selected>Todos</option>");
                    $('#ciudadRecoleccion').select2();
                }
	});

	/**
	 * busqueda ajax de estatos
	 * @param paisId
	 * @param origen
     */
	function buscarEstados(paisId, origen) {
		$.ajax({
			type:     "post",
			headers:  {'X-CSRF-TOKEN': $('#token').val()},
			data:     {paisId: paisId, origen: origen},
			dataType: 'json',
			url:      $('#paisRecoleccion').data('url'),
			beforeSend: function() {
				$('#modalCargando').modal('show');
			}
		}).done(function (respuesta) {
			$('#modalCargando').modal('hide');
			if (respuesta.estatus === 'OK') {
				if (origen === 'recoleccion') {
					$('#estadoRecoleccion').html(respuesta.html);
                                        $('#ciudadRecoleccion option').remove();
                                        $('#ciudadRecoleccion').html("<option value='0' selected>Todos</option>");
                                        $('#ciudadRecoleccion,#estadoRecoleccion').select2();
				}

				if (origen === 'entrega') {
					$('#estadoEntrega').html(respuesta.html);
				}
			}

			if (respuesta.estatus === 'fail') {
				swal('', 'No se pudieron cargar los estados.', 'warning');
			}

		}).fail(function (XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus + ': ' + errorThrown);
			$('#modalCargando').modal('hide');
			swal('', 'No se pudieron cargar los estados.', 'warning');
		});
	}
        
        
        /**
	 * busqueda ajax de ciudades
	 * @param paisId
	 * @param origen
     */
	function buscarCiudades(estadoId) {
		$.ajax({
			type:     "post",
			headers:  {'X-CSRF-TOKEN': $('#token').val()},
			data:     {estadoId: estadoId},
			dataType: 'json',
			url:      $('#estadoRecoleccion').data('url'),
			beforeSend: function() {
				$('#modalCargando').modal('show');
			}
		}).done(function (respuesta) {
			$('#modalCargando').modal('hide');
			if (respuesta.estatus === 'OK') {
                            $('#ciudadRecoleccion').html(respuesta.html);
                            $('#ciudadRecoleccion').select2();
			}

			if (respuesta.estatus === 'fail') {
				swal('', 'No se pudieron cargar las ciudades.', 'warning');
			}

		}).fail(function (XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus + ': ' + errorThrown);
			$('#modalCargando').modal('hide');
			swal('', 'No se pudieron cargar las ciudades.', 'warning');
		});
	}

	$('#buscar').on('click', function () {
		buscarServicios();
	});

	$("#ocultarMapa").on('ifChanged',function(event){
		if($("#ocultarMapa").is(':checked')){
			$("#contenedorMapa").show();

		}
		else{
			$("#contenedorMapa").hide();	
		}
	});

	// ordenar y re-buscar
	$('#ordenar').on('change', function () {
		$('#orderBy').val($(this).val());

		buscarServicios();
	});
        
        var latitudUsuario=19.7018226;
        var longitudUsuario=-98.6166789;
        
        if($("#latitudUsuario").val()!=""){
            latitudUsuario=parseFloat($("#latitudUsuario").val());
            longitudUsuario=parseFloat($("#longitudUsuario").val());
        }


	 map = new google.maps.Map(document.getElementById("mapaServicios"), {
		center: {
			lat: latitudUsuario,
			lng: longitudUsuario
		},
		zoom: 8
	});
	 
	 
	geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitudUsuario, longitudUsuario);
        var mapOptions = {
            zoom: 8,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }

        var options = {
            	imagePath: '../img/clusterer/m'
        	};
        markerCluster = new MarkerClusterer(map,null,options);

        oTable= $('#listadoOfertas').DataTable({
            bLengthChange: false,
            bFilter: false,
            processing: true,
            order: false,
            columns: [
                {data: 'compania', name: 'compania',orderable: false, searchable: false, width: '25%'},
                {data: 'prestador', name: 'prestador',orderable: false, searchable: false, width: '25%'},
                {data: 'dire', name: 'dire',orderable: false, searchable: false, width: '25%'},                
                {data: 'telefono', name: 'telefono',orderable: false, searchable: false, width: '10%'},
                {data: 'accion', name: 'accion',orderable: false, searchable: false, width: '12%'},
        ],
        language:{
            "decimal":        "",
            "emptyTable":     "<center><h4><b>Ningún resultado encontrado.</b></h4><h5 class='text-muted'>Revisa tu búsqueda e inténtalo de nuevo.</h5></center>",
            "info":           "",
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
            }
        }
    });

    // buscar servicios al cargar la página
	buscarServicios();
});

/**
 * buscar los servicios del sistema
 * por default todas
 */
function buscarServicios() {
	var token     = $("#token").val();

	if (!($('#formBusqueda').valid())) {
		return false;
	}

	$.ajax({
		type:     'post',
		headers:  {'X-CSRF-TOKEN': token},
		data:     $('#formBusqueda').serialize(),
		dataType: 'json',
		url:      $('#formBusqueda').attr('action'),
		beforeSend: function () {
			$('#modalCargando').modal('show');
		}
	}).done(function (respuesta) {
		$('#modalCargando').modal('hide');
		$("#resultados").html(respuesta.registros);
		$('#listadoOfertas').children('tbody').html(respuesta.html);

		//remove markers
		clearOverlays();
		markerCluster.clearMarkers();

		if (respuesta.marcadores.length > 0) {
			for (var k = 0; k < respuesta.marcadores.length; k++) {
                               var punto    = respuesta.marcadores[k].pos.split(',');
                               var Oposition = new google.maps.LatLng(parseFloat(punto[0]), parseFloat(punto[1]));
                               
				bounds.extend(Oposition);

				//creamos el marcador
				var marker = new google.maps.Marker({
					position: Oposition,
					map:      map,
					icon:     respuesta.marcadores[k].icono,
				});

				var content = respuesta.marcadores[k].info;

				(function(marker, content) {
					google.maps.event.addListener(marker, 'click', function() {
						infoWindow.setContent(content);
						infoWindow.open(map, marker);
					});
				})(marker, content);

				misMarkers.push(marker);
			}

			// Automatically center the map fitting all markers on the screen
			map.fitBounds(bounds);

			

                    markerCluster.addMarkers(misMarkers);
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

