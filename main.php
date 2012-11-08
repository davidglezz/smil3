<?php

/*
	main.php
	Se encarga de recibir todas las peticiones y enviar las respuestas.
	Se entiende que las peticiones son realizadas mediante ajax,
	por lo que las respuestas no son documentos si no codigos de error/suceso
	y datos pedidos que casi siempre estaran en formato json.
*/
include('php/config.php');

isset($_GET['do']) OR die(8);

$user->signed OR $_GET['do'] == 'special' OR die(10);

isset($actions[$_GET['do']]) OR die(9);
$actions[$_GET['do']]();

// ------------------------------------------------------------

$actions['special'] = function()
{
	isset($_GET['that']) OR die(11);
	isset($actions['special'][$_GET['that']]) OR die(12);
	$actions[$_GET['that']]();
	
};

$actions['special']['register'] = function()
{
	//Proccess Registration
	count($_POST) OR die(13);
	
	//Register User
	$user->register($_POST) OR die($user->error());

	// no errors
	die(0);
};
	
$actions['special']['login'] = function()
{
	//Proccess Login
	count($_POST) OR die(14);
	isset($_POST['username']) OR die(15);
	isset($_POST['password']) OR die(16);

	$auto = isset($_POST['auto']) ? $_POST['auto'] : false;
		
	$user->login($_POST['username'], $_POST['password'], $auto);
	
	$user->has_error() AND die($user->error());

	die(0);
};

$actions['special']['activate'] = function()
{
	count($_POST) OR die(20);
	isset($_POST['c']) OR die(21);

	$hash = $_POST['c'];
	unset($_POST['c']);

    // Activar cuenta
	die(0);
};

$actions['special']['resetPasswd'] = function()
{
	count($_POST) OR die(17);

	$res = $user->pass_reset($_POST['email']);
		
	$res OR die(18);
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
$actions['special']['changePasswd'] = function()
{
	count($_POST) OR die(20);
	isset($_POST['c']) OR die(21);

	$hash = $_POST['c'];
	unset($_POST['c']);

	// TODO: validar y comprobar contraseña
	$user->new_pass($hash, $_POST);
	die(0);
};

$actions['logout'] = function()
{
    $user->logout();
    die(0);
};
		
$actions['changePasswd'] = function()
{
	//Proccess Password change
	count($_POST) OR die(19);

	// TODO: validar y comprobar contraseña
	$user->update($_POST);

	$user->has_error() AND die($user->error());
	die(0);
};

$actions['userUpdate'] = function()
{
	//Proccess Update
	count($_POST) OR die(19);
		
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
		
	die(0);
};

$actions['deleteAccount'] = function()
{
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
	// TODO
};

/*  **************************************/

$actions['setProfilePhoto'] = function()
{
    empty($_FILES) AND die(30);
    
    //$file_name = $_FILES['Filedata']['name'];   
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
    $targetFile =  str_replace('//','/',$targetPath) . $file_name; 
     
    move_uploaded_file($_FILES['Filedata']['tmp_name'], $targetFile) OR die(31);
    
    die(0);
};


?>
