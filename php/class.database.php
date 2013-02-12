<?php

/*
 * Database class
 * Se encarga de gestionar las conexiones con la base de datos
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'smil3');
define('DB_USER', 'smil3');
define('DB_PASS', 'smil3');

class Database extends Singleton
{

	public $connection;

	public function __construct()
	{
		try {
			$this->connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
		} catch (PDOException $e) {
			die("Failed to connect to database, [SQLSTATE] " . $e->getCode());
		}
	}

	public function __destruct()
	{
		$this->connection = null;
	}

	public function connect()
	{
		if (is_object($this->connection))
			return;

		__construct();
	}

	public function close()
	{
		self::$instance = null;
	}

	public function query($sql, $params = null, $fetchMode = PDO::FETCH_NUM)
	{
		$stmt = $this->connection->prepare($sql);

		if (!$stmt)
			return false;
		
		//$stmt->setFetchMode($fetchMode);

		$stmt->execute($params);

		if ($stmt->errorCode() > 0)
		{
			//var_dump($stmt->errorInfo());
			//$error = $stmt->errorInfo();
			//log("PDO({$error[0]})[{$error[1]}] {$error[2]}");
			return false;
		}

		$data = $stmt->fetchAll($fetchMode);
		
		/* Consume menos memoria
		$data = array();
		while ($row = $stmt->fetch(PDO::FETCH_NUM))
			$data[] = $row;
		 */

		//$stmt->closeCursor();
		//$stmt = null;
		
		return $data;//var_dump($data);
	}

	public function exec($name, $values = array())
	{
		$_rs = $this->query('CALL ' . $name, $values);

		return $_rs;
	}

}

// http://erlycoder.com/69/php-mysql-prepared-sql-statement-vs-sql-statement

/*
 * @TODO: probar metodos __construct() __destruct() connect() & close()
 * PDO::quote()
 */
?>