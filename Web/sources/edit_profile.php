<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

if(isset($_POST['edited'])) { //Hay que crear una columna para el color de fondo del banner

	if($_POST['edited'] === 'a') {
		include($_SERVER['DOCUMENT_ROOT'].'/Settings.php');
		$conn = mysqli_connect($localhost, $dbuser, $dbpass, $dbname) or die('Could not connect: ' . mysqli_error($conn));
		$my_stats = unserialize(base64_decode($_POST['stats']));
		$my_id = @$_POST['uid'];
	} else {
		$my_id = getUID();
		$username = mysqli_real_escape_string($conn, @$_POST['username']);
		if($my_stats['username'] != $username) {
			mysqli_query($conn, "UPDATE users SET username = '$username' WHERE id = '$my_id'");
		}
	}

	$show_success = true;
	$real_name = mysqli_real_escape_string($conn, strip_tags(@$_POST['real_name']));
	$email = mysqli_real_escape_string($conn, strip_tags(@$_POST['email']));
	$gender = mysqli_real_escape_string($conn, strip_tags(@$_POST['gender']));
	$date_day = mysqli_real_escape_string($conn, strip_tags(@$_POST['date_day'])); //Tengo que crear un javascript que parsee estos textboxes y compruebe que el dia no es mayor al del mes (99-99-2014) y cosas así tampoco se permiten letras etc..
	$date_month = mysqli_real_escape_string($conn, strip_tags(@$_POST['date_month']));
	$date_year = mysqli_real_escape_string($conn, strip_tags(@$_POST['date_year']));
	$final_date = $date_day."-".$date_month."-".$date_year;
	$location = mysqli_real_escape_string($conn, strip_tags(@$_POST['location']));
	$website_title = mysqli_real_escape_string($conn, strip_tags(@$_POST['website_title']));
	$website_url = mysqli_real_escape_string($conn, strip_tags(@$_POST['website_url']));
	$skype = mysqli_real_escape_string($conn, strip_tags(@$_POST['skype']));
	if($my_stats['real_name'] != $real_name) {
		mysqli_query($conn, "UPDATE users SET real_name = '$real_name' WHERE id = '$my_id'");
	}
	if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
		if($my_stats['email'] != $email) {
			$act = md5(time());
			mysqli_query($conn, "UPDATE users SET email = '$email' WHERE id = '$my_id'");
			mysqli_query($conn, "UPDATE users SET activation = '$act' WHERE id = '$my_id'");
			mail($email, 'GimmeAHit! - Activación de correo', '¡Hola! Para activar tu cuenta visita el siguiente link: http://'.$_SERVER['SERVER_NAME'].'/?action=act&code='.$act);
		}
	} else {
		if($_POST['edited'] != "a") {
			addError("invalidMail");
			$show_success = false;
		}
	}
	if(is_numeric($gender)) {
		if($my_stats['gender'] != $gender) {
			mysqli_query($conn, "UPDATE users SET gender = '$gender' WHERE id = '$my_id'");
		}
	} else {
		if($_POST['edited'] != "a") {
			addError("hackTry");
			$show_success = false;
		}
	}
	if(is_numeric($date_day) && is_numeric($date_month) && is_numeric($date_year)) {
		if($my_stats['birthdate'] != $final_date) {
			mysqli_query($conn, "UPDATE users SET birthdate = '$final_date' WHERE id = '$my_id'");
		}
	} else {
		if($_POST['edited'] != "a") {
			addError("hackTry");
			$show_success = false;
		}
	}
	if($my_stats['location'] != $location) {
		mysqli_query($conn, "UPDATE users SET location = '$location' WHERE id = '$my_id'");
	}
	if($my_stats['website_title'] != $website_title) {
		mysqli_query($conn, "UPDATE users SET website_title = '$website_title' WHERE id = '$my_id'");
	}
	if($my_stats['website_url'] != $website_url) {
		mysqli_query($conn, "UPDATE users SET website_url = '$website_url' WHERE id = '$my_id'");
	}
	if($my_stats['skype'] != $skype) {
		mysqli_query($conn, "UPDATE users SET skype = '$skype' WHERE id = '$my_id'");
	}
	if($_POST['edited'] != "a" && $show_success) {
		setSuccess("profileEdited", "self");
	}
}

