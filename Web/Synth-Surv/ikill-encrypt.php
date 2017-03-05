<?php

$patt = 'abcdefghijklmnopqrstuvwxyz';

function encrypt($str) {
	global $seed, $my_key, $patt;
	
	$str = utf8_decode($str);

	$encryptedString = '';
	$arrayPattern = array();
	$length = strlen($str);

	for($i = 0; $i < 10; $i++) {
		$arrayPattern[] = randomize($patt, $seed+$i);
	}

	for ($i = 0; $i < $length; $i++) {
		$index = floor(uniord($str[$i])/strlen($patt));
		$encryptedString .= $index.$arrayPattern[$index][uniord($str[$i])%(strlen($patt))];
	}

	return $encryptedString;
}

function decrypt($str) {
	global $seed, $my_key, $patt;
	
	$decryptedString = '';
	$arrayPattern = array();
	$length = strlen($str);

	for($i = 0; $i < 10; $i++) {
		$arrayPattern[] = randomize($patt, $seed+$i);
	}

	$index = 0;
	$char = "";
	
	for ($i = 0; $i < $length; $i++) {
		if(($i % 2 == 0)) {
			$index = intval($str[$i]);
		} else {
			$char = $str[$i];
			$decryptedString .= unichr($index*strlen($patt)+strpos($arrayPattern[$index], $str[$i]));
		}
	}

	return $decryptedString;
}

// generate random number
function num($min = 0, $max = 9999999, $s = false) {
	global $seed, $my_key;
	$temp_seed = ((is_numeric($s)) ? $s : $seed);
	$temp_key = ToInt($my_key);
	if ($temp_seed == 0) $temp_seed = mt_rand();
	if ($temp_key == 0) $temp_key = rand();
	$temp_seed = ($temp_seed * 493) % $temp_key;
	return $temp_seed % ($max - $min + 1) + $min;
}

function randomize($str, $s = false) {	
	$shuffled_str = "";
	$temp_str = $str;
	$l = strlen($str);
	for($i = 0; $i < $l; $i++) {
		$index = num(0, strlen($temp_str) - 1, $s);
		$shuffled_str .= $temp_str[$index];
		$temp_str = removechar($temp_str, $index);
	}
	return $shuffled_str;
}

function removechar($str, $index) {
	$sstr1 = substr($str, 0, $index);
	$sstr2 = substr($str, $index+1, strlen($str)-1);
	return $sstr1.$sstr2;
}

function ToInt($str) {
	$str = utf8_decode($str);
	if(empty($str)) return 0;
	$InternalSeed = 0;
	for($x = 0; $x < strlen($str); $x++) {
		if(is_numeric($str[$x])) {
			$InternalSeed += intval($str[$x])*pow($x, 3);
		} else {
			$InternalSeed += uniord($str[$x])*pow($x, 3);
		}
	}
	return $InternalSeed;
}

function uniord($k) {  
    $k1 = ord(substr($k, 0, 1)); 
    $k2 = ord(substr($k, 1, 1)); 
    return $k2 * 256 + $k1; 
} 

function unichr($intval) {
	return mb_convert_encoding(pack('n', $intval), 'UTF-8', 'UTF-16BE');
}

?>