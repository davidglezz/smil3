function checkPassword(P)
{
    var len = P.length;

    var F = len * 2;

    if (P.match(/[a-z]/))
        F += 2;

    if (P.match(/[A-Z]/))
        F += 10;

    if (P.match(/\d+/))
        F += 10;

    if (P.match(/(.*[0-9].*[0-9].*[0-9])/))
        F += 14;

    if (P.match(/.[!,@,#,$,%,^,&,*,?,_,~]/))
        F += 10;

    if (P.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/))
        F += 14;

    if (P.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))
        F += 4;

    if (P.match(/([a-zA-Z])/) && P.match(/([0-9])/))
        F += 6;

    if (P.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[a-zA-Z0-9])/))
        F += 6;

    return F > 100 ? 100 : F;
}

function validatePassConfirm(p1, p2)
{
    p2.setCustomValidity(p1.value != p2.value ? 'Las contraseñas deben coincidir.' : '');
}

var validatePass_msg = ['Muy debil', 'Debil', 'Aceptable', 'Segura', 'Muy segura', 'Demasiado segura', 'Corta. Mínimo 6 caracteres.'];
var validatePass_colors = ['#DD514C', '#FAA732', '#5EB95E', '#5EB95E', '#4BB1CF', '#0E90D2', '#ccc'];

function validatePass(p1)
{
    var nPerc = checkPassword(p1.value);
    var state = p1.value.length >= 6 ? Math.floor(nPerc / 20) : 6;

    $('#passMeter').css({
        width: nPerc+'%',
        backgroundColor: validatePass_colors[state]
        });
    $('#passInfo').html(validatePass_msg[state]);

    p1.setCustomValidity(state == 6 ? 'Contraseña demasiado corta.' : '');
}

function validatePassContent()
{
    var p1 = document.getElementById('p1');
    var nPerc = checkPassword(p1.value);
    var state = p1.value.length >= 6 ? Math.floor(nPerc / 20) : 6;
    p1.setCustomValidity(state == 6 ? 'Contraseña demasiado corta.' : '');
    return '<div id="passInfo">'+validatePass_msg[state]+'</div><div class="progress"><div id="passMeter" class="bar bar-danger" style="width: '+nPerc+'%;background-color:'+validatePass_colors[state]+'"></div></div>';
}

$(document).ready(function() {

    $('[rel=popover]').popover({
        'trigger':'focus',
        'placement':'right'
    });
    $('#p1').popover({
        'trigger':'focus',
        'placement':'right',
        'content': validatePassContent
    });

    var regBtn = $('input[type="submit"]');
    regBtn.button();

    $('#regForm').submit(function() {
        regBtn.button('loading');

        // TODO: validar datos.

        $.ajaxSetup({
            cache: false
        });

        var data = $("#regForm").serialize();

        $.post('main.php?do=special&that=register', data, function(data, status, jqXHR)
        {
            /*if (data === '0')
					window.location = 'login.htm';*/

            $('<div class="alert alert-error span11"><button type="button" class="close" data-dismiss="alert">×</button>'+data+'</div>').appendTo($('.container'));
        }, 'text')
        .error(function() {
            alert("error");
        })
        .complete(function() {
            regBtn.button('reset');
        });


        // Evitamos que se envie por el metodo tradicional.
        return false;
    });
});
