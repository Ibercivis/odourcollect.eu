/*globals $*/
'use strict';
/* ==========================================================================
   GLOBAL VARS
   ========================================================================== */
var regexMobile = '/Mobile|iP(hone|od|ad)|Android|BlackBerry|IEMobile|Kindle|NetFront|Silk-Accelerated|(hpw|web)OS|Fennec|Minimo|Opera M(obi|ini)|Blazer|Dolfin|Dolphin|Skyfire|Zune/';
var isMobile = navigator.userAgent.match(regexMobile);

$(document).ready(function() {
    prepareSvg();
});

function prepareSvg() {
    $('img.svg').each(function() {
        var $img = $(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        $.get(imgURL, function(data) {
            // Get the SVG tag, ignore the rest
            var $svg = $(data).find('svg');

            // Add replaced image's ID to the new SVG
            if (typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Replace image with new SVG
            $img.replaceWith($svg);

            /*for (var i = 0, len = $svg[0].children.length; i < len; i++) {
             var path = $svg[0].children[i].children[0].children[0];
             if (i == 0) { path.classList = 'path_first'; }
             if (i == 1) { path.classList = 'path_second'; }
             if (i == 2) { path.classList = 'path_third'; }
             if (i == 3) { path.classList = 'path_fourth'; }
            } */

        }, 'xml');
    });
}