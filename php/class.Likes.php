<?php

class Likes {

    public static function set($post, $coment, $value)
    {
            $user = User::getInstance()->id;

            $db = Database::getInstance();
            $sql = 'SELECT value FROM likes WHERE publication = ? AND coment = ? AND user = ? LIMIT 1';

            $res = $db->query($sql, array($post, $coment, $user));

            $params = array($value, $post, $coment, $user);

            if (count($res) == 0)
            {
                $sql2 = 'INSERT INTO likes (value, publication, coment, user, ) VALUES (?, ?, ?, ?);';
                $res2 = $db->query($sql2, $params);
            }
            else if ($res[0][0] != $value)
            {
                $sql2 = 'UPDATE likes SET value = ? WHERE publication = ? AND coment = ? AND user = ?;';
                $res2 = $db->query($sql2, $params);
            }

            return $res2 !== false ? self::count($post, $coment) : false;
    }

    public static function get($post, $coment)
    {

    }

    public static function count($post, $coment)
    {
            $db = Database::getInstance();
            $sql = 'SELECT count(*) FROM likes WHERE publication = ? AND coment = ? GROUP BY value';
            $res = $db->query($sql, array($post, $coment));

            return $res;
    }
}

?>
