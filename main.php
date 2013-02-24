<?php

/*
  main.php
  Se encarga de recibir todas las peticiones y enviar las respuestas.
  Se entiende que las peticiones son realizadas mediante ajax,
  por lo que las respuestas no son documentos si no codigos de error/suceso
  y datos pedidos que casi siempre estaran en formato json.
 */

//header('Access-Control-Allow-Origin: *'); // Se pude hacer con apache
//setlocale(LC_ALL, 'es_ES.utf-8');
// Default timezone of server
//date_default_timezone_set('UTC');
// iconv encoding
//iconv_set_encoding("internal_encoding", "UTF-8");
// multibyte encoding
//mb_internal_encoding('UTF-8');
//------------------------------------

require_once('php/autoload.php');
require_once('php/actions.php');
require_once('php/config.php');

Response::init();

isset($_GET['do']) OR Response::sendError(8);

Session::start();
User::getInstance()->signed OR $_GET['do'] == 'special' OR Response::sendError(10);

isset($actions[$_GET['do']]) OR Response::sendError(9);

$actions[$_GET['do']]();

// ------------------------------------------------------------
?>