if(isset($_POST['rel_type'])) {
	$my_id = @$_POST['uid'];
	include($_SERVER['DOCUMENT_ROOT'].'/processor.php');
	switch (@$_POST['rel_type']) {
		case 0: //Delete as friend
			addRelation($my_id, @$_POST['member_id'], -1);
			break;

		case 1: //Delete as follower
			addRelation($my_id, @$_POST['member_id'], -2);
			break;

		case 2: //Unblock
			addRelation($my_id, @$_POST['member_id'], 0);
			break;
		
		case 3: //Accept pending
			addRelation($my_id, @$_POST['member_id'], 1);
			break;

		case 4: //Remove pending
			addRelation($my_id, @$_POST['member_id'], -3);
			break;

		case 5: //Decline req
			addRelation($my_id, @$_POST['member_id'], -4);
			break;

		default:
			break;
	}
}

$my_id = getUID();

if(isset($_POST['passchanged'])) {
	if($my_stats['password'] === md5($_POST['oldpass']) && md5($_POST['newpass']) === md5($_POST['rnewpass'])) {
		$newpass = @$_POST['newpass'];
		mysqli_query($conn, "UPDATE users SET password = '$newpass' WHERE id = '$my_id'");
		setSuccess("passwordChanged", "self");
	} else {
		addError("PassNotMatch", false);
	}
}

if(isset($_POST['reconfirm'])) {
	$act = md5(time());
	mysqli_query($conn, "UPDATE users SET activation = '$act' WHERE id = '$my_id'");
	mail($email, 'GimmeAHit! - Activación de correo', '¡Hola! Para activar tu cuenta visita el siguiente link: http://'.$_SERVER['SERVER_NAME'].'/?action=act&code='.$act);
	setSuccess("reconfirmMail", "self");
}

if(isset($_POST['custom'])) {
	$show_success = true;
	if(empty($_POST['custom-part']) || @$_POST['custom-part'] === 'only_avatar') {
		$avatar_url = mysqli_real_escape_string($conn, @$_POST['avatar_url']);
		if((!empty($avatar_url) && filter_var($avatar_url, FILTER_VALIDATE_URL)) || empty($avatar_url)) {
			if($my_stats['avatar'] != $avatar_url) {
				mysqli_query($conn, "UPDATE users SET avatar = '$avatar_url' WHERE id = '$my_id'");
			}
		} else {
			addError("notValidUrl");
			$show_success = false;
		}
	}
	if(empty($_POST['custom-part']) || @$_POST['custom-part'] === 'only_banner') {
		$banner_url = mysqli_real_escape_string($conn, @$_POST['banner_url']);
		$banner_bcolor = mysqli_real_escape_string($conn, @$_POST['banner_bcolor']);
		$banner_mosaic = mysqli_real_escape_string($conn, @$_POST['banner_mosaic']);
		if((!empty($banner_url) && filter_var($banner_url, FILTER_VALIDATE_URL)) || empty($banner_url)) {
			if($my_stats['banner'] != $banner_url) {
				mysqli_query($conn, "UPDATE users SET banner = '$banner_url' WHERE id = '$my_id'");
			}
			if(isHexColor($banner_bcolor)) {
				if($my_stats['banner_bcolor'] != $banner_bcolor) {
					mysqli_query($conn, "UPDATE users SET banner_bcolor = '$banner_bcolor' WHERE id = '$my_id'");
				}
			} else {
				addError("hackTry");
				$show_success = false;
				echo "<script>alert('".__LINE__."');</script>";				
			}
			if(is_numeric($banner_mosaic)) {
				if($my_stats['banner_mosaic'] != $banner_mosaic) {
					mysqli_query($conn, "UPDATE users SET banner_mosaic = '$banner_mosaic' WHERE id = '$my_id'");
				}
			} else {
				addError("hackTry");
				$show_success = false;
				echo "<script>alert('".__LINE__."');</script>";						
			}
		} else {
			addError("notValidUrl");
			$show_success = false;
		}
	}
	if($show_success) {
		setSuccess("profileEdited", "self");
	}
}

