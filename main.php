<?php

/*
	main.php
	Se encarga de recibir todas las peticiones y enviar las respuestas.
	Se entiende que las peticiones son realizadas mediante ajax,
	por lo que las respuestas no son documentos si no codigos de error/suceso
	y datos pedidos que casi siempre estaran en formato json.
*/

require_once('php/config.php');
require_once('php/actions.php');

isset($_GET['do']) OR die('8');

$user->signed OR $_GET['do'] == 'special' OR die('10');

isset($actions[$_GET['do']]) OR die('9');

$actions[$_GET['do']]();

// ------------------------------------------------------------




?>
