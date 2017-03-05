<?php

include('processor.php');

/*if(isOnline()) {
	$my_stats = getUStats();
} else {
	$my_stats = getVStats();
}

print_r($my_stats);*/

/*$data = getLocation($_SERVER['REMOTE_ADDR'], 1);

echo 'Hora del servidor: '.date("F j, Y, g:i a")."<br>";

date_default_timezone_set($data['timezone']);

echo 'Hora actual: '.date("F j, Y, g:i a");*/

//dump($data);

echo timezone_dropdown();

?>
