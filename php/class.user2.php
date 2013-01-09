<?php

class User extends Singleton
{
	var $id;		//Current user ID
	var $username;	//Signed username
	var $signed;	//Boolean, true = user is signed-in
	
	function login($data)
	{
		$db = Database::getInstance();
		$res = $db->query('select id_user, username, password, salt from users where email = ?', $data);
		if ($res && $hash === hash('sha256', $salt.$password))
		{
			
			
		}
	}
	
	function register($data)
	{
		// Para evitar que se pueda averiguar la contraseña.
		$salt = '|'.sha256(uniqid(rand().'Smil3:)', false)); 
		$password = hash('sha256', $password.$salt);
		
		// Guardar en base de datos el $hash y $salt
		
		
	}
	
}

?>