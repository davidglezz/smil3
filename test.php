<?php

$entrada = array("a", "b", "c", "d", "e");
array_splice($entrada, 2, 1);
var_dump($entrada);



$entrada = array("rojo", "verde", "azul", "amarillo");
array_splice($entrada, 1, -1);
var_dump($entrada);
// $entrada ahora es array("rojo", "amarillo")

$entrada = array("rojo", "verde", "azul", "amarillo");
array_splice($entrada, 1, count($entrada), "naranja");
var_dump($entrada);
// $entrada ahora es array("rojo", "naranja")

$entrada = array("rojo", "verde", "azul", "amarillo");
array_splice($entrada, -1, 1, array("negro", "granate"));
var_dump($entrada);//array("rojo", "verde", "azul", "negro", "granate")

$entrada = array("rojo", "verde", "azul", "amarillo");
array_splice($entrada, 3, 0, "púpura");
var_dump($entrada);
// $entrada ahora es array("rojo", "verde",
//          "azul", "púpura", "amarillo");
?>