<?php

/*
 * Session class
 */

define('SES_EXPIRATIONTIME', '1000'); // ~16.6 min 


class session
{
    
	public static function start()
	{
		$fingerprint = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
		if (!isset($_SESSION))
		{
			session_start();
			session_regenerate_id(true);
			$_SESSION['fingerprint'] = $fingerprint;
			$_SESSION['expirationTime'] = time() + SES_EXPIRATIONTIME;
		}
		else
		{
			if (isset($_SESSION['renew']))
			{
				// TODO: renew ses_id
				unset($_SESSION['renew']);
			}
			
			if ($_SESSION['fingerprint'] != $fingerprint) 
			{
				// posible robo de sesion!!
				$_SESSION['renew'] = true;
				die();
			}
			
			if (isset($_SESSION['expirationTime']))
			{
				if ($_SESSION['expirationTime'] > time())
				{
					// TODO: puede evitar que se mande un mensage, tener en cuenta
					// Logout y pedir login;
					die ('234');
				}
				else
				{
					$_SESSION['expirationTime'] = time() + SES_EXPIRATIONTIME;
				}
			}
		}
	}
	
	public static function end()
	{
		// Destruir todas las variables de sesión.
		$_SESSION = array();

		// Borrar la cookie de sesión.
		if (ini_get("session.use_cookies"))
		{
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}

		// Destruir la sesión.
		return session_destroy();
	}
	
}


// session_name();
// session_id();
?>
