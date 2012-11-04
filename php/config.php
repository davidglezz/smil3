<?php
	include('class.user.php');
	
	$user = new user(false);

	// Si trabajamos en local usamos la base de datos local.
	if ($_SERVER['HTTP_HOST'] != 'smil3.tk')
	{
		$user->db['host'] = "localhost";
		$user->db['user'] = "smil3";
		$user->db['pass'] = "smil3";
		$user->db['name'] = "smil3";
	}

	//Starts the object by triggering the constructor
	$user->start();

	var_dump($user);
	
?>