<?php

function __autoload($name)
{
	require_once "php/class.$name.php";
}

?>
