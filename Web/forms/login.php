<?php

//Security: If somebody visits this, he will return to the index
if(empty($_POST)) {
	header("Location: /");
	exit;
}

include($_SERVER['DOCUMENT_ROOT'].'/processor.php');

$expireTime = @$_POST['duration'];
$username = mysqli_escape_string($conn, @$_POST['username']);
$password = @$_POST['password'];

login($expireTime, $username, $password);

?>