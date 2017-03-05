<?php

$act_code = @$_GET['code'];
$my_id = getUID();

if(isOnline() && isset($act_code) && $my_stats['activation'] == $act_code) {
	mysqli_query($conn, "UPDATE users SET activation = '' WHERE id = '$my_id'");
}

redirect('index.php');

?>