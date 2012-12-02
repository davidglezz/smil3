<?php

require_once('php/class.database.php');

$db = Database::getInstance();
var_dump($db->query(1));
?>
