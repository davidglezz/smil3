<?php
require_once('php/autoload.php');

Session::start();

//header('Location: ' . (User::getInstance()->signed ? 'smil3.htm' : 'login.htm'));
var_dump($_SESSION);
?>
