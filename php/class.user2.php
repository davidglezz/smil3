<?php

class User extends Singleton
{
	var $id;		//Current user ID
	var $username;	//Signed username
	var $signed;	//Boolean, true = user is signed-in

	function login()
	{
		if ($db_hash === hash('sha256', $db_salt.$password))
		{
			
		}
	}
	
	function register($data)
	{
		$salt = '|'.sha256(uniqid(rand().'Smil3', false)); // O incluso mejor si tuviese mayúsculas, minúsculas, caracteres especiales...
		$hash = hash('sha256', $password.$salt);
		unset($password);
		
		// Guardar en base de datos el $hash y $salt
	}
	
}

?>