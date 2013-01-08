<?php

//var_dump(PDO::getAvailableDrivers());

//require_once('php/class.database.php');
require_once('php/autoload.php');

$db = Database::getInstance();

var_dump($db->query('INSERT INTO publications (user, text) VALUES ( ?,  ?);', array(4,'hola')));



?>
