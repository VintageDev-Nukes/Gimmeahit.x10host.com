<?php

$uid = @$_GET['id'];
$action = @$_GET['action'];
$go = @$_GET['go'];

if ($action == "account" && empty($uid) && empty($go)) {
	if (isOnline()) {
		redirect('http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.getUID());
	}
	else {
		redirect('http://'.$_SERVER['SERVER_NAME'].'/?action=login');
	}
} else if ($action == "account" && isset($uid) && $go != "edit") {

		$user_info = getUStats($uid);
		if ($user_info != null) {

			updateProfileVisitors($uid);

			$status_color = "";
			$banner = 'min-height:180px;';
			$with_banner = '';

			if ($user_info['last_activity'] + ONLINE_TIME * 60 > time() && checkGivenRelation($uid, getUID(), (($user_info['show_last_act'] != -1) ? $user_info['show_last_act'] : 4))) {
				$status_color = "border: 8px #00ff00 solid;margin:-8px;";
			}

			if ($user_info['banner'] != null && checkGivenRelation($uid, getUID(), (($user_info['show_banner'] != -1) ? $user_info['show_banner'] : 4))) {
				$banner = 'background: url('.$user_info['banner'].');min-height:500px;';
			}

			if($user_info['banner_bcolor'] != null && checkGivenRelation($uid, getUID(), (($user_info['show_banner'] != -1) ? $user_info['show_banner'] : 4))) {
				$banner .= 'background-color:#'.$user_info['banner_bcolor'].';';
			}

			if(!$user_info['banner_mosaic'] && checkGivenRelation($uid, getUID(), (($user_info['show_banner'] != -1) ? $user_info['show_banner'] : 4))) {
				$banner .= "background-size: cover;";
			}

			if($user_info['banner'] != null || $user_info['banner_bcolor'] != null && checkGivenRelation($uid, getUID(), (($user_info['show_banner'] != -1) ? $user_info['show_banner'] : 4))) {
				$with_banner = "background: #F0F0F0;border: 1px #999 solid;";
			}

			echo '<div id="titlebar" style="margin: 10px 0 0 0;">
			<span style="display:inline-block;float:left;">'.$user_info['username'].'</span>';
			/*if($uid === getUID()) {
			echo '<span style="display:inline-block;float:right;">[Editar perfil]</span>';
			}*/
			echo '</div>';
			echo '<div class="header" style="position:relative;'.$banner.'">';

			// Content inside header (avatar, name, etc)

			echo '<div style="display:inline-block;float:left;margin: 25px;position: absolute;bottom: 0px;">
				<div class="avatar" style="'.$status_color.' background: url('.(($user_info['avatar'] != null && checkGivenRelation($uid, getUID(), (($user_info['show_avatar'] != -1) ? $user_info['show_avatar'] : 4))) ? (($user_info['avatar'] != null) ? $user_info['avatar'] : 'images/user.png') : 'images/user.png').');background-position: center;background-repeat: no-repeat;background-size: cover;">
					<img src="images/msn-frame.png" style="position: relative;left: -30px;top: -30px;">
				</div>
			</div>
			<div class="user_cell" style="'.$with_banner.'">
				<span class="username">'.$user_info['username'].'</span>
				<div style="margin:10px 0 15px 0;">
				Miembro desde '.date($date_format, $user_info['reg_time']-(($dst) ? date("I", $user_info['reg_time'])*3600 : 0)).'<br />
				<span style="color:#acacac;">Última actividad '.((checkGivenRelation($uid, getUID(), (($user_info['show_last_act'] != -1) ? $user_info['show_last_act'] : 4))) ? date($date_format, $user_info['last_activity']-(($dst) ? date("I", $user_info['last_activity'])*3600 : 0)) : '<i>oculto</i>').'</span>
				</div>
			</div>
			<div class="userbox" style="width:150px;position: absolute;bottom: 15px;left: 190px;">
			   <center>
			       <span style="display:block;padding-top:5px;padding-bottom:3px;">Nivel '.$user_info['lvl'].'</span>
			       <div class="pbar" style="margin:0 0 10px 0;"><div class="pbar-c" style="width:0%;"></div></div>
			   </center>
			</div>
			<div style="position:absolute;right:0;bottom:0;">'.((checkGivenRelation($uid, getUID(), (($user_info['show_gender'] != -1) ? $user_info['show_gender'] : 4))) ? (($user_info['gender'] == 0) ? '' : (($user_info['gender'] == 1) ? '<img src="images/icons/male.png" />' : '<img src="images/icons/female.png" />')) : '').'<img src="images/blank.gif" title="'.getLocation($user_info['ip'])['geoplugin_countryName'].'" class="flag '.strtolower(getLocation($user_info['ip'])['geoplugin_countryCode']).' fnone" /></div>
			</div>
			<div class="profile-buttons">
			<div class="pbuttons" id="pb">';
			if ($uid == getUID()) { //Avatar, editar nombre, editar estado
				echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
		            <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/avatar_edit.png" style="margin-top:8px;width:16px;height:16px;"></div>
		            <div class="button" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="showId(\'avatar-custom\');">Editar avatar</span></div>
		        </div>';
				if($user_info['personal_text'] != null) {
					echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
			            <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/ptext_edit.png" style="margin-top:8px;width:16px;height:16px;"></div>
			            <div class="button" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:12px;" onclick="sendForm(\'edit-ptext\');">Editar estado</span></div>
			        </div>';
				} else {
					echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
			            <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/ptext_add.png" style="margin-top:8px;width:16px;height:16px;"></div>
			            <div class="button" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'add-ptext\');">Añadir estado</span></div>
			        </div>';
				}
				if($user_info['banner'] != null) {
					echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
	        	<div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/image_edit.png" style="margin-top:8px;width:16px;height:16px;"></div>
	            <div class="button" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:12px;" onclick="showId(\'banner-custom\');">Editar imagen de fondo</span></div>
	       		</div>';
				} else {
					echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
			            <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/image_add.png" style="margin-top:8px;width:16px;height:16px;"></div>
			            <div class="button" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:12px;" onclick="showId(\'banner-custom\');">Añadir imagen de fondo</span></div>
			        </div>';
				}
				echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
		            <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/edit_profile.png" style="margin-top:8px;width:16px;height:16px;"></div>
		            <div class="button" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="window.location = \'http://' . $_SERVER['SERVER_NAME'] . '/?action=account&go=edit\';">Editar perfil</span></div>
		        </div>';
			} else {
				echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
			        <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/report.png" style="margin-top:8px;width:16px;height:16px;"></div>
			        <div class="button" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'report\');">Reportar usuario</span></div>
			    </div>';
				if(isBlocked(getUID(), $uid)) {
					echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
				        <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/unlock_user.png" style="margin-top:8px;width:16px;height:16px;"></div>
				        <div class="button" id="unblock" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'unblock\', \'block\');">Desbloquear</span></div>
				    </div>';
				} else {
					echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
				        <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/send_mp.png" style="margin-top:8px;width:16px;height:16px;"></div>
				        <div class="button" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'sendmp\');">Enviar mensaje privado</span></div>
				    </div>';
					echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
				        <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/block_user.png" style="margin-top:8px;width:16px;height:16px;"></div>
				        <div class="button" id="block" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'block\', \'unblock\');">Bloquear</span></div>
				    </div>';
					if(isFollowing(getUID(), $uid)) {
						echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
					        <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/cross.png" style="margin-top:8px;width:16px;height:16px;"></div>
					        <div class="button" id="unfollow" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'unfollow\', \'follow\');">Dejar de seguir</span></div>
					    </div>';
					} else {
						echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
					        <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/tick.png" style="margin-top:8px;width:16px;height:16px;"></div>
					        <div class="button" id="follow" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'follow\', \'unfollow\');">Seguir</span></div>
					    </div>';
					}
					if(isFriend(getUID(), $uid)) {
						echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
					        <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/buddy_delete.png" style="margin-top:8px;width:16px;height:16px;"></div>
					        <div class="button" id="delbuddy" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'delbuddy\', \'addbuddy\');">Quitar como amigo</span></div>
					    </div>';
					} else {
						if(isPending(getUID(), $uid)) {
							echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
						        <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/clock.png" style="margin-top:8px;width:16px;height:16px;"></div>
						        <div class="button" id="cancelrequest" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:12px;" onclick="sendForm(\'cancelrequest\', \'addbuddy\');">Pendiente de aprobación</span></div>
						    </div>';
						} else {
							echo '<div class="groupbutton" id="gbutton" style="height:32px;float:right;">
						        <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/buddy_add.png" style="margin-top:8px;width:16px;height:16px;"></div>
						        <div class="button" id="addbuddy" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'addbuddy\', \''.(($user_info['allow_friend_req']) ? 'cancelrequest' : 'delbuddy').'\', \''.(($user_info['allow_friend_req']) ? 'cancelrequest' : 'delbuddy').'\');">Añadir como amigo</span></div>
						    </div>';
						}
					}
				}
			}

			echo '</div>
			</div>
			<div id="injectedcss"></div>
			<div style="display:flex;">
			<div class="profile-stats" style="float:left;width: calc(100% - 313px);">
			<div class="profile-nav-menu">
			    <ul>
			        <li><span onclick="window.location=\'http://'.$_SERVER['SERVER_NAME'].'/\'+insertParam(\'see\', \'general-info\');">Información general</span></li>
			        '.((checkGivenRelation($uid, getUID(), (($user_info['show_moods'] != -1) ? $user_info['show_moods'] : 4))) ? '<li><span onclick="window.location=\'http://'.$_SERVER['SERVER_NAME'].'/\'+insertParam(\'see\', \'moods\');">Estados de <i>'.$user_info['username'].'</i></span></li>' : '').'
			        <!--- <li><span onclick="window.location=\'http://'.$_SERVER['SERVER_NAME'].'/\'+insertParam(\'see\', \'social\');">Amigos, seguidores y siguiendo</span></li> -->
			        <li><span onclick="window.location=\'http://'.$_SERVER['SERVER_NAME'].'/\'+insertParam(\'see\', \'badges\');">Medallas</span></li>
			        <li><span onclick="window.location=\'http://'.$_SERVER['SERVER_NAME'].'/\'+insertParam(\'see\', \'visitors\');">Mensajes de los visitantes</span></li>
			    </ul>
			</div>
			<div class="profile-main-tab">';
			$see = @$_GET['see'];
			switch ($see) {
			case 'moods':
				if(checkGivenRelation($uid, getUID(), (($user_info['show_moods'] != -1) ? $user_info['show_moods'] : 4))) {
					if($uid == getUID()) {
						echo '<div class="userbox">Publicar un estado</div>';
						echo createCommentBox(4, array('placeholder="Comparte una idea" ph="Comparte una idea"', ''));
					}
					echo '<div class="userbox">Todos los estados</div>';
					$moods = getMoods($uid);
					if(count($moods) > 0) {
						if($uid == getUID()) {
							if(checkForUnapprovedComments($uid, 2)) {
								echo '<div class="warn">Tienes respuestas sin aprobar. <u onclick="window.location=\'http://'.$_SERVER['SERVER_NAME'].'/\'+insertParam(\'see\', \'unapproved-replies\');">Revisar respuestas</u>.</div>';
							}
						}
						foreach($moods as $mood) {
							echo displayComment($mood, getUStats($mood['user_id']), 4);
						}
					} else {
						echo '<center style="padding: 10px 0;"><i>'.$user_info['username'].' no tiene ningún estado publicado aún.'.(($uid == getUID() || !isOnline()) ? '' : ' ¡Anímale a que lo haga!').'</i></center>';
					}
					echo '<!--- Falta por meter el paginator -->';
					echo '<script>var menunum = 2;</script>';
				} else {
					redirect("http://".$_SERVER['SERVER_NAME']."/index.php?action=account&id=".$uid);
				}
				break;

			case 'unapproved-replies':
				if($uid === getUID()) {
					echo '<div class="userbox">Respuestas sin aprobar</div>';
					$unapproved_mood_replies = array();
					checkForUnapprovedComments($uid, 2, $unapproved_mood_replies);
					echo '<div class="userbox"><h4>Respuestas en estados sin aprobar ['.count($unapproved_mood_replies).']</h4></div>';
					if(count($unapproved_mood_replies) > 0) {
						foreach($unapproved_mood_replies as $urmood) {
							echo displayComment($urmood, getUStats($urmood['user_id']), 5);
							echo '<div class="approve-reply">
								<form method="post" onsubmit="var confirmed = confirm(\'¿Est\xE1s seguro de que quieres borrar este mensaje?\'); if(confirmed) {sendAjax(event);} return confirmed;">
									<input type="hidden" name="id" value="'.$urmood['id'].'" />
									<input type="hidden" name="uid" value="'.@$_GET['id'].'" />
									<input type="submit" name="delete_unapproved_message" value="Eliminar mensaje" />
								</form>
								<form method="post">
									<input type="hidden" name="id" value="'.$urmood['id'].'" />
									<input type="hidden" name="uid" value="'.@$_GET['id'].'" />
									<input type="submit" name="approve" value="Aprobar mensaje" style="margin-right: 10px;" />
								</form>
							</div>';
						}
					} else {
						echo '<center style="padding: 10px 0;"><i>¡Estás al día!</i></center>';
					}
				} else {
					if(isOnline()) {
						redirect('http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.getUID().'&see=unapproved-replies');
					} else {
						redirect('http://'.$_SERVER['SERVER_NAME'].'/?action=login');
					}
				}
				break;

			case 'friends':
				echo '<div class="userbox">Amigos de <i>'.$user_info['username'].'</i></div>';
				$f = getFriends($uid);
				if(checkGivenRelation($uid, getUID(), (($user_info['show_friends'] != -1) ? $user_info['show_friends'] : 4))) {
					if(count($f) > 0) {
						foreach($f as $friends) {
							$f_stat = getUStats($friends[0]);
							echo '<div class="user-div">
							<div class="small-avatar" style="display:inline-block;background-image: url('.((checkGivenRelation($friends[0], getUID(), (($f_stat['show_avatar'] != -1) ? $f_stat['show_avatar'] : 4))) ? $f_stat['avatar'] : 'images/user.png').');"></div>
								<div style="display:inline-block;width: calc(100% - 37px);line-height:20px;">
										<a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.$friends[0].'"><b>'.$f_stat['username'].'</b></a>
										<!--- <img style="position: relative;top: 2px;" src="images/icons/bullet_star.png">Nivel '.$f_stat['lvl'].' --><br>
										<img src="images/blank.gif" title="'.getLocation($user_info['ip'])['geoplugin_countryName'].'" class="flag '.strtolower(getLocation($user_info['ip'])['geoplugin_countryCode']).' fnone" />'.((checkGivenRelation($friends[0], getUID(), (($user_info['show_gender'] != -1) ? $user_info['show_gender'] : 4))) ? (($user_info['gender'] == 0) ? '' : (($user_info['gender'] == 1) ? '<img src="images/icons/male.png" />' : '<img src="images/icons/female.png" />')) : '').'
										
								</div>
							</div>';
						}
					} else {
						echo '<center style="padding: 10px 0;"><i>'.$user_info['username'].' no tiene amigos.</i></center>';
					}
				} else {
					echo '<center style="padding: 10px 0;"><i>oculto</i></center>';
				}		
				break;

			case 'followers':
				echo '<div class="userbox">Seguidores de <i>'.$user_info['username'].'</i></div>';
				$fs = getFollowers($uid);
				if(checkGivenRelation($uid, getUID(), (($user_info['show_follow'] != -1) ? $user_info['show_follow'] : 4))) {
					if(count($fs) > 0) {
						foreach($fs as $followers) {
							$fs_stat = getUStats($followers[0]);
							echo '<div class="user-div">
							<div class="small-avatar" style="display:inline-block;background-image: url('.((checkGivenRelation($followers[0], getUID(), (($fs_stat['show_avatar'] != -1) ? $fs_stat['show_avatar'] : 4))) ? $fs_stat['avatar'] : 'images/user.png').');"></div>
								<div style="display:inline-block;width: calc(100% - 37px);line-height:20px;">
										<a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.$followers[0].'"><b>'.$fs_stat['username'].'</b></a>
										<!--- <img style="position: relative;top: 2px;" src="images/icons/bullet_star.png">Nivel '.$fs_stat['lvl'].' --><br>
										<img src="images/blank.gif" title="'.getLocation($user_info['ip'])['geoplugin_countryName'].'" class="flag '.strtolower(getLocation($user_info['ip'])['geoplugin_countryCode']).' fnone" />'.((checkGivenRelation($followers[0], getUID(), (($user_info['show_gender'] != -1) ? $user_info['show_gender'] : 4))) ? (($user_info['gender'] == 0) ? '' : (($user_info['gender'] == 1) ? '<img src="images/icons/male.png" />' : '<img src="images/icons/female.png" />')) : '').'
										
								</div>
							</div>';
						}
					} else {
						echo '<center style="padding: 10px 0;"><i>'.$user_info['username'].' no tiene seguidores.</i></center>';
					}
				} else {
					echo '<center style="padding: 10px 0;"><i>oculto</i></center>';
				}		
				break;

			case 'following':
				echo '<div class="userbox">Seguidos por <i>'.$user_info['username'].'</i></div>';
				$fw = getFriends($uid);
				if(checkGivenRelation($uid, getUID(), (($user_info['show_follow'] != -1) ? $user_info['show_follow'] : 4))) {
					if(count($fw) > 0) {
						foreach($fw as $following) {
							$fw_stat = getUStats($following[0]);
							echo '<div class="user-div">
							<div class="small-avatar" style="display:inline-block;background-image: url('.((checkGivenRelation($following[0], getUID(), (($fw_stat['show_avatar'] != -1) ? $fw_stat['show_avatar'] : 4))) ? $fw_stat['avatar'] : 'images/user.png').');"></div>
								<div style="display:inline-block;width: calc(100% - 37px);line-height:20px;">
										<a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.$following[0].'"><b>'.$fw_stat['username'].'</b></a>
										<!--- <img style="position: relative;top: 2px;" src="images/icons/bullet_star.png">Nivel '.$fw_stat['lvl'].' --><br>
										<img src="images/blank.gif" title="'.getLocation($user_info['ip'])['geoplugin_countryName'].'" class="flag '.strtolower(getLocation($user_info['ip'])['geoplugin_countryCode']).' fnone" />'.((checkGivenRelation($following[0], getUID(), (($user_info['show_gender'] != -1) ? $user_info['show_gender'] : 4))) ? (($user_info['gender'] == 0) ? '' : (($user_info['gender'] == 1) ? '<img src="images/icons/male.png" />' : '<img src="images/icons/female.png" />')) : '').'
										
								</div>
							</div>';
						}
					} else {
						echo '<center style="padding: 10px 0;"><i>'.$user_info['username'].' no sigue a nadie.</i></center>';
					}
				} else {
					echo '<center style="padding: 10px 0;"><i>oculto</i></center>';
				}								
				break;

			case 'badges':
				echo '<script>var menunum = 3;</script>';
				break;

			case 'visitors':
				echo '<script>var menunum = 4;</script>';
				break;

			default:

				$strrealname = '';
				$strgender = '';
				$stryears = '';
				$strlocat = '';
				$strname = '';
				$strmail = '';
				$strskype = '';
				$strweb = '';
				$strutxt = '';

				if ($user_info['gender'] == 0) {
					$strgender = 'una persona';
				}
				else
				if ($user_info['gender'] == 1) {
					$strgender = 'un chico';
				}
				else
				if ($user_info['gender'] == 2) {
					$strgender = 'una chica';
				}

				if ($user_info['real_name'] != null) {
					$strrealname = ' que se llama '.$user_info['real_name'];
				}

				if ($user_info['birthdate'] != null) {
					$stryears = ' de '.StrDate($user_info['birthdate']);
				}

				if ($user_info['location'] != null) {
					$strlocat = ' de '.$user_info['location'];
				}

				if ($user_info['email'] != null) {
					$strmail = '<br />Mi correo electrónico es '.$user_info['email'];
					if ($user_info['skype'] == null) {
						$strmail.= '.';
					}
				}

				if ($user_info['skype'] != null) {
					if ($user_info['email'] == null) {
						$strskype = '<br />Mi Skype es '.$user_info['skype'].'.';
					}
					else {
						$strskype = ' y mi Skype es '.$user_info['skype'].'.';
					}
				}

				if ($user_info['website_title'] != null && $user_info['website_url'] != null) {
					$strweb = '<br />Actualmente, tengo una web que se llama '.$user_info['website_title'].' y su enlace es: <a href="'.$user_info['website_url'].'" target="_nofollow">'.$user_info['website_url'].'</a>.';
				}

				if ($user_info['personal_text'] != null) {
					$strutxt = '<br />Me gustaría decir que... <i>'.htmlentities($user_info['personal_text'], ENT_QUOTES).'</i>.';
				}

				echo '<div class="userbox">Sobre mí</div>';
				echo '<script>var menunum = 1;</script>';
				echo '<div style="padding:5px;">Soy '.$strgender.$strrealname.$stryears.$strlocat.' que actualmente reside en '.getLocation($user_info['ip'])['geoplugin_countryName'].'.<br />Soy miembro de esta comunidad desde hace '.StrDate($user_info['reg_time']).' y me contecté por última vez hace '.StrDate($user_info['last_activity']).'.'.$strmail.$strskype.$strweb.$strutxt.'</div>';
				echo '<div class="userbox">Estadísticas en la comunidad</div>
				<ul>
					<li><h3>Visitas al perfil</h3>'.$user_info['p_hits'].'</li>
					<li><h3>Seguidores</h3>'.count(getFollowers($uid)).'</li>
					<li><h3>Siguiendo</h3>'.count(getFollowing($uid)).'</li>
					<li><h3>Amigos</h3>'.count(getFriends($uid)).'</li>
					<li><h3>Nivel</h3>'.$user_info['lvl'].' [0xp/100xp]</li>
					<li><h3>Puntos</h3>'.$user_info['points'].'</li>
					<li><h3>Coins</h3>'.$user_info['coins'].'</li>
				</ul>
				<div class="userbox">Más información</div>';
				echo '<ul>
					<li><h3>Nombre real</h3><span>'.((checkGivenRelation($uid, getUID(), (($user_info['show_real_name'] != -1) ? $user_info['show_real_name'] : 4))) ? (($user_info['real_name'] != null) ? $user_info['real_name'] : '-') : '<i>oculto</i>').'</span>'.(($uid == getUID()) ? '<div class="edit-node"><input type="text" id="real_name" maxlength="100" value="'.$my_stats['real_name'].'" /><img src="images/icons/tick.png" num="1" onclick="sendSForm(this);" /></div><img class="update-button" src="images/icons/update.png" onclick="showUpdateNode(this);" />' : '' ).'</li>
					<li><h3>Sexo</h3><span>'.((checkGivenRelation($uid, getUID(), (($user_info['show_gender'] != -1) ? $user_info['show_gender'] : 4))) ? (($user_info['gender'] == 0) ? '-' : (($user_info['gender'] == 1) ? 'Hombre' : 'Mujer')) : '<i>oculto</i>').'</span>'.(($uid == getUID()) ? '<div class="edit-node"><select id="gender"><option value="0" disabled'.(($my_stats['gender'] == 0) ? ' selected' : '').'>Género</option><option value="1"'.(($my_stats['gender'] == 1) ? ' selected' : '').' data-image="imgs/male.png">Masculino</option><option value="2"'.(($my_stats['gender'] == 2) ? ' selected' : '').' data-image="imgs/female.png">Femenino</option></select><img src="images/icons/tick.png" num="2" onclick="sendSForm(this);" /></div><img class="update-button" src="images/icons/update.png" onclick="showUpdateNode(this);" />' : '' ).'</li>
					<li><h3>Fecha de nacimiento</h3><span>'.((checkGivenRelation($uid, getUID(), (($user_info['show_age'] != -1) ? $user_info['show_age'] : 4))) ? (($user_info['birthdate'] != null) ? $user_info['birthdate'].' ['.floor(getSecs($user_info['birthdate']) / (86400 * 365)).' años]' : '-') : '<i>oculto</i>').'</span>'.(($uid == getUID()) ? '<div class="edit-node">'.date_dropdown(0, 1900, explode('-', $my_stats['birthdate'])[0], explode('-', $my_stats['birthdate'])[1], explode('-', $my_stats['birthdate'])[2]).'<img src="images/icons/tick.png" num="3" onclick="sendSForm(this);" /></div><img class="update-button" src="images/icons/update.png" onclick="showUpdateNode(this);" />' : '' ).'</li>
					<li><h3>Ubicación</h3><span>'.((checkGivenRelation($uid, getUID(), (($user_info['show_location'] != -1) ? $user_info['show_location'] : 4))) ? (($user_info['location'] != null) ? $user_info['location'] : '-') : '<i>oculto</i>').'</span>'.(($uid == getUID()) ? '<div class="edit-node"><input type="text" id="location" maxlength="100" placeholder="¿Donde vives?" value="'.$my_stats['email'].'" /><img src="images/icons/tick.png" num="4" onclick="sendSForm(this);" /></div><img class="update-button" src="images/icons/update.png" onclick="showUpdateNode(this);" />' : '' ).'</li>
					<li><h3>Web personal</h3><span>'.(($user_info['website_title'] != null && $user_info['website_url'] != null) ? '<a href="'.$user_info['website_url'].'" target="_nofollow">'.$user_info['website_url'].'</a>' : '-').'</span>'.(($uid == getUID()) ? '<div class="edit-node">Título: <input type="text" id="website_title" maxlength="100" value="'.$my_stats['website_title'].'" /><br>Url: <input type="text" id="website_url" maxlength="100" value="'.$my_stats['website_url'].'" /><img src="images/icons/tick.png" num="5" onclick="sendSForm(this);" /></div><img class="update-button" src="images/icons/update.png" onclick="showUpdateNode(this);" />' : '' ).'</li>
					<li><h3>Skype</h3><span>'.((checkGivenRelation($uid, getUID(), (($user_info['show_skype'] != -1) ? $user_info['show_skype'] : 4))) ? (($user_info['skype'] != null) ? $user_info['skype'] : '-') : '<i>oculto</i>').'</span>'.(($uid == getUID()) ? '<div class="edit-node"><input type="text" id="skype" maxlength="100" value="'.$my_stats['skype'].'" /><img src="images/icons/tick.png" num="6" onclick="sendSForm(this);" /></div><img class="update-button" src="images/icons/update.png" onclick="showUpdateNode(this);" />' : '' ).'</li>
					<li><h3>Email</h3><span>'.((checkGivenRelation($uid, getUID(), (($user_info['show_mail'] != -1) ? $user_info['show_mail'] : 4))) ? (($user_info['email'] != null && getUID() == $uid) ? $user_info['email'] : '-') : '<i>oculto</i>').'</span>'.(($uid == getUID()) ? '<div class="edit-node"><input type="text" id="email" maxlength="100" value="'.$my_stats['email'].'" />'.(($my_stats['activation'] == null) ? '' : '<span style="margin-left:5px;" onclick="showId(\'confirmmail\');">Confirmar correo</span>').'<img src="images/icons/tick.png" num="7" onclick="sendSForm(this);" /></div><img class="update-button" src="images/icons/update.png" onclick="showUpdateNode(this);" />' : '' ).'</li>
				</ul>';
				if(array_key_exists('activation', $my_stats) && $my_stats['activation'] != null && $uid == getUID()) {
						echo '<div id="confirmmail" class="popup">
							<div class="popup_form" style="height:120px;margin-top:-60px;width:576px;margin-left:-288px;">
								<form method="post">
									<div class="userbox">Confirmar correo electrónico<span class="popup_close" onclick="hideElem(\'confirmmail\');">x</span></div>
									<center>
										<h3 style="padding-bottom:5px;">Para confirmar tu cuenta te hemos enviado un correo a la direción que escribiste.<br>Si no has recibido nada, por favor, revisa la bandeja de correo no deseado<br>o haz click en este botón:</h3>
										<input type="submit" value="Volver a enviar correo de confirmación" name="reconfirm" />
									</center>
								</form>
							</div>
						</div>';
				}
				//include ($_SERVER['DOCUMENT_ROOT'].'/sources/edit_profile.php');
				break;
			}

			echo '</div>
			</div>
			<div class="profile-right-menu">';
			//Seguramente como acciones mostrare los pngs de profile_buttons dentro de cada dialog

				if($see != "friends") {
					$f = getFriends($uid, 0, 18);
					echo '<div class="userbox">Amigos <span class="cap-num"><a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.$uid.'&see=friends">'.count($f).'</a></span></div>';
					echo '<div class="ubox">';
					if(checkGivenRelation($uid, getUID(), (($user_info['show_friends'] != -1) ? $user_info['show_friends'] : 4))) {
						if(count($f) > 0) {
							foreach($f as $friends) {
								$f_stat = getUStats($friends[0]);
								echo '<div class="small-avatar" style="position: relative;background-image: url('.((checkGivenRelation($friends[0], getUID(), (($f_stat['show_avatar'] != -1) ? $f_stat['show_avatar'] : 4))) ? $f_stat['avatar'] : 'images/user.png').');" onmouseover="showElem(this.getElementsByTagName(\'DIV\')[0]);" onmouseleave="hideElem(this.getElementsByTagName(\'DIV\')[0]);">
									<div id="psocial-info" style="display: none;position: absolute;top: -5px;">
										<div class="profile_social_info">
											<div class="avatar-prev" style="margin: 3px;background-image: url('.((checkGivenRelation($friends[0], getUID(), (($f_stat['show_avatar'] != -1) ? $f_stat['show_avatar'] : 4))) ? (($f_stat['avatar'] != null) ? $f_stat['avatar'] : 'images/user.png') : 'images/user.png').');"></div>
											<span style="float:right;width:100px;line-height: 20px;">
												<a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.$friends[0].'"><b>'.$f_stat['username'].'</b></a><br><img src="images/blank.gif" title="'.getLocation($user_info['ip'])['geoplugin_countryName'].'" class="flag '.strtolower(getLocation($user_info['ip'])['geoplugin_countryCode']).' fnone" />'.((checkGivenRelation($friends[0], getUID(), (($user_info['show_gender'] != -1) ? $user_info['show_gender'] : 4))) ? (($user_info['gender'] == 0) ? '' : (($user_info['gender'] == 1) ? '<img src="images/icons/male.png" />' : '<img src="images/icons/female.png" />')) : '').'<br>
												<span style="position: relative;top: -8px;margin-left: -3px;"><img style="position: relative;top: 2px;" src="images/icons/bullet_star.png">Nivel '.$f_stat['lvl'].'</span>
											</span>
										</div>
									</div>
								</div>';
							}
						} else {
							echo '<center style="padding: 10px 0;"><i>'.$user_info['username'].' no tiene amigos.</i></center>';
						}
					} else {
						echo '<center style="padding: 10px 0;"><i>oculto</i></center>';
					}
					echo '</div>';
				}
				if($see != "followers") {
					$fs = getFollowers($uid, 0, 18);
					echo '<div class="userbox">Seguidores <span class="cap-num"><a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.$uid.'&see=followers">'.count($fs).'</a></span></div>';
					echo '<div class="ubox">';
					if(checkGivenRelation($uid, getUID(), (($user_info['show_follow'] != -1) ? $user_info['show_follow'] : 4))) {
						if(count($fs) > 0) {
							foreach($fs as $followers) {
								$fs_stat = getUStats($followers[0]);
								echo '<div class="small-avatar" style="position: relative;background-image: url('.((checkGivenRelation($followers[0], getUID(), (($fs_stat['show_avatar'] != -1) ? $fs_stat['show_avatar'] : 4))) ? $fs_stat['avatar'] : 'images/user.png').');" onmouseover="showElem(this.getElementsByTagName(\'DIV\')[0]);" onmouseleave="hideElem(this.getElementsByTagName(\'DIV\')[0]);">
									<div id="psocial-info" style="display: none;position: absolute;top: -5px;">
										<div class="profile_social_info">
											<div class="avatar-prev" style="margin: 3px;background-image: url('.((checkGivenRelation($followers[0], getUID(), (($fs_stat['show_avatar'] != -1) ? $fs_stat['show_avatar'] : 4))) ? (($fs_stat['avatar'] != null) ? $fs_stat['avatar'] : 'images/user.png') : 'images/user.png').');"></div>
											<span style="float:right;width:100px;line-height: 20px;">
												<a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.$followers[0].'"><b>'.$fs_stat['username'].'</b></a><br><img src="images/blank.gif" title="'.getLocation($user_info['ip'])['geoplugin_countryName'].'" class="flag '.strtolower(getLocation($user_info['ip'])['geoplugin_countryCode']).' fnone" />'.((checkGivenRelation($followers[0], getUID(), (($user_info['show_gender'] != -1) ? $user_info['show_gender'] : 4))) ? (($user_info['gender'] == 0) ? '' : (($user_info['gender'] == 1) ? '<img src="images/icons/male.png" />' : '<img src="images/icons/female.png" />')) : '').'<br>
												<span style="position: relative;top: -8px;margin-left: -3px;"><img style="position: relative;top: 2px;" src="images/icons/bullet_star.png">Nivel '.$fs_stat['lvl'].'</span>
											</span>
										</div>
									</div>
								</div>';
							}
						} else {
							echo '<center style="padding: 10px 0;"><i>'.$user_info['username'].' no tiene seguidores.</i></center>';
						}
					} else {
						echo '<center style="padding: 10px 0;"><i>oculto</i></center>';
					}
					echo '</div>';
				}
				if($see != "following") {
					$fw = getFollowing($uid, 0, 18);
					echo '<div class="userbox">Siguiendo <span class="cap-num"><a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.$uid.'&see=following">'.count($fw).'</a></span></div>';
					echo '<div class="ubox">';
					if(checkGivenRelation($uid, getUID(), (($user_info['show_follow'] != -1) ? $user_info['show_follow'] : 4))) {
						if(count($fw) > 0) {
							foreach($fw as $following) {
								$fw_stat = getUStats($following[0]);
								echo '<div class="small-avatar" style="position: relative;background-image: url('.((checkGivenRelation($following[0], getUID(), (($fw_stat['show_avatar'] != -1) ? $fw_stat['show_avatar'] : 4))) ? $fw_stat['avatar'] : 'images/user.png').');" onmouseover="showElem(this.getElementsByTagName(\'DIV\')[0]);" onmouseleave="hideElem(this.getElementsByTagName(\'DIV\')[0]);">
									<div id="psocial-info" style="display: none;position: absolute;top: -5px;">
										<div class="profile_social_info">
											<div class="avatar-prev" style="margin: 3px;background-image: url('.((checkGivenRelation($following[0], getUID(), (($fw_stat['show_avatar'] != -1) ? $fw_stat['show_avatar'] : 4))) ? (($fw_stat['avatar'] != null) ? $fw_stat['avatar'] : 'images/user.png') : 'images/user.png').');"></div>
											<span style="float:right;width:100px;line-height: 20px;">
												<a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.$following[0].'"><b>'.$fw_stat['username'].'</b></a><br><img src="images/blank.gif" title="'.getLocation($user_info['ip'])['geoplugin_countryName'].'" class="flag '.strtolower(getLocation($user_info['ip'])['geoplugin_countryCode']).' fnone" />'.((checkGivenRelation($following[0], getUID(), (($user_info['show_gender'] != -1) ? $user_info['show_gender'] : 4))) ? (($user_info['gender'] == 0) ? '' : (($user_info['gender'] == 1) ? '<img src="images/icons/male.png" />' : '<img src="images/icons/female.png" />')) : '').'<br>
												<span style="position: relative;top: -8px;margin-left: -3px;"><img style="position: relative;top: 2px;" src="images/icons/bullet_star.png">Nivel '.$fw_stat['lvl'].'</span>
											</span>
										</div>
									</div>
								</div>';
							}
						} else {
							echo '<center style="padding: 10px 0;"><i>'.$user_info['username'].' no sigue a nadie.</i></center>';
						}
					} else {
						echo '<center style="padding: 10px 0;"><i>oculto</i></center>';
					}
					echo '</div>';
				}
				echo '<div class="userbox">Últimos visitantes</div>';
				echo '<div class="ubox">';
				if(checkGivenRelation($uid, getUID(), (($user_info['show_visitors'] != -1) ? $user_info['show_visitors'] : 4))) {
					$visitors = unserialize($user_info['last_visitors']);
					if($visitors != null && count($visitors) > 0) {
						foreach($visitors as $v) {
							$v_stat = getUStats($v['id']);
							echo '<div class="user-div">
									<div class="small-avatar" style="display:inline-block;background-image: url('.((checkGivenRelation($v['id'], getUID(), (($v_stat['show_avatar'] != -1) ? $v_stat['show_avatar'] : 4))) ? $v_stat['avatar'] : 'images/user.png').');"></div>
										<div style="display:inline-block;width: calc(100% - 37px);line-height:20px;">
												<a href="http://'.$_SERVER['SERVER_NAME'].'/?action=account&id='.$v['id'].'"><b>'.$v_stat['username'].'</b></a><br>
												<span style="color: #666;">'.date($date_format, $v['time']-(($dst) ? date("I", $v['time'])*3600 : 0)).'</span>
										</div>';
						}
					} else {
						echo '<center style="padding: 10px 0;"><i>'.$user_info['username'].' no ha sido visitado por nadie.</i></center>';
					}
				} else {
					echo '<center style="padding: 10px 0;"><i>oculto</i></center>';
				}	
			echo '</div></div>
			</div>
		</div>';

		if(!isOnline()) {
			echo '<script>var guest_action_popup = \''.guest_action_popup().'\';
			profile_buttons();</script>';
		}

			//include ($_SERVER['DOCUMENT_ROOT'].'/sources/profile_buttons.php');
			/*if($see === 'moods' || $see == 'visitors') {
				include ($_SERVER['DOCUMENT_ROOT'].'/sources/profile_actions.php');
			}*/

		}
		else {
			echo '<h1>No existe el usuario.</h1>';
		}
} else if ($action == "account" && $go == "edit") {

	$edit_stats = ((empty($uid) || getUID() != 1) ? getUStats(getUID()) : getUStats($uid));
								 //Alternativo a ser administrador es que tu id sea la del creador

	//*Futuramente tendré que comprobar si no se tiene el rango de admin, en vez de usar myid != 1*

	if(isOnline()) {

		echo '<div id="injectedcss"></div><div id="injectedcss2"></div>';
		echo '<div class="profile-stats" style="margin-top:10px;">
		<div class="profile-nav-menu">
		    <ul>
		        <li><span onclick="window.location=\'http://'.$_SERVER['SERVER_NAME'].'/\'+insertParam(\'see\', \'info\');">Informaciones</span></li>
		        <li><span onclick="window.location=\'http://'.$_SERVER['SERVER_NAME'].'/\'+insertParam(\'see\', \'config\');">Configuración y privacidad</span></li>
		        <li><span onclick="window.location=\'http://'.$_SERVER['SERVER_NAME'].'/\'+insertParam(\'see\', \'customization\');">Avatar y Banner</span></li>
		        <li><span onclick="window.location=\'http://'.$_SERVER['SERVER_NAME'].'/\'+insertParam(\'see\', \'social\');">Amigos e ignorados</span></li>
		    </ul>
		</div>
		<div class="profile-main-tab">';
		$see = @$_GET['see'];
		switch ($see) {

			case 'config':
				echo '<script>var menunum = 2;</script>';
				echo '<div style="display:none;">'.nl2br('
============================
||  PRIVACIDAD Y CONFIGURACION  ||
============================

PRIVACIDAD

Para hacer esta parte lo que debo hacer es agrupar las distintas posibilidades de mostrar una stats/variable a alguien:

Solo a mi, a mis amigos, a mis seguidores, a amigos y seguidores, a amigos de amigos, a seguidores de seguidores, a amigos de amigos y seguidores de seguidores, a todos

Luego las distintas variables o stats a mostrar serían:

- Nombre real, localización, sexo, edad, email, skype, banner, estado, avatar, ultima vez conectado, visitantes de tu perfil, mensajes de tu perfil, (siguiendo, amigos y seguidores), medallas, etc...

CONFIGURACION

Zona horaria (detectar si está en verano), poder enviar mensajes a mi perfil, aprobación de los mensajes de los visitantes, aprobación para poder ser mi amigo, formato de la fecha

Autorizar a los miembros a contactarme por email :
    Sí No
     

Autorizar los miembros enviarme mensajes privados. :
    Sí No')."</div>";
				echo '<form method="post">
					<div class="userbox">Privacidad</div>
					<ul>
						<li><h3>Mostrar tu nombre real</h3>'.privacydropdown('real_name', (($edit_stats['show_real_name'] != -1) ? $edit_stats['show_real_name'] : 4)).'</li>
						<li><h3>Mostrar tu localización</h3>'.privacydropdown('location', (($edit_stats['show_location'] != -1) ? $edit_stats['show_location'] : 4)).'</li>
						<li><h3>Mostrar tu sexo</h3>'.privacydropdown('gender', (($edit_stats['show_gender'] != -1) ? $edit_stats['show_gender'] : 4)).'</li>
						<li><h3>Mostrar tu edad</h3>'.privacydropdown('age', (($edit_stats['show_age'] != -1) ? $edit_stats['show_age'] : 4)).'</li>
						<li><h3>Mostrar tu skype</h3>'.privacydropdown('skype', (($edit_stats['show_skype'] != -1) ? $edit_stats['show_skype'] : 3)).'</li>
						<li><h3>Mostrar tu email</h3>'.privacydropdown('email', (($edit_stats['show_mail'] != -1) ? $edit_stats['show_mail'] : 0)).'</li>
						<li><h3>Mostrar tus estados</h3>'.privacydropdown('moods', (($edit_stats['show_moods'] != -1) ? $edit_stats['show_moods'] : 4)).'</li>
						<li><h3>Mostrar tu avatar</h3>'.privacydropdown('avatar', (($edit_stats['show_avatar'] != -1) ? $edit_stats['show_avatar'] : 4)).'</li>
						<li><h3>Mostrar tu banner</h3>'.privacydropdown('banner', (($edit_stats['show_banner'] != -1) ? $edit_stats['show_banner'] : 4)).'</li>
						<li><h3>Mostrar tu última conexión</h3>'.privacydropdown('last_act', (($edit_stats['show_last_act'] != -1) ? $edit_stats['show_last_act'] : 4)).'</li>
						<li><h3>Mostrar los visitantes</h3>'.privacydropdown('visitors', (($edit_stats['show_visitors'] != -1) ? $edit_stats['show_visitors'] : 4)).'</li>
						<li><h3>Mostrar tus amigos</h3>'.privacydropdown('friends', (($edit_stats['show_friends'] != -1) ? $edit_stats['show_friends'] : 1)).'</li>
						<li><h3>Mostrar tus seguidores</h3>'.privacydropdown('follow', (($edit_stats['show_follow'] != -1) ? $edit_stats['show_follow'] : 2)).'</li>
						<li><h3>Mostrar tus medallas</h3>'.privacydropdown('badges', (($edit_stats['show_badges'] != -1) ? $edit_stats['show_badges'] : 4)).'</li>
					</ul>
					<div class="userbox">Configuración</div>
					<ul>
						<li><h3>Tus estados</h3>
							<label><input type="checkbox" name="allow_approve_mood_reply" value="1"'.(($edit_stats['allow_approve_mood_reply']) ? ' checked' : '').' />Permitir aprobar las respuestas a mis estados</label><br>
						</li>
						<li><h3>Mensajes de los visitantes</h3>
							<label><input type="checkbox" name="allow_profile_msgs" value="1"'.(($edit_stats['allow_profile_msgs']) ? ' checked' : '').' />Permitir los mensajes de los visitantes</label><br>
							<label><input type="checkbox" name="allow_approve_profile_messages" value="1"'.(($edit_stats['allow_approve_profile_messages']) ? ' checked' : '').' />Aprobar los nuevos mensajes de los visitantes</label>
						</li>
						<li><h3>Amigos</h3>
							<label><input type="checkbox" name="allow_friend_req" value="1"'.(($edit_stats['allow_friend_req']) ? ' checked' : '').' />Aprobar las peticiones de los miembros antes de ser agregados como amigos</label>
						</li>
						<li><h3>Newsletters</h3>
							<label><input type="checkbox" name="allow_newsletters" value="1"'.(($edit_stats['allow_newsletters']) ? ' checked' : '').' />Permitir a los administradores enviarme boletines informativos sobre la web periódicamente</label>
						</li>
						<li><h3>Opciones misc.</h3>
							<label><input type="checkbox" name="allow_hide_online" value="1"'.(($edit_stats['allow_hide_online']) ? ' checked' : '').' />Ocultar presencia online</label>
							<label><input type="checkbox" name="allow_private_msgs" value="1"'.(($edit_stats['allow_private_msgs']) ? ' checked' : '').' />Permitir a los usuarios enviar mensajes privados</label>
							<label><input type="checkbox" name="allow_warn_onleave" value="1"'.(($edit_stats['allow_warn_onleave']) ? ' checked' : '').' />Permitir a la página web avisarme cuando me vaya sin haber guardado cambios</label>
						</li>
						<li><h3>Fecha & hora</h3>
							Zona horaria<br>
							'.timezone_dropdown((($edit_stats['timezone'] != -1) ? $edit_stats['timezone'] : 0)).'<br>
							Formato de la fecha<br>
							'.dateformat_dropdown((($edit_stats['date_format'] != -1) ? $edit_stats['date_format'] : 1)).'<br>
							<span style="font-size: 10px;">Formato actual: '.date($date_format).'</span><br>
							<label>
								<input type="checkbox" name="detect_dst" value="1"'.(($edit_stats['detect_dst']) ? ' checked' : '').' />Detectar automáticamente cuando mi zona horaria está en el horario de verano
							</label>
						</li>
						<li><h3>Idioma</h3>
							<select name="lang">
								<option disabled selected>Español</option>
							</select>
						</li>
					</ul>
					<input type="hidden" name="uid" value="'.@$_GET['id'].'" />
					<div class="userbox"><center><input type="submit" value="Guardar cambios" name="config" /></center></div>
				</form>';
				break;

			case 'customization':
				echo '<script>var menunum = 3;</script>
				<script type="text/javascript" src="scripts/jscolor/jscolor.js"></script>';
				echo '<form method="post">
					<div class="userbox">Avatar</div>
					<ul>
						<li><h3>Personalizar avatar</h3>
							<input type="textbox" name="avatar_url" placeholder="URL de avatar" value="'.$edit_stats['avatar'].'" style="margin-bottom:5px;" />
						</li>
					</ul>
					<div class="userbox">Banner</div>
					<ul>
						<li><h3>Personalizar banner</h3>
							<input type="textbox" name="banner_url" placeholder="URL del banner" value="'.$edit_stats['banner'].'" style="margin-bottom:5px;" />
							<br>
							<label>
								#<input type="textbox" name="banner_bcolor" placeholder="Color de fondo del banner" style="margin-bottom:5px;" class="color" value="'.$edit_stats['banner_bcolor'].'" />
							</label>
							<br>
							<label><input type="checkbox" name="banner_mosaic"'.(($edit_stats['banner_mosaic']) ? ' checked' : '').' value="1" />Mosáico (no recomendado en fondo sin patrones)</label>
						</li>
					</ul>
					<input type="hidden" name="custom-part" value="" />
					<div class="userbox"><center><input type="submit" value="Guardar cambios" name="custom" /></center></div>
				</form>';
				break;

			case 'social':
				echo '<script>var menunum = 4;</script>';
				echo '<div class="userbox">Amigos</div>
					<ul>
						<li><h3>Lista de amigos</h3>';
							$f = getFriends($edit_stats['id']);
							if(count($f) > 0) {
								echo '<ul>';
								foreach($f as $friends) {
									$f_stats = getUStats($friends[0]);
									echo '<li><h3 style="font-weight:normal;"><a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?action=account&id='.$friends[0].'">'.$f_stats['username'].'</a></h3><img src="images/icons/delete.png" onclick="changeSocial(\''.$friends[0].'\', \'0\', this, \'el.parentNode.style.display = \\\'none\\\'; if(el.parentNode.parentNode.children.length == 1) {el.parentNode.parentNode.insertAdjacentHTML(\\\'afterend\\\', \\\'<h4><i>'.$edit_stats['username'].' no tiene nuevas notificaciones.</i></h4>\\\');}\'); showInfo(null);" /></li>';
									//print_r($friends)."<br>";
								}
								echo '</ul>';
							} else {
								echo '<h4><i>'.$edit_stats['username'].' no tiene amigos.</i></h4>';
							}
						echo'</li>
						<li><h3>Añadir un amigo</h3>
							<form method="post" style="display:inline;" onsubmit="sendCustomAjax(this, \'username=\'+this.children[0].value+\'&\'+this.submited+\'=Enviar-fake\', \'el.parentNode.children[2].innerHTML = html;\');return false;">
								<input type="text" name="username" placeholder="Nick del miembro" />
								<input type="submit" name="addasbuddy" onclick="this.form.submited = this.getAttribute(\'name\');" value="Añadir como amigo" />
								<input type="submit" name="searchuser" onclick="this.form.submited = this.getAttribute(\'name\');" value="Buscar miembro" />
							</form>
							<div class="userlist"></div>
						</li>
					</ul>
					<div class="userbox">Peticiones enviadas</div>';
						$sr1 = getPending($edit_stats['id']);
							if(count($sr1) > 0) {
								echo '<ul>';
								foreach($sr1 as $sended_req) {
									$sr1_stats = getUStats($sended_req[0]);
									echo '<li><h3 style="font-weight:normal;"><a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?action=account&id='.$sended_req[0].'">'.$sr1_stats['username'].'</a></h3><img src="images/icons/cross.png" onclick="changeSocial(\''.$sended_req[0].'\', \'5\', this, \'el.parentNode.style.display = \\\'none\\\'; if(el.parentNode.parentNode.children.length == 1) {el.parentNode.parentNode.insertAdjacentHTML(\\\'afterend\\\', \\\'<h4><i>'.$edit_stats['username'].' no tiene nuevas notificaciones.</i></h4>\\\');}\'); showInfo(null);" /></li>';
								}
								echo '</ul>';
							} else {
								echo '<h4><i>'.$edit_stats['username'].' no ha enviado ninguna petición de amistad.</i></h4>';
							}
					echo '<div class="userbox">Peticiones recibidas</div>';
						$sr2 = getPending($edit_stats['id'], 1);
							if(count($sr2) > 0) {
								echo '<ul>';
								foreach($sr2 as $received_req) {
									$sr2_stats = getUStats($received_req[0]);
									echo '<li><h3 style="font-weight:normal;"><a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?action=account&id='.$received_req[0].'">'.$sr2_stats['username'].'</a></h3><img src="images/icons/tick.png" onclick="changeSocial(\''.$received_req[0].'\', \'3\', this, \'el.parentNode.style.display = \\\'none\\\'; if(el.parentNode.parentNode.children.length == 1) {el.parentNode.parentNode.insertAdjacentHTML(\\\'afterend\\\', \\\'<h4><i>'.$edit_stats['username'].' no tiene nuevas notificaciones.</i></h4>\\\');}\'); showInfo(null);" /><img src="images/icons/cross.png" onclick="changeSocial(\''.$received_req[0].'\', \'4\', this, \'el.parentNode.style.display = \\\'none\\\'; if(el.parentNode.parentNode.children.length == 1) {el.parentNode.parentNode.insertAdjacentHTML(\\\'afterend\\\', \\\'<h4><i>'.$edit_stats['username'].' no tiene nuevas notificaciones.</i></h4>\\\');}\'); showInfo(null);" /></li>';
								}
								echo '</ul>';
							} else {
								echo '<h4><i>'.$edit_stats['username'].' no ha recibido ninguna petición de amistad.</i></h4>';
							}
					echo '<div class="userbox">Usuarios seguidos</div>
					<ul>
						<li><h3>Lista de usuarios seguidos</h3>';
						$fw = getFollowing($edit_stats['id']);
							if(count($fw) > 0) {
								echo '<ul>';
								foreach($fw as $following) {
									$fw_stats = getUStats($following[0]);
									echo '<li><h3 style="font-weight:normal;"><a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?action=account&id='.$following[0].'">'.$fw_stats['username'].'</a></h3><img src="images/icons/cross.png" onclick="changeSocial(\''.$following[0].'\', \'1\', this, \'el.parentNode.style.display = \\\'none\\\'; if(el.parentNode.parentNode.children.length == 1) {el.parentNode.parentNode.insertAdjacentHTML(\\\'afterend\\\', \\\'<h4><i>'.$edit_stats['username'].' no tiene nuevas notificaciones.</i></h4>\\\');}\'); showInfo(null);" /></li>';
									//print_r($friends)."<br>";
								}
								echo '</ul>';
							} else {
								echo '<h4><i>'.$edit_stats['username'].' no sigue a ningún usuario.</i></h4>';
							}
						echo'</li>
						<li><h3>Añadir un seguidor</h3>
							<form method="post" style="display:inline;" onsubmit="sendCustomAjax(this, \'username=\'+this.children[0].value+\'&\'+this.submited+\'=Enviar-fake\', \'el.parentNode.children[2].innerHTML = html;\');return false;">
								<input type="text" name="username" placeholder="Nick del miembro" />
								<input type="submit" name="addasfollowing" onclick="this.form.submited = this.getAttribute(\'name\');" value="Seguir usuario" />
								<input type="submit" name="searchuser" onclick="this.form.submited = this.getAttribute(\'name\');" value="Buscar miembro" />
							</form>
							<div class="userlist"></div>
						</li>
					</ul>
					<div class="userbox">Bloqueados</div>
					<ul>
						<li><h3>Lista de bloqueados</h3>';
						$b = getBlocked($edit_stats['id']);
							if(count($b) > 0) {
								echo '<ul>';
								foreach($b as $blockeds) {
									$b_stats = getUStats($blockeds[0]);
									echo '<li><h3 style="font-weight:normal;"><a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?action=account&id='.$blockeds[0].'">'.$b_stats['username'].'</a></h3><img src="images/icons/delete.png" onclick="changeSocial(\''.$blockeds[0].'\', \'2\', this, \'el.parentNode.style.display = \\\'none\\\'; if(el.parentNode.parentNode.children.length == 1) {el.parentNode.parentNode.insertAdjacentHTML(\\\'afterend\\\', \\\'<h4><i>'.$edit_stats['username'].' no tiene nuevas notificaciones.</i></h4>\\\');}\'); showInfo(null);" /></li>';
									//print_r($friends)."<br>";
								}
								echo '</ul>';
							} else {
								echo '<h4><i>'.$edit_stats['username'].' no tiene bloqueados.</i></h4>';
							}
						echo'</li>
						<li><h3>Añadir un bloqueado</h3>
							<form method="post" style="display:inline;" onsubmit="sendCustomAjax(this, \'username=\'+this.children[0].value+\'&\'+this.submited+\'=Enviar-fake\', \'el.parentNode.children[2].innerHTML = html;\');return false;">
								<input type="text" name="username" placeholder="Nick del miembro" />
								<input type="submit" name="addasblocked" onclick="this.form.submited = this.getAttribute(\'name\');" value="Añadir como bloqueado" />
								<input type="submit" name="searchuser" onclick="this.form.submited = this.getAttribute(\'name\');" value="Buscar miembro" />
							</form>
							<div class="userlist"></div>
						</li>
					</ul>';
				break;

			default:
			
				echo '<script>var menunum = 1;</script>';
				echo '<form method="post">
						<div class="userbox">Cuenta'.(($uid != getUID() && $uid != null) ? ' de <i>'.$edit_stats['username'].'</i>' : '').'</div>
						<ul>
							<li><h3>Nick</h3><input type="text" name="username" maxlength="25" value="'.$edit_stats['username'].'" /></li>
							<li><h3>Email</h3><input type="text" name="email" maxlength="50" value="'.$edit_stats['email'].'" />'.(($edit_stats['activation'] == null) ? '' : '<span style="margin-left:5px;" onclick="showId(\'confirmmail\');">Confirmar correo</span>').'</li>
							<li><h3>Contraseña</h3><span onclick="showId(\'changepass\');">Cambiar contraseña</span></li>
						</ul>
						<div class="userbox">Información personal</div>
						<ul>
							<li><h3>Nombre</h3><input type="text" name="real_name" placeholder="Su nombre real" value="'.$edit_stats['real_name'].'" /></li>
							<li><h3>Sexo</h3><select name="gender">
						              <option disabled'.(($edit_stats['gender'] == 0) ? ' selected' : '').'>Género</option>
						              <option value="1"'.(($edit_stats['gender'] == 1) ? ' selected' : '').' data-image="imgs/male.png">Masculino</option>
						              <option value="2"'.(($edit_stats['gender'] == 2) ? ' selected' : '').' data-image="imgs/female.png">Femenino</option>
					              	</select></li>
							<li><h3>Fecha de nacimiento</h3><!--- <input type="text" name="birthday" size="2" value="" maxlength="2" placeholder="00"> <span style="display:inline-block;margin:0 auto;">-</span> 
					              <input type="text" name="birthmonth" size="2" value="" maxlength="2" placeholder="00"> <span style="display:inline-block;margin:0 auto;">-</span> 
					              <input type="text" name="birthyear" size="4" value="" maxlength="4" placeholder="0000"> -->
					              '.(($edit_stats['birthdate'] != null) ? date_dropdown(0, 1900, explode('-', $edit_stats['birthdate'])[0], explode('-', $edit_stats['birthdate'])[1], explode('-', $edit_stats['birthdate'])[2]) : date_dropdown()).'
					        </li>
							<li><h3>Ubicación</h3><input type="text" name="location" maxlength="100" placeholder="¿Donde vives?" value="'.$edit_stats['location'].'" /></li>
							<li><h3>Título de tu web</h3><input type="text" name="website_title" maxlength="100" value="'.$edit_stats['website_title'].'" /></li>
							<li><h3>Url de tu web</h3><input type="text" name="website_url" maxlength="100" value="'.$edit_stats['website_url'].'" /></li>
							<li><h3>Skype</h3><input type="text" name="skype" maxlength="100" value="'.$edit_stats['skype'].'" /></li>
						</ul>
						<input type="hidden" name="user_id" value="'.((empty($uid) || getUID() != 1) ? getUID() : $uid).'" />
						<div class="userbox"><center><input type="submit" value="Guardar cambios" name="edited" /></center></div>
					</form>';
					echo '<div id="changepass" class="popup">
						<div class="popup_form" style="height:144px;margin-top:-72px;">
							<form method="post">
								<div class="userbox">Cambiar contraseña<span class="popup_close" onclick="hideElem(\'changepass\');">x</span></div>
								<ul>
									<li><h3>Contraseña antigua</h3><input type="password" name="oldpass" maxlength="25" /></li>
									<li><h3>Nueva contraseña</h3><input type="password" name="newpass" maxlength="25" /></li>
									<li><h3>Repetir nueva contraseña</h3><input type="password" name="rnewpass" maxlength="25" /></li>
								</ul>
								<input type="hidden" name="user_id" value="'.((empty($uid) || getUID() != 1) ? getUID() : $uid).'" />
								<div class="userbox"><center><input type="submit" value="Cambiar contraseña" name="passchanged" /></center></div>
							</form>
						</div>
					</div>';
					if($edit_stats['activation'] != null) {
						echo '<div id="confirmmail" class="popup">
							<div class="popup_form" style="height:120px;margin-top:-60px;width:576px;margin-left:-288px;">
								<form method="post">
									<div class="userbox">Confirmar correo electrónico<span class="popup_close" onclick="hideElem(\'confirmmail\');">x</span></div>
									<center>
										<h3 style="padding-bottom:5px;">Para confirmar tu cuenta te hemos enviado un correo a la direción que escribiste.<br>Si no has recibido nada, por favor, revisa la bandeja de correo no deseado<br>o haz click en este botón:</h3>
										<input type="hidden" name="user_id" value="'.((empty($uid) || getUID() != 1) ? getUID() : $uid).'" />
										<input type="submit" value="Volver a enviar correo de confirmación" name="reconfirm" />
									</center>
								</form>
							</div>
						</div>';
					}
				break;
		}

		echo '</div>
		</div>';

		//include ($_SERVER['DOCUMENT_ROOT'].'/sources/edit_profile.php');

	} else {
		redirect('http://'.$_SERVER['SERVER_NAME'].'/?action=login');
		//header('Location: http://'.$_SERVER['SERVER_NAME'].'/?action=login');
	}

}

?>
