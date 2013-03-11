<?php

/**
 * Description of SmileValidate
 *
 * @author David Gonzalez
 */
class SmileValidate
{

    public function username($userName)
    {
        return Validate::string($userName, array('format' => 'a-zA-Z0-9_', 'min_length' => 3, 'max_length' => 30));
    }

    public function password($password)
    {
        return Validate::string($password, array('min_length' => 6, 'max_length' => 50));
    }

    public function name($name)
    {
        return Validate::string($name, array(/* 'format' => VALIDATE_EALPHA.VALIDATE_SPACE, */ 'min_length' => 6, 'max_length' => 75));
    }

    public function gender($gender)
    {
        return Validate::string($gender, array('format' => 'MF', 'min_length' => 1, 'max_length' => 1));
    }

    public function country($country)
    {
        return Validate::string($country, array('format' => VALIDATE_ALPHA_UPPER, 'min_length' => 2, 'max_length' => 2));
    }

    public function birthdate($year, $month, $day)
    {
        if (!(is_numeric($year) && is_numeric($month) && is_numeric($day)))
            return false;

        $year = intval($year);
        $month = intval($month);
        $day = intval($day);

        if (!checkdate($month, $day, $year))
            return false;

        if (age($year, $month, $day) < MINIMUM_AGE)
            return false;

        return true;
    }

    public function activationToken($hash)
    {
        return Validate::string($hash, array('format' => 'a-z0-9', 'min_length' => 32, 'max_length' => 32));
    }

}

?>
