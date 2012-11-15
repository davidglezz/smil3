<?php
	//include('inc/class.uFlex.php');
	
	//Instanciate the user object
	$user = new uFlex(false);
	
	//Add database credentials and information 
	$user->db['host'] = "localhost";
	$user->db['$user'] = "smil3";
	$user->db['pass'] = "smil3";
	$user->db['name'] = "smil3";
	
	
		
	//Starts the object by triggering the constructor
	$user->start();
	
	include('inc/functions.php');
?>