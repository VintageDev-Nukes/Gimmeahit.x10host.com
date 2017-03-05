<?php

//Session

session_start();

#DEFINES

define("INCLUDES", $_SERVER['DOCUMENT_ROOT']."/includes/", true);
define("MAX_ATTEMPS", 5, true);
define("ONLINE_TIME", 30, true); //Tiempo para que un usuario se considere conectado globalmente *tengo que cambiar su uso actual*
define("SESSION_TIME", 30, true); //Tiempo el cual estás conectado *tengo que aplicarlo*
define("SHOW_ERRORS", true);
define("FLOOD_TIME", 30, true);
define("MAX_ACTIONS", 10, true);

if(!defined("CONNECT")) {
	define("CONNECT", true);
}

#END DEFINES

include('Settings.php');

if(CONNECT) {
	$conn = mysqli_connect($localhost, $dbuser, $dbpass, $dbname) or die('Could not connect: ' . mysqli_error($conn));
}

if(SHOW_ERRORS) {
	error_reporting(E_ALL);
	ini_set('display_errors', true);
}

/*

Tareas:

=> Notificaciones
=> Evitar reloads en addError y setSuccess [comenzar a usar ajax en el maximo numero de sitios posibles, para hacer todo más fancy] y usar ajax como alternativa
=> Para el ajax reoganizar todo un poco mover aquellos archivos que use $_POST a forms o una carpeta llamada ajax-forms, y luego donde esten todos los scripts de ajax 
o bien moverlos al script principal o bien dejarlos en una carpeta llamada ajax-scripts (por ejemplo) o ajax a secas
=> Aplicar paginator a las replies del Mood y la preview de seguidores, seguidos y amigos
=> Comenzar a organizar, identar, mejorar y optimizar codigo
=> Por seguridad, comenzar a revisar las ips que un usuario utiliza en todas sus conexiones
=> Para añadir puntos comprobar en segundo plano que la ip del usuario vistante no sea ningun tipo de proxy (ya sea tanto registrada como no)
=> En el registro comprobar que el usuario no usa proxies, de lo contrario podria estar beneficiandose de la web creando varias cuentas con una proxy distinta cada una
por eso he dicho de revisar las ips de todas las conexiones que un usuario realiza o bien iventigar acerca del sistema de "Serial Upvote Reversing" de StackOverFlow para hacer un sistema parecido aquí
=> Como haré una pagina para desbloquear paginas del tipo referal downloads, etc, hay que aumentar al maximo la seguridad de la web
=> Añadir modo manterimiento
=> Hacer pagina status (como x10host) para los momentos de mantenimiento
=> Hacer 3 includes, uno un login independiente otro un registro y otro un login y registro combinados (ajax) [no se si hacer una popup]

*/

//Variables

$msg = "";
$date_formats = array("D j M - G:i", "D j M Y - G:i", "D j M - G:i:s", "d.m.y G:i", "d/m/y, h:i a", "D d M Y, g:i a", "D d M Y, H:i", "D M d, Y g:i a", "D M d Y, H:i", "jS F Y, g:i a", "jS F Y, H:i", "F jS Y, g:i a", "F jS Y, H:i", "j/n/Y, g:i a", "j/n/Y, H:i", "n/j/Y, g:i a", "n/j/Y, H:i", "Y-m-d, g:i a", "Y-m-d, H:i");

//SECURITY RELATED

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

//MISC

function addhit() {

	global $conn; 

	$ip = $_SERVER['REMOTE_ADDR'].":".$_SERVER['REMOTE_PORT'];
	$now = time();

	if(mysqli_num_rows(mysqli_query($conn, "SELECT ip FROM hits WHERE ip = '$ip'"))) {
		mysqli_query($conn, "UPDATE hits SET count = count+1 WHERE ip = '$ip'") or die('Could not continue: '.mysqli_error($conn));
	} else {
		mysqli_query($conn, "INSERT INTO hits (ip, time) VALUES ('$ip', '$now')") or die('Could not continue: '.mysqli_error($conn));
	}

}

//This func set all the info to the visitors (the equivalent of addinfo in TMD (motor.php))
function UpdateInfo() {

	global $conn;

	// #################################################################
	// ######### add IP and user-agent and time or update it ###########
	// #################################################################

	// gather user data
	$ip = $_SERVER["REMOTE_ADDR"];
	if(isOnline()) {
		$id = getUID();
	} else {
		$id = getVID();
	}
	$agent = $_SERVER["HTTP_USER_AGENT"];
	$reg_time = time();
	if(empty($_GET['ref'])) {$refer=null;} else {$refer = mysqli_real_escape_string($conn, $_GET['ref']);}

	if(!mysqli_num_rows(mysqli_query($conn, "SELECT ip FROM visitors WHERE ip = '$ip'"))) // check if the IP is in database
	{   //If not , add it.	
		mysqli_query($conn, "INSERT INTO visitors (ip, user_agent, reg_time, refer_id, last_activity) VALUES ('$ip', '$agent', '$reg_time', '$refer', '$reg_time')") or die('Could not continue: '.mysqli_error($conn));
	} else { //Else, then update some things...
		mysqli_query($conn, "UPDATE visitors SET hits = hits+1 WHERE id = '$id'") or die('Could not continue: '.mysqli_error($conn));
	}

}

function LogCatcher() { //Only Errors

}

function redirect($url) {
	//echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$url.'">'; 
	echo '<script>window.location="'.$url.'";</script>';   
    exit; 
}

function reload() {
	echo '<script>location.reload();</script>';
	exit;
}

function getLocation($ip = null, $api = 0) {
	//Api = 0 => Geoplugin
	//Api = 1 => IP-Api

	if($ip == null) {
		$ip = $_SERVER["REMOTE_ADDR"];
	}

	if($api == 0) {
    	$result = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=".$ip));
	} else {
    	$result = unserialize(file_get_contents("http://ip-api.com/php/".$ip));
	}
    /*if($ip_data && $ip_data->geoplugin_countryName != null){
    	$result['city'] = $ip_data->geoplugin_city;
		$result['region'] = $ip_data->geoplugin_region;
		$result['areaCode'] = $ip_data->geoplugin_areaCode;
		$result['dmaCode'] = $ip_data->geoplugin_dmaCode;
		$result['countryCode'] = $ip_data->geoplugin_countryCode;
		$result['country'] = $ip_data->geoplugin_countryName;
		$result['continentCode'] = $ip_data->geoplugin_continentCode;
		$result['latitude'] = $ip_data->geoplugin_latitude;
		$result['longitude'] = $ip_data->geoplugin_longitude;
		$result['regionCode'] = $ip_data->geoplugin_regionCode;
		$result['region'] = $ip_data->geoplugin_regionName;
		$result['currencyCode'] = $ip_data->geoplugin_currencyCode;
		$result['currencySymbol'] = $ip_data->geoplugin_currencySymbol;
		$result['currencySymbol_UTF8'] = $ip_data->geoplugin_currencySymbol_UTF8;
		$result['currencyConverter'] = $ip_data->geoplugin_currencyConverter;
    }*/
    return $result;
}

