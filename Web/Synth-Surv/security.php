<?php

//Functions

$seed = 667;
$my_key = "ikillnukes is cool";

include('ikill-encrypt.php');

//Debug purpouses

function dump($data) {
    if(is_array($data)) { //If the given variable is an array, print using the print_r function.
        print "-----------------------\n";
        print_r($data);
        print "-----------------------";
    } elseif (is_object($data)) {
        print "==========================\n";
        var_dump($data);
        print "===========================";
    } else {
        print "=========&gt; ";
        var_dump($data);
        print " &lt;=========";
    }
}

//Code

/*

Tareas:

=> Guardar intentos (id, username & password tried, ip, time)
=> Banear ips si se ha intentado más de una vez
=> ¿Comprobar proxies?

*/

if(@$_GET['get'] === 'latest') {
  echo 'Latest version';
  exit;
}

$my_secret_key = '@uw716K8ÑV53JydCñ';

//1ª etapa

if(@$_GET['ikill_key'] != $my_secret_key)  {
  if(empty($_SERVER['HTTP_AUTHORIZATION'])) {
    echo '<center style="display:block;height:40px;position:relative;top:50%;margin-top:-20px;"><h1 style="line-height:40px;">Area restringida</h1></center>'; //redirect?
    exit;
  }
} else {
  echo encrypt('Sneruj87:Seazoux_98(Ikillñukes)F');
  exit;
}



//2ª etapa [si alguien intenta algo aquí avisar de inmediato e intentar banear la ip que entró aquí]

//user => password
$users = array('Sneruj87' => 'Seazoux_98(Ikillñukes)F');

if (isset($_SERVER['HTTP_AUTHORIZATION']) && preg_match('/Basic\s+(.*)$/i', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
    list($name, $password) = explode(':', base64_decode($matches[1]));
    if(isset($users[$name]) && utf8_decode($users[$name]) != $password) {
      echo '<center style="display:block;height:40px;position:relative;top:50%;margin-top:-20px;"><h1 style="line-height:40px;">Area restringida1</h1></center>'; //redirect?
      exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  //header("HTTP/1.0 405 Method Not Allowed", true, 405); //Para no dar pistas he quitado el header
  //echo "Datos Erroneos!"; //Aunque acierten el usuario y la contraseña en el navegador se seguirá mostrando el error de que los datos son incorrectos
  exit;
}

?>