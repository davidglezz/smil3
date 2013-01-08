<?php
/*
 * Clase para clases de instancia unica
 */

class Singleton
{
	protected static $instance = null;

	protected function __construct() { }
	protected function __clone() { }

	public static function getInstance()
	{
		if (!isset(static::$instance))
			static::$instance = new static;

		return static::$instance;
	}
}

// Para versiones anteriores a PHP 5.3 se debe mezclar
// con cada clase, no funciona con herencia.
/*
class Singleton
{
	private static $instance;
	
	public static function getInstance()
	{
		// if (!self::$instancia instanceof self)
		if (!isset(self::$instance))
		{
			//$c = __CLASS__; 
			//self::$instance = new $c();  
			self::$instance = new self();
		}
		return self::$instance;
	}
}
*/
?>
