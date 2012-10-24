<?php
//mysqli_report(MYSQLI_REPORT_OFF);

if (!isset($_GET['f']))
	die(4);

$fn = $_GET['f'];
	
if ($fn = 'logout')
{
	session_unset();
    session_destroy();
	die(0);
}

if ($fn = 'login')
{
	session_unset();
    session_destroy();
    session_start();
	
	// TODO: Comprobar email
	
	$db = new mysqli('localhost', 'root', 'admin', 'music');
	if (mysqli_connect_errno())
		die('1');
	
	// TODO: Comprobar que se ejcuto correctamente la consulta & store_result()
	$query = $db->prepare('SELECT id_user, name, level FROM user WHERE email = ? AND passw = ? LIMIT 1');
	$mail = $_POST['mail'];
	$query->bind_param('ss', $mail, sha1($_POST['pass']));
    $query->execute();
	//$query->store_result();
    $query->bind_result($res);
    $query->fetch();
    
    if($db->numRows($res) != 1)
	{
		$query->close();
		$db->close();
		die(2);
	}
    
	// TODO: Registrar inicio de sesion
    $_SESSION['uid'] = $res['id_user'];
	$_SESSION['name'] = $res['name'];
	$_SESSION['mail'] = $mail;
	$_SESSION['level'] = $res['level'];
    $_SESSION['expire'] = time() + 300;
	
    // TODO: Liberar $res
    $query->close();
	$db->close();
	die(0);
}

if ($fn = 'register')
{
	// TODO: Comprobar datos de entrada [js], verificacion de email mas simple
	$mail = strtolower($_POST['mail']);
	preg_match('/^([a-z0-9\._]+)\@([a-z0-9\.-]+)\.([a-z]{2,4})/', $mail) or die(11);
	
	$passw = $_POST['pass'];
	strlen($passw) > 5 or die(12);
	$passw = sha1($passw);
	
	$name = $_POST['name'];
	strlen($passw) > 5 or die(13);
	
	// TODO: Check birthday date
	$birthday = $_POST['birthday'];

	$sex = $_POST['sex'];
	$sex == 'm' or $sex == 'f' or die(15);
	
	$key = md5(time());
	
	$db = new mysqli('localhost', 'root', 'admin', 'music');
	if (mysqli_connect_errno())
		die('1');
		
	// TODO: Send confirmation mail
	
	$query = $db->prepare('INSERT INTO unconfirmedUsers(email, passw, name, birthday, sex, key, regdate) VALUES(?, ?, ?, ?, ?, ?, NOW())');
	$query->bind_param('ssssss', $mail, $passw, $name, $birthday, $sex, $key);
    $query->execute();
	
    if($db->affected_rows != 1)
	{
		$query->close();
		$db->close();
		die(5);
	}
	
    $query->close();
	$db->close();
	die(0);
}

if ($fn = 'confirm')
{
	$db = new mysqli('localhost', 'root', 'admin', 'music');
	if (mysqli_connect_errno())
		die('1');
	
	$query = $db->prepare('SELECT id_unconfirmedUser FROM user WHERE email = ? AND key = ? LIMIT 1');
	$mail = strtolower($_POST['mail']);
	$query->bind_param('ss', $mail, $_POST['key']);
    $query->execute();
	//$query->store_result();
    $query->bind_result($res);
    $query->fetch();
    
    if($db->numRows($res) != 1)
	{
		$query->close();
		$db->close();
		die(7);
	}
	
	$id = $res['id_unconfirmedUser'];
	$query->close();
	
	$db->query('INSERT INTO users(email, passw, name, birthday, sex, regdate) SELECT email, passw, name, birthday, sex, NOW() FROM unconfirmedUsers WHERE id_unconfirmedUser = $id LIMIT 1');
		
	if($db->affected_rows != 1)
	{
		$db->close();
		die(7);
	}
	
	$db->query('DELETE FROM unconfirmedUsers WHERE id_unconfirmedUser = $id');
	
	$db->close();
	die(0);
}

if (!isset($_SESSION) or $_SESSION['expire'] > time());
{
	session_unset();
    session_destroy();
    
    //session_start();
	
	// TODO: guardar contexto para continuar despues de autentificarse
    
    die(3);
}

$db = new mysqli('localhost', 'davidgg666', 'zzzzzz', 'music');

if (mysqli_connect_errno())
	die('1');

