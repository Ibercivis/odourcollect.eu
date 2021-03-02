/*globals $*/
'use strict';
/* ==========================================================================
   GLOBAL VARS
   ========================================================================== */
var regexMobile = '/Mobile|iP(hone|od|ad)|Android|BlackBerry|IEMobile|Kindle|NetFront|Silk-Accelerated|(hpw|web)OS|Fennec|Minimo|Opera M(obi|ini)|Blazer|Dolfin|Dolphin|Skyfire|Zune/';
var isMobile = navigator.userAgent.match(regexMobile);

$(document).ready(function() {
   
    /* $('#popup__more-info-form').on('submit', function(e){
        e.preventDefault();
        comprovaMoreInfoFormData();
    }); */

});

function comprovaMoreInfoFormData() {
    $('#popup__form-input-errors').removeClass("visible").addClass("no_visible");
    $('#popup__form-input-conditions').removeClass("visible").addClass("no_visible");

    $('#popup__more-info-form__input-submit').hide();
    $('#form__loader').show();

    var valid = true;
    var conditions = true;
    var formData = $('#popup__more-info-form').serialize();
    var form = $('#popup__more-info-form');

    var name = $('#name');
    var surname = $('#surname');
    var email = $('#email');
    var phone = $('#phone');
    var city = $('#city');
    var country = $('#country');
    var program = $('#program');

    if (!validateTextInput(name)) { valid = false; }
    if (!validateTextInput(surname)) { valid = false; }
    if (!validateTextInput(phone)) { valid = false; }
    if (!validateTextInput(city)) { valid = false; }
    if (!validateSelectInput(country)) { valid = false; }
    if (!validateEmailInput(email)) { valid = false; }
    if (!validateSelectInput(program)) { valid = false; }

    if (!$('#contact__privacy').is(':checked')) {
        $('#contact__privacy-label').addClass("contact__privacy-checkbox_error");
        $('#popup__form-input-conditions').removeClass("no_visible").addClass("visible");

        if (valid == false) {
            $('#popup__form-input-errors').addClass("visible").removeClass("no_visible");
            $('#popup__more-info-form__input-submit').show();
            $('#form__loader').hide();
            return false;
        }

        $('#popup__more-info-form__input-submit').show();
        $('#form__loader').hide();

    } else {
        $('#contact__privacy-label').removeClass("contact__privacy-checkbox_error");
        $('#popup__form-input-conditions').removeClass("visible").addClass("no_visible");

        if (valid) {
            $.ajaxSetup({
                headers: { 'X-XSRF-Token': $('meta[name="_token"]').attr('content') }
            });

            $.ajax({
                type  : 'POST',
                url   : form.attr('action'),
                data  : formData,
                success : function(data) {
                    if($.isEmptyObject(data.error)){
                        form.slideUp();
                        $('#normal_txt').hide();
                        $('#thanks_txt').show();
                    }else{
                        $.each(data.error, function(i, item) {
                            $('#'+i).addClass("culinary__input_error");
                        });
                        $('#popup__form-input-errors').addClass("visible").removeClass("no_visible");
                        $('#popup__more-info-form__input-submit').show();
                        $('#form__loader').hide();
                    }
                },
                error : function() {}
            });
        } else {
            $('#popup__form-input-errors').addClass("visible").removeClass("no_visible");
            $('#popup__more-info-form__input-submit').show();
            $('#form__loader').hide();
            return false;
        }
    }
}

function validateFileInput(input) {
    var empty = input[0].files.length;
    var id = input.attr('id');
    var valid = true;

    $('#'+id+'-empty_error').removeClass('visible').addClass('no_visible');
    $('#'+id+'-max_error').removeClass('visible').addClass('no_visible');

    if (empty == 0) {
        $('#'+id+'-empty_error').removeClass('no_visible').addClass('visible');
        valid = false;
    }else{
        var size = input[0].files[0].size;
        if(size > 2500000) {
            $('#'+id+'-max_error').removeClass('no_visible').addClass('visible');
            valid = false;
        }
    }
    return valid;
}

function validateTextInput(input) {
    var value = input.val();
    if (value) {
        input.removeClass("culinary__input_error");
        return true;
    } else {
        input.addClass("culinary__input_error");
        return false;
    }
}

function validateSelectInput(input) {
    var value = input.val();
    if (value != '0') {
        input.removeClass("culinary__input_error");
        return true;
    } else {
        input.addClass("culinary__input_error");
        return false;
    }
}

function validateNumberInput(input) {
    var value = input.val();
    if (value) {
        if ($.isNumeric(value) && value > 0) {
            input.removeClass("culinary__input_error");
            return true;
        } else {
            input.addClass("culinary__input_error");
            return false;
        }
    } else {
        input.addClass("culinary__input_error");
        return false;
    }
}

function validateEmailInput(input) {
    var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    var value = input.val();

    var is_email = re.test(input.val());

    if (value) {
        if (is_email) {
            input.removeClass("culinary__input_error");
            return true;
        } else {
            input.addClass("culinary__input_error");
            return false;
        }
    } else {
        input.addClass("culinary__input_error");
        return false;
    }
}

function validatePhoneInput(input) {
    var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
    var value = input.val();
    var value = input.val();

    var is_phone = filter.test(input.val());

    if (value) {
        if (is_phone) {
            input.removeClass("culinary__input_error");
            return true;
        } else {
            input.addClass("culinary__input_error");
            return false;
        }
    } else {
        input.addClass("culinary__input_error");
        return false;
    }
}

/*------- /VALIDACIO DEL FORMULARI --------*/
function limpiaForm(miForm) {
    // recorremos todos los campos que tiene el formulario
    $(':input', miForm).each(function() {
        var type = this.type;
        var tag = this.tagName.toLowerCase();
        //limpiamos los valores de los campos…
        if (type == 'text' || type == 'password' || tag == 'textarea' || type == 'email')
            this.value = "";
        // excepto de los checkboxes y radios, le quitamos el checked
        // pero su valor no debe ser cambiado
        else if (type == 'checkbox' || type == 'radio')
            this.checked = false;
        // los selects le ponesmos el indice a -
        else if (tag == 'select')
            this.selectedIndex = -1;
    });
}