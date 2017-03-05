<?php

include('processor.php');

if(isOnline()) {
	$my_stats = getUStats();
} else {
	$my_stats = getVStats();
}

if((isOnline() && $my_stats['timezone'] == -1) || !isOnline()) {
  date_default_timezone_set(getLocation($_SERVER['REMOTE_ADDR'], 1)['timezone']);
} else {
  date_default_timezone_set(timezone_identifiers_list()[(int)$my_stats['timezone']]);
}

$dst = isOnline() && !$my_stats['detect_dst'];

$date_format = $date_formats[1];

if(isOnline() && $my_stats['date_format'] != -1) {
  $date_format = $date_formats[(int)$my_stats['date_format']];
}

echo '<!DOCTYPE html>
<html>
<head>';

$ref = @$_GET['ref'];
if(isset($ref) && !isOnline()) {
  //header("Location: http://".$_SERVER['SERVER_NAME']."/index.php?action=register&ref=".$ref);
  setcookie("ref", $ref, time()+86400);
  //redirect($_SERVER['SCRIPT_URI']);
}

  echo '<meta charset="UTF-8">
	<title>Inicio</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="scripts/progress-circle.js"></script>
  <script src="scripts/web.js"></script>
  <link rel="stylesheet" type="text/css" href="styles/web.css" />
  <link rel="stylesheet" type="text/css" href="styles/flags.css" />
  <link href="styles/circle.css" rel="stylesheet" type="text/css">
</head>
<body>';

echo '<div id="banner">
    <table style="width:100%;"><tr><td align="left" valign="top" style="width:324px;"><a href="http://'.$_SERVER['SERVER_NAME'].'/index.php"><img src="images/logo.png" /></a></td><td align="center" valign="top">
    <div id="userbox" style="padding:20px 10px 10px 10px;height:80px;margin: 0 auto;width:225px;">
        <center>
	    <h4 title="¡Enviasela a tus amigos para ganar 1 punto por cada persona que entre!" style="color:#fff;">ID de referido</h4>
            <input value="http://'.$_SERVER['SERVER_NAME'].'/?ref='.getVID().'" style="width:100%;" />
	    <br>';
	if(isOnline()) {
	    echo '<span style="color:#fff;">¡Enviasela a tus amigos para ganar 1 punto por cada persona que entre!</span>';
	} else {
	    echo '<div id="coins" class="boxinside" style="width:100px;margin-top:5px;overflow:hidden;">
  		<img src="images/icons/point-bronze.png" />
  		<span>'.$my_stats['points'].'</span>
  	    </div>';
	}
        echo '</center>
    </div></td>
<td align="right" valign="top" style="width:395px;">
	<div id="userbox" style="height:110px;float:right;">'; 

