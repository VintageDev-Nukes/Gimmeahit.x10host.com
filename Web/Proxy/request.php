<?php

include($_SERVER["DOCUMENT_ROOT"].'/processor.php');

//Decode user, pass & code from base64

$dec = explode(base64_decode($_GET['decode']), "|");

$action = $_GET['action'];
$user = $dec[0];
$pass = $dec[1];
$code = $dec[2];
$url = $_GET['url'];
$visits = $_GET['vc'];
$visitors = $_GET['vu'];

$now = time();
$ip = $_SERVER['REMOTE_ADDR'];

$str_query = "# VC:".$visits."|VU:".$visitors."|URL:".base64_encode($url)." #";

switch ($action) {

	case 'sender':
		if(isset($url) && is_numeric($visits) && is_numeric($visitors)) {
			mysqli_query("INSERT INTO requests (request, time, ip, usercode) VALUES ('$str_query', '$now', '$ip', '$code')");
		}
		break;

	case 'reader':
		echo 'Reader';
		break;
	
	default:
		echo '404';
		break;

}

die('404');

?>