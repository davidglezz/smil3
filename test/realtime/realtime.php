<?php

while(1)
{
	$data = "Hola";
	echo json_encode($data);
	flush();
	sleep(5);
}

?>