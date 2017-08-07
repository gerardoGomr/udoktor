'use strict';

jQuery(document).ready(function($) {
    let $map            = $('#map'),
        locations       = JSON.parse(atob($('#locations').val())),
        map             = null,
        marker          = null,
        markers         = [],
        center          = null,
        geocoder        = new google.maps.Geocoder(),
        centerLatitude  = 16.754577926390958,
        centerLongitude = -93.12732696533203;

    // loading map on Tuxtla
    map = new google.maps.Map(document.querySelector('#map'), {
        center: {
            lat: centerLatitude,
            lng: centerLongitude
        },
        zoom: 7
    });

    if (!$.isEmptyObject(locations)) {
        for (let i = 0; i < locations.length; i++) {
            console.log(locations[i]);
            console.log(locations[i].latitude);
            console.log(locations[i].longitude);
            let marker = new google.maps.Marker({
                position:   {
                    lat: locations[i].latitude,
                    lng: locations[i].longitude
                },
                map:        map,
                draggable:  true
            });

            markers.push(marker);
        }
    }

    center = map.getCenter();
});