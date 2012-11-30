<?php

/*
* Database class
* Se encarga de gestionar las conexiones con la base de datos
* Usara el gestor de bases de datos mySQL
*/

define('DB_HOST', 'localhost');
define('DB_NAME', 'smil3');
define('DB_USER', 'smil3');
define('DB_PASS', 'smil3');

require_once('query.php');
require_once('class.singleton.php');

class Database extends Singleton
{
	private $connection;
	
	private function connect()
	{
		__construct();
	}
	
	public function __construct()
	{
		$this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (mysqli_connect_errno())
		{
			// die('Error de Conexion (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			self::$instance = null;
		}
	}
	
	private function close()
	{
		$connection->close();
		self::$instance = null;
	}
	
	/*public static function fetchArray($stmt)
	{
		$data = mysqli_stmt_result_metadata($stmt);

		$fields = array();
		$out = array();

		$fields[0] = &$stmt;
		for($i = 1; $field = mysqli_fetch_field($data); $i++)
			$fields[$i] = &$out[$field->name];

		call_user_func_array(mysqli_stmt_bind_result, $fields);
		$stmt->fetch();
		return count($out) ? $out : false;
    }*/
	
	public function query($n, $params = null)
	{
		global $query;
		
		/* Create a prepared statement */
		$stmt = $this->connection->prepare($query[$n][0]);

		if(!$stmt)
			return false;

		/* TODO: Bind parameters */
		if ($query[$n][1] != '')
			$stmt->bind_param($query[$n][1], $params);
		//call_user_func_array(mysqli_stmt_bind_param, $query[$n][1]);

		$stmt->execute();
		
		$data = array();
		$result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM))
        {
            //var_dump($row);
			$data[] = $row;
        }
		
		$stmt->close();
		return $data;
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
	


}


// REFERENCIA: http://es2.php.net/manual/es/book.mysqli.php
?>