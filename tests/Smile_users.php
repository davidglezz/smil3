<?php
require_once('../php/class.Session.php');
require_once('../php/class.Singleton.php');
require_once('../php/class.Database.php');
require_once('../php/class.User.php');

Session::start();

// add test users: username, password, email, name, birthdate, sex, country
$users[] = array('JuanPerez', 'Juan Pérez', '1990-12-02', 'M', 'ES');
$users[] = array('MaxMuster' , 'Max Muster', '1988-11-04', 'M', 'ES');
$users[] = array('Simon McCool', 'Simon McCool', '1989-10-06', 'M', 'ES');
$users[] = array('John Citizen', 'John Citizen', '1993-09-08', 'M', 'ES');
$users[] = array('Joe Blow', 'Joe Blow', '1996-08-10', 'M', 'ES');
$users[] = array('Fred Nerk', 'Fred Nerk', '1998-07-12', 'M', 'ES');
$users[] = array('Mongo Aurelio', 'Mongo Aurelio', '1991-06-14', 'M', 'ES');
$users[] = array('Doña María', 'Doña María', '1992-05-16', 'F', 'ES');
$users[] = array('Fulano de Tal', 'Fulano de Tal', '1979-04-18', 'M', 'ES');
$users[] = array('Don Nadie', 'Don Nadie', '1982-03-20', 'M', 'ES');
$users[] = array('Menganito', 'Menganito', '1981-02-22', 'M', 'ES');
$users[] = array('Rosalia', 'Rosalia', '1985-01-24', 'F', 'ES');

$user = User::getInstance();
for ($i = 0; $i < count($users); $i++)
{
	$data = array('username' => $users[$i][0],
				'password' => '123456',
				'email' => 'mail'.$i.'@mail.com',
				'name' => $users[$i][1],
				'birthdate' => $users[$i][2],
				'sex' => $users[$i][3],
				'country' => $users[$i][4]	);
	
	$user->register($data);
}






?>
