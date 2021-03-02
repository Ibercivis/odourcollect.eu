/*globals $*/
'use strict';
/* ==========================================================================
   GLOBAL VARS
   ========================================================================== */
var regexMobile = '/Mobile|iP(hone|od|ad)|Android|BlackBerry|IEMobile|Kindle|NetFront|Silk-Accelerated|(hpw|web)OS|Fennec|Minimo|Opera M(obi|ini)|Blazer|Dolfin|Dolphin|Skyfire|Zune/';
var isMobile = navigator.userAgent.match(regexMobile);

$(document).ready(function() {
    loadScripts();
});

function loadScripts(){
    if($("#barcelona__map").length > 0) {
        $.getScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyBn_ELXYi4P6_R2jNw1jfM7Q_pEjyZHeFg", function(data, textStatus, jqxhr) {
              initGoogleMaps();
        });
    }
}

/*   GOOGLE MAPS   */
var featureOptions = [{ "featureType": "water", "elementType": "geometry", "stylers": [{ "color": "#e9e9e9" }, { "lightness": 17 }] }, { "featureType": "landscape", "elementType": "geometry", "stylers": [{ "color": "#f5f5f5" }, { "lightness": 20 }] }, { "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{ "color": "#ffffff" }, { "lightness": 17 }] }, { "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{ "color": "#ffffff" }, { "lightness": 29 }, { "weight": 0.2 }] }, { "featureType": "road.arterial", "elementType": "geometry", "stylers": [{ "color": "#ffffff" }, { "lightness": 18 }] }, { "featureType": "road.local", "elementType": "geometry", "stylers": [{ "color": "#ffffff" }, { "lightness": 16 }] }, { "featureType": "poi", "elementType": "geometry", "stylers": [{ "color": "#f5f5f5" }, { "lightness": 21 }] }, { "featureType": "poi.park", "elementType": "geometry", "stylers": [{ "color": "#dedede" }, { "lightness": 21 }] }, { "elementType": "labels.text.stroke", "stylers": [{ "visibility": "on" }, { "color": "#ffffff" }, { "lightness": 16 }] }, { "elementType": "labels.text.fill", "stylers": [{ "saturation": 36 }, { "color": "#333333" }, { "lightness": 40 }] }, { "elementType": "labels.icon", "stylers": [{ "visibility": "off" }] }, { "featureType": "transit", "elementType": "geometry", "stylers": [{ "color": "#f2f2f2" }, { "lightness": 19 }] }, { "featureType": "administrative", "elementType": "geometry.fill", "stylers": [{ "color": "#fefefe" }, { "lightness": 20 }] }, { "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{ "color": "#fefefe" }, { "lightness": 17 }, { "weight": 1.2 }] }];

function initGoogleMaps() {
    var cibMap = new google.maps.Map(document.getElementById('barcelona__map'), {
        zoom: 15,
        center: { lat: 41.427307, lng: 2.200700 }
    });

    cibMap.setOptions({ styles: featureOptions });

    var markerIcon = 'https://cib.education/img/map-icon.png';

    var cibMarker = new google.maps.Marker({
        position: { lat: 41.427307, lng: 2.200700 },
        map: cibMap,
        icon: markerIcon
    });
}