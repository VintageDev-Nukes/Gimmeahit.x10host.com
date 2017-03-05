<?php

echo '<script>
function check() {
(document.getElementById("infinite").checked == true) ? max() : min();
}
function max() {
	document.getElementById("duration").disabled = true;
	document.getElementById("duration").value = document.getElementById("duration").max;
}
function min() {
	document.getElementById("duration").disabled = false;
	document.getElementById("duration").value = document.getElementById("duration").min;
}
</script>';
//showInfo();
echo '<center>
	<form method="post" onajaxsuccess="window.location = lastpage; localStorage.setItem(\'redirect\', \'\');"> <!--- action="http://'.$_SERVER['SERVER_NAME'].'/forms/login.php" -->
		<h4>Usuario:</h4>
		<input name="username" type="text" maxlength="20" />
		<br>
		<h4>Contraseña:</h4>
		<input name="password" type="password" />
		<br>
		<h4>Tiempo de expirado:</h4>
		<input id="duration" name="duration" type="number" min="10" max="31556926" value="10" />
		<br>
		<input id="infinite" type="checkbox" onClick="check()" />
		<label for="infinite">Sesión infinita</label>
		<br><br>
		<input name="login" type="submit" value="¡Conéctame!" />
	</form>
</center>';

?>