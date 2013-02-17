<?php

class User extends Singleton
{	
	public $id;			//Current user ID
	public $username;	//Signed username
	public $signed;		//Boolean, true = user is signed-in
	public $name;		// Full name
	
	protected function __construct()
	{
		$this->signed = false;
		$this->id = $this->username = $this->name = null;
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
		if ($this->signed)
			$this->__construct();
		
		$db = Database::getInstance();
		$sql = 'SELECT id_user, username, password, salt, name FROM users WHERE username = ? LIMIT 1';
		$res = $db->query($sql, array($user));
		
		if (!$res) // || empty($res)
			return false;
		
		if ($res[0][2] === hash('sha256', $password.$res[0][3]))
		{
			$this->id = $res[0][0];
			$this->username = $res[0][1];
			$this->name = $res[0][4];
			$this->signed = true;
			return true;
		}
		else
		{
			// TODO
			// Log login fail.
			return false;
		}
	}
	
	public function logout()
	{
		$this->__construct(); // No deberia hacer falta
		Session::end();
	}
	
	public function register($data)
	{
		// Generar hash y salt
		$data['salt'] = hash('sha256', uniqid(rand().'Smil3:)', false), false); 
		$data['password'] = hash('sha256', $data['password'].$data['salt']);
		$data['reg_date'] = time();
		// Insertar en la base de datos
		$db = Database::getInstance();
		$sql = 'INSERT INTO users (username, password, salt, email, name, birthdate, sex, country, reg_date) VALUES (:username, :password, :salt, :email, :name, :birthdate, :sex, :country, :reg_date);';
		
		$res = $db->query($sql, $data);
		
		return $res !== false;
	}
	
	public function update($key, $value)
	{
		$sql = "UPDATE users SET $key=:$key WHERE id_user=$this->id LIMIT 1;";
		$res = Database::getInstance()->query($sql, array($key => $value));
		return $res !== false;
	}
	
	public function checkExistUser($user)
	{
		$db = Database::getInstance();
		$sql = 'SELECT id_user FROM users WHERE username = ? LIMIT 1';
		$res = $db->query($sql, array($user));
		return $res !== false && !empty($res);
	}
	
	public function pass_reset($data)
	{
		// Enviar email de confirmación con un codigo.
	}
	
	public function pass_change($data)
	{

	}
	
	
	
	
}


// http://www.php.net/manual/en/function.crypt.php
// http://stackoverflow.com/questions/4795385/how-do-you-use-bcrypt-for-hashing-passwords-in-php

?>