//or $my_stats != null
if(isOnline()) {

echo '<div style="padding:7.5px;float:left;">
  <style>
  .prog-circle .after {
    background: url('.(($my_stats['avatar'] != null) ? $my_stats['avatar'] : 'images/user.png').');
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    background-color: #fff;
  }
  </style>
  <div id="circle"><span><img src="images/icons/bullet_star.png" />Nivel '.$my_stats['lvl'].'</span></div>
  <div class="exp">0xp/100xp</div>';
  //<div id="level" style="position: relative;top: -75px;margin-left: 28px;">'.$my_stats['lvl'].'</div>
echo '</div>
<div style="float:right;padding: 5px 10px 0 5px;min-width: 135px;">
  <center>
    <a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account" style="text-decoration:none;display: inline-block;position: relative;top: 2px;">
      <h2 style="display:inline;color:white;font-weight:bold;color:white;">'.getCUser().'</h2>
      <!--- <img src="images/icons/ext.png" /> -->
    </a>
    <div class="u-menu" style=" height: 16px; padding: 5px 0;">
      <img src="images/icons/notifications.png" style=" padding: 0 5px 0 0; "><span class="v-sep"></span>
      <a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&go=edit"><img src="images/icons/config.png" style=" padding: 0 5px; "></a><span class="v-sep"></span>
      <span onclick="sendCustomAjax(this, \'logout='.@$_COOKIE['SESSION_ID'].'\');" onajaxsuccess="window.location = lastpage; localStorage.setItem(\'redirect\', \'\');"><img src="images/icons/log_out.png" style="padding: 0 0 0 5px;"></span>
    </div>
  </center>
  <div id="coins" class="boxinside" style="min-width:53.25px;float:left;">
  	<img src="images/icons/point-bronze.png" />
  	<span>'.$my_stats['points'].'</span>
  </div>
  <div id="coins" class="boxinside" style="min-width:53.25px;float:right;">
  	<img src="images/icons/coin.png" />
  	<span>'.$my_stats['coins'].'</span>
  </div>
  <span style="font-size: 10px;color: #fff;position: relative;top: 3px;left: -18px;">
    <img src="images/icons/add.png" style="position: relative;top: 5px;left: 2px;"> Obtener más coins
  </span>
</div>';

echo "<script>
( function( $ ){
    $( '#circle' ).progressCircle({
      nPercent        : 90,
      showPercentText : false,
      thickness       : 2,
      circleSize      : 80
    });
})( jQuery );
</script>";

} else {
  echo '<div style="padding: 10px;">
  <div style="color:white;"><h3 style="display:inline;">¡Hola visitante!</h3> Por favor, conectate o registrate.<br>¿Olvidaste tu contraseña?<br>¿Perdiste tu email de activación?</div>
  <div class="groupbutton" style="height:32px;float:left;margin:10px 0 0 0px;">
            <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/log_in.png" style="margin-top:8px;width:16px;height:16px;"></div>
            <div class="button" style="border-radius:0px;"><a href="index.php?action=login" style="line-height:32px;text-decoration:none;font-size:16px;">Conectarse</a></div>
          </div>
  <div class="groupbutton" style="height:32px;float:left;margin:10px 0 0 10px;">
            <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/register.png" style="margin-top:8px;width:16px;height:16px;"></div>
            <div class="button" style="border-radius:0px;"><a href="index.php?action=register" style="line-height:32px;text-decoration:none;font-size:16px;">Registrarse</a></div>
          </div>
    <!--- <div class="authbtn"><a href="index.php?action=login">Conectarse</a></div>
    <div class="authbtn"><a href="index.php?action=register">Registro</a></div> -->
  </div>';
}

echo'</div></td></tr></table></div>';

echo '<div id="template">';

echo '<div class="sub">';

echo "<div id='navbar'>
<ul>
   <li class='first'><a href='index.php'><span>Inicio</span></a></li>
   <li class='has-sub'><a href='#'><span>Servicios</span></a>
      <ul>
         <li><a href='#'><span>Proxy List</span></a></li>
         <li><a href='#'><span>Paid2Click</span></a></li>
         <li class='last'><a href='#'><span>Descargas (referal)</span></a></li>
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>Comunidad</span></a>
      <ul>
         <li><a href='#'><span>Foro</span></a></li>
         <li><a href='#'><span>Blog</span></a></li>
         <li class='last'><a href='#'><span>Mercado</span></a></li>
      </ul>
   </li>
   <li class='has-sub last'><a href='#'><span>Proyectos</span></a>
      <ul>
         <!--- <li><a href='#'><span>Equipo de desarrolladores</span></a></li> -->
         <li><a href='index.php?action=fa-api'><span>ForoActivo API</span></a></li>
         <li><a href='#'><span>Sykoland</span></a></li>
         <li><a href='#'><span>Proyectos Unity</span></a></li>         
         <li class='last'><a href='#'><span>Todos mis proyectos</span></a></li>
      </ul>
   </li>
</ul>
</div>";

//echo $_SERVER['REQUEST_URI'];

//showInfo();

//echo '<div id="success">...<img style="float:right;position: relative;top: -1px;" src="images/icons/close.png" onclick="hideElem(this.parentNode);"></div>';

echo '<div id="HTMLInfo"></div>';

$go = @$_GET['action'];
if(empty($go)){$go='index';}
 
switch($go){

  case 'index':
    include(INCLUDES.'mainIndex.php');
    break;

  case 'account':
    include(INCLUDES.'profile.php');
    break;
 
  case 'register':
    include(INCLUDES.'register.php');
    break;

  case 'login':
    include(INCLUDES.'login.php');
    break;

  case 'act':
    include(INCLUDES.'activation.php');
    break;

  case 'fa-api':
    include(INCLUDES.'fa-api.php');
    break;

  case 'release-notes':
    include(INCLUDES.'rnotes.php');
    break;
 
  default:
    include('404.php');
    break;
}

echo '</div>
</div>
<div id="footer">Página hecha por Ikillnukes<br>&copy; 2014 - <script>document.write((new Date()).getFullYear().toString());</script></div>';

echo '</body>
</html>';

//Free memory
if(mysqli_ping($conn)) {mysqli_close($conn);}

?>