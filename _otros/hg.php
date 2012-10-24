<?php
putenv('PATH='. getenv('PATH') .':/home/username/bin'); // /usr/bin/hg
exec('hg pull -u 2>&1', $output);
var_dump($output);
?>