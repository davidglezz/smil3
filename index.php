<?php
include('php/config.php');

header("Location: " . (!$user->signed ? 'smil3.htm' : 'login.htm'));

?>
