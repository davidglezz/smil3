<?php

/**
 * Session class
 * @author David Gonzalez <davidgg666@gmail.com>
 * @version 1.2.1
 * @date 20/11/2012
 */

define('SES_EXPIRATIONTIME', '1000'); // ~16.6 min


class Session
{
	public static function start($expire = true)
	{
		if (!isset($_SESSION))
			session_start();
			
		$fingerprint = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
		
		if (!isset($_SESSION['fingerprint']))
		{
			session_regenerate_id(true);
			$_SESSION['fingerprint'] = $fingerprint;
			$_SESSION['expirationTime'] = $expire ? time() + SES_EXPIRATIONTIME : 0;
		}
		else
		{
			if ($_SESSION['fingerprint'] != $fingerprint) 
			{
				// posible robo de sesion!!
				$_SESSION['renew'] = true;
				self::deleteCookie();
				// TODO: ban ip (si mas de 2 o 3 intentos)
				die();
			}
			
			if (isset($_SESSION['renew']))
			{
				session_regenerate_id(true);
				unset($_SESSION['renew']);
			}
			
			if ($_SESSION['expirationTime'] != 0)
			{
				if ($_SESSION['expirationTime'] < time())
				{
					// TODO: puede evitar que se mande un mensage, tener en cuenta
					// Logout y pedir login;
					self::end();
					self::start();
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
		self::deleteCookie();

		// Destruir la sesión.
		return session_destroy();
	}
	
	private static function deleteCookie()
	{
		if (!ini_get("session.use_cookies"))
			return;
		
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params['path'],
				$params['domain'], $params['secure'], $params['httponly']);
	}


}


?>