function dump($data) {
    if(is_array($data)) { //If the given variable is an array, print using the print_r function.
        print "<pre>-----------------------\n";
        print_r($data);
        print "-----------------------</pre>";
    } elseif (is_object($data)) {
        print "<pre>==========================\n";
        var_dump($data);
        print "===========================</pre>";
    } else {
        print "=========&gt; ";
        var_dump($data);
        print " &lt;=========";
    }
}

function StrDate($date) {

	$str_time = "";

	if(!is_numeric($date)) {
		$date = strtotime($date);
	}

	$diff = time() - $date;

	if($diff < 3) {
		$str_time = 'unos instantes'; 
	} else if($diff < 10) {
		$str_time = 'unos segundos';
	} else if($diff < 60) {
		$str_time = $diff.' segundos';
	} else if($diff >= 60 && $diff < 120) {
        $str_time = floor($diff/60).' minuto';		
	} else if($diff < 3600) {
        $str_time = floor($diff/60).' minutos';		
	} else if($diff >= 3600 && $diff < 7200) {
        $str_time = floor($diff/3600).' hora';		
	} else if($diff < 86400) {
        $str_time = floor($diff/3600).' horas';		
	} else if($diff >= 86400 && $diff < 86400*2) {
        $str_time = floor($diff/86400).' día';		
	} else if($diff < 604800) {
        $str_time = floor($diff/86400).' días';		
	} else if($diff >= 604800 && $diff < 604800*2) {
        $str_time = floor($diff/604800).' semana';		
	} else if($diff < 2592000) {
        $str_time = floor($diff/604800).' semanas';		
	} else if($diff >= 2592000 && $diff < 2592000*2) {
        $str_time = floor($diff/2592000).' mes';		
	} else if($diff < 31536000) {
        $str_time = floor($diff/2592000).' meses';		
	} else if($diff >= 31536000 && $diff < 31536000*2) {
        $str_time = floor($diff/31536000).' año';		
	} else {
        $str_time = floor($diff/31536000).' años';		
	} 

	return $str_time;

}

function ShortNumFormat($num, $dots = false) 
{

	$string = "";

	if($num >= 1000000000000000000000000000000000) {
		$string = formatnumber(round($num/1000000000000000000000000000000000, 1), $dots)."D";
	} else if($num >= 1000000000000000000000000000000) {
		$string = formatnumber(round($num/1000000000000000000000000000000, 1), $dots)."N";
	} else if($num >= 1000000000000000000000000000) {
		$string = formatnumber(round($num/1000000000000000000000000000, 1), $dots)."O";
	} else if($num >= 1000000000000000000000000) {
		$string = formatnumber(round($num/1000000000000000000000000, 1), $dots)."Spt";
	} else if($num >= 1000000000000000000000) {
		$string = formatnumber(round($num/1000000000000000000000, 1), $dots)."Sx";
	} else if($num >= 1000000000000000000) {
		$string = formatnumber(round($num/1000000000000000000, 1), $dots)."Q";
	} else if($num >= 1000000000000000) {
		$string = formatnumber(round($num/1000000000000000, 1), $dots)."Ct";
	} else if($num >= 1000000000000) {
		$string = formatnumber(round($num/1000000000000, 1), $dots)."T";
	} else if($num >= 1000000000) {
		$string = formatnumber(round($num/1000000000, 1), $dots)."B";
	} else if($num >= 1000000) {
		$string = formatnumber(round($num/1000000, 1), $dots)."M";
	} else if($num >= 1000) {
		$string = formatnumber(round($num/1000, 1), $dots)."k";
	} else {
		$string = $num;
	}

	return $string;

}

function QueryDuration(){
   static $querytime_begin;
   list($usec, $sec) = explode(' ',microtime());
    
       if(!isset($querytime_begin))
      {   
         $querytime_begin= ((float)$usec + (float)$sec);
      }
      else
      {
         $querytime = (((float)$usec + (float)$sec)) - $querytime_begin;
         echo sprintf('<center style="position:relative;top:-15px;">La consulta tardó %01.5f segundos</center>', $querytime);
      }
}

function Random($array, $cur, $max) 
{
		
	$num = rand(1, $max);
		
	while(true) 
	{
		if(!in_array($num, $array, true)) 
		{
			if($cur > $max) 
			{
				die('El valor máximo es menor que el valor actual.');
			}
			break;
		} else 
		{
			$num = rand(1, $max);
		}
			
	}
		
	$array[$cur] = $num;
	return $array;
		
}

function perenc($str) 
{
	$newstr = "";
	for($i = 0; $i < strlen($str); $i++) 
	{
		$newstr .= "%".dechex(ord(substr($str, $i, 1)));
	}
	return $newstr;
}

function perdec($dec) 
{
	$newstr = "";
	$decarray = explode("%", $dec);
	for($i = 0; $i < count($decarray); $i++) 
	{
		$newstr .= chr(hexdec($decarray[$i]));
	}
	return $newstr;
}

function mb_unserialize($string) {
    $string = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $string);
    return unserialize($string);
}

