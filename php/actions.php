<?php

$specialActions['register'] = function()
{
	isset($_POST) OR Response::sendError(13); // TODO: count($_POST) == x

	// Se deben aceptar los terminos de uso y de servicio
	isset($_POST['aceptTOS']) OR Response::sendError(14);

	// array con los datos del registro.
	$data = array();

	// Nombre de usuario
	Validate::string($_POST['username'], array('format' => 'a-zA-Z0-9_', 'min_length' => 3, 'max_length' => 30)) OR Response::sendError(15);
	$data['username'] = $_POST['username'];

	// Contraseña
	Validate::string($_POST['password'], array('min_length' => 6, 'max_length' => 25)) OR Response::sendError(16);
	$data['password'] = $_POST['password'];

	// Email
	$_POST['email'] = trim($_POST['email']);
	Validate::email($_POST['email']) OR Response::sendError(17);
	$data['email'] = $_POST['email'];

	// Nombre
	Validate::string($_POST['name'], array(/*'format' => VALIDATE_EALPHA.VALIDATE_SPACE,*/ 'min_length' => 6, 'max_length' => 75)) OR Response::sendError(18);
	$data['name'] = $_POST['name'];

	// Fecha (YYYY-MM-DD) //TODO verificar que sea mayor de edad
	$data['birthdate'] = $_POST['birthdate_year'].'-'.$_POST['birthdate_month'].'-'.$_POST['birthdate_day'];
	Validate::date($data['birthdate'], array('format'=>'%Y-%m-%d')) OR Response::sendError(19);

	// Sexo (Male, Female)
	Validate::string($_POST['sex'], array('format' => 'MF', 'min_length' => 1, 'max_length' => 1)) OR Response::sendError('20');
	$data['sex'] = $_POST['sex'];

	// Pais (ES)
	Validate::string($_POST['country'], array('format' => VALIDATE_ALPHA_UPPER, 'min_length' => 2, 'max_length' => 2)) OR Response::sendError('21');
	$data['country'] = $_POST['country'];

	//Register User
	User::getInstance()->register($data) OR Response::sendError('22');

	// TODO: enviar email de confirmación
};

$specialActions['login'] = function()
{
	isset($_POST['username'], $_POST['password']) OR Response::sendError('25');
	Validate::string($_POST['username'], array('format' => 'a-zA-Z0-9_', 'min_length' => 3, 'max_length' => 30)) OR Response::sendError('26');
	User::getInstance()->login($_POST['username'], $_POST['password']) OR Response::sendError('27');
};

$specialActions['activate'] = function()
{
	isset($_GET, $_GET['c']) OR Response::sendError('30');

	$hash = $_GET['c'];
	unset($_GET['c']);

	Validate::string($hash, array('format' => VALIDATE_ALPHA.VALIDATE_NUM, 'min_length' => 1, 'max_length' => 1)) OR Response::sendError('31');

	User::getInstance()->activate($hash) OR Response::sendError('32');
};

$specialActions['resetPasswd'] = function()
{
	isset($_POST) OR Response::sendError('40');

	$res = $user->pass_reset($_POST['email']);

	$res OR Response::sendError('18');
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
	count($_POST) OR Response::sendError('20');
	isset($_POST['c']) OR Response::sendError('21');

	$hash = $_POST['c'];
	unset($_POST['c']);

	// TODO: validar y comprobar contraseña
	$user->new_pass($hash, $_POST);
};


$actions['special'] = function()
{
	global $specialActions;
	isset($_GET['that']) OR Response::sendError('11');
	isset($specialActions[$_GET['that']]) OR Response::sendError('12');
	$specialActions[$_GET['that']]();
};


$actions['logout'] = function()
{
	User::getInstance()->logout();
};

$actions['changePasswd'] = function()
{

	//Proccess Password change
	count($_POST) OR Response::sendError('19');

	// TODO: validar y comprobar contraseña
	//$user->update($_POST);
};

$actions['userUpdate'] = function()
{
	$user = User::getInstance();

	//Proccess Update
	count($_POST) OR Response::sendError('19');

	foreach($_POST as $name => $val)
		if($user->data[$name] == $val)
			unset($_POST[$name]);

	//Update info
	if (count($_POST))
		$user->update($_POST);
};

$actions['getStartInfo'] = function()
{
	$user = User::getInstance();

	$data['user'] = array($user->id, $user->username, $user->name);
	$data['msgs'] = 0;
	$data['notif'] = 0;
	Response::add($data);
};

$actions['deleteAccount'] = function()
{
	// TODO
};

/* publications functions **************************************/

