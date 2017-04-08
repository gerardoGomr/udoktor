$(document).ready(function() {
	// cargar mapa
	var puntoRecoger      = new google.maps.LatLng($("#latitudRecoger").val(), $("#longitudRecoger").val()),
		puntoEntregar     = new google.maps.LatLng($("#latitudEntregar").val(),$("#longitudEntregar").val()),
		markerRojo,
		markerVerde,
		verde             = $('#green').val(),
		rojo			  = $('#red').val(),
		mapa;
        bounds = new google.maps.LatLngBounds(),
		questionsErrorText = '';


	// generar mapa
    mapa = new google.maps.Map(document.getElementById("mapaEnvio"), {
	  	center: {
	  		lat: 19.432608,
	  		lng: -99.133208
	  	},
        zoom: 8
 	});

    // markers
 	markerRojo = new google.maps.Marker({
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
    });

    bounds.extend(puntoRecoger);
    bounds.extend(puntoEntregar);

    mapa.fitBounds(bounds);

    // ############################################################
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

	// validar formulario
    $('#formPregunta').validate({
    	rules: {
    		pregunta: 'required'
    	},
    	messages: {
    		pregunta: 'Por favor, escriba su pregunta.'
    	}
    });

    // enviar pregunta
    $('#guardarPregunta').click(function (event) {
		// validar que el texto de pregunta no contenga números, correos o urls
		if (!validateQuestionText()) {
			swal('Error', 'El texto de la pregunta contiene carácteres inválidos: ' + questionsErrorText, 'warning');
			return false;
		}

    	if ($('#formPregunta').valid()) {
    		$.ajax({
    			url:      $('#formPregunta').attr('action'),
    			type:     'post',
    			headers:  {'X-CSRF-TOKEN': $('#formPregunta').find('input[name="_token"]').val() },
    			dataType: 'json',
    			data:     $('#formPregunta').serialize(),
    			beforeSend: function() {
    				waitingDialog();
    			}
    		})
    		.done(function(respuesta) {
    			closeWaitingDialog();

    			if (respuesta.estatus === 'fail') {
    				swal('Error', 'Ocurrió un error al guardar la pregunta.', 'warning');
    			}

    			if (respuesta.estatus === 'ok') {
    				$('#seccionPreguntas').html(respuesta.html);

					swal({
						title: '',
						text:  'Pregunta registrada con éxito.',
						type:  'info',
						closeOnConfirm: true
					}, function() {
						// cerrar modal y reiniciar campo de pregunta
						$('#pregunta').val('');
						$('#modalPregunta').modal('hide');
					});
    			}
    		})
    		.fail(function(XmlHttpRequest, textStatus, errorThrown) {
    			closeWaitingDialog();
    			console.log(textStatus + ': ' + errorThrown);
    			swal('Error', 'Ocurrió un error al guardar la pregunta.', 'warning');
    		});

    	}
    });

    $('#modalPregunta').on('click', function () {
		$('#pregunta').focus();
    });

	/**
	 * validate that the entry of question doesn´t have numbers, emails or urls
	 * return true if the validation is succesfull
	 * 
	 * @return bool
	 */
	function validateQuestionText() {
		var text     = $('#pregunta').val(),
			elements = [];

		// separar por espacio
		elements      = text.split(' ');
		totalElements = elements.length;

		// recorrer cada uno de los elementos
		for (var i = 0; i < totalElements; i++) {
			// números
			/*if (!isNaN(parseFloat(elements[i])) && isFinite(elements[i])) {
				questionsErrorText = 'Números';
				return false;
			}*/

			// urls
			var pattern = /^(https?:\/\/)?((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|((\d{1,3}\.){3}\d{1,3}))(\:\d+)?(\/[-a-z\d%_.~+]*)*(\?[;&a-z\d%_.~+=-]*)?(\#[-a-z\d_]*)?$/;
			if (pattern.test(elements[i])) {
				questionsErrorText = 'URL\'s';
				return false;
			}

			// correos
			pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			if (pattern.test(elements[i])) {
				questionsErrorText = 'Emails';
				return false;
			}
		}

		return true;
	}
});

/* Pregunta si quiere entrar al grupo y si es verdadero envia la solicitud*/
function enviarSolicitud(url,id){
	swal({   
		title: "",   
		text: "Ud. no puede ofertar en este grupo. ¿Desea que lo contactemos para añadirlo al grupo?",   
		type: "warning",   
		showCancelButton: true,   
		  
		confirmButtonText: "Sí, Contactarme",   
		closeOnConfirm: true }, 
		function(){   
			waitingDialog();
			$.ajax({
					type:     "post",
					headers:  {'X-CSRF-TOKEN': $('#token').val()},
					data:     {id: id},
					dataType: 'json',
					url:      url,
					success: function(respuesta){
						if(respuesta.estatus=="OK"){
							swal("","Se ha enviado la solicitud, pronto nos comunicaremos con Ud.","success");
						}
						else{
							swal("","Ocurrió un problema, intenta más tarde","error");
						}
						closeWaitingDialog();
					}
				});

		}
	);
	return;
	
}


function soloLetras(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyz,.";
    
    tecla_especial = false
    

    if(letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}

function limpia() {
    var val = document.getElementById("pregunta").value;
    var tam = val.length;
    for(i = 0; i < tam; i++) {
        if(!isNaN(val[i]))
            document.getElementById("pregunta").value = '';
    }
}