function guest_action_popup() {
	return trim(preg_replace('/\s\s+/', ' ', '<div id="guest_popup" class="guest_action_popup" style="position:absolute;top:47px;display:none;">
		<div class="plogin_close_button">x</div>
		<b>Se parte de GimmeAHit!</b><br><br><span style="color:gray;">Esta acción solo está disponible para usuarios de GimmeAHit!</span>  <div class="groupbutton" style="height:32px;float:left;margin:10px 0 0 0px;">
            <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/log_in.png" style="margin-top:8px;width:16px;height:16px;"></div>
            <div class="button" style="border-radius:0px;"><a href="index.php?action=login" style="line-height:32px;text-decoration:none;font-size:16px;">Conectarse</a></div>
          </div>
  <div class="groupbutton" style="height:32px;float:left;margin:10px 0 0 10px;">
            <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/register.png" style="margin-top:8px;width:16px;height:16px;"></div>
            <div class="button" style="border-radius:0px;"><a href="index.php?action=register" style="line-height:32px;text-decoration:none;font-size:16px;">Registrarse</a></div>
          </div></div>'));
}

function uinfo_popup($user_id) {

}

function date_dropdown($year_limit = 0, $lower_year = 1900, $sday = 0, $smonth = 0, $syear = 0){
        //$html_output = ''; '    <div id="date_select" >'."\n";
        //$html_output .= '        <label for="date_day">Date of birth:</label>'."\n";

        /*days*/
        $html_output = '           <select name="date_day" id="day_select">'."\n";
            for ($day = 1; $day <= 31; $day++) {
                $html_output .= '               <option'.(($day ==  $sday) ? ' selected' : '').'>' . $day . '</option>'."\n";
            }
        $html_output .= '           </select>'."\n";

        /*months*/
        $html_output .= '           <select name="date_month" id="month_select" >'."\n";
        $months = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            for ($month = 1; $month <= 12; $month++) {
                $html_output .= '               <option'.(($month ==  $smonth) ? ' selected' : '').' value="' . $month . '">' . $months[$month] . '</option>'."\n";
            }
        $html_output .= '           </select>'."\n";

        /*years*/
        $html_output .= '           <select name="date_year" id="year_select">'."\n";
            for ($year = (date("Y") - $year_limit); $year >= $lower_year; $year--) {
                $html_output .= '               <option'.(($year ==  $syear) ? ' selected' : '').'>' . $year . '</option>'."\n";
            }
        $html_output .= '           </select>'."\n";

        //$html_output .= '   </div>'."\n";
    return $html_output;
}

function getSecs($older_date, $recent_date = null) {
	if($recent_date == null) {$recent_date = time();}
	$r = ((!is_numeric($recent_date)) ? strtotime($recent_date) : $recent_date);
	$o = ((!is_numeric($older_date)) ? strtotime($older_date) : $older_date);
	return $r - $o;
}

function privacydropdown($name, $s = 0) {
	$pdd = '<select name="'.$name.'_pdd">
		<option value="0"'.(($s == 0) ? ' selected' : '').'>Solo a mi</option>
		<option value="1"'.(($s == 1) ? ' selected' : '').'>A mis amigos</option>
		<option value="2"'.(($s == 2) ? ' selected' : '').'>A mis seguidores</option>
		<option value="3"'.(($s == 3) ? ' selected' : '').'>A mis amigos y seguidores</option>
		<option value="4"'.(($s == 4) ? ' selected' : '').'>A todos</option>
	</select>';
	return $pdd;
}

function timezone_dropdown($si = 0) {
	global $conn;
	$stats = getUStats();
	$dst = isOnline() && !$stats['detect_dst'];
	$all = timezone_identifiers_list();
	$last_tm = "";
	$index = 0;
	$final_str = '<select name="timezone">';
	foreach($all as $timezone) {
		$tm = explode('/', $timezone);
		if($tm[0] != $last_tm) {
			if($index != 0) {
				$final_str .= "</optgroup>";
			}
			$final_str .= '<optgroup label="'.$tm[0].'">';
		}
		$offset = date_offset_get(date_create(date("d-m-Y"), timezone_open($timezone)));
		$final_str .= '<option value="'.$index.'"'.(($index == $si) ? ' selected' : '').'>'.((@$tm[1] != null) ? str_replace("_", " ", @$tm[1]) : $timezone)." (UTC ".(($offset >= 0) ? "+" : "-").gmdate('H:i', $offset).") ".gmdate("H:i", time()+$offset-(($dst) ? gmdate("I")*3600 : 0)).'</option>';
		$last_tm = $tm[0];
		if($index == count($all)-1) {
			$final_str .= "</optgroup>";
		}
		$index++;
	}
	$final_str .= "</select>";
	return $final_str;
}

function dateformat_dropdown($si = 0) {
	global $date_formats;
	$index = 0;
	$final_str = '<select name="date_format">';
	foreach($date_formats as $df) {
		$final_str .= '<option value="'.$index.'"'.$index.'"'.(($index == $si) ? ' selected' : '').'>'.date($df).'</option>';
		$index++;
	}
	$final_str .= '</select>';
	return $final_str;
}

function validHexColor($hex_color){
  return preg_match('/^(#|)([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i', $hex_color);
}

function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}

function createCommentBox($type, $other_info = null) {
	$arr = array();
	$my_stats = getUStats();
	$str = '<form method="post" class="comment-form" onclick="cbox_focus(this);" onmouseover="cbox_blur(this);" onajaxsuccess="reload();">
							<div class="avatar-prev" style="background-image: url('.(($my_stats['avatar'] != null) ? $my_stats['avatar'] : 'images/user.png').');"></div>
							<div class="comment_box">
								<textarea %0% name="message" onkeyup="resize(this);'.(($my_stats['allow_warn_onleave']) ? ' if(typeof window.onbeforeunload != \'function\') {window.onbeforeunload = lostChanges;}' : '').'"></textarea>
							</div>
							<input type="hidden" name="type" value="'.$type.'" />
							<div style="display:none;" class="comment-form-send"><input name="new-'.(($type == 0 || $type == 2 || $type == 4) ? 'message' : 'reply').'" type="submit" value="Comentar" /></div>
							%1%
						</form>';
	preg_match_all('/\%\d+\%/', $str, $arr);
	if(empty($other_info) || (int)str_replace('%', '', (string)max($arr[0])) != (count($other_info)-1)) {return;}
	return string_format($str, $other_info);
}

function displayComment($comment, $user_info, $type) {
	global $my_stats, $uid;
	//Type: 0 = Profile message, 1 = Profile comment, 2 = Ticket message, 3 = Ticket comment, 4 = User mood, 5 = Mood comment, 6 = Feedback
	//If Type == -1 we are showing comments of a message
	$is_reply = true;
	if($type != 1 && $type != 3 && $type != 5) {
		$c_type = (($type == 0) ? 0 : (($type == 2) ? 1 : 2));
		$comments = getComments($comment['id'], $c_type);
		$c_str = "\n\n<!--- Show rest of comments to this message -->\n\n";
		foreach($comments as $comm) {
			$c_str .= displayComment($comm, getUStats($comm['user_id']), (($c_type == 0) ? 1 : (($c_type == 1) ? 3 : 5)));
		}
		$is_reply = false;
	}
	return '<div class="comment'.(($is_reply) ? '-sub' : '').'">
								<div class="avatar-prev" style="background-image: url('.((checkGivenRelation($uid, getUID(), (($user_info['show_avatar'] != -1) ? $user_info['show_avatar'] : 4))) ? (($user_info['avatar'] != null) ? $user_info['avatar'] : 'images/user.png') : 'images/user.png').');"></div>
								<div'.((isOnline()) ? ' style="padding-bottom:19px;"' : '').'>
									<b style="font-size: 13px;">'.$user_info['username'].'</b> <span style="font-size: 11px;color: #666;">escribió hace '.StrDate($comment[((@$comment['action_time'] != null) ? 'action_time' : 'creation_time')]).'</span><br>
									<span style="color: #333;">'.$comment['message'].'</span>
									'.((isOnline()) ? '<div class="comment_options">'.((!$is_reply) ? '<img src="images/icons/comments.png" onclick="showElem(this.parentNode.parentNode.parentNode.childNodes[4].childNodes[1]);" /> · ' : '').'<span class="votes">'.getVotes($comment['id']).'</span> <img src="images/icons/thumb_up.png" style="transform: rotateY(180deg);" onclick="sendCustomAjax(this, \'vote=up&linked_id='.$comment['id'].'&type='.$type.'\', \'var html2 = localStorage.getItem(\\\'infoHTML\\\'); el.parentNode.getElementsByClassName(\\\'votes\\\')[0].innerHTML = (parseInt(el.parentNode.getElementsByClassName(\\\'votes\\\')[0].innerHTML)+((html2.indexOf(\\\'Voto recibido\\\') > -1) ? 1 : 0)).toString();\'); showInfo(null);" /> <img src="images/icons/thumb_down.png" onclick="sendCustomAjax(this, \'vote=down&linked_id='.$comment['id'].'&type='.$type.'\', \'var html2 = localStorage.getItem(\\\'infoHTML\\\'); el.parentNode.getElementsByClassName(\\\'votes\\\')[0].innerHTML = (parseInt(el.parentNode.getElementsByClassName(\\\'votes\\\')[0].innerHTML)-((html2.indexOf(\\\'Voto anulado\\\') > -1) ? 1 : 0)).toString();\'); showInfo(null);" />'.(($type == 4) ? ' · 0 <img src="images/icons/heart_add.png" /> · 0 <img src="images/icons/shareaholic.png" />' : '').((@$_GET['see'] != 'unapproved-replies') ? (($comment['user_id'] == getUID()) ? '<div style="float: right;"><img src="images/icons/comment_edit.png" /> · <img src="images/icons/comment_delete.png" /> · <img src="images/icons/comments_delete.png" /></div>' : (($uid == getUID()) ? '<div style="float: right;"><img src="images/icons/comment_delete.png" /> · <img src="images/icons/report.png" /> · <img src="images/icons/block_user.png" /></div>' : '')) : '').'</div>': '').'
								</div>'
								.((!$is_reply) ? '<div>
									'.((isOnline()) ? '<div style="display: none;">
									'.((!$is_reply) ? createCommentBox($type, array('placeholder="Publica un comentario" ph="Publica un comentario"', '<input type="hidden" name="linked_id" value="'.$comment['id'].'" /><input type="hidden" name="type" value="'.$c_type.'" />')) : '').'
									</div>' : '').'
								</div>
								'.$c_str : '').
							'</div>';
}

function string_format($str, $arr) {
	if(count($arr) == 0) {return;}
	$final_str = $str;
	for($i = 0; $i < count($arr); $i++) {
		$final_str = str_replace("%".$i."%", $arr[$i], $final_str);
	}
	return $final_str;
}

function multiarray_search($array, $key, $value)
{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, multiarray_search($subarray, $key, $value));
        }
    }

    return $results;
}


