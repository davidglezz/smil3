<?php

/*
  Error class
  Se encarga de los mensajes de error.

 */

class Error
{
    public static $msg = array(
    0 => 'OK',
    1 => 'No se pudo conectar con la base de datos.',
    2 => '',
    8 => 'Se esperaba un comando.',
    9 => 'Comando no válido.',
    10 => 'Necesita estar identificado.',
    11 => 'Se esperaba un comando especial.',
    12 => 'Comando especial no válido.',
    // Registro de usuario
    13 => 'Se necesitan datos para registrar nu nuevo usuario.',
    14 => 'Es necesario aceptar los terminos del servicio.',
    15 => 'El nombre de usuario no cumple los requisitos.',
    16 => 'La contraseña no cumple los requisitos.',
    17 => 'La direccion de correo electronico no es válida.',
    18 => 'Nombre de la persona no cumple los requisitos.',
    19 => 'La fecha no es valida o no alcanzas la edad minima para registrarte.',
    20 => 'Debe especificarse un genero válido.',
    21 => 'El pais no es válido.',
    22 => 'No se pudo registrar, Posiblemente el usuario ya esta en la base de datos.',
    // Login
    25 => 'Faltan datos de inicio de sesión',
    26 => 'El nombre de usuario no es válido.',
    27 => 'Usuario o contraseña incorrecto.',
    // Activar cuenta
    30 => 'Se debe proporcionar un codigo de activación.',
    31 => 'El codigo de activación no es válido.',
    32 => 'El codigo de activación no es incorrecto.',
    // Recuperar contraseña
    34 => '',
    35 => '',
    36 => '',
    // Cambiar contraseña ()
    37 => '',
    38 => '',
    39 => '',
    40 => '',
    //
    41 => 'Faltan datos.',
    42 => 'Las contraseñas deben coincidir.',

    // Publicaciones
    60 => 'No se pueden hacer publicaciones vacias.',
    61 => 'No se ha podido publicar.',
    65 => 'Se debe especificar una publicacion.',
    66 => 'El argumento debe ser un número.',

    // Mensajes privados
    70 => '',

    // Seguir
    80 => 'Se requiere el id de la persona.',
    81 => 'El identificador de la persona debe ser un número.',
    82 => 'No se ha podido realizar la operacion.',
    83 => '',

    85 => 'No se han podido las listas.'
    );

}

?>