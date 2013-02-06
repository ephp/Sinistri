jQuery.fx.interval = 100;
var this_window = window.location;

function distanza(da_lat, da_lon, a_lat, a_lon) {
    return (Math.acos((Math.sin(deg2rad(da_lat)) * Math.sin(deg2rad(a_lat))) + (Math.cos(deg2rad(da_lat)) * Math.cos(deg2rad(a_lat)) * Math.cos(deg2rad(da_lon - a_lon))))) * terra;
}

function deg2rad(radians) {
    return radians * (Math.PI/180);
}

function km(distanza) {
    if (distanza < 0) {
        return 'n.d.';
    }
    if (distanza < 1) {
        distanza = distanza * 1000;
        if (distanza < 1) {
            return '1m';
        }
        if (distanza < 100) {
            return Math.round(distanza) + 'm';
        }
        return Math.round(distanza, -1) + 'm';
    }
    if (distanza < 10) {
        return Math.round(distanza, 1) + 'km';
    }
    return Math.round(distanza) + 'km';
}

/* MESSAGGI ERRRORE DRAG&DROP FILE */
var fileUploadErrors = {
    maxFileSize: 'Il file caricato è troppo grande.',
    minFileSize: 'Il file caricato è troppo piccolo. Controlla che tu non abbia caricato un link simbolio.',
    acceptFileTypes: 'Tipo di file non accettato.',
    maxNumberOfFiles: 'Max number of files exceeded',
    uploadedBytes: 'Uploaded bytes exceed file size',
    emptyResult: 'Empty file upload result'
};

/* CONTROLLO ERRORE PER I FORM */
function erroreForm(campo, messaggio) {
    campo.closest('div').append('<div class="form-alert">'+messaggio+'</div>');
    campo.addClass('alert-red');
    campo.focus(function(){
        $(this).removeClass('alert-red');
    });
    return false;
}
/* PROTOTYPE PER STRINGHE */
String.prototype.swapcase = function(){
    return this.replace(/([a-z]+)|([A-Z]+)/g,function($0,$1,$2){
        return ($1) ? $0.toUpperCase() : $0.toLowerCase();
    });
}