//Las variables de este tipo de funciones no necesitan parseo alguno porq ya están parseadas por defecto
function getVotes($id) {
	global $conn;
	if(@$id == null) {return false;}
	$q = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(vote) AS total FROM vote WHERE linked_id='$id'"));
	return (($q['total'] != null) ? $q['total'] : 0);
}

function actionExists($id, $type, $uid = null, &$arr = null) {
	global $conn;
	if($uid == null) {$uid = getUID();}
	if($arr == null) {$arr = array();}
	$q = mysqli_query($conn, "SELECT * FROM vote WHERE user_id = '$uid' AND type = '$type' AND linked_id = '$id'");
	$arr = mysqli_fetch_array($q);
	return mysqli_num_rows($q) > 0;
}

//VISITOR RELATED

function getVID($ip = null) {
	global $conn;
	if($ip == null) { //Me
		//Isonline no tiene q tenerse en cuenta debido a que lo uso para obtener la id de refer 
		$me = $_SERVER['REMOTE_ADDR'];
		return mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM visitors WHERE ip = '$me'"))['id'];
	} else {
		return mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM visitors WHERE ip = '$ip'"))['id'];
	}
}

function getVStats($id = null) {
	global $conn;
	if($id != null) {
		return mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM visitors WHERE id = '$id'"));
	} else {
		$me = getVID();
		return mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM visitors WHERE id = '$me'"));
	}
}

//USER RELATED

function addUser($username, $password, $code, $email, $ref) {

	global $conn;

	$ip = $_SERVER['REMOTE_ADDR'];
	$now = time();

	mysqli_query($conn, "INSERT INTO users (username, password, ip, reg_time, email, last_activity, code, ref_id) VALUES ('$username', '$password', '$ip', '$now', '$email', '$now', '$code', '$ref')");

	setSuccess("register");

}

function checkUser($username, $password, $code = null) {

	global $conn;

	$stats = getUStats(getUID($username));

	$user = $stats['username'];
	$pass = $stats['password'];
	$cd = $stats['code'];

	if($code != null) {
		return (strcmp($username, $user) === 0) && (strcmp($password, $pass) === 0) && (strcmp($code, $cd) === 0); 
		//mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE username = '$username' AND password = '$password' AND code = '$code'"));
	} else {
		return (strcmp($username, $user) === 0) && (strcmp($password, $pass) === 0);
		//mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE username = '$username' AND password = '$password'"));
	} 

}

function userExists($username) {

	global $conn;
		
	return mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'"));

}

function Attemps($name, $action) {
	if(empty($_SESSION['ATTEMPS'])) {$attempArray = array();} else {$attempArray = $_SESSION['ATTEMPS'];}
	switch ($action) {

		case 'set':
		case 'reset': //Sets or reset all the attemps
			$attempArray[$name] = array('ATTEMPS' => MAX_ATTEMPS);
			break;
		
		case 'substract':
			if($attempArray[$name] != null) {
				$attempArray[$name]['ATTEMPS'] -= 1;
			} else {
				Attemps($name, 'set');
				$attempArray[$name]['ATTEMPS'] = MAX_ATTEMPS - 1;
			}
			break;

		case 'get':
			if($attempArray[$name] != null) {
				return $attempArray[$name]['ATTEMPS'];
			} else {
				Attemps($name, 'set');
				return MAX_ATTEMPS;
			}
			break;

		default:
			return;
			break;
	}
	$_SESSION['ATTEMPS'] = $attempArray;
}

//Obsolete
function register($username, $password, $email, $ref) {

	$continue = false;

	if (empty($_POST["vercode"]))  {
		addError('emptyCaptcha'); 
	} elseif($_POST["vercode"] != $_SESSION["vercode"]) {
		addError('incorrectCaptcha'); 
	} 

	if(empty($username)) {
		addError('emptyUsername');
	} elseif(preg_match('/\^|`|\*|\+|<|>|\[|\]|¨|´|\{|\}|\||\\|\"|\@|·|\#|\$|\%|\&|\¬|\/|\(|\)|=|\?|\'|¿|ª|º/', $username)) {
		addError('forbiddenChars');
	} elseif(userExists($username)) {
		addError('userExists');
	}

	if(empty($password)) {
		addError('emptyPassword');
	} else {
		$password = md5($password);
	}

	if(empty($email)) {
		addError('emptyEmail');
	} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		addError('invalidMail');
	} 

	if(count($_SESSION['errors']) == 0) {
		$continue = true;
	}

	if($continue) {
		$code = NewGuid();
		addUser($username, $password, $code, $email, $ref);
	}

}

function loginAttemps($action) {

	switch ($action) {
		case 'wrong':
			if (isset($_SESSION['LOGIN_LAST_ATTEMP']) && (time() - $_SESSION['LOGIN_LAST_ATTEMP'] > ONLINE_TIME*60)) {
	    		Attemps('LOGIN', 'reset');
			} else {
				Attemps('LOGIN', 'substract');
			}
			break;
		
		default:
			return;
			break;
	}

}

//Obsolete
function login($expireTime, $username, $password) {
	$continue = true;
	if(empty($username)) {
		$continue = false;
		addError('emptyUsername');
	}
	if(empty($password)) {
		$continue = false;
		addError('emptyPassword');
	}
	if(is_numeric($expireTime)) {
		$expireTime = (int)$expireTime;
	} else {
		$continue = false;
		addError('hackTry');
	}
	if(!empty($username) && !empty($password) && !checkUser($username, md5($password)) && Attemps('LOGIN', 'get') > 0) {
		$continue = false;
		loginAttemps('wrong');
		if(Attemps('LOGIN', 'get') == 0) {
			addError('attempsWasted');
		} else {
			addError('wrongCredentials');
		}
	}
	if($continue) {
		$code = getUStats(getUID($username))['code'];
		$session_id = md5($username.md5($password).$code);
		setcookie("SESSION_ID", base64_encode($username."|".$session_id), time()+$expireTime*60, "/");
		//echo '<script>alert("'.$_SESSION['last_page'].'");</script>';
		setSuccess("login", $_SESSION['last_page']);
	}
}

