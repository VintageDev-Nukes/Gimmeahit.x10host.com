<?php 

if(!isOnline() || getUID() == 1) {
	echo '<!DOCTYPE html>
	<html>
	<head>
		<title>¡Registarse!</title>
	</head>
	<body>';
			//showInfo();
			echo '<center>';
	/*		if(!($_SERVER['HTTP_X_FORWARDED_FOR']
	   || $_SERVER['HTTP_X_FORWARDED']
	   || $_SERVER['HTTP_FORWARDED_FOR']
	   || $_SERVER['HTTP_CLIENT_IP']
	   || $_SERVER['HTTP_VIA']
	   || in_array($_SERVER['REMOTE_PORT'], array(8080,80,6588,8000,3128,553,554,9999,9000))
	   || @fsockopen($_SERVER['REMOTE_ADDR'], 80, $errno, $errstr, 3))) { //codeCheck($_SERVER['REMOTE_ADDR'], $_POST['code']) (Fatal error)*/
			echo '<form action="http://'.$_SERVER['SERVER_NAME'].'/forms/registration.php" method="post">
				<h4>Usuario:</h4>
				<input name="username" type="text" maxlength="20" />
				<br>
				<h4>Contraseña:</h4>
				<input name="password" type="password" />
				<br>
				<h4>Confirme contraseña:</h4>
				<input name="pass_confirm" type="password" />
				<br>
				<h4>Email:</h4>
				<input name="email" type="text" />
				<br>
				<h4>Captcha:</h4>
				<img src="/c/captcha.php"/><br>
				<input type="text" name="vercode" />
				<input type="hidden" name="ref" value="'.@$_COOKIE['ref'].'" />
				<br><br>
				<input name="newreg" type="submit" value="¡Registrarse!" />
			</form>';
		/*} else {
			echo '<h1>El registro con proxies no está permitido, por favor, contacte con el administrador para recibir un código y así poder entrar:</h1>
			<form action="http://'.$_SERVER['SERVER_NAME'].'/index.php?action='.$_GET['action'].'" method="post"><input name="code" type="text" /></form>';
		}*/
		echo '</center>
	</body>
	</html>';
} else {
	goHtmlIndex();
}

?>