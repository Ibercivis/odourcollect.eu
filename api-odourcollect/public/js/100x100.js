$(document).ready(function() {

	altura_fondo();
});


$(window).scroll(function() {

}).resize(function() {
  altura_fondo();
});

function altura_fondo() {
    var h = $(window).height();
    var w = $(window).width();

    if (window.innerHeight > 450) {

      if (window.innerWidth > 800) {
        $('.center-v').height(h);
      } else {
        $('.center-v').height(400);
      }
    } else {

      $('.center-v').height(400);
    }

};