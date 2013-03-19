<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>ListManager</title>
    </head>
    <body>
        <?php
        require_once('../php/config.php');
        require_once('../php/class.Session.php');
        require_once('../php/class.Singleton.php');
        require_once('../php/class.ListManager.php');
        require_once('../php/class.Database.php');
        require_once('../php/class.User.php');


        Session::start();

        $list = ListManager::getInstance();
        var_dump($list);

        $list->add('Oviedo');
        var_dump($list);

        $list->add('Prueba');
        var_dump($list);

        $list->delete(4);
        var_dump($list);

        $list->delete(8);
        var_dump($list);


        ?>
    </body>
</html>
