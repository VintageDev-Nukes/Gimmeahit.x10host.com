<?php

#DEFINES

if(!defined("CONNECT")) {
	define("CONNECT", true);
}

#END DEFINES

include($_SERVER['DOCUMENT_ROOT'].'/Settings.php');

if(CONNECT) {
	$conn = mysqli_connect($localhost, $dbuser, $dbpass, $dbname) or die('Could not connect: ' . mysqli_error($conn));
}

//Security

function NewSessionId() { 
    $guidText = bin2hex(openssl_random_pseudo_bytes(16));
    return $guidText;
}

function NewGuid($input = null) { 
	if(empty($input)) {$input = str_replace(".", "", $_SERVER['REMOTE_ADDR']);}
    $s = strtoupper(md5(base64_encode($input))); 
    $guidText = 
        substr($s,0,8) . '-' . 
        substr($s,8,4) . '-' . 
        substr($s,12,4). '-' . 
        substr($s,16,4). '-' . 
        substr($s,20); 
    return $guidText;
}

//User Related

function addUserToWeb($username, $password, $email) {
	global $conn;

	$ip = $_SERVER['REMOTE_ADDR'];
	$now = time();
	$code = NewGuid();

	$query = mysqli_query($conn, "INSERT INTO users (username, password, ip, email, reg_time, last_activity, code) VALUES ('$username', '$password', '$ip', '$email', '$now', '$now', '$code')") or die('Error at line '.__LINE__);
	return true;
}

function addUserToDB($username, $password, $email, $unique_id, $linked_id = 0) {
	global $conn;

	$unique_id = mysqli_real_escape_string($conn, strip_tags($unique_id));
	if(!mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `synth-surv_users` WHERE unique_id = '$unique_id'"))) {
		if(!$linked_id) {
			$linked_id = "(SELECT MAX(id) FROM users)";
		} else {
			$linked_id = "'".mysqli_real_escape_string($conn, strip_tags($linked_id))."'";
		}
		$ip = $_SERVER['REMOTE_ADDR'];
		$now = time();

		$query = mysqli_query($conn, "INSERT INTO `synth-surv_users` (linked_id, username, password, ip, email, reg_time, unique_id) VALUES ($linked_id, '$username', '$password', '$ip', '$email', '$now', '$unique_id')") or die('Error at line '.__LINE__);
	} else {
		echo getError('idAlreadyExists');
		exit;
	}
	return true;
}

function userExistInWeb($username, $password, &$user) {
	global $conn;
	$username = mysqli_real_escape_string($conn, strip_tags($username));
	$password = md5($password);
	$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$password'") or die('Error at line '.__LINE__);
	$user = mysqli_fetch_assoc($query);
	return mysqli_num_rows($query) > 0;
}

function userExistInDB($username, $password, &$user) {
	global $conn;
	$username = mysqli_real_escape_string($conn, strip_tags($username));
	$password = md5($password);
	$query = mysqli_query($conn, "SELECT * FROM `synth-surv_users` WHERE username = '$username' AND password = '$password'") or die('Error at line '.__LINE__);
	$user = mysqli_fetch_assoc($query);
	return mysqli_num_rows($query) > 0;
}

function setUserKey($username, $key) {
	global $conn;
	$username = mysqli_real_escape_string($conn, strip_tags($username));
	$key = mysqli_real_escape_string($conn, strip_tags($key));
	$query = mysqli_query($conn, "SELECT * FROM `synth-surv_users` WHERE username = '$username'") or die('Error at line '.__LINE__);
	$exists = mysqli_num_rows($query) > 0;
	if($exists) {
		$user = mysqli_fetch_assoc($query);
		$now = time();
		$gameplay_time = (int)($now - $user['last_connection']);
		$last_conn = ", last_connection = '".$now."'";
		$gm_str = "";
		if(empty($key)) {
			$last_conn = "";
			$gm_str = ", gameplay_time = gameplay_time + $gameplay_time";
		}
		mysqli_query($conn, "UPDATE `synth-surv_users` SET session_id = '$key'$last_conn$gm_str WHERE username = '$username'") or die('Error at line '.__LINE__);
		return true;
	} else {
		echo getError('notRegistered');
		return false;
	}
	return true;
}

function unsetUserKey($username) {
	return setUserKey($username, '');
}

function isValidKey($username, $key) {
	global $conn;
	$username = mysqli_real_escape_string($conn, strip_tags($username));
	$key = mysqli_real_escape_string($conn, strip_tags($key));
	return mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `synth-surv_users` WHERE username = '$username' AND session_id = '$key'")) > 0;
}

function correctPassword($username, $password) {
	global $conn;
	$username = mysqli_real_escape_string($conn, strip_tags($username));
	$password = md5($password);
	return mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `synth-surv_users` WHERE username = '$username' AND password = '$password'")) > 0;
}

//Information

function getError($str, $lang = false) {
	$msg = $str;
	switch ($str) {
		case 'emptyUsername':
			$msg = "El nombre de usuario especificado es una cadena de texto vacía, por favor, rellene este campo (es obligatorio).";
			break;

		case 'emptyPassword':
			$msg = "La contraseña especificada es una cadena de texto vacía, por favor, rellene este campo (es obligatorio).";
			break;

		case 'emptyEmail':
			$msg = "El correo electrónico especificado es una cadena de texto vacía, por favor, rellene este campo (es obligatorio).";
			break;

		case 'notRegistered':
			$msg = "No estás registrado, debes registrarte primero para poder conectarte.";
			break;

		case 'forbiddenChars':
			$msg = "El nombre de usuario especificado contiene caracteres no válidos. Caracteres especiales permitidos: '.', '-' y '_'";
			break;

		case 'invalidEmailFormat':
			$msg = "El email que has especificado no es válido, debes usar el siguiente formato: nombre@dominio.com";
			break;

		case 'alreadyRegistered':
			$msg = "Ya estás registrado. ¿Has olvidado tu contraseña?, visita la página web para recuperarla.";
			break;

		case 'weakPassword':
			$msg = "La contraseña que vas a usar es demasiado pequeña, escribe al menos más de 6 caracteres.";
			break;

		case 'idAlreadyExists':
			$msg = "No puedes registrar más de una cuenta desde el mismo ordenador. Lo siento. :(";
			break;
	}
	return $msg."\n";
}

?>