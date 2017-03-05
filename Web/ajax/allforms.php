<?php

//Security: If somebody visits this, he will return to the index
/*if(empty($_POST)) {
	header("Location: /");
	exit;
}*/

/*

Tareas:

Todos los $user_id != 1 reemplazarlo por lo de los rangos

*/

include($_SERVER['DOCUMENT_ROOT'] . '/processor.php');

//General variables
$my_id        = getUID();
$show_success = true;
$uid          = @$_POST['uid'];
$my_stats	  = getUStats();

if (isset($_POST['newreg'])) {
    
    $username = mysqli_escape_string($conn, strip_tags(@$_POST['username']));
    $password = @$_POST['password'];
    $email    = mysqli_escape_string($conn, strip_tags(@$_POST['email']));
    $ref      = ((empty($_POST['ref']) === true || is_numeric($_POST['ref']) === false) ? 0 : $_POST['ref']);
    
    if (empty($_POST["vercode"])) {
        addAjaxError('emptyCaptcha');
    } elseif (@$_POST["vercode"] != $_SESSION["vercode"]) {
        addAjaxError('incorrectCaptcha');
    }
    
    if (empty($username)) {
        addAjaxError('emptyUsername');
    } elseif (preg_match('/\^|`|\*|\+|<|>|\[|\]|¨|´|\{|\}|\||\\|\"|\@|·|\#|\$|\%|\&|\¬|\/|\(|\)|=|\?|\'|¿|ª|º/', $username)) {
        addAjaxError('forbiddenChars');
    } elseif (userExists($username)) {
        addAjaxError('userExists');
    }
    
    if (empty($password)) {
        addAjaxError('emptyPassword');
    } else {
        $password = md5($password);
    }
    
    if (empty($email)) {
        addAjaxError('emptyEmail');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        addAjaxError('invalidMail');
    }
    
    if (count($_SESSION['errors']) == 0) {
        $continue = true;
    }
    
    if ($continue) {
        $code = NewGuid();
        addUser($username, $password, $code, $email, $ref);
    }
}

if (isset($_POST['login'])) {
    if (!isOnline()) {
        $expireTime = @$_POST['duration'];
        $username   = mysqli_escape_string($conn, @$_POST['username']);
        $password   = @$_POST['password'];
        if (empty($username)) {
            $show_success = false;
            addAjaxError('emptyUsername');
        }
        if (empty($password)) {
            $show_success = false;
            addAjaxError('emptyPassword');
        }
        if (is_numeric($expireTime)) {
            $expireTime = (int) $expireTime;
        } else {
            $show_success = false;
            addAjaxError('hackTry');
        }
        if (!empty($username) && !empty($password) && !checkUser($username, md5($password)) && Attemps('LOGIN', 'get') > 0) {
            $show_success = false;
            loginAttemps('wrong');
            if (Attemps('LOGIN', 'get') == 0) {
                addAjaxError('attempsWasted');
            } else {
                addAjaxError('wrongCredentials');
            }
        }
        if ($show_success) {
            $code       = getUStats(getUID($username))['code'];
            $session_id = md5($username . md5($password) . $code);
            setcookie("SESSION_ID", base64_encode($username . "|" . $session_id), time() + $expireTime * 60, "/");
            setAjaxSuccess("login");
        }
    } else {
        addAjaxError("alreadyLogged");
    }
}

if (isset($_POST['logout'])) {
    if (isOnline()) {
        $token = @$_POST['logout'];
        if (array_key_exists('SESSION_ID', $_COOKIE)) {
            $me         = getCUser();
            $stats      = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE username = '$me'"));
            $session_id = md5($stats['username'] . $stats['password'] . $stats['code']);
            if ($token === base64_encode($stats['username'] . "|" . $session_id)) {
                setcookie("SESSION_ID", "", time() - 3600, '/', '', 0);
                unset($_COOKIE['SESSION_ID']);
                setAjaxSuccess("logout");
            } //else == hackTry
        }
    } else {
        addAjaxError("notLoggedIn");
    }
}

