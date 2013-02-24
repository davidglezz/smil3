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

?>
