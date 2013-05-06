<?php

/**
 * Response class
 * Se encarga de gestionar la salida de los datos al cliente, asegura
 * que sean siempre datos json por lo que no deberia haber ningun
 * die, echo, print o semejantes. La salida de texto estandar quedara
 * reflejada en la variable debug
 *
 * @author David Gonzalez
 */

class Response extends Singleton
{
	private $data;

	protected function __construct()
	{
        header('Content-Type: application/json; charset=utf8');
		ob_start();
		$this->data = array('error' => 0);
	}

	public function __destruct()
	{
		$this->forceSend();
	}

	protected function forceSend()
	{
		if (isset($_COOKIE['debug']) && $_COOKIE['debug'] == '1')
            $this->data['debug'] = ob_get_contents();

		ob_end_clean();

		echo json_encode($this->data);
	}

	public function addData($new)
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
		if (is_array($data))
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
