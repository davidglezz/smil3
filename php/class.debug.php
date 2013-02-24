<?php

if (DEBUG)
{
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
}
else
{
	error_reporting(0);
	ini_set('display_errors', 'Off');
}



/**
 * Write to the application log file using error_log
 *
 * @param string $message to save
 * @return bool
 */
function log_message($message)
{
	$path = SP . 'Storage/Log/' . date('Y-m-d') . '.log';

	// Append date and IP to log message
	return error_log(date('H:i:s ') . getenv('REMOTE_ADDR') . " $message\n", 3, $path);
}



/*

var_dump();
var_export()
debug_backtrace()
debug_print_backtrace()


 trigger_error('Prueba de error');


if (version_compare(phpversion(), '5.1.0', '<') == true) { die ('PHP5.1 Only');



 */