function getCUser() {
	return explode("|", base64_decode(@$_COOKIE['SESSION_ID']))[0];
}

function getUID($username = null) {
	global $conn;
	if($username == null) { //Me
		if(isOnline()) {
			$me = getCUser();
			return mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM users WHERE username = '$me'"))['id'];
		} else {
			return 0;
		}
	} else {
		return mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'"))['id'];
	}
}

function getUStats($id = null) {
	global $conn;
	if($id != null) {
		return mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'"));
	} else {
		$me = getUID();
		if($me != 0) {
			return mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE id = '$me'"));
		} else {
			return null;
		}
	}
}

function isOnline($id = null) {
	global $conn;
	if($id == null) { //Myself
		if(array_key_exists('SESSION_ID', $_COOKIE)) {
			$me = getCUser();
			$stats = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE username = '$me'"));
			$session_id = md5($stats['username'].$stats['password'].$stats['code']);
			return @$_COOKIE['SESSION_ID'] === base64_encode($stats['username']."|".$session_id);
		} else {
			return false;
		}
	} else {
		return mysqli_fetch_array(mysqli_query($conn, "SELECT last_activity FROM users WHERE id = '$id'"))['last_activity'] + ONLINE_TIME*60 > time();
	}

}

//Obsolete
function logout($token) {
	global $conn;
	if(array_key_exists('SESSION_ID', $_COOKIE)) {
		$me = getCUser();
		$stats = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE username = '$me'"));
		$session_id = md5($stats['username'].$stats['password'].$stats['code']);
		if($token === base64_encode($stats['username']."|".$session_id)) {
			setcookie("SESSION_ID", "", time()-3600, '/', '', 0);
			unset($_COOKIE['SESSION_ID']);
			setSuccess("logout", $_SESSION['last_page']);
		}
	}
}

function safeRegCodeCheck($ip, $code) {
	global $conn;
	return mysqli_num_rows(mysqli_query($conn, "SELECT id FROM safereg WHERE ip = '$ip' AND code = '$code'"));	
}

function addRelation($user_id, $second_id, $relation_type) {
	global $conn;
	if(!isOnline() || $user_id == null || $second_id == null || $relation_type == null || $user_id == $second_id) {
		exit;
	}
	if(!(($relation_type === 1 || $relation_type === 2 | $relation_type === 4 || $relation_type === 5) && internalRelationCheck($user_id, $second_id, $relation_type))) {
		$p_relation = 0; //Processed relation
		$u_info = getUStats((($user_id != getUID()) ? $user_id : $second_id)); //Obtener la información del otro usuario
		switch ($relation_type) {
			case -1: //Delfriend
				if(isFriend($user_id, $second_id)) {
					if(!isFollowing($user_id, $second_id)) {
						$p_relation = 0;
					} else if(isFollowing($user_id, $second_id)) {
						$p_relation = 2;
					}
				}
				break;

			case -2: //Unfollow
				if(isFollowing($user_id, $second_id)) {
					if(!isFriend($user_id, $second_id)) {
						$p_relation = 0;
					} else if(isFriend($user_id, $second_id)) {
						$p_relation = 1;
					} else if(isPending($user_id, $second_id)) {
						$p_relation = 5;
					}
				}
				break;

			case -3: //Remove pending (creo que está de al revés)
				$t_us = $user_id;
				$user_id = $second_id;
				$second_id = $t_us;
				if(isPending($user_id, $second_id)) {
					if(!isFollowing($user_id, $second_id)) {
						$p_relation = 0;
					} else {
						$p_relation = 2;
					}
				}
				break;

			case -4: //Decline req
				if(isPending($user_id, $second_id)) {
					if(!isFollowing($user_id, $second_id)) {
						$p_relation = 0;
					} else {
						$p_relation = 2;
					}
				}
				break;

			/*case -3: //Unblock
				$p_relation = 0;
				break;*/

			case 1: //Add buddy
				if(!isFriend($user_id, $second_id)) {
					if($u_info['allow_friend_req']) {
						if(!isFollowing($user_id, $second_id)) {
							$p_relation = 5; //Only pending
						} else {
							$p_relation = 6; //Follow + request sended
						}
					} else {
						if(!isFollowing($user_id, $second_id)) {
							$p_relation = 1;
						} else {
							$p_relation = 3;
						}
					}
				}
				break;
			
			case 2: //Follow
				if(!isFollowing($user_id, $second_id)) {
					if(!isFriend($user_id, $second_id)) {
						$p_relation = 2;
					} else if(isFriend($user_id, $second_id)) {
						$p_relation = 3;
					} else if(isPending($user_id, $second_id)) {
						$p_relation = 6;
					}
				}
				break;

			case 4: //Block
				$p_relation = 4;
				break;

			case 0: //Unblock
				$p_relation = 0;
				break;

			default:
				addError("hackTry", false);
				break;
		}
		$now = time();
		if(!mysqli_num_rows(mysqli_query($conn, "SELECT * FROM relation_table WHERE user_id = '$user_id' AND second_id = '$second_id'"))) {
			$r = mysqli_query($conn, "INSERT INTO relation_table (user_id, second_id, relation_type, action_time) VALUES ('$user_id', '$second_id', '$p_relation', '$now')") or die('Could not continue: '.mysqli_error($conn));
		} else {
			$r = mysqli_query($conn, "UPDATE relation_table SET relation_type = '$p_relation' WHERE user_id = '$user_id' AND second_id = '$second_id'") or die('Could not continue: '.mysqli_error($conn));
		}
		if($r) {
			$user_stats = getUStats($second_id);
			$u = $user_stats['username'];
			switch ($relation_type) {
				case -1: //Delfriend
					//customSuccess("Has borrado a ".getUStats($second_id)['username']." como amigo.", "self");
					setAjaxSuccess('friendRemoved', array($u));
					break;

				case -2: //Unfollow
					//customSuccess("Has dejado de seguir a ".getUStats($second_id)['username'].".", "self");
					setAjaxSuccess('userUnfollowed', array($u));
					break;

				case -3: //Remove pending
					//customSuccess("Has cancelado la petición de amistad hacia ".getUStats($second_id)['username'].".", "self");
					setAjaxSuccess('removePending');
					break;

				case -4: //Decline req
					//customSuccess("Has cancelado la petición de amistad de ".getUStats($second_id)['username'].".", "self");
					setAjaxSuccess('declinePending');
					break;

				case 0:
					setAjaxSuccess('userUnblocked', array($u));
					break;

				case 1: //Add buddy
					//customSuccess("Has añadido a ".getUStats($second_id)['username']." como amigo.", "self");
					setAjaxSuccess('friendAdded', array($u, (($user_stats['allow_friend_req']) ? 'Tu petición esta pendiente de aprobación.' : '')));
					break;
				
				case 2: //Follow
					//customSuccess("Has comenzado a seguir a ".getUStats($second_id)['username'].".", "self");
					setAjaxSuccess('userFollowed', array($u));
					break;

				case 4: //Block
					//customSuccess("Has bloqueado a ".getUStats($second_id)['username'].". ¿Quieres reportarlo?", "self");
					setAjaxSuccess('userBlocked', array($u));
					break;

				default:
					addAjaxError("hackTry", false);
					break;
			}
		} else {
			customError("Query inválida: ".mysqli_error($conn));
		}
	} else {
		addAjaxError('relationAlreadyExists');
	}
}

