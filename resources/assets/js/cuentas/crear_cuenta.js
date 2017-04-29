jQuery(document).ready(function($) {
    var $paso2                = $('#paso2'),
        $pasoAnterior         = $('#pasoAnterior'),
        $crearCuenta          = $('#crearCuenta'),
        $mapa                 = $('#mapa'),
        $abrirMapa            = $('#abrirMapa'),
        $modalMapa            = $('#modalMapa'),
        $formCrearCuenta      = $('#formCrearCuenta'),
        $latitud              = $('#latitud'),
        $longitud             = $('#longitud'),
        $ubicacion            = $('#ubicacion'),
        $servicios            = $('#servicios'),
        $informacionBasica    = $('#informacionBasica'),
        $informacionPrestador = $('#informacionPrestador'),
        $captcha              = $('#captcha'),
        mapaEnvio             = null,
        marker                = null,
        geocoder              = new google.maps.Geocoder(),
        listaServicios        = [{
            value: '1',
            text: 'Aplicacion de inyecciones'
        }, {
            value: '2',
            text: 'Consulta'
        }],
        servicios                = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local:          listaServicios
        });

    servicios.initialize();
    $servicios.tagsinput({
        itemValue: 'value',
        itemText:  'text',
        typeaheadjs: {
            name:       'servicios',
            displayKey: 'text',
            source:     servicios.ttAdapter()
        }
    });

    // click para el paso 2
	$paso2.on('click', function () {
		inicializarValidacionForm();

        if ($formCrearCuenta.valid()) {

            if (!$('#aceptaTerminos').prop('checked')) {
                swal('Por favor, acepte los términos y condiciones.');
                return false;
            }

            var tipoCuenta;

            if ($('#cuentaCliente').prop('checked')) {
                tipoCuenta = 1;
            }

            if ($('#cuentaPrestador').prop('checked')) {
                tipoCuenta = 2;
            }

            $informacionBasica.hide(300);
            $captcha.show(300);
            $pasoAnterior.show(300);
            $crearCuenta.show(300);

            if (tipoCuenta === 2) {
                // show data for prestador
                $informacionPrestador.show(300);
            }

            grecaptcha.reset();
        }
	});

    // regresar un paso
    $pasoAnterior.on('click', function () {
        $captcha.hide(300);
        $crearCuenta.hide(300);
        $pasoAnterior.hide(300);

        if ($informacionPrestador.is(':visible')) {
            $informacionPrestador.hide(300);
        }

        $informacionBasica.show(300);
    });

    $crearCuenta.on('click', function () {
        inicializarValidacionForm();
        if ($formCrearCuenta.valid()) {
            console.log('valid');
        }
    });

    /**
     * inicializar validacion de form
     */
    function inicializarValidacionForm() {
        $formCrearCuenta.validate({
            highlight: function (input) {
                console.log(input);
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function (input) {
                $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function (error, element) {
                $(element).parents('.input-group').append(error);
                $(element).parents('.form-group').append(error);
            }
        })
            .settings.ignore = ':disabled,:hidden';
    }

    // abrir modal del mapa
    $abrirMapa.on('click', function(event) {
        // gmaps on load
        mapaEnvio = new google.maps.Map(document.querySelector('#mapa'), {
            center: {
                lat: 19.432608,
                lng: -99.133208
            },
            zoom: 7
        });

        marker = new google.maps.Marker({
            position: event.latLng,
            map: mapaEnvio,
            draggable: true
        });

        // listener mapa
        google.maps.event.addListener(mapaEnvio, 'click', function(event) {
            marker.setMap(null);

            marker = new google.maps.Marker({
                position: event.latLng,
                map: mapaEnvio,
                draggable: true
            });

            $latitud.val(event.latLng.lat());
            $longitud.val(event.latLng.lng());
            obtenerDireccionPunto(event.latLng);

            google.maps.event.addListener(marker, 'click', function(event) {
                marker.setMap(null);
                $latitud.val('');
                $longitud.val('');
                $ubicacion.val('');
            });
        });

        $modalMapa.modal('show');
    });

    // función para repintar mapa
    $modalMapa.on("shown.bs.modal", function () {
        google.maps.event.trigger(mapaEnvio, "resize");
    });

    /**
     * obtener la direccion real del punt
     *
     * @param  string punto
     * @return void
     */
    function obtenerDireccionPunto(punto) {
        geocoder.geocode({ 'latLng': punto }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    var cadenaOrigen1    = '',
                        addressComponent = results[0].address_components,
                        aCompareAdress   = {
                            'locality' : 'municipio',
                            'administrative_area_level_1':'estado',
                            'administrative_area_level_2' : 'estado2',
                            'country' : 'pais'
                        };

                    for (var iAddress in addressComponent) {
                        var type = aCompareAdress[addressComponent[iAddress].types[0]];
                        if(type != null){
                            if(type === 'municipio')
                                cadenaOrigen1 = cadenaOrigen1 + addressComponent[iAddress].short_name + ',';
                            if(type === 'estado')
                                cadenaOrigen1 = cadenaOrigen1 + addressComponent[iAddress].short_name + ',';
                            if(type === 'estado2')
                                cadenaOrigen1 = cadenaOrigen1 + addressComponent[iAddress].short_name + ',';
                            if(type === 'pais')
                                cadenaOrigen1 = cadenaOrigen1 + addressComponent[iAddress].long_name + ',';
                        }
                    }
                    $ubicacion.val(cadenaOrigen1);
               }
            }
        });
    }
});