if(isOnline()) {
    if (isset($_POST['new-message'])) {
        $_SESSION['last_flood_try'] = lastMessageActivity($my_id, 4);
        if ($now > $_SESSION['last_flood_try'] + FLOOD_TIME) {
            $now     = time();
            $message = mysqli_real_escape_string($conn, nl2br(strip_tags(@$_POST['message'])));
            $type    = @$_POST['type'];
            $table   = (($type == 0) ? 'profile_messages' : (($type == 2) ? 'ticket' : (($type == 4) ? 'user_moods' : 'feedback')));
            if (is_numeric($type)) {
                $r = mysqli_query($conn, "INSERT INTO $table (user_id, creation_time, message) VALUES ('$my_id', '$now', '$message')");
                if ($r) {
                    setAjaxSuccess("new" . (($type == 0) ? 'PMsg' : (($type == 2) ? 'Ticket' : (($type == 4) ? 'Mood' : 'Feedback'))));
                } else {
                    addAjaxError("Query inválida: " . mysqli_error($conn));
                }
            } else {
                addAjaxError("hackTry");
            }
        } else {
            addAjaxError("floodDetected");
        }
    }

    if (isset($_POST['new-reply'])) {
        $type                       = mysqli_real_escape_string($conn, @$_POST['type']);
        $_SESSION['last_flood_try'] = lastMessageActivity($my_id, (($type == 0) ? 1 : (($type == 1) ? 3 : 5)));
        if ($now > $_SESSION['last_flood_try'] + FLOOD_TIME) {
            $now       = time();
            $message   = mysqli_real_escape_string($conn, nl2br(strip_tags(@$_POST['message'])));
            $linked_id = mysqli_real_escape_string($conn, @$_POST['linked_id']);
            $approved  = (($type == 2) ? !(int) $user_info['allow_approve_mood_reply'] : 1); //Check there if the message is spam
            if (is_numeric($linked_id) && is_numeric($type)) {
                $r = mysqli_query($conn, "INSERT INTO comments (user_id, action_time, message, linked_id, type, approved) VALUES ('$my_id', '$now', '$message', '$linked_id', '$type', '$approved')");
                if ($r) {
                    if (!$user_info['allow_approve_mood_reply']) {
                        setAjaxSuccess("newReply");
                    } else {
                        setAjaxSuccess("newReplyPending");
                    }
                } else {
                    addAjaxError("Query inválida: " . mysqli_error($conn));
                }
            } else {
                addAjaxError("hackTry");
            }
        } else {
            addAjaxError("floodDetected");
        }
    }

    if (isset($_POST['approve'])) {
        $id  = mysqli_real_escape_string($conn, @$_POST['id']);
        checkForUnapprovedComments($uid, 2, $arr);
        if (count(multiarray_search($arr, 'id', $id)) > 0) {
            if (is_numeric($id)) {
                $r = mysqli_query($conn, "UPDATE comments SET approved = '1' WHERE id = '$id'");
                if ($r) {
                    setAjaxSuccess("replyApproved");
                } else {
                    addAjaxError("Query inválida: " . mysqli_error($conn));
                }
            } else {
                addAjaxError("hackTry");
            }
        } else {
            addAjaxError("hackTry");
        }
    }

    if (isset($_POST['delete_unapproved_message'])) {
        $id  = mysqli_real_escape_string($conn, @$_POST['id']);
        checkForUnapprovedComments($uid, 2, $arr);
        if (count(multiarray_search($arr, 'id', $id)) > 0) {
            if (is_numeric($id)) {
                $r = mysqli_query($conn, "UPDATE comments SET approved = '1' WHERE id = '$id'");
                if ($r) {
                    setAjaxSuccess("replyDeleted");
                } else {
                    addAjaxError("Query inválida: " . mysqli_error($conn));
                }
            } else {
                addAjaxError("hackTry");
            }
        } else {
            addAjaxError("hackTry");
        }
    }

    if(isset($_POST['vote'])) {
        if(!($_POST['vote'] != "up" && $_POST['vote'] != "down")) {
            $linked_id = mysqli_real_escape_string($conn, strip_tags(@$_POST['linked_id']));
            $b = !actionExists($linked_id, 4, null, $arr);
                    //v=== Esto lo he puesto porque justo cuando se comprueba el valor dicho valor no existe se añade mas tarde...
            //Bugged
            $add = $b && !($arr != null && (int)$arr['vote'] === (($_POST['vote'] == 'up') ? 1 : -1));
            if($add) {
                $_SESSION['last_flood_try'] = lastActionActivity($my_id, 0);
                if(!isActionFlooded($my_id, 0)) {
                    $type = mysqli_real_escape_string($conn, strip_tags(@$_POST['type']));
                    $vote = (($_POST['vote'] === "up") ? 1 : -1);
                    $action_time = time();
                    $r = mysqli_query($conn, "INSERT INTO vote (type, linked_id, user_id, action_time, vote) VALUES ('$type', '$linked_id', '$my_id', '$action_time', '$vote')");
                    if ($r) {
                        setAjaxSuccess("voteAdded");
                    } else {
                        addAjaxError("Query inválida: " . mysqli_error($conn));
                    }
                } else {
                    addAjaxError('floodDetected');
                }
            } else {
                $r = mysqli_query($conn, "DELETE FROM vote WHERE linked_id = '$linked_id' AND user_id = '$my_id'");
                if ($r) {
                    setAjaxSuccess("voteDeleted");
                } else {
                    addAjaxError("Query inválida: " . mysqli_error($conn));
                }
            }
        }
    }
}

