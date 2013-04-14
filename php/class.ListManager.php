<?php

/**
 * ListManager
 * Clase para la administracion de listas.
 *
 * @author David Gonzalez
 */
class ListManager extends Singleton
{
    private $lists;

    protected function __construct()
    {
        $sql = 'SELECT id_list, name, `order` FROM lists WHERE owner = ? ORDER BY id_list ASC;';
        $params = array(User::getInstance()->id);
        $this->lists = Database::getInstance()->query($sql, $params);
    }

    public function add($name)
    {
        $size = count($this->lists);
        $pos = 0;

        if ($size > 255)
            return false;

        while ($pos < $size && $this->lists[$pos][0] == $pos)
            $pos++;

        array_splice($this->lists, $pos, 0, array(array($pos, $name, $size)));

        // La columna order va entre comillas por que es una palabra reservada SQL
        $sql = 'INSERT INTO lists (id_list, owner, name, `order`) VALUES (?, ?, ?, ?);';
        $dat = array($pos, User::getInstance()->id, $name, $size);
        $res = Database::getInstance()->query($sql, $dat);

        return $res !== false;
    }

    public function delete($id)
    {
        $pos = getPosition($id);

        if ($pos === false)
            return false;

        array_splice($this->lists, $pos, 1);
        $sql = 'DELETE FROM lists WHERE id_list=? AND owner=? LIMIT 1;';
        $res = Database::getInstance()->query($sql, array($id, User::getInstance()->id));

        return $res !== false;
    }

    public function getAll()
    {
        return $this->lists;
    }

    public function get($id)
    {
        $pos = getPosition($id);
        return $pos !== false ? $this->lists[$pos] : false;
    }

    private function getPosition($id)
    {
        $size = count($this->lists);

        for ($pos = 0; $pos < $size; $pos++)
            if ($this->lists[$pos][0] != $id)
                return $pos;

        return false;

        /* Otra manera.
          $size = count($this->lists);
          $pos = 0;
          while ($pos < $size && $this->lists[$pos][0] != $id)
          $pos++;

          return $pos >= $size ? false : $pos; */
    }

    public function rename($id, $name)
    {
        $pos = getPosition($id);

        if ($pos === false)
            return false;

        $this->lists[$pos][1] = $name;

        $sql = 'UPDATE lists SET name=? WHERE id_list=? AND owner=? LIMIT 1;';
        $params = array($name, $id, User::getInstance()->id);
        $res = Database::getInstance()->query($sql, $params);

        return $res !== false;
    }

    public function order($id, $newPos)
    {
        $pos = getPosition($id);

        if ($pos === false)
            return false;

        if ($newPos == $pos)
            return true;

        $db = Database::getInstance();

        $sql = 'UPDATE lists SET `order` = `order` %s 1 WHERE `owner` = %d AND `order` BETWEEN %d AND %d LIMIT %d;';
        $sql = $newPos > $pos ?
                sprintf($sql, '-', User::getInstance()->id, $pos + 1, $newPos, $newPos - $pos) :
                sprintf($sql, '+', User::getInstance()->id, $pos, $newPos - 1, $newPos - $pos);

        $res = $db->query($sql);

        if ($res === false)
            return false;

        $sql = 'UPDATE lists SET `order`=? WHERE `owner`=? AND id_list=? LIMIT 1;';
        $res = $db->query($sql, array($id, User::getInstance()->id));

        return $res !== false;
    }

}

/*
  SELECT min(a.id_list + 1)
  FROM lists AS a LEFT JOIN lists AS b ON b.id_list=a.id_list + 1
  WHERE (b.id_list Is Null);
 */
?>
