<?php

//Security: If somebody visits this, he will return to the index
if(empty($_POST)) {
	header("Location: /");
	exit;
}

include($_SERVER['DOCUMENT_ROOT'].'/processor.php');

$username = mysqli_escape_string($conn, @$_POST['username']);
$password = @$_POST['password'];
$email = mysqli_escape_string($conn, @$_POST['email']);
$ref = ((empty($_POST['ref']) === true || is_numeric($_POST['ref']) === false) ? 0 : $_POST['ref']);

register($username, $password, $email, $ref);

?>