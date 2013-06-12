<?php

$specialActions['register'] = function()
{
            isset($_POST) OR Response::sendError(13); // TODO: count($_POST) == x
            // Se deben aceptar los terminos de uso y de servicio
            isset($_POST['aceptTOS']) OR Response::sendError(14);

            // array con los datos del registro.
            $data = array();

            // Nombre de usuario
            SmileValidate::username($_POST['username']) OR Response::sendError(15);
            $data['username'] = $_POST['username'];

            // Contraseña
            SmileValidate::password($_POST['password']) OR Response::sendError(16);
            $data['password'] = $_POST['password'];

            // Email
            $_POST['email'] = trim($_POST['email']);
            Validate::email($_POST['email']) OR Response::sendError(17);
            $data['email'] = trim($_POST['email']);

            // Nombre
            SmileValidate::name($_POST['name']) OR Response::sendError(18);
            $data['name'] = $_POST['name'];

            // Fecha (YYYY-MM-DD)
            SmileValidate::birthdate($_POST['birthdate_year'], $_POST['birthdate_month'], $_POST['birthdate_day']) OR Response::sendError(19);
            //SmileValidate::age($_POST['birthdate_year'], $_POST['birthdate_month'], $_POST['birthdate_day']) OR Response::sendError(20);
            $data['birthdate'] = $_POST['birthdate_year'] . '-' . $_POST['birthdate_month'] . '-' . $_POST['birthdate_day'];

            // Sexo (Male, Female)
            SmileValidate::gender($_POST['sex']) OR Response::sendError(21);
            $data['sex'] = $_POST['sex'];

            // Pais (ES)
            SmileValidate::country($_POST['country']) OR Response::sendError(22);
            $data['country'] = $_POST['country'];

            //Register User
            User::getInstance()->register($data) OR Response::sendError(23);

            // TODO: enviar email de confirmación
};

$specialActions['login'] = function()
{
            isset($_POST['username'], $_POST['password']) OR Response::sendError(25);
            SmileValidate::username($_POST['username']) OR Response::sendError(26);
            User::getInstance()->login($_POST['username'], $_POST['password']) OR Response::sendError(27);
};

// TODO
$specialActions['activate'] = function()
{
            isset($_GET, $_GET['c']) OR Response::sendError(30);
            SmileValidate::activationToken($_GET['c']) OR Response::sendError(31);
            User::getInstance()->activate($_GET['c']) OR Response::sendError(32);
};

$specialActions['resetPasswd'] = function()
{
            isset($_POST) OR Response::sendError(40);

            $res = $user->pass_reset($_POST['email']);

            $res OR Response::sendError('18');
            //Hash succesfully generated
            // TODO: Send an email to $res['email'] with the URL+HASH $res['hash']
            // to enter the new password.
            // $url = "../?page=change-password&c=" . $res['hash'];
            /* mail($res['email'], 'Cambia de contraseña', 'Pulsa el enlace para continuar <a href="{$res["hash"]}">{$res["hash"]}</a>');

              $nombre = $_POST['nombre'];
              $mail = $_POST['mail'];
              $empresa = $_POST['empresa'];
              $header = 'From: ' . $mail . " \r\n";
              $header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
              $header .= "Mime-Version: 1.0 \r\n";
              $header .= "Content-Type: text/plain";

              $mensaje = "Este mensaje fue enviado por " . $nombre . ", de la empresa " . $empresa . " \r\n";
              $mensaje .= "Su e-mail es: " . $mail . " \r\n";
              $mensaje .= "Mensaje: " . $_POST['mensaje'] . " \r\n";
              $mensaje .= "Enviado el " . date('d/m/Y', time());

              $para = 'info@tusitio.com';
              $asunto = 'Contacto desde Taller Webmaster';
              mail($para, $asunto, utf8_decode($mensaje), $header);
              echo '&estatus=ok&'; */


            // redirigir a la pagina de cambiar contraseña
};

// cambia la contraseña si la olvidaste
$specialActions['changePasswd'] = function()
{
            count($_POST) OR Response::sendError(37);
            isset($_POST['c']) OR Response::sendError(38);

            $hash = $_POST['c'];
            unset($_POST['c']);

            // TODO: validar y comprobar contraseña
            $user->new_pass($hash, $_POST);
};


$actions['special'] = function()
{
            global $specialActions;
            isset($_GET['that']) OR Response::sendError(11);
            isset($specialActions[$_GET['that']]) OR Response::sendError(12);
            $specialActions[$_GET['that']]();
};


$actions['logout'] = function()
{
            User::getInstance()->logout();
};

$actions['changePasswd'] = function()
{

            //Proccess Password change
            count($_POST) OR Response::sendError(41);
            // si pass1 != pass2 Response::sendError(42);
            // TODO: validar y comprobar contraseña
            //$user->update($_POST);
};

