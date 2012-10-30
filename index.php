<?php
include('php/config.php');

redirect($user->signed ? "smil3.htm" : 'login.htm');

?>
