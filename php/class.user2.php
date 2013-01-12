<?php

class User extends Singleton
{	
	public $id;			//Current user ID
	public $username;	//Signed username
	public $signed;		//Boolean, true = user is signed-in
	
	protected function __construct()
	{
		$this->signed = false;
		$this->id = null;
		$this->username = null;
	}
		
	// se sobreescribe ya que la instancia debe guardarse en la sesion.
	public static function getInstance()
	{
		if (!isset($_SESSION[__CLASS__]))
			$_SESSION[__CLASS__] = new static;

		return $_SESSION[__CLASS__];
	}

	public function login($user, $password)
	{
		// Validar datos
		//Validate::string($user, array());
		
		// Comprobar datos
		$db = Database::getInstance();
		$res = $db->query('SELECT id_user, username, password, salt FROM users WHERE username = ? LIMIT 1', array($user));
		
		if ($res && $res[2] === hash('sha256', $password.$res[3]))
		{
			$this->id = $res[0];
			$this->username = $res[1];
			$this->signed = true;
		}
	}
	
	public function logout($data)
	{
		$this->__construct();
	}
	
	public function register($data)
	{
		// Validar datos
		
		
		// Generar hash
		$salt = '|'.hash('sha256', uniqid(rand().'Smil3:)', false), false); 
		$password = hash('sha256', $password.$salt);
		
		// Insertar en la base de datos
		$db = Database::getInstance();
		$sql = 'INSERT INTO users (username, password, salt, email, name, birthdate, sex, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?);';
		$arg = array($username, $password, $salt, $email, $name, $birthdate, $sex, $country);
		$res = $db->query($sql, $arg);
		return true;
	}
	
	public function update($data)
	{
		// Validar datos
		
		
		// Insertar en la base de datos
		$db = Database::getInstance();
		$sql = 'INSERT INTO users (username, password, salt, email, name, birthdate, sex, country, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);';
		$arg = array($user);
		$res = $db->query($sql, $arg);
	}
	
	public function pass_reset($data)
	{
		// Enviar email de confirmación
		// con un 
	}
	
	public function pass_change($data)
	{

	}
	
	
	
	
}


// http://www.php.net/manual/en/function.crypt.php
// http://stackoverflow.com/questions/4795385/how-do-you-use-bcrypt-for-hashing-passwords-in-php

?>