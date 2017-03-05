<?php

//Security: If somebody visits this, he will return to the index
if(empty($_POST)) {
	header("Location: /");
	exit;
}

include($_SERVER['DOCUMENT_ROOT'].'/processor.php');

$token = @$_POST['token'];

logout($token);

?>