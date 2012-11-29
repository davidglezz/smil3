<?php

class User
{
	static $instance;
public static function getInstance()
{
	if (!(self::$instance instanceof self))
		self::$instance=new self();
	
	return self::$instance;
}
   

/*private static $instance; 
  public static function getInstance() { 
    if(!isset(self::$instance)) { 
      $c = __CLASS__; 
      self::$instance = new $c(); 
    } 
    return self::$instance; 
  }*/
   
	function login()
	{
		if ($db_hash === hash('sha256', $db_salt.$password))
		{
			
		}
	}
	
	function register()
	{
		$salt = '|'.sha256(uniqid(rand(), false)); // O incluso mejor si tuviese mayúsculas, minúsculas, caracteres especiales...
		$hash = hash('sha256', $salt.$password); // Puede ponerse delante o detrás, es igual
		unset($password);
		
		// Guardar en base de datos el $hash y $salt
	}
	
}

?>