<?php

/*
Database class
Se encarga de gestionar las conexiones con la base de datos

*/

define('DB_HOST', '');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');

class Database
{
	var $connection = null;


	function connect()
	{
		if (is_object($this->connection))
			return true;

		/* Connect to an ODBC database using driver invocation */
		$user = $this->db['user'];
		$pass = $this->db['pass'];
		$host = $this->db['host'];
		$name = $this->db['name'];
		$dsn = $this->db['dsn'];

		if (!$dsn)
			$dsn = "mysql:dbname={$name};host={$host}";

		$this->report("Connecting to database...");

		try {
			$this->db = new PDO($dsn, $user, $pass);
			$this->report("Connected to database.");
		}catch(PDOException $e){
			$this->error("Failed to connect to database, [SQLSTATE] " . $e->getCode());
		}

		return is_object($this->db);
	}

	//Test field in database for a value
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

	//Executes SQL query and checks for success
	function check_sql($sql, $args=false)
	{
		$st = $this->getStatement($sql);

		if(!$st)
		{
			$this->report("No se obtuvo nada de la base de datos");
			return false;
		}
			

		if($args)
		{
			$st->execute($args);
			$this->report("SQL Data Sent: [" . implode(', ',$args) . "]"); //Log the SQL Query first
		}
		else
		{
			$st->execute();
		}

		$rows = $st->rowCount();

		if($rows > 0){
			//Good, Rows where affected
			$this->report("$rows row(s) where Affected");
			return true;
		}else{
			//Bad, No Rows where Affected
			$this->report("No rows were Affected");
			return false;
		}
	}

	//Get a single user row depending on arguments
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

	/*
	 * Get the PDO statment
	*/
	function getStatement($sql, $args=false)
	{
		if (!$this->connect())
		{
			$this->report("No se a podido conectar a las base de datos.");
			return false;
		}


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

	// para usar con prepare
	function getPDOConstantType($var)
	{
		if( is_int( $var ) )
			return PDO::PARAM_INT;
		if( is_bool( $var ) )
			return PDO::PARAM_BOOL;
		if( is_null( $var ) )
			return PDO::PARAM_NULL;
		//Default  
		return PDO::PARAM_STR;
	}
	

	function __constructor()
	{
		$this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				
		if (mysqli_connect_error())
		{
			// die('Error de Conexión (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			
		}
		
		// $this->connection->host_info . "\n";
		
	}
	
	function __destructor()
	{
		$this->close();
	}
	
	function close()
	{
		return $this->connection->close();
	}
}


// REFERENCIA: http://es2.php.net/manual/es/book.mysqli.php
?>