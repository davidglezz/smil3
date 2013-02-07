/* Nano Templates (Tomasz Mazur, Jacek Becela) */
//http://jsfiddle.net/UXZDy/1/
(function($){
  $.nano = function(template, data) {
    return template.replace(/\{([\w\.]*)\}/g, function (str, key) {
      var keys = key.split("."), value = data[keys.shift()];
      $.each(keys, function () { value = value[this]; });
      return (value === null || value === undefined) ? "" : value;
    });
  };
})(jQuery);

var Smil3 = function()
{
	var self = this;
	
	self.user = {};
				
	// Util functions
	var getRoute = function()
	{
		var link = document.createElement("a");
		link.href = History.getState().url;
		return link.pathname;
	}
				
	var getStartInfo = function()
	{
		$.post('main.php?do=getStartInfo', {}, function(data, status, jqXHR)
		{
			if (data === '10')
			{
				// Obtenemos la direccion a la que se queria acceder y la guardamos para ir despues de identificarse.
				self.dest = getRoute();
				self.router.navigate('/login');
				return;
			}

			data = jQuery.parseJSON(data);
			
			console.log(data);

			//self.user = data.user;
			$('#username').text(data.user[2]);
			
			var mainApp = $('#mainApp');
			var profileLink = mainApp.find('.navbar a[href^="/profile"]');
			profileLink.attr('href', profileLink.attr('href') + '/' + data.user[1]);
	
			self.chView(mainApp);
			self.router.perform();

		}, 'text')
		.error(function() {
			console.warn('Error de conexion');
		})
		.complete(function() {	});
	}
				
	// Alertas
	self.alert = (function()
	{
		var container = $('#alerts');
		var template = $('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button><span></span></div>');
					
		return {
			'container': container,
			'show': function(msg){
				template.find('span').html(msg);
				template.clone().appendTo(container);
			},
			'delete': function(){
				container.find('.alert').remove();
			}
		}
	})();
	
	
	// Vistas
	var currView = $('#loadingMsg');
				
	self.chView = function(el)
	{
		if (el === currView)
			return;

		currView.hide();
		currView = el.show();
	}
				
				
	// Login
	var loginElement = $('#login');
	var loginForm = $('#login form:first');
	var loginBtn = $('#login input[type="submit"]').button();
				
	loginForm.submit(function(e)
	{
		e.preventDefault();
		loginBtn.button('loading');

		$.ajaxSetup({
			cache: false
		});

		var data = loginForm.serialize();
			
		$.post('main.php?do=special&that=login', data, function(data, status, jqXHR)
		{
			if (data === '0')
			{
				$('#login input[type="text"], #login input[type="password"]').val('');
				getStartInfo();
				var goTo = self.dest || '/';
				self.dest = null;
				self.router.navigate(goTo);
			}
			else
			{
				$('#login input[type="password"]').val('');
				self.alert.show('Usuario o contraseña incorrecto.');
			}
						
		}, 'text')
		.error(function() {
			console.warn('Error en la peticion');
		})
		.complete(function() {
			loginBtn.button('reset');
		});

		return false; 
	});
				

	// MainApp
	var mainApp = $('#mainApp');
	var mainNavbar = mainApp.find('.navbar');
	var mainContent = $('#main-content');
				
	var changeTab = function(id)
	{
		$('#main-content > .active').removeClass('active');
		mainNavbar.find('li.active > a[href^="/"]').parent().removeClass('active');
		$('#' + id).addClass('active');
		mainNavbar.find('a[href="/' + id + '"]').parent().addClass('active');
	};
				
	// Main/Publish
	var publishPopup = $('#new_publish');
	var publishCont = publishPopup.parent();
	var publishBtn = publishPopup.find('button[type="submit"]').button();
				
				
	publishPopup.click(function (e) {
		e.stopPropagation();
	});
		
	publishCont.find('a').click(function(e)
	{
		e.preventDefault();
		e.stopPropagation();
	
		if (publishCont.hasClass('open')) {
			publishCont.removeClass('open');
			// TODO: remove .one Click event
			return;
		}
					
		publishCont.addClass('open');
		publishPopup.find('textarea').focus();
					
		$('body').one('click', function() {
			publishCont.removeClass('open');
		});
	});
				
	publishBtn.click(function(e)
	{
		e.preventDefault();
		publishBtn.button('loading');
		$.ajaxSetup({
			cache: false
		});
			
		var data = publishPopup.find('textarea').serialize();
			
		$.post('main.php?do=publish', data, function(data, status, jqXHR)
		{
			console.log(data);
			if (data === '0')
			{
				$('#new_publish textarea').val('');
			}
			else
			{
				// TODO: show error
				console.warn('No se ha podido publicar.')
			}
		}, 'text')
		.error(function() {
			console.warn('No se ha podido publicar.')
		})
		.complete(function() {
			publishBtn.button('reset');
		});
			
		publishCont.removeClass('open');
	});
		
	publishPopup.find('button[type="button"]').click(function()
	{
		publishCont.removeClass('open');
	});
				
	// Main/Profile
	
	self.profile = (function(){
		var conainer = $('profile');
		
		return {
			'load' : function (u)
			{
				$.getJSON('main.php', {'do':'getProfile','user':u}, function(data, status, jqXHR)
				{
					console.log(data);
					
					if (data === '0')
					{
						data = jQuery.parseJSON(data);
					}
					else
					{
						// TODO: show error
						console.warn('No se ha podido.')
					}
				}, 'text')
				.error(function() {
					console.warn('No se ha podido.')
				})
				.complete(function() {
					
				});
			}
		};
	})();

	// Main/Settings
	var settingsSidenav = $('#settings .sidenav');
	var settingsTabContent = $('#settings .tab-content');
				
	self.settings = {
		'showTab': function(id)
		{
			changeTab('settings');
			settingsSidenav.find('.active').removeClass('active');
			settingsTabContent.find('.active').removeClass('active');
			$('#s-' + id).addClass('active');
			settingsSidenav.find('a[href="/settings/' + id + '"]').parent().addClass('active');
		},
		'init': function()
		{
			
		}
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
			progressBar.css({'width': value+'%'});
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
			'url': 'main.php?do=updatePhoto',
			'type': 'POST',
			'data': formData,
			'xhr': function()
			{
				myXhr = $.ajaxSettings.xhr();
				if(myXhr.upload) // check if upload property exists
				{
					progressBar.css({'width': '0%'});
					profileFotoForm.find('div.progress').show(100);
					myXhr.upload.addEventListener('progress',progressHandleFn, false);
				}
				return myXhr;
			},
			//Ajax events
			'beforeSend':  function(e){console.log('beforeSend', e)},
			'success': function(e)
			{
				profileFotoForm.find('div.progress').hide(250);
				// TODO: load thumb
				profileFotoIn.val('');
				profileFoto.attr('src', profileFoto.attr('src') + '?' + (new Date).getTime());
				
			},
			'error': function(){console.log('error')},
			//Options to tell JQuery not to process data or worry about content-type
			'cache': false,
			'contentType': false,
			'processData': false
		});
		
	

	});			
				
				
				
				
	// Router
	var routes = {
		'/' : function(){
			self.chView($('#mainApp'));
			$('#main-content > .active').removeClass('active');
			$('li.active > a[href^="/"]').parent().removeClass('active');
			$('#home').addClass('active');
			$('a[href="/"]').parent().addClass('active');
		},

		'/profile' : function(){
			console.warn('Se intenta acceder a /profile')
		},

		'/profile/:id' : function(p){
			self.profile.load(p);
			changeTab('profile');
		},

		'/messages' : function(){
			changeTab('messages');
		},

		'/messages/:id': function(){
		},

		'/notifications': function(){
			changeTab('notifications');
		},

		'/login': function(){
			self.chView($('#login'));
		},

		'/logout': function(){
			// Por seguridad seria mejor recargar la pagina para que no queden datos del anterior usuario.
			self.chView($('#loadingMsg'));

			$.get('main.php?do=logout', function() {
				self.router.navigate('/login');
			});
		},

		'/settings': function(){
			self.settings.showTab('profile');
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

		'/register': function(){
			self.chView($('#register'));
		},

		'/forgotPassword': function(){
			self.chView($('#forgotPassword'));
		}
	};
				
	self.router = new Staterouter(routes);
				
	// Router links handler
	$(document).on('click', 'a[href^="/"]', function(e) {
		e.preventDefault();
		self.router.navigate($(this).attr('href'));
		//e.stopPropagation();
		return false;
	});
				
	// INIT
	// TODO: comprobar si se quiere acceder a una pagina que no hace falta estar identificado.
	getStartInfo()
	//console.log($.jStorage.storageAvailable());
	//$('body').tooltip({	selector: '[rel=tooltip]'});
				

				
	// ajax handler
	$.ajaxSetup({
		beforeSend: function() {
			$("#loading").show()
		},
		complete:   function() {
			$("#loading").hide()
		},
		error: function() {
			shelf.alert.show('No se pudo acceder');
		}
	});
				
	return self;
};
		
// Creamos la instancia.
var app = new Smil3();
			

			
/*
var activeList = 0;

var uptade = function()
{
	$.post('main.php?do=getPub', {}, function(data, status, jqXHR)
	{
		console.log(data);

		data = jQuery.parseJSON(data);
		console.log(data);
		$('#username').text(data.name)

	}, 'text')
	.error(function() { alert("error"); })
	.complete(function() {	});
}

var updateTimer = setInterval(update, 30000);





App.Helpers.checkSyncStatus = function() {
  if (App.get('syncCheck')) { return; }

  var check = function() {
    $.ajax('/sync_status', {
      dataType: 'json',
      success: function(resp) {
        if (resp.status === 'done') {
          App.Helpers.reloadUser(function() {
            clearInterval(App.get('syncCheck'));
            App.set('syncCheck', null);
          });
        }
      }
    });
  };

  App.set('syncCheck', setInterval(check, 1000));
};
 */