function isFriend($user_id, $second_id) {
	return internalRelationCheck($user_id, $second_id, 1);
}

function isFollowing($user_id, $second_id) {
	return internalRelationCheck($user_id, $second_id, 2);
}

function isBlocked($user_id, $second_id) {
	return internalRelationCheck($user_id, $second_id, 4);
}

function isPending($user_id, $second_id) {
	return internalRelationCheck($user_id, $second_id, 5);
}

function internalRelationCheck($user_id, $second_id, $relation_type) {
	global $conn;
	$state = mysqli_fetch_array(mysqli_query($conn, "SELECT relation_type FROM relation_table WHERE user_id = '$user_id' AND second_id = '$second_id'"))['relation_type'];
	return $state != null && (($state == 3 && ($relation_type == 1 || $relation_type == 2) || $relation_type == $state) || $relation_type == 4 && $state == $relation_type || $relation_type == 5 && ($state == 5 || $state == 6)); // && ((($relation_type == 1 || $relation_type == 2) && ($state == $relation_type || $state == 3)) || ($relation_type == 4 && $state == $relation_type));
}

function getFollowers($user_id, $limit = null) {
	global $conn;
	$lstr = (($limit != null) ? 'LIMIT '.(string)$limit : '');
	$elem = array();
	$result = mysqli_query($conn, "SELECT user_id FROM relation_table WHERE second_id = '$user_id' AND (relation_type = '2' OR relation_type = '3') $lstr");
	while($rs=mysqli_fetch_row($result)) {
		$elem[] = $rs;
	}
	return $elem;
}

function getFollowing($user_id, $limit = null) {
	global $conn;
	$lstr = (($limit != null) ? 'LIMIT '.(string)$limit : '');
	$elem = array();
	$result = mysqli_query($conn, "SELECT second_id FROM relation_table WHERE user_id = '$user_id' AND (relation_type = '2' OR relation_type = '3') $lstr");
	while($rs=mysqli_fetch_row($result)) {
		$elem[] = $rs;
	}
	return $elem;
}

//t = 0 (los usuarios que ese usuario tiene como amigos/bloqueados), t = 1 (los usuarios que a ese usuario lo tienen por amigo/bloqueado), t = 2 (ambos casos anteriores)

function getFriends($user_id, $t = 0, $limit = null) {
	global $conn;
	$lstr = (($limit != null) ? 'LIMIT '.(string)$limit : '');
	if($t == 0) {
        $elem = array();
        $result = mysqli_query($conn, "SELECT second_id FROM relation_table WHERE user_id = '$user_id' AND (relation_type = '1' OR relation_type = '3') $lstr");
		while($rs=mysqli_fetch_row($result)) {
			$elem[] = $rs;
		}
		return $elem;
	} else if($t == 1) {
        $elem = array();
        $result = mysqli_query($conn, "SELECT user_id FROM relation_table WHERE second_id = '$user_id' AND (relation_type = '1' OR relation_type = '3') $lstr");
		while($rs=mysqli_fetch_row($result)) {
			$elem[] = $rs;
		}
		return $elem;
	} else { //t = 2
        $elem = array();
        $result = mysqli_query($conn, "SELECT user_id, second_id FROM relation_table WHERE (user_id = '$user_id' AND (relation_type = '1' OR relation_type = '3') OR second_id = '$user_id' AND (relation_type = '1' OR relation_type = '3'))");
		while($rs=mysqli_fetch_row($result)) {
			$elem[] = $rs;
		}
		return $elem;
	}
}

function getBlocked($user_id, $t = 0) {
	global $conn;
		if($t == 0) {
        $elem = array();
        $result = mysqli_query($conn, "SELECT second_id FROM relation_table WHERE user_id = '$user_id' AND relation_type = '4'");
		while($rs=mysqli_fetch_row($result)) {
			$elem[] = $rs;
		}
		return $elem;
	} else if($t == 1) {
        $elem = array();
        $result = mysqli_query($conn, "SELECT user_id FROM relation_table WHERE second_id = '$user_id' AND relation_type = '4'");
		while($rs=mysqli_fetch_row($result)) {
			$elem[] = $rs;
		}
		return $elem;
	} else { //t = 2
        $elem = array();
        $result = mysqli_query($conn, "SELECT user_id, second_id FROM relation_table WHERE (user_id = '$user_id' AND relation_type = '4' OR second_id = '$user_id' AND relation_type = '4')");
		while($rs=mysqli_fetch_row($result)) {
			$elem[] = $rs;
		}
		return $elem;
	}
}

function getPending($user_id, $t = 0) {
	global $conn;
	if($t == 0) {
        $elem = array();
        $result = mysqli_query($conn, "SELECT second_id FROM relation_table WHERE user_id = '$user_id' AND (relation_type = '5' OR relation_type = '6')");
		while($rs=mysqli_fetch_row($result)) {
			$elem[] = $rs;
		}
		return $elem;
	} else if($t == 1) {
        $elem = array();
        $result = mysqli_query($conn, "SELECT user_id FROM relation_table WHERE second_id = '$user_id' AND (relation_type = '5' OR relation_type = '6')");
		while($rs=mysqli_fetch_row($result)) {
			$elem[] = $rs;
		}
		return $elem;
	} else { //t = 2
        $elem = array();
        $result = mysqli_query($conn, "SELECT user_id, second_id FROM relation_table WHERE (user_id = '$user_id' AND (relation_type = '5' OR relation_type = '6') OR second_id = '$user_id' AND (relation_type = '5' OR relation_type = '6'))");
		while($rs=mysqli_fetch_row($result)) {
			$elem[] = $rs;
		}
		return $elem;
	}
}

function checkGivenRelation($user_id, $second_id, $rel = 0) {
	global $conn;
	$state = mysqli_fetch_array(mysqli_query($conn, "SELECT relation_type FROM relation_table WHERE user_id = '$user_id' AND second_id = '$second_id'"))['relation_type'];
	return $rel == 4 || $user_id == $second_id || ($rel == $state);
}

function getMoods($user_id) {
	global $conn;
	$elem = array();
	$result = mysqli_query($conn, "SELECT * FROM user_moods WHERE user_id = '$user_id' ORDER BY creation_time DESC");
	while($rs=mysqli_fetch_array($result)) {
		$elem[] = $rs;
	}
	return $elem;
}

