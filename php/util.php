<?php

/*
 * Calcula los años desde la fecha $year-$month-$day
 */

function age($year, $month, $day)
{
    $now = getdate();

    $age = $now['year'] - $year;

    if ($month == $now['mon'])
    {
        if ($day > $now['mday'])
            $$age--;
    }
    elseif ($month > $now['mon'])
    {
        $$age--;
    }

    return $$age;
}


// cadena de 32 letras y numeros aleatorios.
function authToken()
{
    return md5(str_shuffle(chr(mt_rand(32, 126)) . uniqid() . microtime(TRUE)));
}

?>
