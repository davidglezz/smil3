<?php

/*
 * Array de consultas a la base de datos
 * 
 */

// Inserta una publicacion: id_user, text
$query[0] = array('INSERT INTO  publications (user, text) VALUES ( ?,  ?);', 'is');


$query[1] = array('select * from publications;', '');

?>
