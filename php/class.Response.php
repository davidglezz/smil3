<?php

/**
 * Response class
 * Se encarga de gestionar la salida de los datos al cliente, y
 * que sean siempre datos json por lo que no deberia haber ningun
 * die, echo, print o semejantes. La salida de texto estandar quedara
 * reflejada en la variable stdout
 *
 * @author David Gonzalez
 */

class Response extends Singleton
{
	private $data;
	
	protected function __construct()
	{
		ob_start();
		$this->data = array('error' => 0);
	}
	
	public function __destruct()
	{
		$this->data['stdout'] = ob_get_contents();
		ob_end_clean();
		$this->send();
	}

	protected function send()
	{
		echo json_encode($this->data);
	}
	
	protected function addData($new)
	{
		$this->data = array_merge($this->data, $new);
	}
	
	// Accesos directos para mas facilidad
	public static function sendError($param)
	{
		self::getInstance()->addData(array('error' => $param));
		die();
	}
	
	public static function add($param)
	{
		self::getInstance()->addData($param);
	}
	
	public static function init()
	{
		self::getInstance();
	}
}


?>
