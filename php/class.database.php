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

	private $connection;

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

	public function query($sql, $params = null)
	{
		$stmt = $this->connection->prepare($sql);

		if (!$stmt)
			return false;
		
		//$stmt->setFetchMode(PDO::FETCH_NUM);

		$stmt->execute($params);

		if ($stmt->errorCode() > 0) {
			//$error = $stmt->errorInfo();
			//log("PDO({$error[0]})[{$error[1]}] {$error[2]}");
			return false;
		}

		$data = $stmt->fetchAll(PDO::FETCH_NUM);
		/*$data = array();
		while ($row = $stmt->fetch(PDO::FETCH_NUM))
			$data[] = $row;*/

		//$stmt->closeCursor();
		//$stmt = null;
		return $data;
	}

	public function exec($name, $values = array())
	{
		$_rs = $this->query('CALL ' . $name, $values);

		return $_rs;
	}
	
	/*
	function check_field($field, $val, $err = false)
	{
		$res = $this->getRow(Array($field => $val));

		if($res)
		{
			$err ? $this->form_error($field,$err) : $this->form_error($field,"The $field $val exists in database");
			$this->report("There was a match for $field = $val");
			return true;
		}
		else
		{
			$this->report("No Match for $field = $val");
			return false;
		}
	}
	
	function getRow($args)
	{
		$sql = "SELECT * FROM :table WHERE :args LIMIT 1";

		$st = $this->getStatement($sql, $args);

		if(!$st) return false;

		if(!$st->rowCount()){
			$this->report("Query returned empty");
			return false;
		}

		return $st->fetch(PDO::FETCH_ASSOC);
	}
	
	function getStatement($sql, $args=false)
	{

		if ($args)
		{
			foreach ($args as $field => $val)
				$finalArgs[] = " {$field}=:{$field}";

			$finalArgs = implode(" AND", $finalArgs);

			if (strpos($sql, " :args"))
				$sql = str_replace(" :args", $finalArgs, $sql);
			else
				$sql .= $finalArgs;
		}

		//Replace the :table placeholder
		$sql = str_replace(" :table ", " users ", $sql);

		$this->report("SQL Statement: {$sql}"); //Log the SQL Query first

		if ($args)  //Log the SQL Query first
			$this->report("SQL Data Sent: [" . implode(', ', $args) . "]");

		//Prepare the statement
		$res = $this->db->prepare($sql);

		if($args) $res->execute($args);

		if($res->errorCode() > 0 ){
			$error = $res->errorInfo();
			$this->error("PDO({$error[0]})[{$error[1]}] {$error[2]}");
			return false;
		}

		return $res;
	}
*/
}

// http://erlycoder.com/69/php-mysql-prepared-sql-statement-vs-sql-statement

/*
 * @TODO: probar metodos __construct() __destruct() connect() & close()
 * PDO::quote()
 */
?>