function end($code)
{
	$db->close();
	die($code);
}


	
		
		/*case "user_add":
			if($_SESSION['tipo']=="A"){
				if($_GET["a"])
				{
					$dat = mysql_fetch_assoc(mysql_query('SELECT * FROM `newusers` WHERE `id` = "' . $_GET["u"] . '" LIMIT 1'));
					mysql_query('INSERT INTO `users` (`nombre` ,`ape1` ,`email` ,`pass` ,`tipo` ,`fechaing`) VALUES ("'.$dat["first_name"].'", "'. $dat["last_name"] .'","'. $dat["email"].'", "'. $dat["pass"] .'", "C", NOW( ))') or die ('1');
				}
			mysql_query('DELETE FROM `newusers` WHERE `id` = "' . $_GET["u"] . '" LIMIT 1');
			echo ('0');
			}else{die('2');};
		break;
		case "user_del":
			if($_SESSION['tipo']=="A"){
			mysql_query('DELETE FROM `users` WHERE `id` = "' . $_GET["u"] . '" LIMIT 1');
			echo ('0');
			}else{die('2');};
		break;
		case "user_new":
			$pass=md5($_POST['pass']);
			if(mysql_num_rows(mysql_query('SELECT * FROM users WHERE email=' .$_POST['mail']))){
				echo ('5');
			} else {
				mysql_query('INSERT INTO newusers (first_name, last_name, comentario, email, pass) VALUES ("'.$_POST["nombre"].'", "'. $_POST["ap1"].'", "'. $_POST["com"].'", "'. $mail.'" ,"'. $pass.'")') or die('1');
				echo ('0');
			}
		break;
		case "user_cpass":
			if(isset($_GET["uid"]) && $_SESSION['tipo']=="A"){
				mysql_query('UPDATE `users` SET `pass` = \'' . md5($_POST['newp1']) . '\' WHERE `users`.`id` = "'.$_GET["uid"].'" LIMIT 1;') or die("Fallo en la base de datos");
				echo ('0');
			}else{
				$a = mysql_fetch_assoc(mysql_query('SELECT `pass` FROM `users` WHERE `id` = "' . $_SESSION['userid'] . '" LIMIT 1'));
				if(md5($_POST['oldpass']) == $a["pass"]){
					mysql_query('UPDATE `users` SET `pass` = \'' . md5($_POST['newp1']) . '\' WHERE `users`.`id` = '.$_SESSION['userid'].' LIMIT 1;') or die('1');
					echo ('0');
				}else{
					echo ('6');
				}	
			}
		break;
		case "user_edit":
			if($_SESSION['tipo']=="A" && isset($_GET["uid"])){
				mysql_query('UPDATE `users` SET `dni` = \''.$_POST["dni"].'\', `nombre` = \''.$_POST["nombre"].'\', `ape1` = \''.$_POST["ape1"].'\', `ape2` = \''.$_POST["ape2"].'\', `nacido` = \''.$_POST["nacido"].'\', `movil` = \''.$_POST["movil"].'\', `telf` = \''.$_POST["telf"].'\', `telfvillo` = \''.$_POST["telfvillo"].'\', `ciudad` = \''.$_POST["ciudad"].'\', `cp` = \''.$_POST["cp"].'\', `dir` = \''.$_POST["dir"].'\', `email` = \''.$_POST["email"].'\', `tipo` = \''.$_POST["tipo"].'\' WHERE `users`.`id` = '.$_GET["uid"].' LIMIT 1;') or die('1');
				echo ('0');
			}elseif($_SESSION['logged']=="1"){
				mysql_query('UPDATE `users` SET `dni` = \''.$_POST["dni"].'\', `nombre` = \''.$_POST["nombre"].'\', `ape1` = \''.$_POST["ape1"].'\', `ape2` = \''.$_POST["ape2"].'\', `nacido` = \''.$_POST["nacido"].'\', `movil` = \''.$_POST["movil"].'\', `telf` = \''.$_POST["telf"].'\', `telfvillo` = \''.$_POST["telfvillo"].'\', `ciudad` = \''.$_POST["ciudad"].'\', `cp` = \''.$_POST["cp"].'\', `dir` = \''.$_POST["dir"].'\', `email` = \''.$_POST["email"].'\' WHERE `users`.`id` = '.$_SESSION['userid'].' LIMIT 1;') or die('1');
				echo ('0');
			}else{
				echo('7');
			};
		break;
		case "user_get":
			if($_SESSION['tipo']=="A" && isset($_GET["uid"])){
				$data = mysql_fetch_assoc(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$_GET["uid"].'"'));
				echo (json_encode($data));	
			}elseif($_SESSION['logged']=="1"){
				$data = mysql_fetch_assoc(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$_SESSION['userid'].'"'));
				echo (json_encode($data));
			}else{
				echo('7');
			};
		break;*/
		
		
		
		/*
		 	case "dowload":
			if (!isset($_GET['f']) || empty($_GET['f']))
			{
				exit();
			}
			$root = "media/";
			$file = basename($_GET['f']);
			$path = $root.$file;
			$type = '';	 
			is_file($path) or exit();
			$size = filesize($path);
			if (function_exists('mime_content_type')) {
				$type = mime_content_type($path);
			} else if (function_exists('finfo_file')) {
				$info = finfo_open(FILEINFO_MIME);
				$type = finfo_file($info, $path);
				finfo_close($info); 
			}
			if ($type == '') $type = "application/force-download";
			header("Content-Type: $type");
			header("Content-Disposition: attachment; filename=$file");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: $size");
			readfile($path);
			break;
		 * */
        

?>
