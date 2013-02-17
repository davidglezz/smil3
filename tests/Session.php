<?php
	require_once('../php/class.Session.php');
	Session::start();

	if (isset($_GET['fn']))
	{
		if($_GET['fn'] == 'end')
			Session::end();
		
		
	}
	
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Session class test</title>
		<style>
			body {
				font-family: monospace;
			}
		</style>
    </head>
    <body>
		<p>
			<a href="?fn=start" >start</a> | 
			<a href="?fn=end" >end</a>
		</p>
		
		<?php
		echo 'name: ' . session_name() . '<br />';
		echo 'id:   ' . session_id() . '<br />';
		echo '$_SESSION: ';
		@var_dump($_SESSION);
		?>
    </body>
</html>






?>
