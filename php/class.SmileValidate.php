<?php

/**
 * Description of SmileValidate
 *
 * @author David Gonzalez
 */
class SmileValidate
{

    public static function username($userName)
    {
        return Validate::string($userName, array('format' => 'a-zA-Z0-9_', 'min_length' => 3, 'max_length' => 30));
    }

    public static function password($password)
    {
        return Validate::string($password, array('min_length' => 6, 'max_length' => 50));
    }

    public static function name($name)
    {
        return Validate::string($name, array(/* 'format' => VALIDATE_EALPHA.VALIDATE_SPACE, */ 'min_length' => 6, 'max_length' => 75));
    }

    public static function gender($gender)
    {
        return Validate::string($gender, array('format' => 'MF', 'min_length' => 1, 'max_length' => 1));
    }

    public static function country($country)
    {
        return Validate::string($country, array('format' => VALIDATE_ALPHA_UPPER, 'min_length' => 2, 'max_length' => 2));
    }

    public static function birthdate($year, $month, $day)
    {
        if (!(is_numeric($year) && is_numeric($month) && is_numeric($day)))
            return false;

        $year = intval($year);
        $month = intval($month);
        $day = intval($day);

        return checkdate($month, $day, $year);
    }

    public static function age($year, $month, $day)
    {
        $year = intval($year);
        $month = intval($month);
        $day = intval($day);

        include_once 'util.php';

        return age($year, $month, $day) >= MINIMUM_AGE;
    }

    public static function activationToken($hash)
    {
        return Validate::string($hash, array('format' => 'a-z0-9', 'min_length' => 32, 'max_length' => 32));
    }

}

?>