$actions['userUpdate'] = function()
{
            //Proccess Update
            count($_POST) OR Response::sendError(41);

            $sql = "UPDATE users SET {$_POST['field']}=? WHERE  id_user=? LIMIT 1;";
            $params = Array($_POST['value'],User::getInstance()->id);
            $res = Database::getInstance()->query($sql, $params);

            $res !== false OR Response::sendError(42);
};

$actions['getStartInfo'] = function()
{
            $user = User::getInstance();

            $data = (array) $user;
            $data['user'];
            $data['msgs'] = 0;
            $data['notif'] = 0;

            // listas
            $sql = 'SELECT id_list as id, name FROM lists WHERE owner = ' . $user->id . ' ORDER BY `order` ASC;';
            $data['lists'] = Database::getInstance()->query($sql, null, PDO::FETCH_ASSOC);

            Response::add($data);
};

$actions['deleteAccount'] = function()
{
            // TODO
};

/* publications functions ************************************* */

$actions['getPub'] = function()
{
            isset($_POST['id']) OR Response::sendError('111');


            $sql = 'SELECT publications (user, text) VALUES ( ?,  ?);';
            $params = array($_POST['id']);

            Database::getInstance()->query($sql, $params);
};

$actions['getPosts'] = function()
{
            $sql = 'SELECT id_publication as id, username, name, text, time , originalPub
                    FROM publications
                    INNER JOIN relations ON userB = user
                    INNER JOIN users ON user = id_user
                    WHERE userA = ' . User::getInstance()->id;

            if (isset($_GET['list']) && is_numeric($_GET['list']))
            {
                $sql .= ' AND list = ' . intval($_GET['list']);
            }

            if (isset($_GET['time']) && is_numeric($_GET['time']))
            {
                $sql .= ' AND time > ' . intval($_GET['time']);
            }

            $res = Database::getInstance()->query($sql, null, PDO::FETCH_ASSOC);
            Response::add(array('posts' => $res));
};

$actions['delPub'] = function()
{
            isset($_GET['pid']) OR Response::sendError(65);
            is_numeric($_GET['pid']) OR Response::sendError(66);
            // La consulta no hace falta que sea preparada
            $sql = 'DELETE FROM publications WHERE id_publication=? AND user=? LIMIT 1;';
            $args = array(intval($_GET['pid']), User::getInstance()->id);
            Database::getInstance()->query($sql, $args);
};

$actions['publish'] = function()
{
            isset($_POST['pubcontent']) OR Response::sendError(60);
            User::getInstance()->publish($_POST['pubcontent']);
};

/* Private messages functions ************************************* */

$actions['getMsg'] = function()
{
            // TODO
};

$actions['delMsg'] = function()
{
            // TODO
};

$actions['sendMsg'] = function()
{
            //$a = stripslashes($b);
            // strip_tags();
            $msg = htmlspecialchars($_POST['$msg']);
            // TODO
};

/* * ************************************ */

$actions['updatePhoto'] = function()
{
            empty($_FILES) AND Response::sendError('30');
            $username = User::getInstance()->username;
            $path = $_SERVER['DOCUMENT_ROOT'] . '/userdata/' . $username . '.jpg';
            $_FILES['file']['error'] AND Response::sendError('31');
            move_uploaded_file($_FILES['file']['tmp_name'], $path) OR Response::sendError('32');
};

$actions['userUpdateField'] = function()
{
            //global $updateFieldFn;
            isset($_GET['field'], $_GET['value']) OR Response::sendError('19');
            //isset($updateFieldFn[$_GET['field']]) OR Response::sendError('20');
            //$updateFieldFn[$_GET['field']]();
            // TODO: Validar
            User::getInstance()->update($_GET['field'], $_GET['value']);

            $sql = 'UPDATE users SET ' . $_GET['field'] . '=? WHERE id_user=54 LIMIT 1;';
            $db = Database::getInstance();
            $res = $db->query($sql, array($_GET['value']), PDO::FETCH_ASSOC);
};


$updateFieldFn['username'] = function()
{
            $user = User::getInstance();
            $sql = "UPDATE users SET `activated`=1, `web`='www.davidxl.es', `bio`='Me gusta la Smile. ', `work`='Estudiante' WHERE  `id_user`=45 LIMIT 1;";
};


