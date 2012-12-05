
var feedback = (function(){
	var style = document.createElement('style');
	style.innerText = '#feedback{position:fixed;top:50%;left:-5.2em;background-color:#bbb;padding:5px 10px;border-radius:0 5px 5px 0;color:#fff;text-shadow:0 1px 0 #000;text-decoration:none;-moz-transition:all .7s;-webkit-transition:all .7s;-o-transition:all .7s;transition:all .7s}#feedback:hover{left:0;background-color:#888}';
	document.head.appendChild(style);
	
	var dlg = document.createElement('div');
	dlg.setAttribute('class','modal hide');
	dlg.setAttribute('id','feedbackDialog');
	dlg.innerHTML =  '<form style="margin:0" id="feedbackForm" method="post" action="main.php?do=special&amp;that=feedback"><div class="modal-header"><h3>¡Vaya! Solucionemos este error</h3></div><div class="modal-body"><textarea style="width:97%" id="description-text" name="feedbackDescription" rows="10" placeholder="Descripción del problema" required ></textarea><div id="privacy-note">Se enviará el nombre y la versión de tu navegador y la dirección url actual junto con la información que hayas decidido proporcionar. Esta información se utilizará para la mejora del diagnóstico de incidencias y de la red social. Toda la información personal que envíes, ya sea de forma explícita o accidental, se protegerá de acuerdo con nuestras políticas de privacidad.<strong> Al enviar esta información, aceptas que SMIL3 puede utilizar la información que proporciones para mejorar.</strong></div></div><div class="modal-footer"><input type="button" class="btn btn-large" value="Cancelar" /><input type="submit" class="btn btn-large btn-primary" value="Enviar" data-loading-text="Enviando..." /></div></form>';
	document.body.appendChild(dlg);
	
	var btn = document.createElement('a');
	btn.setAttribute('id','feedback');
	btn.setAttribute('title','feedback');
	btn.setAttribute('href','feedback.htm');
	btn.innerHTML =  'Feedback <i class="icon-bell icon-white"></i>';
	document.body.appendChild(btn);
	
	var $dlg = $(dlg);
	$(btn).click(function(){
		$dlg.modal('show');
		document.getElementById('description-text').focus();
		return false;
	});
	
	$dlg.find('input[type="submit"]').get(0).click(function(){
		alert('ok');
	})
	
})();


