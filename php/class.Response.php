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
		$this->forceSend();
	}

	protected function forceSend()
	{
		$this->data['stdout'] = ob_get_contents();
		ob_end_clean();
		echo json_encode($this->data);
	}

	protected function addData($new)
	{
		$this->data = array_merge($this->data, $new);
	}

	// Accesos directos para mas facilidad
	public static function sendError($err)
	{
		Response::getInstance()->addData(array('error' => $err));
		die();
	}

	public static function send($data = null)
	{
		if ($data && is_array($data))
			Response::getInstance()->addData($data);
		die();
	}

	public static function add($param)
	{
		Response::getInstance()->addData($param);
	}

	public static function init()
	{
		Response::getInstance();
	}
}


?>