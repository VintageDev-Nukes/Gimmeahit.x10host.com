<?php 
if(session_status() == PHP_SESSION_NONE) {
    session_start();
} 
$text = rand(10000,999999); 
$_SESSION["vercode"] = $text; 

$image = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT']."/images/bg.jpg");
$txtColor = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 5, 5, 5, $text, $txtColor);
header("Content-type: image/jpeg");
imagejpeg($image);
?>
