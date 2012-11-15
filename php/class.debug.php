var_dump();
var_export()
debug_backtrace()
debug_print_backtrace()

error_reporting(E_ALL);


if (version_compare(phpversion(), '5.1.0', '<') == true) { die ('PHP5.1 Only'); 




// For loading classes
function __autoload($class_name) {
$filename = strtolower($class_name) . '.php';
$file = site_path . 'classes' . DIRSEP . $filename;
if (file_exists($file) == false) {
return false;
}
include ($file);
}