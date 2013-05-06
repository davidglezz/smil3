var app = function()
{
    var user = {};
    var dest = null;

    // Alertas
    var alerts = (function()
    {
        var container = $('#alerts');
        var template = $('<div class="alert"><button type="button" class="close" data-dismiss="alert">×</button><span></span></div>');

        return {
            'show': function(msg, type) //type: error, success, info
            {
                template.find('span').html(msg);
                if (type) template.addClass('alert-' + type);
                template.clone().appendTo(container);
            },
            'clear': function(){
                container.find('.alert').remove();
            }
        }
    })();


    // Templates
    var template = (function()
    {
        var load = function(name, callback)
        {
            var file = 'templates/' + name + '.html';

            $.ajax({
                url: file,
                dataType: 'text',
                success: function(tmplData)
                {
                    $.templates(name, tmplData);

                    console.log('Cargada plantilla: ' + name);

                    if (typeof(callback) == 'function')
                        callback();
                }
            });

        }
        return {
            'load' : load
        }
    })()


    // View Controller
    var view = (function()
    {
        var curent = 'loadingMsg';
        var views = {
            'loadingMsg' : $('#loadingMsg')
        };

        return {

            register: function(name, controller)
            {
                views[name] = controller;
            },

            show: function(id)
            {
                if (curent != id)
                {
                    views[curent].hide();
                    views[id].show();
                    curent = id;
                }
            }
        }
    })();


    // Router
    var router = new Staterouter({
        '/' : function(){
            mainApp.changeTab('home');
        },

        '/profile' : function(){
            console.warn('Se intenta acceder a /profile')
        },

        '/profile/:id' : function(p){
            mainApp.profile.show(p);
        },

        '/messages' : function(){
            mainApp.changeTab('messages');
        },

        '/messages/:id': function(){
        },

        '/notifications': function(){
            mainApp.changeTab('notifications');
        },

        '/login': function(){
            view.show('login');
        },

        '/logout': function(){
            // Por seguridad seria mejor recargar la pagina
            // para que no queden datos del anterior usuario.
            view.show('loadingMsg');
            Smil3.logout(function() {
                router.navigate('/login');
            });
        },

        '/settings': function(){
            mainApp.changeTab('settings');
            mainApp.settings.showTab('profile');
        },

        '/settings/profile': function(){
            self.settings.showTab('profile');
        },

        '/settings/account': function(){
            self.settings.showTab('account');
        },

        '/settings/password': function(){
            self.settings.showTab('password');
        },

        '/settings/notifications': function(){
            self.settings.showTab('notifications');
        },

        '/settings/lists': function(){
            self.settings.showTab('lists');
        },

        '/help': function(){
            self.settings.showTab('help');
        },

        '/about': function(){
            self.settings.showTab('about');
        },

        '/register': function()
        {
            view.show('register');
        },

        '/forgotPassword': function(){
            self.chView($('#forgotPassword'));
        }
    });

    // Login
    var login = (function()
    {
        var container, loginForm, loginBtn;

        var load = function()
        {
            template.load('login', function()
            {
                $($.trim($.render.login())).insertAfter('#loadingMsg');

                container = $('#login');
                loginForm = $('#login form:first').submit(onSubmit);
                loginBtn = $('#login input[type="submit"]').button();

                login.show = show;
                login.hide = hide;

                login.show();
            });
        }

        var show = function()
        {
            container.show();
        }

        var hide = function()
        {
            container.hide();
        }

        var onSubmit = function(e)
        {
            e.preventDefault();
            loginBtn.button('loading');

            Smil3.login(loginForm.serialize(), onLogin)

            return false;
        }

        var onLogin = function()
        {
            Smil3.getStartInfo(onGetStartInfo);
            $('#login input[type="text"], #login input[type="password"]').val('');
            loginBtn.button('reset');
            alerts.clear();
        }

        Smil3.errorHandlers[10] = function()
        {
            dest = router.getRoute();
            router.navigate('/login');
        }

        Smil3.errorHandlers[27] = Smil3.errorHandlers[26] = Smil3.errorHandlers[25] = function()
        {
            $('#login input[type="password"]').val('');
            alerts.show('Usuario o contraseña incorrecto.', 'error');
            loginBtn.button('reset');
        }

        return {
            'show': load,
            'hide': function(){}
        }
    })();

    view.register('login', login);


    // Register
    var register = (function()
    {
        var container, registerForm, registerBtn;
        var validatePass_msg = ['Muy debil', 'Debil', 'Aceptable', 'Segura', 'Muy segura', 'Demasiado segura', 'Corta. Mínimo 6 caracteres.'];
        var validatePass_colors = ['#DD514C', '#FAA732', '#5EB95E', '#5EB95E', '#4BB1CF', '#0E90D2', '#ccc'];

        var load = function()
        {
            template.load('register', function()
            {

                $($.trim($.render.register())).insertAfter('#loadingMsg');

                $('#register [rel=popover]').popover({
                    'trigger':'focus',
                    'placement':'right'
                });

                $('#p1').popover({
                    'trigger':'focus',
                    'placement':'right',
                    'content': validatePassContent
                });

                container = $('#register');
                registerForm = $('#register form:first').submit(onSubmit);
                console.log(registerForm)
                registerBtn = $('#register input[type="submit"]').button();

                var p1 = document.getElementById('p1');
                var p2 = document.getElementById('p2');

                p1.oninput = function ()
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

                p2.oninput = function ()
                {
                    p2.setCustomValidity(p1.value != p2.value ? 'Las contraseñas deben coincidir.' : '');
                }

                register.show = show;
                register.hide = hide;

                register.show();
            });
        }

        var onSubmit = function(e)
        {
            e.preventDefault();
            registerBtn.button('loading');
            Smil3.register(registerForm.serialize(), onRegister)
            return false;
        }

        var onRegister = function()
        {
            registerForm[0].reset();
            registerBtn.button('reset');
            alerts.show('Usuario registrado correctamente.', 'success');
            router.navigate('/login');
        }

        var show = function()
        {
            container.show();
        }

        var hide = function()
        {
            container.hide();
        }

        var checkPassword = function(P)
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

        var validatePassContent = function ()
        {
            var p1 = document.getElementById('p1');
            var nPerc = checkPassword(p1.value);
            var state = p1.value.length >= 6 ? Math.floor(nPerc / 20) : 6;
            p1.setCustomValidity(state == 6 ? 'Contraseña demasiado corta.' : '');
            return '<div id="passInfo">'+validatePass_msg[state]+'</div><div class="progress"><div id="passMeter" class="bar bar-danger" style="width: '+nPerc+'%;background-color:'+validatePass_colors[state]+'"></div></div>';
        }

        Smil3.errorHandlers[13] = Smil3.errorHandlers[14] = Smil3.errorHandlers[15] = Smil3.errorHandlers[16] = Smil3.errorHandlers[17] = Smil3.errorHandlers[18] = Smil3.errorHandlers[19] = Smil3.errorHandlers[20] = Smil3.errorHandlers[21] = Smil3.errorHandlers[22] = Smil3.errorHandlers[23] = function(d)
        {
            alerts.show('Algun dato incorrecto.', 'error');
            registerBtn.button('reset');
        }

        return {
            'show': load,
            'hide': function(){}
        }
    })();

    view.register('register', register);


    // Main App
    var mainApp = (function()
    {
        var container;
        var navbar;
        var content;

        var visible = false;

        var changeTab = function(id)
        {
            if (!visible)
                view.show('mainApp');

            content.find('.tab-pane.active').removeClass('active');
            navbar.find('li.active > a[href^="/"]').parent().removeClass('active');
            $('#' + id).addClass('active');
            var selector = (id == 'home' ? 'a[href="/"]' : 'a[href^="/' + id + '"]');
            navbar.find(selector).parent().addClass('active');
        };

        var load = function()
        {
            template.load('mainApp', function()
            {
                $($.trim($.render.mainApp(user))).insertAfter('#loadingMsg');

                container = $('#mainApp');
                navbar = container.find('.navbar-fixed-top');
                content = $('#main-content');

                mainApp.show = show;
                mainApp.hide = hide;

                router.navigate(dest || '/');
                dest = null;

                mainApp.show();

                // preload needed templates
                template.load('post', post.init());
                template.load('profile', profile.init());

                postWriter = postWriter();
            });
        }

        var show = function()
        {
            container.show();
            visible = true;
        }

        var hide = function()
        {
            container.hide();
            visible = false;
        }

        var profile = (function()
        {
            var container;

            var init = function()
            {
                container = $('#profile');
            }

            var onLoaded = function(data)
            {
                container.html($.render.profile(data));
                mainApp.changeTab('profile');
            }

            var show = function(user)
            {
                Smil3.getProfile(user, onLoaded)
            }

            //$($.trim($.render.mainApp(user))).insertAfter('#loadingMsg');
            return {
                'show': show,
                'init': init
            };
        })();


        $.views.helpers({
            unix2time: function(time) {
                var dt = new Date(time*1000);
                return dt.toLocaleDateString("es-ES") + ' ' + dt.toLocaleTimeString();
            }
        });

        var post = (function()
        {
            var container;

            var init = function()
            {
                container = $('#posts');

                Smil3.getPosts({}, onLoaded);
            }

            var onLoaded = function(data)
            {
                $($.trim($.render.post(data.posts))).appendTo(container);
            }

            var show = function(user)
            {
                Smil3.getProfile(user, onLoaded)
            }

            //$($.trim($.render.mainApp(user))).insertAfter('#loadingMsg');
            return {
                'show': show,
                'init': init
            };
        })();

        // Publish - new
        var postWriter = function(){

            var popup;
            var el;
            var submitBtn;
            var textarea;

            var init = function()
            {
                popup = $('#new_publish');
                el = popup.parent();
                submitBtn = popup.find('button[type="submit"]').button();
                textarea = popup.find('textarea');
            }

            init();

            var show = function()
            {
                el.addClass('open');
                textarea.focus();

                $('body').one('click', function() { // probar a poner hide a secas
                    hide();
                });
            }

            var hide = function()
            {
                el.removeClass('open');
            // TODO: remove .one Click event
            }

            popup.click(function(e)
            {
                e.stopPropagation();
            });

            el.find('a').click(function(e)
            {
                e.preventDefault();
                e.stopPropagation();
                el.hasClass('open') ? hide() : show();
            });

            submitBtn.click(function(e)
            {
                e.preventDefault();
                submitBtn.button('loading');

                Smil3.publish(textarea.serialize(), function(d){
                    hide();
                    textarea.val('');
                    submitBtn.button('reset');
                });
            });

            popup.find('button[type="button"]').click(function()
            {
                hide();
                textarea.val('');
                submitBtn.button('reset');
            });

            return {
                'show': show,
                'hide': hide,
                'init': init
            };
        };

        // Settings

        var settings = function()
        {
            var container, sidenav, tabContent;

            var load = function()
            {
                template.load('settings', function()
                {
                    $($.trim($.render.settings(user))).insertAfter('#loadingMsg');

                    container = $('#settings');
                    sidenav = container.find('.sidenav');
                    tabContent = container.find('.tab-content');

                    settings.show = show;
                    settings.hide = hide;

                    mainApp.changeTab('settings');
                });
            }

            var onLoaded = function(data)
            {
                container.html($.render.profile(data));
                mainApp.changeTab('profile');
            }

            var show = function(user)
            {
                Smil3.getProfile(user, onLoaded)
            }

            var showTab = function(id)
            {
                changeTab('settings');
                sidenav.find('.active').removeClass('active');
                tabContent.find('.active').removeClass('active');
                $('#s-' + id).addClass('active');
                sidenav.find('a[href="/settings/' + id + '"]').parent().addClass('active');
            }


            var profileFotoIn = $('#profileFotoIn');
            var profileFotoForm = profileFotoIn.parent();
            var progressBar = profileFotoForm.find('.bar').eq(0);
            var profileFoto = profileFotoForm.prev();

            var progressHandleFn = function(e)
            {
                if(e.lengthComputable)
                {
                    var value = e.loaded * 100 / e.total;
                    if (value > 100) value = 100;
                    if (value < 0) value = 0;
                    progressBar.css({
                        'width': value+'%'
                    });
                }
            }

            profileFotoIn.change(function()
            {
                var file = this.files[0];

                if (!file)
                    return;

                if (file.size > 8000000)
                {
                    self.alert.show('El archivo es demasiado grande. Máximo 8MB');
                    profileFotoIn.val('');
                    return;
                }

                var formData = new FormData(profileFotoForm[0]);

                $.ajax({
                    'url': self.api + '?do=updatePhoto',
                    'type': 'POST',
                    'data': formData,
                    'xhr': function()
                    {
                        myXhr = $.ajaxSettings.xhr();
                        if(myXhr.upload) // check if upload property exists
                        {
                            progressBar.css({
                                'width': '0%'
                            });
                            profileFotoForm.find('div.progress').show(100);
                            myXhr.upload.addEventListener('progress',progressHandleFn, false);
                        }
                        return myXhr;
                    },
                    //Ajax events
                    'beforeSend':  function(e){
                        console.log('beforeSend', e)
                    },
                    'success': function(e)
                    {
                        profileFotoForm.find('div.progress').hide(250);
                        // TODO: load thumb
                        profileFotoIn.val('');
                        profileFoto.attr('src', profileFoto.attr('src') + '?' + (new Date).getTime());

                    },
                    'error': function(){
                        console.log('error')
                    },
                    //Options to tell JQuery not to process data or worry about content-type
                    'cache': false,
                    'contentType': false,
                    'processData': false
                });
            });

            return {
                'show': show,
                'init': init
            };
        }

        return {
            'show': load,
            'hide': function(){},
            'changeTab': changeTab,
            'profile' : profile,
            'settings': settings
        }
    })();

    view.register('mainApp', mainApp);


    var otro = (function(){

        })();


    // Init
    var init = function()
    {
        // Router links handler
        $(document).on('click', 'a[href^="/"]', function(e) {
            e.preventDefault();
            router.navigate($(this).attr('href'));
            //e.stopPropagation();
            return false;
        });

        // ajax handler
        $.ajaxSetup({
            beforeSend: function() {
                $("#loading").show()
            },
            complete:   function() {
                $("#loading").hide()
            },
            error: function() {
                alerts.show('No se pudo acceder.', 'error');
            },
            cache: false
        });
    }


    var onGetStartInfo = function(data)
    {
        user = data;
        view.show('mainApp');
    }

    // Constructor
    init();
    Smil3.getStartInfo(onGetStartInfo);

}();




function parseTwit(str)
{
    //parse URL
    str = str.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&~\?\/.=]+/g,function(s){
        return s.link(s);
    });

    //parse user_name
    str = str.replace(/[@]+[A-Za-z0-9_]+/g,function(s){
        var user_name = s.replace('@','');
        return s.link("http://twitter.com/"+user_name);
    });

    //parse hashtag
    str = str.replace(/[#]+[A-Za-z0-9_]+/g,function(s){
        var hashtag = s.replace('#','');
        return s.link("http://search.twitter.com/search?q="+hashtag);
    });

    return str;
}