function sanitize(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().remove(/[^a-zA-Z0-9 \-\_\.\,\;\:\'\?\!\"àéèìòù]/g/s));
        });
    });
}
function urlify(text) {
    var urlRegex = /(https?:\/\/[^\s\<]+)/g;
    return text.replace(urlRegex, function(url) {
        return '<a href="' + url + '">' + url + '</a>';
    })
}
function sanitizeCurrency(fields) {
    fields.forEach(function(field){
        field.change(function(){
            value = $(this).val().replace(",", ".").remove(/[^0-9\.]/g);
            n = 0;
            i = 0;
            nc = 0;
            value.chars(function(c) {
                if(c == '.'){
                    n++;
                } 
                if(n == 2){
                    i = nc;
                    n++;
                } 
                nc++;
            });
            if(n > 1) {
                value = value.substring(0, i);
                value = Math.abs(parseFloat(value == '' || value == '.' ? 0 : value));
                $(this).val(value.toFixed(2));
            } else {
                value = Math.abs(parseFloat(value == '' || value == '.' ? 0 : value));
                $(this).val(value.toFixed(2));
            }
        });
    });
}
function sanitizeTelefono(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().remove(/[^0-9 \-\.]/g));
        });
    });
}
function sanitizeSkype(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().remove(/[^a-zA-Z0-9\-\.]/g));
        });
    });
}
function sanitizeUrl(fields) {
    fields.forEach(function(field){
        field.change(function(){
            _sanitizeUrl($(this))
        });
    });
}
function _sanitizeUrl(field) {
    if(!field.val().startsWith(/http(s)?:\/\//) && field.val().trim() != '') {
        field.val('http://'+field.val());
    }
}
function sanitizeHtml(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val(optimizeHtml($(this).val(), false));
        });
    });
}
function sanitizeHtmlMin(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val(optimizeHtml($(this).val(), true));
        });
    });
}
function optimizeHtml(val, min) {
    text = val;
    if(min) {
        text = text.stripTags('a', 'div', 'span', 'ol', 'ul', 'li', 'dl', 'dt', 'dd');
        text = text.removeTags('script', 'img', 'hr');
    } else {
        text = text.stripTags('a', 'div', 'span');
        text = text.removeTags('script', 'img', 'hr');
    }
    text = text.stripTags('table', 'tbody', 'tr', 'th', 'td', 'thead', 'h1', 'h2', 'h3','h4','h5', 'hr');
    text = text.remove(/<p>[ ]*<\/p>/g);
    text = text.remove(/<\/strong><strong>/g);
    text = text.remove(/<\/em><em>/g);
    text = text.replace(/\&nbsp\;/g, ' ');
    text = text.replace(/<\/strong>[ ]*<strong>/g, ' ');
    text = text.replace(/<\/em>[ ]*<em>/g, ' ');
    text = text.replace(/<li><p>/g, '<li>');
    text = text.replace(/<\/p><\/li>/g, '</li>');
    text = text.replace(/\\/g, '');
    
    //alert(text);
    
    return text;
}
function floatField(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().remove(/[^0-9\.\,]/g).replace(',', '.'));
        });
    });
}
function capitalize(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().capitalize());
        });
    });
}
function capitalizeAll(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().titleize());
        });
    });
}
function uppercase(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().toUpperCase());
        });
    });
}
function lowercase(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().toLowerCase());
        });
    });
}
function sanitize_regex(fields, regex) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().remove(regex));
        });
    });
}
function autoCheckEmail(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().trim());
            if($(this).val() != '') {
                if(!checkEmail($(this).val())) {
                    fancyAlert('Email non valida');
                    $(this).val('');
                }
            }
        });
    });
}
function checkEmail(email) {
    re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (email.match(re)) {
        return true;
    }
    return false;
}
function autoCheckSito(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().trim());
            if($(this).val() != '') {
                if(!checkSito($(this).val())) {
                    fancyAlert('Pagina internet non valida');
                    $(this).val('');
                }
            }
        });
    });
}
function checkSito(url) {
    re = /^http(s)?:\/\/[a-z0-9_\-\.]+(\.)[a-z]{2,4}/;
    if (url.match(re)) {
        return true;
    }
    return false;
}
function autoCheckCF(fields) {
    fields.forEach(function(field){
        field.change(function(){
            $(this).val($(this).val().trim());
            if($(this).val() != '') {
                if(!checkCF($(this).val()) && !checkPI($(this).val())) {
                    fancyAlert('Codie Fiscale non valido');
                    $(this).val('');
                }
            }
        });
    });
}
function checkCF(cf) {
    re = /^[a-zA-Z]{6}[0-9a-zA-Z]{2}[a-zA-Z]{1}[0-9a-zA-Z]{2}[a-zA-Z]{1}[0-9a-zA-Z]{3}[a-zA-Z]{1}$/;
    if (cf.match(re)) {
        return true;
    }
    return false;
}
function checkCAP(cap) {
    re = /^[0-9]{5}$/;
    if ((cap+'').match(re)) {
        return true;
    }
    return false;
}

function getMatchRegexp(subject, re) {
    var m = re.exec(subject);
    if (m == null) {
        return false;
    } else {
        var s = "";
        for (i = 0; i < m.length; i++) {
            s = s + m[i] + "\n";
        }
        return s;
    }
}

function setPlaceholder(field, value) {
    if(field.val() == '')
        field.defaultvalue(value);
}

/* CHECK VARIABILI */
function is_int(input){
    return typeof(input)=='number'&&parseInt(input)==input;
}
function is_string(input){
    return typeof(input)=='string';
}

function testVariabile(variabile) {
    return !(eval('window.'+variabile) === undefined);
}

function testFunction(funzione) {
    fx = eval('window.'+funzione);
    
    //alert(fx);
    
    if(fx === undefined) {
        return false;
    //  alert("UND");
    }
    //alert("def");
    return typeof(fx) == 'function';
    
}

function checkVariabili(exist) {
    if(!(testVariabile('noCheck') && noCheck)) {
        if(typeof exist == 'string') {
            if(eval('window.'+exist) === undefined) {
                alert("Definire '"+exist+"' per continuare");
            }
        }
        if(typeof exist == 'object') {
            exist.forEach(function(variabile){
                if(eval('window.'+variabile) === undefined) {
                    alert("Definire '"+variabile+"' per continuare");
                }
            });
        }
    }
}

function setCampiObbligatori(check, callback) {
    ok = true;
    check.forEach(function(campo_form){
        label = $('label[for="'+campo_form.attr('id')+'"]');       
        label.addClass('obbligatorio');
        campo_form.change(function(){
            checkCampiObbligatori(check, callback);
        }).keyup(function(){
            checkCampiObbligatori(check, callback);
        });
        ok = ok && campo_form.val().trim() != '';
    });
    checkCampiObbligatori(check, callback)
}

