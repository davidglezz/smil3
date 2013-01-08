$(document).ready(function()
{
	var regBtn = $('input[type="submit"]')
	regBtn.button();

	$('#loginForm').submit(function() {
		regBtn.button('loading');

		// TODO: validar datos.

		$.ajaxSetup({ cache: false });

		var data = $("#loginForm").serialize();
		console.log(data);
		
		$.post('main.php?do=special&that=login', data, function(data, status, jqXHR)
		{
			if (data === '0')
				window.location = 'smil3.htm';
			
			$('<div class="alert alert-error span11"><button type="button" class="close" data-dismiss="alert">×</button>'+data+'</div>').appendTo($('.container'));
		}, 'text')
		.error(function() { alert("error"); })
		.complete(function() {
			regBtn.button('reset');
		});


		// Evitamos que se envie por el metodo tradicional.
		return false; 
	});
});