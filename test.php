<?php
	$actions['test'] = function()
	{
		echo 'Test';
		
	};

	
    var_dump(isset($actions[$_GET['fn']]));
?>
