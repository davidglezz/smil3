<?php

$specialActions['register'] = function()
{
	global $user;
	//Proccess Registration

	count($_POST) OR die('13'); // TODO: count($_POST) == x

	isset($_POST['aceptTOS']) OR die('14');
	unset($_POST['aceptTOS']);

	$_POST['birthdate'] = $_POST['birthdate_year'].'-'.$_POST['birthdate_month'].'-'.$_POST['birthdate_day'];
	unset($_POST['birthdate_year']);
	unset($_POST['birthdate_month']);
	unset($_POST['birthdate_day']);

	//Register User
	if (!$user->register($_POST))
	{
		var_dump($user->console);
		die(json_encode($user->error()));
	}

	// TODO: enviar email de confirmación

	// no errors
	die('0');
};
	
$specialActions['login'] = function()
{
	global $user;
	//Proccess Login
	isset($_POST['username']) OR die('15');
	isset($_POST['password']) OR die('16');

	$auto = isset($_POST['auto']) ? $_POST['auto'] : false;
		
	$user->login($_POST['username'], $_POST['password'], $auto);
	
	if ($user->has_error())
	{
		var_dump($user->console);
		die(json_encode($user->error()));
	}

	die('0');
};

$specialActions['activate'] = function()
{
	global $user;
	count($_POST) OR die('20');
	isset($_POST['c']) OR die('21');

	$hash = $_POST['c'];
	unset($_POST['c']);

	// Activar cuenta
	$user->activate($hash);
	die('0');
};

$specialActions['resetPasswd'] = function()
{
	global $user;
	count($_POST) OR die('17');

	$res = $user->pass_reset($_POST['email']);
		
	$res OR die('18');
	//Hash succesfully generated

	// TODO: Send an email to $res['email'] with the URL+HASH $res['hash']
	// to enter the new password.
	// $url = "../?page=change-password&c=" . $res['hash'];
	/*mail($res['email'], 'Cambia de contraseña', 'Pulsa el enlace para continuar <a href="{$res["hash"]}">{$res["hash"]}</a>');
    
    $nombre = $_POST['nombre'];
    $mail = $_POST['mail'];
    $empresa = $_POST['empresa'];
    $header = 'From: ' . $mail . " \r\n";
    $header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
    $header .= "Mime-Version: 1.0 \r\n";
    $header .= "Content-Type: text/plain";
    
    $mensaje = "Este mensaje fue enviado por " . $nombre . ", de la empresa " . $empresa . " \r\n";
    $mensaje .= "Su e-mail es: " . $mail . " \r\n";
    $mensaje .= "Mensaje: " . $_POST['mensaje'] . " \r\n";
    $mensaje .= "Enviado el " . date('d/m/Y', time());

    $para = 'info@tusitio.com';
    $asunto = 'Contacto desde Taller Webmaster';
    mail($para, $asunto, utf8_decode($mensaje), $header);
    echo '&estatus=ok&';*/
    
    
	// redirigir a la pagina de cambiar contraseña
};

// cambia la contraseña si la olvidaste
$specialActions['changePasswd'] = function()
{
	global $user;
	count($_POST) OR die('20');
	isset($_POST['c']) OR die('21');

	$hash = $_POST['c'];
	unset($_POST['c']);

	// TODO: validar y comprobar contraseña
	$user->new_pass($hash, $_POST);
	die('0');
};


$actions['special'] = function()
{
	global $specialActions;
	isset($_GET['that']) OR die('11');
	isset($specialActions[$_GET['that']]) OR die('12');
	$specialActions[$_GET['that']]();
};


$actions['logout'] = function()
{
	global $user;
	$user->logout();
	die('0');
};
		
$actions['changePasswd'] = function()
{
	global $user;
	//Proccess Password change
	count($_POST) OR die('19');

	// TODO: validar y comprobar contraseña
	$user->update($_POST);

	$user->has_error() AND die($user->error());
	die('0');
};

$actions['userUpdate'] = function()
{
	global $user;
	//Proccess Update
	count($_POST) OR die('19');
		
	foreach($_POST as $name=>$val)
		if($user->data[$name] == $val)
			unset($_POST[$name]);

	//Update info
	if (count($_POST))
	{
		$user->update($_POST);

		//If there are errors
		$user->has_error() AND die($user->error());
	}
		
	die('0');
};

$actions['getStartInfo'] = function()
{
	global $user;
	$data = $user->data;
	unset($data['password']);
	unset($data['activated']);
	unset($data['confirmation']);
	unset($data['signed']);

	// TODO: Mensages, otras notificaciones


	die(json_encode($data)); 
	
};

$actions['deleteAccount'] = function()
{
	// TODO
};

/* publications functions **************************************/

$actions['getPub'] = function()
{
	
};

$actions['delPub'] = function()
{
	// TODO
};

$actions['sendPub'] = function()
{
	//$a = stripslashes($b);
	// strip_tags();
	$txt = htmlspecialchars(mysql_real_escape_string($_POST['$txt']));
	$query = 'INSERT INTO  publications (user, text) VALUES ( 4,  "'. $txt .'");';
	
	// TODO
};

/* Private messages functions **************************************/

$actions['getMsg'] = function()
{
	// TODO
};

$actions['delMsg'] = function()
{
	// TODO
};

$actions['sendMsg'] = function()
{
	//$a = stripslashes($b);
	// strip_tags();
	$msg = htmlspecialchars(mysql_real_escape_string($_POST['$msg'])); 
	// TODO
};

/*  **************************************/

$actions['setProfilePhoto'] = function()
{
	empty($_FILES) AND die('30');
	$path = $_SERVER['DOCUMENT_ROOT'] . '/user/'. $user->username . 'jpg';
	move_uploaded_file($_FILES['Filedata']['tmp_name'], $path) OR die('31');

	die('0');
};

?>