function checkCampiObbligatori(check, callback) {
    ok = true;
    check.forEach(function(campo_form){
        ok = ok && (campo_form.val().trim() != '');
    //alert(campo_form.attr('id')+' ('+(ok ? 1 : 0)+')');
    });
    eval(callback+'('+(ok?'true':'false')+')');
}

/***********
 * UTILITY *
 ***********/

function serialize(_obj) {
    if(_obj == null) 
        return 'null';
    // Let Gecko browsers do this the easy way
    if (typeof _obj.toSource !== 'undefined' && typeof _obj.callee === 'undefined')
    {
        return _obj.toSource();
    }
    // Other browsers must do it the hard way
    switch (typeof _obj)
    {
        // numbers, booleans, and functions are trivial:
        // just return the object itself since its default .toString()
        // gives us exactly what we want
        case 'number':
        case 'boolean':
        case 'function':
            return _obj;
            break;

        // for JSON format, strings need to be wrapped in quotes
        case 'string':
            return '\'' + _obj + '\'';
            break;

        case 'object':
            var str;
            if (_obj.constructor === Array || typeof _obj.callee !== 'undefined')
            {
                str = '[';
                var i, len = _obj.length;
                for (i = 0; i < len-1; i++) {
                    str += serialize(_obj[i]) + ',';
                }
                str += serialize(_obj[i]) + ']';
            }
            else
            {
                str = '{';
                var key;
                for (key in _obj) {
                    str += key + ':' + serialize(_obj[key]) + ',';
                }
                str = str.replace(/\,$/, '') + '}';
            }
            return str;
            break;

        default:
            return 'UNKNOWN';
            break;
    }
}

function fancyAlert(msg) {
    jQuery.fancybox({
        modal: true,
        padding: 3,
        margin: 0,
        content: "<div class=\"alert\"><h3>"+msg+"</h3><div style=\"text-align:center;margin-top:40px;\"><button class=\"button-orange large\" id=\"fancy-ok\" type=\"button\" onclick=\"jQuery.fancybox.close();\" >OK</button></div></div>"
    });
}

var fancyConfirmResult;
function fancyConfirm(msg,ok_txt,ko_txt,callback) {
    jQuery.fancybox({
        modal : true,
        padding: 3,
        margin: 0,
        content: "<div class=\"alert\"><h3>"+msg+"</h3><div style=\"text-align:right;margin-top:10px;\"><button id=\"fbc_ok\" onClick=\"javascript:fancyConfirmOk()\" class=\"button-orange large\" type=\"button\" value=\""+ok_txt+"\">"+ok_txt+"</button><button id=\"fbc_ko\" onClick=\"javascript:fancyConfirmKo()\" class=\"button-orange large\" type=\"button\" id=\"fancyConfirm_cancel\" value=\""+ko_txt+"\">"+ko_txt+"</button></div></div>",
        beforeClose : function() {
            eval(callback+"("+(fancyConfirmResult?"true":"false")+")");
        }
    });
}
function fancyConfirmOk() {
    fancyConfirmResult = true; 
    jQuery.fancybox.close();
}
function fancyConfirmKo() {
    fancyConfirmResult = false; 
    jQuery.fancybox.close();
}
 
function removeObj(id) {
    $(id).remove();
}

/* Funzione per i tabs */

function switch_tabs(obj, tabContent) {
    $(tabContent).hide();
    obj.closest('ul').children().children('a').removeClass("active");
    var id = obj.attr("rel");

    $('#'+id).show();
    obj.addClass("active");
}

function switch_class_tabs(obj, tabContent) {
    $(tabContent).hide();
    obj.closest('ul').children().children('a').removeClass("active");
    var id = obj.attr("rel");
    
    $('.'+id).show();
    obj.addClass("active");
}

/* caratteristiche dello spinner js */

/* spinner standard per i campi grandi */

var opts = {
    lines: 62, // The number of lines to draw
    length: 2, // The length of each line
    width: 2, // The line thickness
    radius: 6, // The radius of the inner circle
    color: '#333', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1/32
};

var optsbigdef = {
    lines: 64, // The number of lines to draw
    length: 4, // The length of each line
    width: 3, // The line thickness
    radius: 10, // The radius of the inner circle
    color: '#333', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1/32
};

var optsbig = {
    lines: 64, // The number of lines to draw
    length: 4, // The length of each line
    width: 2, // The line thickness
    radius: 10, // The radius of the inner circle
    color: '#fff', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1/8
};

var optsbigtab = { /* spinner grande per i tabs */
    lines: 64, // The number of lines to draw
    length: 8, // The length of each line
    width: 5, // The line thickness
    radius: 20, // The radius of the inner circle
    color: '#4C80B6', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1/8
};

