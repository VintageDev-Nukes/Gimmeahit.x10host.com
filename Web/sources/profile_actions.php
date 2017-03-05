<?php

//Moods, profile messages, etc.. Goes here

$user_id = getUID();

if(isset($_POST['new-message'])) {
	$_SESSION['last_flood_try'] = lastMessageActivity($user_id, 4);
	if($now > $_SESSION['last_flood_try'] + FLOOD_TIME) {
		$now = time();
		$message = mysqli_real_escape_string($conn, nl2br(strip_tags(@$_POST['message'])));
		$type = @$_POST['type'];
		$table = (($type == 0) ? 'profile_messages' : (($type == 2) ? 'ticket' : (($type == 4) ? 'user_moods' : 'feedback')));
		if(is_numeric($type)) {
			$r = mysqli_query($conn, "INSERT INTO $table (user_id, creation_time, message) VALUES ('$user_id', '$now', '$message')");
			if($r) {
				setSuccess("new".(($type == 0) ? 'PMsg' : (($type == 2) ? 'Ticket' : (($type == 4) ? 'Mood' : 'Feedback'))), "self");
			} else {
				customError("Query inválida: ".mysqli_error($conn));
			}
		} else {
			addError("hackTry");
		}
	} else {
		addError("floodDetected");
	}
}

if(isset($_POST['new-reply'])) {
	$type = mysqli_real_escape_string($conn, @$_POST['type']);
	$_SESSION['last_flood_try'] = lastMessageActivity($user_id, (($type == 0) ? 1 : (($type == 1) ? 3 : 5)));
	if($now > $_SESSION['last_flood_try'] + FLOOD_TIME) {
		$now = time();
		$message = mysqli_real_escape_string($conn, nl2br(strip_tags(@$_POST['message'])));
		$linked_id = mysqli_real_escape_string($conn, @$_POST['linked_id']);
		$approved = (($type == 2) ? !(int)$user_info['allow_approve_mood_reply'] : 1); //Check there if the message is spam
		if(is_numeric($linked_id) && is_numeric($type)) {
			$r = mysqli_query($conn, "INSERT INTO comments (user_id, action_time, message, linked_id, type, approved) VALUES ('$user_id', '$now', '$message', '$linked_id', '$type', '$approved')");
			if($r) {
				if(!$user_info['allow_approve_mood_reply']) {
					setSuccess("newReply", "self");
				} else {
					setSuccess("newReplyPending", "self");
				}	
			} else {
				customError("Query inválida: ".mysqli_error($conn));
			}
		} else {
			addError("hackTry");
		}
	} else {
		addError("floodDetected");
	}
}

if(isset($_POST['approve'])) {
	$id = mysqli_real_escape_string($conn, @$_POST['id']);
	$arr = array();
	checkForUnapprovedComments($uid, 2, $arr);
	if(isOnline() && count(multiarray_search($arr, 'id', $id)) > 0) {
		if(is_numeric($id)) {
			$r = mysqli_query($conn, "UPDATE comments SET approved = '1' WHERE id = '$id'");
			if($r) {
				setSuccess("replyApproved", "self");
			} else {
				customError("Query inválida: ".mysqli_error($conn));
			}
		} else {
			addError("hackTry");
		}
	} else {
		addError("hackTry");
	}
}

if(isset($_POST['delete_unapproved_message'])) {
	$id = mysqli_real_escape_string($conn, @$_POST['id']);
	$arr = array();
	checkForUnapprovedComments($uid, 2, $arr);
	if(isOnline() && count(multiarray_search($arr, 'id', $id)) > 0) {
		if(is_numeric($id)) {
			$r = mysqli_query($conn, "UPDATE comments SET approved = '1' WHERE id = '$id'");
			if($r) {
				setSuccess("replyDeleted", "self");
			} else {
				customError("Query inválida: ".mysqli_error($conn));
			}
		} else {
			addError("hackTry");
		}
	} else {
		addError("hackTry");
	}
}

//Ajax for thumb up, thumb down, fav, share, reply, edit and delete
echo '<script>

</script>';

?>