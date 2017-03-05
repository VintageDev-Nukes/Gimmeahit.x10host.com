<?php

include($_SERVER["DOCUMENT_ROOT"].'/processor.php');

if($_SERVER['HTTP_X_FORWARDED_FOR']
   || $_SERVER['HTTP_X_FORWARDED']
   || $_SERVER['HTTP_FORWARDED_FOR']
   || $_SERVER['HTTP_CLIENT_IP']
   || $_SERVER['HTTP_VIA']
   || in_array($_SERVER['REMOTE_PORT'], array(8080,80,6588,8000,3128,553,554,9999,9000))
   || @fsockopen($_SERVER['REMOTE_ADDR'], 80, $errno, $errstr, 3)) {
   	//Display the content
	echo "<h1>If you can see that webpage this means that this proxy works correctly.</h1>";
	//And add a hit to the database...
	addhit();
} else {
	echo "<h1>You aren't a proxy... So, you can't pass.</h1>";
}

?>