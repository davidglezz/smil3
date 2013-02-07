<?php

/*
 * Array de consultas a la base de datos
 * 
 */

// Inserta una publicacion: id_user, text
$query[0] = 'INSERT INTO  publications (user, text) VALUES ( ?,  ?);';


$query[1] = 'select * from publications;';

// calcula la edad de un usuario
//SELECT ((YEAR(CURDATE())-YEAR(date_birth)) - (RIGHT(CURDATE(),5)<RIGHT(date_birth,5))) FROM users WHERE id = 2;
//select (CURDATE()-birth_dt)/365 FROM users WHERE id = 2;

?>
