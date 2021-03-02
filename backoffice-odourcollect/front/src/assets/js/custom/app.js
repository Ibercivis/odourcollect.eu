/*globals $*/
'use strict';
/* ==========================================================================
    IMPORTS
========================================================================== */

//import CHARTING from './functions/charting.js';

/* ==========================================================================
   GLOBAL VARS
   ========================================================================== */
var regexMobile = '/Mobile|iP(hone|od|ad)|Android|BlackBerry|IEMobile|Kindle|NetFront|Silk-Accelerated|(hpw|web)OS|Fennec|Minimo|Opera M(obi|ini)|Blazer|Dolfin|Dolphin|Skyfire|Zune/';
var isMobile = navigator.userAgent.match(regexMobile);

var dt = require( 'datatables.net-bs4' )( window, $ );

$(document).ready(function() {
    loadScripts();
    heightAdjustments();
    scrollAdjustments();

    //feather.replace();

    /* if($("#federations_list").length > 0) {
        $('#federations_list').DataTable({ordering: true, searching: true, info: false, "lengthChange": false, "pageLength": 20});

        $('.federation__delete-button').click(function(){
            var id = $(this).attr('data-id');
            $('#form__delete-federation_input-id').val(id);
            $('#modal__delete-federation').modal();
        });
    }*/ 
    
});

$(window).resize(function() {
    heightAdjustments();
    scrollAdjustments();
});

$(window).scroll(function(){
    scrollAdjustments();
});

function scrollAdjustments(){ }

function loadScripts(){ }

function heightAdjustments() { }