$actions['getPub'] = function()
{
	isset($_POST['id']) OR Response::sendError('111');

	$txt = $_POST['$txt'];

	$sql = 'SELECT publications (user, text) VALUES ( ?,  ?);';
	$params =  array(4, $txt);

	Database::getInstance()->query($sql, $params);
};

$actions['delPub'] = function()
{
	isset($_GET['pid']) OR Response::sendError('71');
	is_numeric($_GET['pid']) OR Response::sendError('72');
	// La consulta no hace falta que sea preparada
	$sql = 'DELETE FROM publications WHERE id_publication=? AND user=? LIMIT 1;';
	$args = array(intval($_GET['pid']), User::getInstance()->id);
	Database::getInstance()->query($sql, $args);
};

$actions['publish'] = function()
{
	isset($_POST['pub']) OR Response::sendError('60');
	$user = User::getInstance();
	$sql = 'INSERT INTO publications (user, text, time) VALUES ( ?,  ?, ?);';
	$pub = htmlspecialchars($_POST['pub']);
	$params =  array($user->id, $pub, time());
	Database::getInstance()->query($sql, $params);
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
	empty($_FILES) AND Response::sendError('30');
	$username = User::getInstance()->username;
	$path = $_SERVER['DOCUMENT_ROOT'] . '/user/'. $username . '.jpg';
	$_FILES['file']['error'] AND Response::sendError('31');
	move_uploaded_file($_FILES['file']['tmp_name'], $path) OR Response::sendError('32');
};

$actions['userUpdateField'] = function()
{
	//global $updateFieldFn;
	isset($_GET['field'], $_GET['value']) OR Response::sendError('19');
	//isset($updateFieldFn[$_GET['field']]) OR Response::sendError('20');
	//$updateFieldFn[$_GET['field']]();

	// TODO: Validar
	User::getInstance()->update($_GET['field'], $_GET['value']);

	$sql = 'UPDATE users SET '.$_GET['field'].'=? WHERE id_user=54 LIMIT 1;';
	$db = Database::getInstance();
	$res = $db->query($sql, array($_GET['value']), PDO::FETCH_ASSOC);
};


$updateFieldFn['username'] = function()
{
	$user = User::getInstance();

	$sql = "UPDATE users SET `activated`=1, `web`='www.davidxl.es', `bio`='Me gusta la Smile. ', `work`='Estudiante' WHERE  `id_user`=45 LIMIT 1;";
};


$actions['getProfile'] = function()
{
	isset($_GET['user']) OR Response::sendError('81');
	Validate::string($_GET['user'], array('format' => 'a-zA-Z0-9_', 'min_length' => 3, 'max_length' => 30)) OR Response::sendError('82');

	$sql = 'SELECT  id_user,  username, email, name, birthdate, sex, country, city, last_login, reg_date, web, bio, work, showMail, showBirth FROM users WHERE username=? LIMIT 1;';
	$db = Database::getInstance();
	$profile = $db->query($sql, array($_GET['user']),PDO::FETCH_ASSOC);

	isset($profile[0]) or Response::sendError('83');
	$profile = $profile[0];

	if ($profile['showMail'] !== '1')
		unset($profile['email']);

	if ($profile['showBirth'] !== '1')
		unset($profile['birthdate']);

	unset($profile['showBirth'], $profile['showMail']);

	$sql = 'SELECT name FROM relations LEFT JOIN lists ON  id_list = list WHERE userA=? AND userB=? LIMIT 1;';
	$res = $db->query($sql, array(User::getInstance()->id , $profile['id_user'] ));

	$profile['relation'] = count($res) ? ($res[0][0] === null ? '-' : $res[0][0]) : null;

	// TODO: Lista de seguidores y los que sigue

	Response::add($profile);
};


/* ***************************************** */

$actions['follow'] = function()
{
	isset($_GET['uid']) OR Response::sendError('85');
	is_numeric ($_GET['uid']) OR Response::sendError('86');

	// TODO: list support

	$sql = 'INSERT INTO `relations` (`userA`, `userB`) VALUES (?, ?);';
	$db = Database::getInstance();
	$res = $db->query($sql, array(User::getInstance()->id, intval($_GET['uid'])));

	$res !== false OR Response::sendError('87');
};

$actions['unfollow'] = function()
{
	isset($_GET['uid']) OR Response::sendError('85');
	is_numeric ($_GET['uid']) OR Response::sendError('86');

	$sql = 'DELETE FROM relations WHERE userA=? AND userB=? LIMIT 1;';
	$db = Database::getInstance();
	$db->query($sql, array(User::getInstance()->id, intval($_GET['uid'])));
};


?>