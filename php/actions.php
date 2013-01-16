<?php

$specialActions['register'] = function()
{
	isset($_POST) OR die('13'); // TODO: count($_POST) == x
	
	// Se deben aceptar los terminos de uso y de servicio
	isset($_POST['aceptTOS']) OR die('14');
	
	// array con los datos del registro.
	$data = array();

	// Nombre de usuario
	Validate::string($_POST['username'], array('format' => 'a-zA-Z0-9_', 'min_length' => 3, 'max_length' => 30)) OR die('15');
	$data['username'] = $_POST['username'];
	
	// Contraseña
	Validate::string($_POST['password'], array('min_length' => 6, 'max_length' => 25)) OR die('16');
	$data['password'] = $_POST['password'];
	
	// Email
	Validate::email($_POST['email']) OR die('17');
	$data['email'] = $_POST['email'];
	
	// Nombre
	Validate::string($_POST['name'], array(/*'format' => VALIDATE_EALPHA.VALIDATE_SPACE,*/ 'min_length' => 6, 'max_length' => 75)) OR die('18');
	$data['name'] = $_POST['name'];
	
	// Fecha (YYYY-MM-DD) //TODO verificar que sea mayor de edad
	$data['birthdate'] = $_POST['birthdate_year'].'-'.$_POST['birthdate_month'].'-'.$_POST['birthdate_day'];
	Validate::date($data['birthdate'], array('format'=>'%Y-%m-%d')) OR die('19');
	
	// Sexo (Male, Female)
	Validate::string($_POST['sex'], array('format' => 'MF', 'min_length' => 1, 'max_length' => 1)) OR die('20');
	$data['sex'] = $_POST['sex'];	
			
	// Pais (ES)
	Validate::string($_POST['country'], array('format' => VALIDATE_ALPHA_UPPER, 'min_length' => 2, 'max_length' => 2)) OR die('21');
	$data['country'] = $_POST['country'];	

	//Register User
	User::getInstance()->register($data) OR die('22');

	// TODO: enviar email de confirmación

	// no errors
	die('0');
};
	
$specialActions['login'] = function()
{
	isset($_POST['username'], $_POST['password']) OR die('25');
	Validate::string($_POST['username'], array('format' => 'a-zA-Z0-9_', 'min_length' => 3, 'max_length' => 30)) OR die('26');
	User::getInstance()->login($_POST['username'], $_POST['password']) OR die('27');
	die('0');
};

$specialActions['activate'] = function()
{
	isset($_GET, $_GET['c']) OR die('30');
	
	$hash = $_GET['c'];
	unset($_GET['c']);
	
	Validate::string($hash, array('format' => VALIDATE_ALPHA.VALIDATE_NUM, 'min_length' => 1, 'max_length' => 1)) OR die('31');

	User::getInstance()->activate($hash) OR die('32');

	die('0');
};

$specialActions['resetPasswd'] = function()
{
	isset($_POST) OR die('40');

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
	User::getInstance()->logout();
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
	$user = User::getInstance();
	
	//Proccess Update
	count($_POST) OR die('19');
		
	foreach($_POST as $name => $val)
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
	$user = User::getInstance();
	
	$data['user'] = array($user->id, $user->username, $user->name);
	$data['msgs'] = 0;
	$data['notif'] = 0;

	die(json_encode($data)); 
	
};

$actions['deleteAccount'] = function()
{
	// TODO
};

/* publications functions **************************************/

$actions['getPub'] = function()
{
	isset($_POST['$id']) OR die('111');
	
	$txt = $_POST['$txt'];
	
	$sql = 'SELECT publications (user, text) VALUES ( ?,  ?);';
	$params =  array(4, $txt);
			
	$db->query($sql, $params);
	die('0');
};

$actions['delPub'] = function()
{
	// TODO
};

$actions['sendPub'] = function()
{
	isset($_POST['pub']) OR die('60');
	$user = User::getInstance();
	$sql = 'INSERT INTO publications (user, text, time) VALUES ( ?,  ?, ?);';
	$pub = htmlspecialchars($_POST['pub']);
	$params =  array($user->id, $pub, time());	
	Database::getInstance()->query($sql, $params);
	die('0');
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

$actions['updatePhoto'] = function()
{
	empty($_FILES) AND die('30');
	$path = $_SERVER['DOCUMENT_ROOT'] . '/user/'. $user->username . 'jpg';
	move_uploaded_file($_FILES['Filedata']['tmp_name'], $path) OR die('31');

	die('0');
};


$actions['getProfile'] = function()
{
	empty($_FILES) AND die('30');
	$path = $_SERVER['DOCUMENT_ROOT'] . '/user/'. $user->username . 'jpg';
	move_uploaded_file($_FILES['Filedata']['tmp_name'], $path) OR die('31');

	die('0');
};



?>