function getComments($linked_id, $type, $approved = 1, $user_id = -1) {
	global $conn;
	//Type: 0 = Profile, 1 = Ticket, 2 = User mood
	$elem = array();
	if($linked_id != -1) {
		$result = mysqli_query($conn, "SELECT * FROM comments WHERE linked_id = '$linked_id' AND type = '$type' AND approved = '$approved' ORDER BY action_time DESC");
		while($rs=mysqli_fetch_array($result)) {
			$elem[] = $rs;
		}
	} else {
		if($user_id != -1) {
			if($user_id == 0) {
				$result = mysqli_query($conn, "SELECT * FROM comments WHERE type = '$type' AND approved = '$approved' ORDER BY action_time DESC");
				while($rs=mysqli_fetch_array($result)) {
					$elem[] = $rs;
				}
			} else {
				return;
			}
		} else {
			$result = mysqli_query($conn, "SELECT * FROM comments WHERE user_id = '$user_id' AND type = '$type' AND approved = '$approved' ORDER BY action_time DESC");
			while($rs=mysqli_fetch_array($result)) {
				$elem[] = $rs;
			}
		}
	}
	return $elem;
}

function checkForUnapprovedComments($user_id, $type, &$searched_array = null) {
	global $conn;
	$moods = getMoods($user_id);
	if($searched_array == null) {$searched_array = array();}
	foreach ($moods as $mood) {
		$linked_id = $mood['id'];
		$result = mysqli_query($conn, "SELECT * FROM comments WHERE linked_id = '$linked_id' AND approved = '0' AND $user_id = '$user_id'");
		while ($rs = mysqli_fetch_array($result)) {
			$searched_array[] = $rs;
		}
	}
	return count($searched_array) > 0;
}

function sendMP($from, $to, $topic, $message) {

}

function updateProfileVisitors($visited_id) {
	global $conn;
	if(isOnline()) {
		$id = getUID();
		if($id != $visited_id) {
			$now = time();
			$user_stats = getUStats($visited_id);
			$last_visitors = $user_stats['last_visitors'];
			$varr = array();
			if($last_visitors != null) {
				$varr = unserialize($last_visitors);
			}
			$t = 1;
			if(@$varr[$id] != null) {
				$t = $varr[$id]['times']+1;
			}
			$varr[$id] = array('id' => $id, 'time' => $now, 'times' => $t);
			$f_str = serialize($varr);
			mysqli_query($conn, "UPDATE users SET last_visitors = '$f_str' WHERE id = '$visited_id'") or die('Could not continue: '.mysqli_error($conn));
		}
	}
}

//FLOOD RELATED

function lastMessageActivity($user_id, $type = 0) {
	global $conn;
	
	switch ($type) {
		case 0: //Profile message
			return mysqli_fetch_row(mysqli_query($conn, "SELECT creation_time FROM profile_msgs WHERE user_id = '$user_id' ORDER BY creation_time DESC"))[0];
			break;
		case 1: //Profile comment
			return mysqli_fetch_row(mysqli_query($conn, "SELECT action_time FROM comments WHERE user_id = '$user_id' AND type = '0' ORDER BY action_time DESC"))[0];
			break;
		case 2: //Ticket message
			return mysqli_fetch_row(mysqli_query($conn, "SELECT creation_time FROM ticket WHERE user_id = '$user_id' ORDER BY creation_time DESC"))[0];
			break;
		case 3: //Ticket comment
			return mysqli_fetch_row(mysqli_query($conn, "SELECT action_time FROM comments WHERE user_id = '$user_id' AND type = '1' ORDER BY action_time DESC"))[0];
			break;
		case 4: //User mood
			return mysqli_fetch_row(mysqli_query($conn, "SELECT creation_time FROM user_moods WHERE user_id = '$user_id' ORDER BY creation_time DESC"))[0];
			break;
		case 5: //Mood comment
			return mysqli_fetch_row(mysqli_query($conn, "SELECT action_time FROM comments WHERE user_id = '$user_id' AND type = '2' ORDER BY action_time DESC"))[0];
			break;
		case 6: //Feedback
			return mysqli_fetch_row(mysqli_query($conn, "SELECT creation_time FROM feedback WHERE user_id = '$user_id' ORDER BY creation_time DESC"))[0];
			break;
	}

	return false;

}

function lastActionActivity($user_id, $type) {
	global $conn;

	switch ($type) {
		case 0: //Vote
			return mysqli_fetch_row(mysqli_query($conn, "SELECT action_time FROM vote WHERE user_id = '$user_id' ORDER BY action_time DESC"))[0];
			break;
		case 1: //Fav
			return mysqli_fetch_row(mysqli_query($conn, "SELECT action_time FROM fav WHERE user_id = '$user_id' ORDER BY action_time DESC"))[0];
			break;
		case 2: //Share
			return mysqli_fetch_row(mysqli_query($conn, "SELECT action_time FROM share WHERE user_id = '$user_id' ORDER BY action_time DESC"))[0];
			break;
	}

	return false;

}

function isActionFlooded($user_id, $type, $until = FLOOD_TIME, $action_limit = MAX_ACTIONS) {
	global $conn;

	$time = time() - $action_limit;

	switch ($type) {
		case 0: //Vote
			return mysqli_num_rows(mysqli_query($conn, "SELECT id FROM vote WHERE user_id = '$user_id' AND action_time >= '$time'")) > $action_limit;
			break;
		case 1: //Fav
			return mysqli_num_rows(mysqli_query($conn, "SELECT id FROM fav WHERE user_id = '$user_id' AND action_time >= '$time'")) > $action_limit;
			break;
		case 2: //Share
			return mysqli_num_rows(mysqli_query($conn, "SELECT id FROM share WHERE user_id = '$user_id' AND action_time >= '$time'")) > $action_limit;
			break;
	}

	return false;

}

//FORMS

function goIndex() {
	header("Location: /");
}

function goHtmlIndex() {
	echo '<script>window.location="http://'.$_SERVER['SERVER_NAME'].'/index.php";</script>';
}

function goBack() {
	echo '<script>window.history.back();</script>';
}

function addAjaxError($name) {
	$_SESSION['ajaxErrors'][] = errorCaption($name);
	//echo errorCaption($name);
}

//Obsolete
function addError($name, $goBack = true) {
	if($goBack) {goBack();}
	if($_SESSION['errors'] != null) {$finalArray = $_SESSION['errors'];} else {$finalArray = array();}
	//Errors types: emptyCaptcha, incorrectCaptcha, emptyRegName, emptyRegName, emptyRegPass, emptyRegMail, emptyRegPassConfirm, regPassNotMatch
	array_push($finalArray, $name);
	$_SESSION['errors'] = $finalArray;
}

