<?php

//There you can check if user exists or credentials are correct (made it with attemps for avoid hackers), or the award of the proxies, etc

include('processor.php');

$go = @$_GET['go'];

if(empty($go)) {
	die('Cannot continue.');
} else {
	switch ($go) {
		case 'user-check':
			$user = @$_GET['u'];
			$pass = md5(@$_GET['p']);
			$code = @$_GET['c'];
			echo (checkUser($user, $pass, $code) == true) ? "True" : "False";
			break;
		
		default:
			return;
			break;
	}
}

?>