<?php

/**
 * Response class
 * Se encarga de gestionar la salida de los datos al cliente,
 * por lo que no puede haber ningun die, echo o semejantes.
 *
 * @author David Gonzalez
 */

class response extends Singleton
{
	private $data;
	
	protected function __construct() {
		$this->data = array('error' => '0');
	}
	
	protected function __destruct() {
		$this->send();
	}

	public function send()
	{
		die(json_encode($this->data));
	}
	
	public function add()
	{
		
	}
}

?>
