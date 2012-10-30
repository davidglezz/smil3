<?php
include('inc/class.uFlex.php');
include("config.php");

if(!$user->signed)
	redirect("./smil3.htm");

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>SMIL3</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="SMIL3" />
	<meta name="author" content="David Gonzalez, Tony Marín" />
	<link href="css/bootstrap.css" rel="stylesheet" />
	<style type="text/css">
		html, body {
			height: 100%;
		}
		
		body {
			padding-top: 60px;
			padding-bottom: 40px;
			
			/*background-color:#ffffff;
			background-image: -moz-radial-gradient(0% 0%, circle cover, #ffffff, #c2c2c2 100%);
			background-image: -webkit-radial-gradient(0% 0%, circle cover, #ffffff, #c2c2c2 100%);
			background-image: -o-radial-gradient(0% 0%, circle cover, #ffffff, #c2c2c2 100%);
			background-image: -ms-radial-gradient(0% 0%, circle cover, #ffffff, #c2c2c2 100%);
			background-image: radial-gradient(0% 0%, circle cover, #ffffff, #c2c2c2 100%);
			background-attachment: fixed;*/

		}
	</style>
	<link href="css/bootstrap-responsive.css" rel="stylesheet" />
	<link rel="shortcut icon" href="images/favicon.ico" />
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png" />
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
	
	<div class="tab-content container">
			
		<div class="hero-unit" id="about">
			<h1>Bienvenido a  SMIL3</h1>
			<p>SMIL3 es una red social moderna y gratis.</p>
			<p>Si todavia no eres miembro <a href="#register" class="btn btn-primary ">Regístrate »</a></p>
		</div>
		
		<div class="tab-pane active" id="login">
			<form class="form-horizontal">
				<fieldset>
					<legend>Identifícate</legend>
					<div class="control-group">
						<label class="control-label" for="user">E-mail</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="user">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="passwd">Contraseña</label>
						<div class="controls">
							<input type="password" class="input-xlarge"  id="passwd">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="optionsCheckbox"></label>
						<div class="controls">
							<label class="checkbox">
								<input type="checkbox" id="optionsCheckbox" value="option1">
								No cerrar sesión
							</label>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Iniciar sesión</button>
						<a class="pull-right" href="#forgottenPassword">No recuerdo mi contrasea.</a>
					</div>
				</fieldset>
			</form>
		</div>
		
		<div class="tab-pane" id="register">
			<form class="form-horizontal">
				<fieldset>
					<legend>Formulario de registro de nuevo usuario</legend>
					<div class="control-group">
						<label class="control-label" for="name">Nombre</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="name">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="mail">E-mail</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="mail">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="pass1">Contraseña</label>
						<div class="controls">
							<input type="password" class="input-xlarge" id="pass1">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="pass2">Confirmar contraseña</label>
						<div class="controls">
							<input type="password" class="input-xlarge" id="pass2">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="TOS">Aviso legal</label>
						<div class="controls">
							<textarea class="input-xlarge" id="TOS" rows="5">
En cumplimiento de lo dispuesto en la Ley Orgánica 15/1999, de 13 de diciembre, de Protección de Datos de Carácter Personal y su normativa de desarrollo, queremos informarle de que los datos recabados serán incorporados a un fichero propiedad de Editorial Edinumen S.L. con la finalidad de poder gestionar técnicamente los diferentes servicios puestos a su disposición, así como mantenerle informado de nuestras novedades, eventos, ofertas y servicios.
Podrá ejercer, en cualquier momento, sus derechos de acceso, rectificación, cancelación y oposición al tratamiento de sus datos, en los términos previstos legalmente a través de la siguiente dirección web: www.edinumen.es/bajas.</textarea>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="aceptTOS"></label>
						<div class="controls">
							<label class="checkbox">
								<input type="checkbox" id="aceptTOS" value="aceptTOS">
								He leído y acepto las condiciones
							</label>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Registrarme</button>
					</div>
				</fieldset>
			</form>
		</div>
		
		
		<div class="tab-pane" id="forgottenPassword">
			<form class="form-horizontal">
				<fieldset>
					<legend>Recuperar la contraseña</legend>
					<div class="control-group">
						<label class="control-label" for="umail">E-mail</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="umail">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="instructions">Instrucciones</label>
						<div class="controls">
							<textarea class="input-xlarge" id="instructions" rows="5">
Reciviras un email con un enlace de confirmacion.Una vez confirmes el sistema generar una contraseña aleatoria y te la enviara a tu email. Se recomienda cambiarla por una mas facil de recordar. Si no recives el email, acuerdate de mirar en la vandeja del spam.</textarea>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="aceptIns"></label>
						<div class="controls">
							<label class="checkbox">
								<input type="checkbox" id="aceptIns" value="aceptIns">
								He leído las instrucciones
							</label>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Recuperar contraseña</button>
					</div>
				</fieldset>
			</form>
		</div>
		
		<hr />
		
		<footer>
			<p>&copy; 2012 David Gonzalez, Tony Marín &#65410;</p>
		</footer>
	</div>

	<script src="js/jquery-1.8.2.min.js"></script> 
	<script src="js/bootstrap.min.js"></script> 
	<script>
		$('#myTab a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
		})
	</script>
</body>
</html>
