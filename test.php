<?php

//var_dump(PDO::getAvailableDrivers());

//require_once('php/class.database.php');
require_once('php/autoload.php');

var_dump(Validate::number(50, array('decimal' => false, 'min' => 1, 'max' => 50)));



?>