function errorCaption($name) {
	switch ($name) {
		case 'emptyCaptcha':
			return "Te falta la captcha, debes completarla para poder continuar.";
			break;

		case 'incorrectCaptcha':
			return "La captcha es errónea.";
			break;

		case 'emptyUsername':
			return "El nombre está vacio debe escribir uno para poder continuar.";
			break;

		case 'emptyPassword':
			return "La contraseña está vacía debe escribir una para poder continuar.";
			break;

		case 'emptyEmail':
			return "El email está vacío debe escribir uno para poder continuar.";
			break;

		case 'emptyPassConfirm':
			return "Debes confirmar tu contraseña.";
			break;	

		case 'PassNotMatch':
			return "Las contraseñas no coinciden.";
			break;			

		case 'invalidMail':
			return "El email que pusiste es inválido.";
			break;		

		case 'forbiddenChars':
			return "Los caracteres permitidos para el nombre de usuario son '.', '-'y '_'.";
			break;	

		case 'userExists':
			return "El usuario que desea registrar ya existe.";
			break;

		case 'attempsWasted':
			return "Tus intentos se acabaron espera 30 minutos para desbloquear esta acción.";
			break;

		case 'wrongCredentials':
			return "Los datos que introduciste son erróneos. Te quedan ".Attemps('LOGIN', 'get')." ".((Attemps('LOGIN', 'get') == 1) ? "intento" : "intentos").".";
			break;

		case 'notValidUrl':
			return "La URL que diste no es válida";
			break;	

		case 'floodDetected':
			return "¡Ten paciencia! Has realizado muchas aciones en poco tiempo, debes esperar ".(FLOOD_TIME-time()-$_SESSION['last_flood_try'])." segundos.";
			break;		

		case 'alreadyLogged':
			return "Ya estás conectado.";
			break;				

		case 'notLoggedIn':
			return "No estás conectado.";
			break;

		case 'relationAlreadyExists':
			return "La actual relación ya existe.";
			break;

		case 'alreadyVoted':
			return "Ya has votado aquí.";
			break;
		
		case 'hackTry':
			return "Intento de sabotaje detectado. :(";
			break;

		default: //Custom error
			return $name;
			break;
	}
}

//Obsolete
function customError($str, $goBack = true) {
	if($goBack) {goBack();}
	if($_SESSION['errors'] != null) {$finalArray = $_SESSION['errors'];} else {$finalArray = array();}
	//Errors types: emptyCaptcha, incorrectCaptcha, emptyRegName, emptyRegName, emptyRegPass, emptyRegMail, emptyRegPassConfirm, regPassNotMatch
	array_push($finalArray, $str);
	$_SESSION['errors'] = $finalArray;
}

//Obsolete
function showErrors() {
	if(count(@$_SESSION['errors']) > 0) {
		$errorArray = $_SESSION['errors'];
		if($errorArray != null) {
			echo '<div id="errors">';
			if(count($errorArray) > 1) {echo 'Al enviar el formulario se encontraron los siguientes '.count($errorArray).' errores:';} else {echo 'Hubo un error al enviar el formulario:';}
			echo '<ul>';
			for ($i=0; $i < count($errorArray); $i++) { 
				echo '<li>'.errorCaption($errorArray[$i]).'</li>';
			}
			echo '</ul></div>';
			$_SESSION['errors'] = null;
		}
	}
}

//Obsolete
function setSuccess($place = null, $goto = "index") {
	if($place == null) {$place = $_GET['action'];}
	$_SESSION['success'] = $place;
	if($goto == "index") {goHtmlIndex();} else {if($goto == "self") {redirect($_SERVER['REQUEST_URI']);} else {redirect($goto);}}
}

//Obsolete
function customSuccess($str, $goto = "index") {
	$_SESSION['success'] = $str;
	if($goto == "index") {goHtmlIndex();} else {if($goto == "self") {redirect($_SERVER['REQUEST_URI']);} else {redirect($goto);}}
}

//Obsolete
function showSuccess() {
	if(count(@$_SESSION['errors']) == 0 && @$_SESSION['success'] != null) {
		$place = $_SESSION['success'];
		$msg = successCaption($place);
		echo '<div id="success">'.$msg.'<img style="float:right;position: relative;top: -1px;" src="images/icons/close.png" onclick="hideElem(this.parentNode);"></div>';
		$_SESSION['success'] = null;
	}
}

function successCaption($name) {
	switch($name) {
		case 'register':
			return "Fuiste correctamente registrado.";
			break;

		case 'login':
			return "Te contectaste correctamente.";
			break;

		case 'logout':
			return "Te desconectaste correctamente.";
			break;

		case 'profileEdited':
			return "Perfil editado correctamente.";
			break;

		case 'passwordChanged':
			return "Contraseña cambiada correctamente.";
			break;

		case 'reconfirmMail':
			return "Correo electrónico de confirmación enviado.";
			break;

		case 'newMood':
			return "Has publicado un nuevo estado.";
			break;

		case 'newReply':
			return "Has publicado una nueva respuesta.";
			break;

		case 'newReplyPending':
			return "Tu respuesta está pendiente de aprobación.";
			break;

		case 'newPMsg':
			return "Mensaje enviado correctamente.";
			break;

		case 'newTicket':
			return "Has creado un nuevo ticket";
			break;

		case 'newFeedback':
			return "Has creado un nuevo mensaje de Feedback.";
			break;

		case 'replyApproved':
			return "Has aprobado una respuesta.";
			break;

		case 'replyDeleted':
			return "Has borrado una respuesta.";
			break;

		case 'friendAdded':
			return "Has agregado a %0% como amigo. %1%";
			break;
			
		case 'friendRemoved':
			return "Has borrado a %0% como amigo.";
			break;

		case 'userBlocked':
			return "Has bloqueado a %0%.";
			break;		

		case 'userUnblocked':
			return "Has desbloqueado a %0%";
			break;

		case 'userFollowed':
			return "Has comenzado a seguir a %0%.";
			break;

		case 'userUnfollowed':
			return "Has dejado de seguir a %0%.";
			break;

		case 'pendingRequest':
			return "Tu petición está pendiente de aprobación.";
			break;

		case 'declinePending':
		case 'removePending':
			return "Has cancelado la autorización pendiente.";
			break;

		case 'voteAdded':
			return "Voto recibido correctamente.";
			break;

		case 'voteDeleted':
			return "Voto anulado correctamente.";
			break;

		default:
			return $name;
			break;
	}
}

function setAjaxSuccess($name, $arr = null) {
	echo '<div id="success">'.(($arr == null) ? successCaption($name) : string_format(successCaption($name), $arr)).'<img style="float:right;position: relative;top: -1px;" src="images/icons/close.png" onclick="hideElem(this.parentNode);"></div>';
}

//Obsolete
//Show errors or success
function showInfo() {
	showErrors();
	showSuccess();
}

$now = time();

if(isOnline()) {
	$me = getCUser();
	$temp_stats = getUStats(getUID());
	$last_conn = $temp_stats['last_activity'];
	$final_time = 0;
	if($last_conn + ONLINE_TIME * 60 < time()) { //User disconnected
		$started_conn = $temp_stats['started_conn_time'];
		$online_time = $temp_stats['online_time'];
		$final_time += $last_conn - $started_conn;
		mysqli_query($conn, "UPDATE users SET started_conn_time = '$now' WHERE username = '$me'");
		mysqli_query($conn, "UPDATE users SET online_time = '$final_time' WHERE username = '$me'");
	}
	mysqli_query($conn, "UPDATE users SET last_activity = '$now' WHERE username = '$me'");
	$temp_stats = null;
} else {
	UpdateInfo();
}

$my_ip = $_SERVER['REMOTE_ADDR'];
mysqli_query($conn, "UPDATE visitors SET last_activity = '$now' WHERE ip = '$my_ip'");

//Obsolete
if(!strpos($_SERVER['REQUEST_URI'], "login") && !strpos($_SERVER['REQUEST_URI'], "logout")) {$_SESSION['last_page'] = $_SERVER['REQUEST_URI'];}

?>