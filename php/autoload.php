<?php

function __autoload($class_name)
{
	include_once 'inc/class.' . $class_name . '.inc.php';
}
?>
