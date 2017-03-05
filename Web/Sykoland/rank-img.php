<?php

$type = @$_GET['type'];
$qnt = @$_GET['qnt'];

$x = 146;
$y = 59;

header("Content-type: image/png");

$im = imagecreatetruecolor($x, $y);

$black = imagecolorallocate($im, 0, 0, 0);
imagecolortransparent($im, $black);
$white = imagecolorallocate($im, 255, 255, 255);
imagecolortransparent($im, $white);

$firstUrl = 'http://i.imgur.com/C8LJ2Pz.png';
$secondUrl = 'http://i.imgur.com/E3Toxry.png';

$first = imagecreatefrompng($firstUrl);
$second = imagecreatefrompng($secondUrl);

imagecopymerge($im, $first, 0, 0, 0, 0, $x, $y, 100);
imagecopymerge($im, $second, 9, 35, 0, 0, 1*$qnt, 12, 100);

imagepng($im);

imagedestroy($im);

?>