var optsbigfancy = { /* spinner grande per i tabs */
    lines: 16, // The number of lines to draw
    length: 0, // The length of each line
    width: 5, // The line thickness
    radius: 24, // The radius of the inner circle
    color: '#fff', // #rgb or #rrggbb
    speed: 1, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: true, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1/8
};

var optsbar = {
    lines: 64, // The number of lines to draw
    length: 2, // The length of each line
    width: 2, // The line thickness
    radius: 6, // The radius of the inner circle
    color: '#fff', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1/8
};

/* spinner piccolo per i bottoni scuri */

var optsbut = {
    lines: 64, // The number of lines to draw
    length: 1, // The length of each line
    width: 2, // The line thickness
    radius: 4, // The radius of the inner circle
    color: '#fff', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1/8
};

/* spinner piccolo per i bottoni chiari */

var optsbutblack = {
    lines: 64, // The number of lines to draw
    length: 1, // The length of each line
    width: 2, // The line thickness
    radius: 4, // The radius of the inner circle
    color: '#333', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1/32
};

/* definizione variabili spinners */
/*
var spinnerDef = new Spinner(opts).spin(); // grigio medie dimensioni, da abbinare a "preloader" (uso di default)
var spinnerBigDef = new Spinner(optsbigdef).spin(); // grigio grande, da abbinare a "preloader"
var spinnerBig = new Spinner(optsbig).spin(); // bianco grande, da abbinare a "preloader-util"
var spinnerBigTab = new Spinner(optsbigtab).spin(); // bianco molto grande, da abbinare a "preloader-util" (usato per i grandi contenuti come i cambi di tab)
var spinnerBigFancy = new Spinner(optsbigfancy).spin(); // grigio molto grande, da abbinare Fancybox
var spinnerBar = new Spinner(optsbar).spin(); // da abbinare a "preloader-bar"
var spinnerBut = new Spinner(optsbut).spin(); // bianco piccolo, da abbinare a "preloader-button"
var spinnerButBlack = new Spinner(optsbutblack).spin(); // nero piccolo, da abbinare a "preloader-button"
*/
/*---------------------------*/

/*   attivazioni varie */

/*---------------------------*/

function showThis(idOfObj) {
    $(idOfObj).fadeIn(250);
    $('html').click(function() {
        $(idOfObj).fadeOut(250);
    });
}

loadContentCache = [];
loadContentPreload = [];
loadContenteReady = true;

function loadContent(url, target, idOfObj, param){
    if(!param) {
        param = {};
    }
    key = url+target+idOfObj+serialize(param);
    $(idOfObj).fadeIn(250).append('<div id="preloader-icon">&nbsp;</div>');
    if(loadContenteReady) {
        loadContenteReady = false;
        if(!loadContentCache.find(key)) {
            loadContentCache.add(key);
            spinnerButBlack.spin();
            $('#preloader-icon').append(spinnerButBlack.el);
            $.ajax({
                url: url,
                data: param,
                context: document.body,
                success: function(data){
                    loadContentPreload[key] = data;
                    $(target).html(data);
                    spinnerButBlack.stop();
                    removeObj('#preloader-icon');
            
                    $('html').click(function() {
                        $(idOfObj).fadeOut(250);
                    });
                    $(target).click(function(event){
                        event.stopPropagation();
                    });
                    
                    loadContenteReady = true;
                } 
            })
        } else {
            $(target).html(loadContentPreload[key]);
            $('html').click(function() {
                $(idOfObj).fadeOut(250);
            });
            $(target).click(function(event){
                event.stopPropagation();
            });
            loadContenteReady = true;
        }
    }
}

function setPositionBallon(_baloon, _target) {
    baloon = $('#'+_baloon);
    target = $('#'+_target).offset();
    baloon.offset({
        left: target.left, 
        top: target.top + 10
    });
}
function scrollTo(o,s){
    var d = $(o).offset().top;
    $("html:not(:animated),body:not(:animated)").animate({
        scrollTop: d
    }, s, 'swing'); 
}


is_explorer = navigator.appName=="Microsoft Internet Explorer";

function removeFile(url) {
    $.ajax({
        url: url,
        type: 'DELETE',
        success: function(msg) {
        //alert(msg);
        }
    });
}

function attivaFancybox() {
    $('.fancybox').fancybox({
        hideOnOverlayClick: false,
        transitionIn: 'elastic',
        padding:3,
        margin:0
    });
}

$(document).ready(function(){
    attivaFancybox();
    $(".autogrow").autoGrow();
});