if (isset($_POST['edited'])) {
    
    $username      = mysqli_real_escape_string($conn, strip_tags(@$_POST['username']));
    $real_name     = mysqli_real_escape_string($conn, strip_tags(@$_POST['real_name']));
    $email         = mysqli_real_escape_string($conn, strip_tags(@$_POST['email']));
    $gender        = mysqli_real_escape_string($conn, strip_tags(@$_POST['gender']));
    $date_day      = mysqli_real_escape_string($conn, strip_tags(@$_POST['date_day'])); //Tengo que crear un javascript que parsee estos textboxes y compruebe que el dia no es mayor al del mes (99-99-2014) y cosas así tampoco se permiten letras etc..
    $date_month    = mysqli_real_escape_string($conn, strip_tags(@$_POST['date_month']));
    $date_year     = mysqli_real_escape_string($conn, strip_tags(@$_POST['date_year']));
    $final_date    = $date_day . "-" . $date_month . "-" . $date_year;
    $location      = mysqli_real_escape_string($conn, strip_tags(@$_POST['location']));
    $website_title = mysqli_real_escape_string($conn, strip_tags(@$_POST['website_title']));
    $website_url   = mysqli_real_escape_string($conn, strip_tags(@$_POST['website_url']));
    $skype         = mysqli_real_escape_string($conn, strip_tags(@$_POST['skype']));
    $user_id       = mysqli_real_escape_string($conn, strip_tags(@$_POST['user_id']));
    if($user_id != $my_id && $user_id != 1) {
        $show_success = false;
        exit;
    }
    if(isset($_POST['username']) && strlen($username) > 0) {
		if (preg_match('/\^|`|\*|\+|<|>|\[|\]|¨|´|\{|\}|\||\\|\"|\@|·|\#|\$|\%|\&|\¬|\/|\(|\)|=|\?|\'|¿|ª|º/', $username)) {
	        addAjaxError('forbiddenChars');
	        $show_success = false;
	    } elseif (getUID($username) != $my_id && userExists($username)) {
	        addAjaxError('userExists');
	        $show_success = false;
	    } 
	} elseif(isset($_POST['username']) && strlen($username) === 0) {
		addAjaxError('emptyUsername');
        $show_success = false;
	}
	if(isset($email) && strlen($email) > 0 && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        addAjaxError("invalidMail");
        $show_success = false;
    } /*elseif(isset($email) && strlen($email) === 0) {
    	addAjaxError("emptyEmail");
    	$show_success = false;
    }*/
	if(isset($gender) && strlen($gender) > 0 && !is_numeric($gender)) {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (isset($date_day) && strlen($date_day) > 0 && !is_numeric($date_day)) {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (isset($date_month) && strlen($date_month) > 0 && !is_numeric($date_month)) {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (isset($date_year) && strlen($date_year) > 0 && !is_numeric($date_year)) {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if ($show_success) {
    	if ($my_stats['username'] != $username) {
	        mysqli_query($conn, "UPDATE users SET username = '$username' WHERE id = '$user_id'");
	    }
	    if ($my_stats['real_name'] != $real_name) {
	        mysqli_query($conn, "UPDATE users SET real_name = '$real_name' WHERE id = '$user_id'");
	    }
    	if ($my_stats['email'] != $email) {
            $act = md5(time());
            mysqli_query($conn, "UPDATE users SET email = '$email' WHERE id = '$user_id'");
            mysqli_query($conn, "UPDATE users SET activation = '$act' WHERE id = '$user_id'");
            mail($email, 'GimmeAHit! - Activación de correo', '¡Hola! Para activar tu cuenta visita el siguiente link: http://' . $_SERVER['SERVER_NAME'] . '/?action=act&code=' . $act);
        }
        if ($my_stats['gender'] != $gender) {
            mysqli_query($conn, "UPDATE users SET gender = '$gender' WHERE id = '$user_id'");
        }
        if ($my_stats['birthdate'] != $final_date) {
            mysqli_query($conn, "UPDATE users SET birthdate = '$final_date' WHERE id = '$user_id'");
        }
	    if ($my_stats['location'] != $location) {
	        mysqli_query($conn, "UPDATE users SET location = '$location' WHERE id = '$user_id'");
	    }
	    if ($my_stats['website_title'] != $website_title) {
	        mysqli_query($conn, "UPDATE users SET website_title = '$website_title' WHERE id = '$user_id'");
	    }
	    if ($my_stats['website_url'] != $website_url) {
	        mysqli_query($conn, "UPDATE users SET website_url = '$website_url' WHERE id = '$user_id'");
	    }
	    if ($my_stats['skype'] != $skype) {
	        mysqli_query($conn, "UPDATE users SET skype = '$skype' WHERE id = '$user_id'");
	    }
        setAjaxSuccess("profileEdited");
    }
}

if (isset($_POST['rel_type'])) {
    switch ($_POST['rel_type']) {
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

if (isset($_POST['passchanged'])) {
    $user_id = mysqli_real_escape_string($conn, strip_tags(@$_POST['user_id']));
    if($user_id != $my_id && $user_id != 1) {
        exit;
    }
    if ($my_stats['password'] === md5($_POST['oldpass']) && md5($_POST['newpass']) === md5($_POST['rnewpass'])) {
        $newpass = md5($_POST['newpass']);
        mysqli_query($conn, "UPDATE users SET password = '$newpass' WHERE id = '$user_id'");
        setAjaxSuccess("passwordChanged");
    } else {
        addAjaxError("PassNotMatch");
    }
}

if (isset($_POST['reconfirm'])) {
    $user_id = mysqli_real_escape_string($conn, strip_tags(@$_POST['user_id']));
    if($user_id != $my_id && $user_id != 1) {
        exit;
    }
    $act = md5(time());
    mysqli_query($conn, "UPDATE users SET activation = '$act' WHERE id = '$user_id'");
    mail($email, 'GimmeAHit! - Activación de correo', '¡Hola! Para activar tu cuenta visita el siguiente link: http://' . $_SERVER['SERVER_NAME'] . '/?action=act&code=' . $act);
    setAjaxSuccess("reconfirmMail");
}

if (isset($_POST['custom'])) {
    if (empty($_POST['custom-part']) || @$_POST['custom-part'] === 'only_avatar') {
        $avatar_url = mysqli_real_escape_string($conn, @$_POST['avatar_url']);
        if ((!empty($avatar_url) && filter_var($avatar_url, FILTER_VALIDATE_URL)) || empty($avatar_url)) {
            if ($my_stats['avatar'] != $avatar_url) {
                mysqli_query($conn, "UPDATE users SET avatar = '$avatar_url' WHERE id = '$my_id'");
            }
        } else {
            addAjaxError("notValidUrl");
            $show_success = false;
        }
    }
    if (empty($_POST['custom-part']) || @$_POST['custom-part'] === 'only_banner') {
        $banner_url    = mysqli_real_escape_string($conn, @$_POST['banner_url']);
        $banner_bcolor = mysqli_real_escape_string($conn, @$_POST['banner_bcolor']);
        $banner_mosaic = mysqli_real_escape_string($conn, @$_POST['banner_mosaic']);
        if ((!empty($banner_url) && filter_var($banner_url, FILTER_VALIDATE_URL)) || empty($banner_url)) {
            if ($my_stats['banner'] != $banner_url) {
                mysqli_query($conn, "UPDATE users SET banner = '$banner_url' WHERE id = '$my_id'");
            }
            if (validHexColor($banner_bcolor)) {
                if ($my_stats['banner_bcolor'] != $banner_bcolor) {
                    mysqli_query($conn, "UPDATE users SET banner_bcolor = '$banner_bcolor' WHERE id = '$my_id'");
                }
            } else {
                addAjaxError("hackTry");
                $show_success = false;
                echo "<script>alert('" . __LINE__ . "');</script>";
            }
            if (is_numeric($banner_mosaic)) {
                if ($my_stats['banner_mosaic'] != $banner_mosaic) {
                    mysqli_query($conn, "UPDATE users SET banner_mosaic = '$banner_mosaic' WHERE id = '$my_id'");
                }
            } else {
                addAjaxError("hackTry");
                $show_success = false;
                echo "<script>alert('" . __LINE__ . "');</script>";
            }
        } else {
            addAjaxError("notValidUrl");
            $show_success = false;
        }
    }
    if ($show_success) {
        setAjaxSuccess("profileEdited");
    }
}

if (isset($_POST['config'])) {
    $edit_stats                     = ((empty($uid) || $my_id != 1) ? getUStats($my_id) : getUStats($uid));
    $show_real_name                 = mysqli_real_escape_string($conn, @$_POST['real_name_pdd']);
    $show_location                  = mysqli_real_escape_string($conn, @$_POST['location_pdd']);
    $show_gender                    = mysqli_real_escape_string($conn, @$_POST['gender_pdd']);
    $show_age                       = mysqli_real_escape_string($conn, @$_POST['age_pdd']);
    $show_skype                     = mysqli_real_escape_string($conn, @$_POST['skype_pdd']);
    $show_mail                      = mysqli_real_escape_string($conn, @$_POST['email_pdd']);
    $show_moods                     = mysqli_real_escape_string($conn, @$_POST['moods_pdd']);
    $show_avatar                    = mysqli_real_escape_string($conn, @$_POST['avatar_pdd']);
    $show_banner                    = mysqli_real_escape_string($conn, @$_POST['banner_pdd']);
    $show_last_act                  = mysqli_real_escape_string($conn, @$_POST['last_act_pdd']);
    $show_visitors                  = mysqli_real_escape_string($conn, @$_POST['visitors_pdd']);
    $show_friends                   = mysqli_real_escape_string($conn, @$_POST['friends_pdd']);
    $show_follow                    = mysqli_real_escape_string($conn, @$_POST['follow_pdd']);
    $show_badges                    = mysqli_real_escape_string($conn, @$_POST['badges_pdd']);
    $allow_approve_mood_reply       = (int) @$_POST['allow_approve_mood_reply'];
    $allow_profile_msgs             = (int) @$_POST['allow_profile_msgs'];
    $allow_approve_profile_messages = (int) @$_POST['allow_approve_profile_messages'];
    $allow_friend_req               = (int) @$_POST['allow_friend_req'];
    $allow_newsletters              = (int) @$_POST['allow_newsletters'];
    $allow_hide_online              = (int) @$_POST['allow_hide_online'];
    $allow_private_msgs             = (int) @$_POST['allow_private_msgs'];
    $allow_warn_onleave             = (int) @$_POST['allow_warn_onleave'];
    $timezone                       = mysqli_real_escape_string($conn, @$_POST['timezone']);
    $date_format                    = mysqli_real_escape_string($conn, @$_POST['date_format']);
    $detect_dst                     = mysqli_real_escape_string($conn, @$_POST['detect_dst']);
    $lang                           = mysqli_real_escape_string($conn, @$_POST['lang']);
    if (is_numeric($show_real_name)) {
        if ($my_stats['show_real_name'] != $show_real_name) {
            mysqli_query($conn, "UPDATE users SET show_real_name = '$show_real_name' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_location)) {
        if ($my_stats['show_location'] != $show_location) {
            mysqli_query($conn, "UPDATE users SET show_location = '$show_location' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_gender)) {
        if ($my_stats['show_gender'] != $show_gender) {
            mysqli_query($conn, "UPDATE users SET show_gender = '$show_gender' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_age)) {
        if ($my_stats['show_age'] != $show_age) {
            mysqli_query($conn, "UPDATE users SET show_age = '$show_age' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_skype)) {
        if ($my_stats['show_skype'] != $show_skype) {
            mysqli_query($conn, "UPDATE users SET show_skype = '$show_skype' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_mail)) {
        if ($my_stats['show_mail'] != $show_mail) {
            mysqli_query($conn, "UPDATE users SET show_mail = '$show_mail' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_moods)) {
        if ($my_stats['show_moods'] != $show_moods) {
            mysqli_query($conn, "UPDATE users SET show_moods = '$show_moods' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_avatar)) {
        if ($my_stats['show_avatar'] != $show_avatar) {
            mysqli_query($conn, "UPDATE users SET show_avatar = '$show_avatar' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_banner)) {
        if ($my_stats['show_banner'] != $show_banner) {
            mysqli_query($conn, "UPDATE users SET show_banner = '$show_banner' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_last_act)) {
        if ($my_stats['show_last_act'] != $show_last_act) {
            mysqli_query($conn, "UPDATE users SET show_last_act = '$show_last_act' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_visitors)) {
        if ($my_stats['show_visitors'] != $show_visitors) {
            mysqli_query($conn, "UPDATE users SET show_visitors = '$show_visitors' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_friends)) {
        if ($my_stats['show_friends'] != $show_friends) {
            mysqli_query($conn, "UPDATE users SET show_friends = '$show_friends' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_follow)) {
        if ($my_stats['show_follow'] != $show_follow) {
            mysqli_query($conn, "UPDATE users SET show_follow = '$show_follow' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($show_badges)) {
        if ($my_stats['show_badges'] != $show_badges) {
            mysqli_query($conn, "UPDATE users SET show_badges = '$show_badges' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (empty($allow_approve_mood_reply) || (isset($allow_approve_mood_reply) && is_numeric($allow_approve_mood_reply))) {
        if ($my_stats['allow_approve_mood_reply'] != $allow_approve_mood_reply) {
            mysqli_query($conn, "UPDATE users SET allow_approve_mood_reply = '$allow_approve_mood_reply' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (empty($allow_profile_msgs) || (isset($allow_profile_msgs) && is_numeric($allow_profile_msgs))) {
        if ($my_stats['allow_profile_msgs'] != $allow_profile_msgs) {
            mysqli_query($conn, "UPDATE users SET allow_profile_msgs = '$allow_profile_msgs' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (empty($allow_approve_profile_messages) || (isset($allow_approve_profile_messages) && is_numeric($allow_approve_profile_messages))) {
        if ($my_stats['allow_approve_profile_messages'] != $allow_approve_profile_messages) {
            mysqli_query($conn, "UPDATE users SET allow_approve_profile_messages = '$allow_approve_profile_messages' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (empty($allow_friend_req) || (isset($allow_friend_req) && is_numeric($allow_friend_req))) {
        if ($my_stats['allow_friend_req'] != $allow_friend_req) {
            mysqli_query($conn, "UPDATE users SET allow_friend_req = '$allow_friend_req' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (empty($allow_newsletters) || (isset($allow_newsletters) && is_numeric($allow_newsletters))) {
        if ($my_stats['allow_newsletters'] != $allow_newsletters) {
            mysqli_query($conn, "UPDATE users SET allow_newsletters = '$allow_newsletters' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (empty($allow_hide_online) || (isset($allow_hide_online) && is_numeric($allow_hide_online))) {
        if ($my_stats['allow_hide_online'] != $allow_hide_online) {
            mysqli_query($conn, "UPDATE users SET allow_hide_online = '$allow_hide_online' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (empty($allow_private_msgs) || (isset($allow_private_msgs) && is_numeric($allow_private_msgs))) {
        if ($my_stats['allow_private_msgs'] != $allow_private_msgs) {
            mysqli_query($conn, "UPDATE users SET allow_private_msgs = '$allow_private_msgs' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (empty($allow_warn_onleave) || (isset($allow_warn_onleave) && is_numeric($allow_warn_onleave))) {
        if ($my_stats['allow_warn_onleave'] != $allow_warn_onleave) {
            mysqli_query($conn, "UPDATE users SET allow_warn_onleave = '$allow_warn_onleave' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($timezone)) {
        if ($my_stats['timezone'] != $timezone) {
            mysqli_query($conn, "UPDATE users SET timezone = '$timezone' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($date_format)) {
        if ($my_stats['date_format'] != $date_format) {
            mysqli_query($conn, "UPDATE users SET date_format = '$date_format' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    if (is_numeric($detect_dst)) {
        if ($my_stats['detect_dst'] != $detect_dst) {
            mysqli_query($conn, "UPDATE users SET detect_dst = '$detect_dst' WHERE id = '$my_id'");
        }
    } else {
        addAjaxError("hackTry");
        $show_success = false;
    }
    //Para evitar posibles xss
    /*if($my_stats['lang'] != $lang) {
    mysqli_query($conn, "UPDATE users SET lang = '$lang' WHERE id = '$my_id'");
    }*/
    if ($show_success) {
        setAjaxSuccess("profileEdited");
    }
}

if(isOnline()) {
	if (isset($_POST['go']) && $uid != $my_id) {
	    $go_where = @$_POST['go'];
	    switch ($go_where) {
	        
	        case 'follow':
	            addRelation($my_id, $uid, 2);
	            break;
	        
	        case 'addbuddy':
	            addRelation($my_id, $uid, 1);
	            break;
	        
	        case 'block':
	            addRelation($my_id, $uid, 4);
	            break;
	        
	        case 'unblock':
	            addRelation($my_id, $uid, 0);
	            break;
	        
	        case 'delbuddy':
	            addRelation($my_id, $uid, -1);
	            break;
	        
	        case 'unfollow':
	            addRelation($my_id, $uid, -2);
	            break;

	        case 'cancelrequest':
	        	addRelation($my_id, $uid, -4);
	        	break;
	        
	        case 'report':
	            # code...
	            break;
	        
	        case 'sendmp':
	            
	            break;
	        
	        case 'add-ptext':
	            # code...
	            break;
	        
	        case 'edit-ptext':
	            # code...
	            break;
	        
	        default:
	            break;
	            
	    }
	} else if ($uid == $my_id && isset($_POST['go'])) {
		$go_where = @$_POST['go'];
	    switch ($go_where) {
	        
	        /*case 'edit-profile':
	            redirect('http://' . $_SERVER['SERVER_NAME'] . '/?action=account&go=edit');
	            break;*/
	        
	        case 'edit-avatar':
	            # code...
	            break;
	        
	        case 'add-banner':
	            # code...
	            break;
	        
	        case 'edit-banner':
	            # code...
	            break;
	        
	        case 'add-ptext':
	            # code...
	            break;
	        
	        case 'edit-ptext':
	            # code...
	            break;
	        
	        default:
	            break;
	            
	    }
	}
}

if(isOnline()) {
	if(isset($_POST['searchuser'])) {
		$username = mysqli_real_escape_string($conn, @$_POST['username']);
		$result = mysqli_query($conn, "SELECT * FROM users WHERE username LIKE '%$username%'");
		if(mysqli_num_rows($result) > 0 || !(mysqli_num_rows($result) === 1 && $result[0]['id'] === $my_id)) {
			echo '<select>';
			while($rs=mysqli_fetch_array($result)) {
				if($rs['id'] != $my_id) {
					echo '<option value="'.$rs['username'].'">'.$rs['username'].'</option>';
				}
			}
			echo '</select><input type="button" value="Seleccionar" onclick="this.parentNode.parentNode.children[1].children[0].value = this.parentNode.children[0].value; this.parentNode.innerHTML = \'\';" style="margin-left:10px;" />';
		} else {
			echo 'No se encontraron coincidencias.';
		}
	}
	if(isset($_POST['addasfriend'])) {
		$username = mysqli_real_escape_string($conn, @$_POST['username']);
		if(isset($username) && strlen($username) > 0) {
			addRelation($my_id, getUID($username), 1);
		}
	}
	if(isset($_POST['addasfollowing'])) {
		$username = mysqli_real_escape_string($conn, @$_POST['username']);
		if(isset($username) && strlen($username) > 0) {
			addRelation($my_id, getUID($username), 2);
		}
	}
	if(isset($_POST['addasblocked'])) {
		$username = mysqli_real_escape_string($conn, @$_POST['username']);
		if(isset($username) && strlen($username) > 0) {
			addRelation($my_id, getUID($username), 4);
		}
	}
}



if (count(@$_SESSION['ajaxErrors']) > 0) {
    $errorArray = $_SESSION['ajaxErrors'];
    echo '<div id="errors"><img style="float:right;position: relative;top: -1px;" src="images/icons/close.png" onclick="hideElem(this.parentNode);">';
    if (count($errorArray) > 1) {
        echo 'Al enviar el formulario se encontraron los siguientes ' . count($errorArray) . ' errores:';
    } else {
        echo 'Hubo un error al enviar el formulario:';
    }
    echo '<ul>';
    for ($i = 0; $i < count($errorArray); $i++) {
        echo '<li>' . errorCaption($errorArray[$i]) . '</li>';
    }
    echo '</ul></div>';
    $_SESSION['ajaxErrors'] = null;
}

?>