$actions['getProfile'] = function()
{
            isset($_GET['user']) OR Response::sendError('81');
            Validate::string($_GET['user'], array('format' => 'a-zA-Z0-9_', 'min_length' => 3, 'max_length' => 30)) OR Response::sendError('82');

            $sql = 'SELECT  id_user,  username, email, name, birthdate, sex, country, city, last_login, reg_date, web, bio, work, showMail, showBirth FROM users WHERE username=? LIMIT 1;';
            $db = Database::getInstance();
            $profile = $db->query($sql, array($_GET['user']), PDO::FETCH_ASSOC);

            isset($profile[0]) or Response::sendError('83');
            $profile = $profile[0];

            if ($profile['showMail'] !== '1')
                unset($profile['email']);

            if ($profile['showBirth'] !== '1')
                unset($profile['birthdate']);

            unset($profile['showBirth'], $profile['showMail']);

            $yo = User::getInstance();

            // Estado
            if (intval($yo->id) != intval($profile['id_user']))
            {
                $sql = 'SELECT name FROM relations LEFT JOIN lists ON id_list = list WHERE userA=? AND userB=? LIMIT 1;';
                $res = $db->query($sql, array($yo->id, $profile['id_user']));
                //$profile['relation'] = count($res) ? ($res[0][0] === null ? '-' : $res[0][0]) : null;
                $profile['relation'] = count($res) ? '1' : '0';
            }
            else
            {
                $profile['relation'] = null;
            }
            // sigue y seguidores
            $profile['following'] = $yo->getFollowingOf($profile['id_user']);
            $profile['followers'] = $yo->getFollowersOf($profile['id_user']);
            $profile['followerCount'] = $yo->getNumFollowersOf($profile['id_user']);
            $profile['followingCount'] = $yo->getNumFollowingOf($profile['id_user']);

            // Ultimas publicaciones
            $profile['posts'] = $yo->getLastestPostOf($profile['id_user']);

            Response::add($profile);
};


$actions['getUserData'] = function()
{
            $yo = User::getInstance();
            $sql = 'SELECT  id_user,  username, email, name, birthdate, sex, country, city, last_login, reg_date, web, bio, work, showMail, showBirth FROM users WHERE id_user=? LIMIT 1;';
            $db = Database::getInstance();
            $profile = $db->query($sql, array($yo->id), PDO::FETCH_ASSOC);

            isset($profile[0]) or Response::sendError('83');
            $profile = $profile[0];

            Response::add($profile);
};


/* * **************************************** */

$actions['follow'] = function()
{
            isset($_GET['uid']) OR Response::sendError(80);
            is_numeric($_GET['uid']) OR Response::sendError(81);
            User::getInstance()->follow(intval($_GET['uid'])) !== false OR Response::sendError(83);
};

$actions['unfollow'] = function()
{
            isset($_GET['uid']) OR Response::sendError(80);
            is_numeric($_GET['uid']) OR Response::sendError(81);
            User::getInstance()->unfollow(intval($_GET['uid'])) !== false OR Response::sendError(83);
};

$actions['following'] = function()
{
            $data = User::getInstance()->getFollowing();
            $data !== false OR Response::sendError(86);
            Response::add($data);
};

$actions['followers'] = function()
{
            $data = User::getInstance()->getFollowers();
            $data !== false OR Response::sendError(86);
            Response::add($data);
};

$actions['lists'] = function()
{
            $lists = ListManager::getInstance()->getAll();
            $lists !== false OR Response::sendError(85);
            Response::add($lists);
};


$actions['like'] = function()
{
            $post = intval($_GET['post']);
            $coment = intval($_GET['coment']);
            $value = 1;

            $data = Likes::set($post, $coment, $value);
            $data !== false OR Response::sendError(91);
            Response::send($data);
};

$actions['unlike'] = function()
{
            $post = intval($_GET['post']);
            $coment = intval($_GET['coment']);
            $value = -1;

            $data = Likes::set($post, $coment, $value);
            $data !== false OR Response::sendError(91);
            Response::send($data);
};

$actions['searchAutocomplete'] = function()
{
            $q = $_GET['q'];

            $len = strlen($q);

            $s = $q;
            if ($q[0] == '@' || $q[0] == '#')
                $s = substr($q, 1);

            $sql = "SELECT DISTINCT CONCAT('@',username) as r FROM users WHERE username LIKE '$s%' LIMIT 10";
            $sq2 = "SELECT DISTINCT CONCAT('#',id_tag) as r FROM tags WHERE id_tag LIKE '$s%' LIMIT 10";

            if ($q[0] == '@')
            {
                if ($len < 2)
                    return;

                $sql = $sq1;
            }
            else if ($q[0] == '#')
            {
                if ($len < 2)
                    return;

                $sql = $sq2;
            }
            else
            {
                $sql = $sql . ' UNION ' . $sq2;
            }

            $data = Database::getInstance()->query($sql, array(), PDO::FETCH_NUM);

            $data2 = array();
            foreach ($data as $value)
                if ($value)
                    $data2[] = $value[0];

            Response::add($data2);
};

$actions['search'] = function()
{
            $sql = 'SELECT  id_publication as id, name, username, text, time  FROM publications INNER JOIN users ON user = id_user INNER JOIN tags t ON t.publication = id_publication WHERE id_tag = ? ORDER BY publications.time DESC LIMIT 25;';
            $params = array($_GET['q']);
            $data = Database::getInstance()->query($sql, $params, PDO::FETCH_ASSOC);

            Response::add(array('posts' => $data));
};
?>