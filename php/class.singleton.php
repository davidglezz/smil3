<?php

/*
 * Clase para clases de instancia unica
 */

abstract class Singleton
{
    
    protected static $instance;

    protected function __construct() {}

    protected function __clone() {}

    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(static::$instance[$class]))
            static::$instance[$class] = new static;

        return static::$instance[$class];
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
