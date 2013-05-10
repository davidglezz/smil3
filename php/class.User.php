<?php

class User extends Singleton
{

    public $id;   //Current user ID
    public $username; //Signed username
    public $signed;  //Boolean, true = user is signed-in
    public $name;  // Full name

    protected function __construct()
    {
        $this->signed = false;
        $this->id = $this->username = $this->name = null;
    }

    // se sobreescribe ya que la instancia debe guardarse en la sesion.
    public static function getInstance()
    {
        if (!isset($_SESSION[__CLASS__]))
            $_SESSION[__CLASS__] = new static;

        return $_SESSION[__CLASS__];
    }

    public function login($user, $password)
    {
        if ($this->signed)
            $this->__construct();

        $db = Database::getInstance();
        var_dump(Database::getInstance());
        $sql = 'SELECT id_user, username, password, salt, name FROM users WHERE username = ? LIMIT 1';
        $res = $db->query($sql, array($user));

        if (!$res) // || empty($res)
            return false;

        if ($res[0][2] === hash('sha256', $password . $res[0][3]))
        {
            $this->id = intval($res[0][0]);
            $this->username = $res[0][1];
            $this->name = $res[0][4];
            $this->signed = true;
            return true;
        }
        else
        {
            // TODO
            // Log login fail.
            return false;
        }
    }

    public function logout()
    {
        $this->__construct(); // No deberia hacer falta
        Session::end();
    }

    public function register($data)
    {
        // Imagen de perfil por defecto
        $hash = md5(strtolower($data['email']));
        $params = '?s=260&d=retro';
        $url = 'http://www.gravatar.com/avatar/' . $hash . $params;
        echo $url;
        $img = './userdata/' . $data['username'] . '.jpg';
        file_put_contents($img, file_get_contents($url));

        // Generar hash y salt
        $data['salt'] = hash('sha256', uniqid(rand() . 'Smil3:)', false), false);
        $data['password'] = hash('sha256', $data['password'] . $data['salt']);
        $data['reg_date'] = time();

        // Insertar en la base de datos
        $db = Database::getInstance();
        $sql = 'INSERT INTO users (username, password, salt, email, name, birthdate, sex, country, reg_date) VALUES (:username, :password, :salt, :email, :name, :birthdate, :sex, :country, :reg_date);';

        $res = $db->query($sql, $data);

        return $res !== false;
    }

    public function update($key, $value)
    {
        $sql = "UPDATE users SET $key=:$key WHERE id_user=$this->id LIMIT 1;";
        $res = Database::getInstance()->query($sql, array($key => $value));
        return $res !== false;
    }

    public function checkExistUser($user)
    {
        $db = Database::getInstance();
        $sql = 'SELECT id_user FROM users WHERE username = ? LIMIT 1';
        $res = $db->query($sql, array($user));
        return $res !== false && !empty($res);
    }

    public function pass_reset($data)
    {
        // Enviar email de confirmación con un codigo.
    }

    public function pass_change($data)
    {

    }

    // Followers
    public function getFollowersOf($user)
    {
        $sql = 'SELECT userA as id, username, name FROM relations INNER JOIN users ON userA = id_user WHERE userB = ? ;';
        $db = Database::getInstance();
        $params = array($user);
        return $db->query($sql, $params, PDO::FETCH_ASSOC);
    }

    public function getFollowers()
    {
        return getFollowersOf($this->id);
    }

    public function getNumFollowersOf($user)
    {
        $sql = 'SELECT count(*) FROM relations WHERE userB = ? ;';
        $db = Database::getInstance();
        $params = array($user);
        $res = $db->query($sql, $params);
        return $res[0][0];
    }

    public function getNumFollowers()
    {
        return getNumFollowersOf($this->id);
    }

    // Following
    public function getFollowingOf($user)
    {
        $sql = 'SELECT userB as id, username, name FROM relations INNER JOIN users ON userB = id_user WHERE userA = ? ;';
        $db = Database::getInstance();
        $params = array($user);
        return $db->query($sql, $params, PDO::FETCH_ASSOC);
    }

    public function getFollowing()
    {
        return getFollowingOf($this->id);
    }

    public function getNumFollowingOf($user)
    {
        $sql = 'SELECT count(*) FROM relations WHERE userA = ? ;';
        $db = Database::getInstance();
        $params = array($user);
        $res = $db->query($sql, $params);
        return $res[0][0];
    }

    public function getNumFollowing()
    {
        return getNumFollowingOf($this->id);
    }

    // Post
    public function getLastestPostOf($user)
    {
        $sql = 'SELECT  id_publication as id, name, username, text, FROM_UNIXTIME(time, "%e/%c/%Y %h:%i") as time  FROM publications INNER JOIN users ON user = id_user WHERE user = ? ORDER BY publications.time DESC LIMIT 25;';
        $db = Database::getInstance();
        $params = array($user);
        return $db->query($sql, $params, PDO::FETCH_ASSOC);
    }

    public function publish($text)
    {
        $db = Database::getInstance();
        $text = nl2br(htmlspecialchars($text));
        $sql = 'INSERT INTO publications (user, text, time) VALUES ( ?,  ?, ?);';
        $params = array($this->id, $text, time());
        $db->query($sql, $params); // OR 61;


        $postID = $db->connection->lastInsertId();

        // parse hashtag
        preg_match_all("/[#]+([a-zA-Z0-9_]+)/", $text, $hashtags);

        if (count($hashtags) && count($hashtags[1]))
        {
            $sql = 'INSERT INTO tags (id_tag, publication) VALUES (?, ?);';
            $stmt = $db->connection->prepare($sql);

            foreach ($hashtags[1] as $hashtag)
            {
                $stmt->execute(array($hashtag, $postID));
            }
        }

        //parse mention
        /*
        preg_match_all("/[@]+([a-zA-Z0-9_]+)/", $text, $mentions);
        if (count($mentions) && count($mentions[1]))
        {
            $sql1 = 'INSERT INTO tags (id_tag, publication) VALUES (?, ?);';
            foreach ($mentions[1] as $mention)
            {
            }
        }
         */
    }

}

// http://www.php.net/manual/en/function.crypt.php
// http://stackoverflow.com/questions/4795385/how-do-you-use-bcrypt-for-hashing-passwords-in-php
?>