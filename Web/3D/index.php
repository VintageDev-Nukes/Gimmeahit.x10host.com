<?php 

$ft = @$_GET['ft'];
$fl = @$_GET['fl'];
$fr = @$_GET['fr'];

if(!isset($ft)) {$ft = "http://cokane.com/rock_textures/rock09.jpg";}
if(!isset($fl)) {$fl = "http://cokane.com/rock_textures/rock09.jpg";}
if(!isset($fr)) {$fr = "http://cokane.com/rock_textures/rock09.jpg";}

echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> CSS3 Cube </title>
<link rel="stylesheet" href="style.css" />
<style>
.top .blockTexture {
	width: 100%;
	height: 100%;
	background-image: url("'.$ft.'");
	background-size: 100% 100%;
}
.left .blockTexture {
	width: 100%;
	height: 100%;
	background-image: url("'.$fl.'");
	background-size: 100% 100%;
}
.right .blockTexture {
	width: 100%;
	height: 100%;
	background-image: url("'.$fr.'");
	background-size: 100% 100%;
}
</style>
</head>
<body>
<div style="position:relative;margin-left:0px;margin-top:15px;">
    <div class="face top"><div class="blockTexture"><div class="light"></div></div></div>
    <div class="face left"><div class="blockTexture"><div class="light"></div></div></div>
    <div class="face right"><div class="blockTexture"><div class="light"></div></div></div>
</div>
</body>
</html>
<!-- This tutorial is provided in part by Server Intellect Web Hosting Solutions (www.serverintellect.com. Visit www.cssatoms.com for more CSS Tutorials. -->';

?>