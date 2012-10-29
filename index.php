<?php
include('inc/class.uFlex.php');
include("config.php");

if(!$user->signed)
	redirect("./?page=login");

?>