if(isset($_POST['config'])) {
	$edit_stats = ((empty(@$_GET['id']) || getUID() != 1) ? getUStats(getUID()) : getUStats(@$_GET['id']));
	$show_success = true;
	$show_real_name = mysqli_real_escape_string($conn, @$_POST['real_name_pdd']);
	$show_location = mysqli_real_escape_string($conn, @$_POST['location_pdd']);
	$show_gender = mysqli_real_escape_string($conn, @$_POST['gender_pdd']);
	$show_age = mysqli_real_escape_string($conn, @$_POST['age_pdd']);
	$show_skype = mysqli_real_escape_string($conn, @$_POST['skype_pdd']);
	$show_mail = mysqli_real_escape_string($conn, @$_POST['email_pdd']);
	$show_moods = mysqli_real_escape_string($conn, @$_POST['moods_pdd']);
	$show_avatar = mysqli_real_escape_string($conn, @$_POST['avatar_pdd']);
	$show_banner = mysqli_real_escape_string($conn, @$_POST['banner_pdd']);
	$show_last_act = mysqli_real_escape_string($conn, @$_POST['last_act_pdd']);
	$show_visitors = mysqli_real_escape_string($conn, @$_POST['visitors_pdd']);
	$show_friends = mysqli_real_escape_string($conn, @$_POST['friends_pdd']);
	$show_follow = mysqli_real_escape_string($conn, @$_POST['follow_pdd']);
	$show_badges = mysqli_real_escape_string($conn, @$_POST['badges_pdd']);
	$allow_approve_mood_reply = (int)@$_POST['allow_approve_mood_reply']; 
	$allow_profile_msgs = (int)@$_POST['allow_profile_msgs']; 
	$allow_approve_profile_messages = (int)@$_POST['allow_approve_profile_messages']; 
	$allow_friend_req = (int)@$_POST['allow_friend_req'];
	$allow_newsletters = (int)@$_POST['allow_newsletters']; 
	$allow_hide_online = (int)@$_POST['allow_hide_online']; 
	$allow_private_msgs = (int)@$_POST['allow_private_msgs']; 
	$allow_warn_onleave = (int)@$_POST['allow_warn_onleave']; 
	$timezone = mysqli_real_escape_string($conn, @$_POST['timezone']); 
	$date_format = mysqli_real_escape_string($conn, @$_POST['date_format']); 
	$detect_dst = mysqli_real_escape_string($conn, @$_POST['detect_dst']); 
	$lang = mysqli_real_escape_string($conn, @$_POST['lang']); 
	if(is_numeric($show_real_name)) {			
		if($my_stats['show_real_name'] != $show_real_name) {
			mysqli_query($conn, "UPDATE users SET show_real_name = '$show_real_name' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_location)) {	
		if($my_stats['show_location'] != $show_location) {
			mysqli_query($conn, "UPDATE users SET show_location = '$show_location' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_gender)) {	
		if($my_stats['show_gender'] != $show_gender) {
			mysqli_query($conn, "UPDATE users SET show_gender = '$show_gender' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_age)) {	
		if($my_stats['show_age'] != $show_age) {
			mysqli_query($conn, "UPDATE users SET show_age = '$show_age' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_skype)) {	
		if($my_stats['show_skype'] != $show_skype) {
			mysqli_query($conn, "UPDATE users SET show_skype = '$show_skype' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_mail)) {	
		if($my_stats['show_mail'] != $show_mail) {
			mysqli_query($conn, "UPDATE users SET show_mail = '$show_mail' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_moods)) {	
		if($my_stats['show_moods'] != $show_moods) {
			mysqli_query($conn, "UPDATE users SET show_moods = '$show_moods' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_avatar)) {	
		if($my_stats['show_avatar'] != $show_avatar) {
			mysqli_query($conn, "UPDATE users SET show_avatar = '$show_avatar' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_banner)) {	
		if($my_stats['show_banner'] != $show_banner) {
			mysqli_query($conn, "UPDATE users SET show_banner = '$show_banner' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_last_act)) {	
		if($my_stats['show_last_act'] != $show_last_act) {
			mysqli_query($conn, "UPDATE users SET show_last_act = '$show_last_act' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_visitors)) {	
		if($my_stats['show_visitors'] != $show_visitors) {
			mysqli_query($conn, "UPDATE users SET show_visitors = '$show_visitors' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_friends)) {	
		if($my_stats['show_friends'] != $show_friends) {
			mysqli_query($conn, "UPDATE users SET show_friends = '$show_friends' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_follow)) {	
		if($my_stats['show_follow'] != $show_follow) {
			mysqli_query($conn, "UPDATE users SET show_follow = '$show_follow' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($show_badges)) {	
		if($my_stats['show_badges'] != $show_badges) {
			mysqli_query($conn, "UPDATE users SET show_badges = '$show_badges' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(empty($allow_approve_mood_reply) || (isset($allow_approve_mood_reply) && is_numeric($allow_approve_mood_reply))) {	
		if($my_stats['allow_approve_mood_reply'] != $allow_approve_mood_reply) {
			mysqli_query($conn, "UPDATE users SET allow_approve_mood_reply = '$allow_approve_mood_reply' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(empty($allow_profile_msgs) || (isset($allow_profile_msgs) && is_numeric($allow_profile_msgs))) {	
		if($my_stats['allow_profile_msgs'] != $allow_profile_msgs) {
			mysqli_query($conn, "UPDATE users SET allow_profile_msgs = '$allow_profile_msgs' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(empty($allow_approve_profile_messages) || (isset($allow_approve_profile_messages) && is_numeric($allow_approve_profile_messages))) {	
		if($my_stats['allow_approve_profile_messages'] != $allow_approve_profile_messages) {
			mysqli_query($conn, "UPDATE users SET allow_approve_profile_messages = '$allow_approve_profile_messages' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(empty($allow_friend_req) || (isset($allow_friend_req) && is_numeric($allow_friend_req))) {	
		if($my_stats['allow_friend_req'] != $allow_friend_req) {
			mysqli_query($conn, "UPDATE users SET allow_friend_req = '$allow_friend_req' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(empty($allow_newsletters) || (isset($allow_newsletters) && is_numeric($allow_newsletters))) {	
		if($my_stats['allow_newsletters'] != $allow_newsletters) {
			mysqli_query($conn, "UPDATE users SET allow_newsletters = '$allow_newsletters' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(empty($allow_hide_online) || (isset($allow_hide_online) && is_numeric($allow_hide_online))) {	
		if($my_stats['allow_hide_online'] != $allow_hide_online) {
			mysqli_query($conn, "UPDATE users SET allow_hide_online = '$allow_hide_online' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(empty($allow_private_msgs) || (isset($allow_private_msgs) && is_numeric($allow_private_msgs))) {	
		if($my_stats['allow_private_msgs'] != $allow_private_msgs) {
			mysqli_query($conn, "UPDATE users SET allow_private_msgs = '$allow_private_msgs' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(empty($allow_warn_onleave) || (isset($allow_warn_onleave) && is_numeric($allow_warn_onleave))) {	
		if($my_stats['allow_warn_onleave'] != $allow_warn_onleave) {
			mysqli_query($conn, "UPDATE users SET allow_warn_onleave = '$allow_warn_onleave' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($timezone)) {	
		if($my_stats['timezone'] != $timezone) {
			mysqli_query($conn, "UPDATE users SET timezone = '$timezone' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($date_format)) {	
		if($my_stats['date_format'] != $date_format) {
			mysqli_query($conn, "UPDATE users SET date_format = '$date_format' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	if(is_numeric($detect_dst)) {	
		if($my_stats['detect_dst'] != $detect_dst) {
			mysqli_query($conn, "UPDATE users SET detect_dst = '$detect_dst' WHERE id = '$my_id'");
		}
	} else {
		addError("hackTry");
		$show_success = false;
	}
	//Para evitar posibles xss
	/*if($my_stats['lang'] != $lang) {
		mysqli_query($conn, "UPDATE users SET lang = '$lang' WHERE id = '$my_id'");
	}*/
	if($show_success) {
		setSuccess("profileEdited", "self");
	}
}

switch (@$_GET['go']) {
	case 'edit':
		echo '<script>
		(function() {
			document.getElementById("injectedcss").innerHTML = "<style>.profile-nav-menu ul li:not(:nth-child("+menunum+")) {background:#eee;color:#555;}</style>";
		})();
		function changeSocial(id, reltype) {
			var dataString = "member_id="+id+"&uid='.getUID().'&rel_type="+reltype;
			$.ajax({
			type: "POST",
			url: "/sources/edit_profile.php",
			data: dataString,
			cache: false,
			success: function() {
				document.location.reload(true);
			}, error: function(xhr, status, error) {
			  var err = eval("(" + xhr.responseText + ")");
			  alert("Error: "+err.Message);
			}
			});
			return false;
		}
		</script>';
		break;
	
	default:
		echo '<script>
		function showUpdateNode(elem) {
			elem.parentNode.childNodes[1].style.display = "none";
			elem.parentNode.childNodes[2].style.display = "inline";
			elem.parentNode.childNodes[3].style.display = "none";
		}
		function sendSForm() {
			var real_name = document.getElementById("real_name").value;
			var date_day = document.getElementById("day_select").value;
			var date_month = document.getElementById("month_select").value;
			var date_year = document.getElementById("year_select").value;
			var gender = document.getElementById("gender").value;
			var location = document.getElementById("location").value;
			var website_title = document.getElementById("website_title").value;
			var website_url = document.getElementById("website_url").value;
			var skype = document.getElementById("skype").value;
			var email = document.getElementById("email").value;
			var dataString = "real_name="+real_name+"&date_day="+date_day+"&date_month="+date_month+"&date_year="+date_year+"&gender="+gender+"&location="+location+"&website_title="+website_title+"&website_url="+website_url+"&skype="+skype+"&email="+email+"&edited=a&uid='.@$_GET['id'].'&stats='.base64_encode(serialize($my_stats)).'";
			$.ajax({
			type: "POST",
			url: "/sources/edit_profile.php",
			data: dataString,
			cache: false,
			success: function() {
				document.location.reload(true);
			}
			});
			return false;
		}
	</script>';
	break;
}

?>
