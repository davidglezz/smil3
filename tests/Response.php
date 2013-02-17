<?php
require_once('../php/class.Singleton.php');
require_once('../php/class.Response.php');

Response::init();

echo 'Hola';

Response::add(array('clave' => 'Valor', 5));

Response::sendError(3);

die('');
?>
