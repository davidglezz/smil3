<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php

        function CalculaEdad($fecha)
        {
            list($Y, $m, $d) = explode("-", $fecha);
            return( date("md") < $m . $d ? date("Y") - $Y - 1 : date("Y") - $Y );
        }

        function age($y, $m, $d)
        {
            $now = getdate();

            $age = $now['year'] - $y;

            if ($m == $now['mon'])
            {
                if ($d > $now['mday'])
                    $$age--;
            }
            elseif ($m > $now['mon'])
            {
                $$age--;
            }

            return $$age;
        }

        var_dump('1994-02-23', age(1995, 02, 23));
        var_dump('1994-02-24', age(1995, 02, 24));
        var_dump('1994-02-25', age(1995, 02, 25));
        ?>
    </body>
</html>
