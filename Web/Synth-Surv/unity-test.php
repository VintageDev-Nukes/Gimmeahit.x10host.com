<?php

include('funcs.php');

include('security.php');

if(isset($_POST) && isset($_POST['action'])) {
	switch($_POST['action']) {
		case 'login':
			$username = mysqli_real_escape_string($conn, strip_tags($_POST['username']));
			$password = mysqli_real_escape_string($conn, strip_tags($_POST['password']));
			$show_key = false;
			if(isset($username) && isset($password)) {
				$user_web = null;
				$user_db = null;
				$user_in_web = userExistInWeb($username, $password, $user_web);
				$user_in_db = userExistInDB($username, $password, $user_db);
				if(!$user_in_web && !$user_in_db) { //User is not registered anywhere yet
					//So we have to send alert to the game
					echo getError('notRegistered');
				} else { //We can register the user without asking because it has part of it's information in the other table
					if($user_in_web && !$user_in_db) { //User is only registered at web
						$show_key = addUserToDB($user_web['username'], $user_web['password'], $user_web['email'], "", $user_web['id']);
					} else if(!$user_in_web && $user_in_db) { //User is only registered at DB
						$show_key = addUserToWeb($user_db['username'], $user_db['password'], $user_db['email']);
					} else { //In both places
						$show_key = true;
					}
				}
			} else {
				if(empty($username)) {
					echo getError('emptyUsername');
				} else if(empty($password)) {
					echo getError('emptyPassword');
				}
			}
			if($show_key) {
				$key = NewSessionId();
				if(setUserKey($username, $key)) echo "OK 200:".$key;
			}
			break;

		case 'register':
			$username = mysqli_real_escape_string($conn, strip_tags($_POST['username']));
			$password = mysqli_real_escape_string($conn, strip_tags($_POST['password']));
			$email = mysqli_real_escape_string($conn, strip_tags($_POST['email']));
			$unique_id = mysqli_real_escape_string($conn, strip_tags($_POST['unique_id']));
			$show_success = false;
			//Check for invalid email, forbidden chars, etc...
			if(isset($username) && isset($password) && isset($email)) {
				if(preg_match('/\^|`|\*|\+|<|>|\[|\]|¨|´|\{|\}|\||\\|\"|\@|·|\#|\$|\%|\&|\¬|\/|\(|\)|=|\?|\'|¿|ª|º/', $username)) {
					echo getError('forbiddenChars');
					exit;
				}
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					echo getError('invalidEmailFormat');
					exit;
				}
				if(strlen($password) < 6) {
					echo getError('weakPassword');
					exit;
				}
				$user_web = null;
				$user_db = null;
				$user_in_web = userExistInWeb($username, $password, $user_web);
				$user_in_db = userExistInDB($username, $password, $user_db);
				if(!$user_in_web && !$user_in_db) { //User is not registered anywhere yet
					//So we proceed to register it on both places...
					if(addUserToWeb($username, md5($password), $email)) {
						$show_success = addUserToDB($username, md5($password), $email, $unique_id);
					}
				} else {
					//The following two conditions abound on the code because we only have to register the user on the BD, but maybe we can fill all the tables
					if($user_in_web && !$user_in_db) { //User is only registered at web
						$show_success = addUserToDB($user_web['username'], $user_web['password'], $user_web['email'], "", $user_web['id']);
					} else if(!$user_in_web && $user_in_db) { //User is only registered at DB
						$show_success = addUserToWeb($user_db['username'], $user_db['password'], $user_db['email']);
					} else { //In both places
						//User already registered, show alert... ¿Did you forget your password?
						echo getError('alreadyRegistered');
					}
				}
			} else {
				if(empty($username)) {
					echo getError('emptyUsername');
				} else if(empty($password)) {
					echo getError('emptyPassword');
				} else if(empty($email)) {
					echo getError('emptyEmail');
				}
			}
			if($show_success) {
				echo 'Registered correctly';
			}
			break;

		case 'checkForUpdates':
			# code...
			break;

		// vv Actions that need main key to work vv

		case 'logout':
			if(isset($_POST['key'])) {
				$key = mysqli_real_escape_string($conn, strip_tags($_POST['key']));
				$username = mysqli_real_escape_string($conn, strip_tags(@$_POST['username']));
				$password = mysqli_real_escape_string($conn, strip_tags(@$_POST['password']));
				if(isset($_POST['username']) && isset($_POST['password']) && correctPassword($username, $password) && isValidKey($username, $key)) unsetUserKey($username);
			}
			break;
		
		default:
			break;
	}
}

?>