'use strict';

jQuery(document).ready(function($) {
    let $map                    = $('#map'),
        $latitude               = $('#latitude'),
        $longitude              = $('#longitude'),
        $location               = $('#location'),
        map                     = null,
        marker                  = null,
        center                  = null,
        geocoder                = new google.maps.Geocoder(),
        centerLatitude          = $latitude.val() === ''  ? 19.432608  : Number($latitude.val()),
        centerLongitude         = $longitude.val() === '' ? -99.133208 : Number($longitude.val());

    map = new google.maps.Map(document.querySelector('#map'), {
        center: {
            lat: centerLatitude,
            lng: centerLongitude
        },
        zoom: 7
    });

    marker = new google.maps.Marker({
        position:   {
            lat: centerLatitude,
            lng: centerLongitude
        },
        map:        map,
        draggable:  true
    });

    // listener mapa
    google.maps.event.addListener(map, 'click', function(event) {
        marker.setMap(null);

        marker = new google.maps.Marker({
            position: event.latLng,
            map: map,
            draggable: true
        });

        $latitude.val(event.latLng.lat());
        $longitude.val(event.latLng.lng());
        obtenerDireccionPunto(event.latLng);

        google.maps.event.addListener(marker, 'click', function(event) {
            marker.setMap(null);
            $latitude.val('');
            $longitude.val('');
            $location.val('');
        });
    });

    center = map.getCenter();

    // re paint the map
    $('#mapLocationLink').on('click', function () {
        setTimeout(function () {
            google.maps.event.trigger(map, 'resize');
            map.setCenter(center);
        }, 2000);
    });

    /**
     * get the real direction from point
     *
     * @param string punto
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
                    $location.val(cadenaOrigen1);
               }
            }
        });
    }
});