<?php

$go_where = @$_POST['go'];
$my_id = getUID();

if($uid != getUID() && isset($go_where)) {
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
} else if($uid == getUID() && isset($go_where)) {
	switch ($go_where) {

		case 'edit-profile':
			redirect('http://'.$_SERVER['SERVER_NAME'].'/?action=account&go=edit');
			break;

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

if(isOnline()) {
	echo '<script>
		function sendForm(str) {
			document.getElementById("pvalue").value = str;
			document.getElementById("pactions").onsubmit();
		}
	</script>
	<form id="pactions" method="post">
		<input type="hidden" id="pvalue" name="go" />
	</form>';
} else {
	echo '<script>
		(function(){
			function AttachEvents() {
				var bt = document.getElementsByClassName("innerbutton");
				var close = document.getElementsByClassName("plogin_close_button");
				for(i = 0; i < bt.length; i++) {
					bt[i].setAttribute("name", i);
					bt[i].onclick = function() {
						this.parentNode.parentNode.childNodes[5].style.display = "block";
						document.getElementById("injectedcss").innerHTML = "";
						document.getElementById("injectedcss").innerHTML = "<style>#pb > .groupbutton:nth-child("+(parseInt(this.getAttribute("name"))+1)+") > .button {display:block!important;}#pb > .groupbutton:not(:nth-child("+(parseInt(this.getAttribute("name"))+1)+")) > .button {display:none!important;}</style>";
					};
					close[i].onclick = function() {
						this.parentNode.parentNode.childNodes[5].style.display = "none";
						document.getElementById("injectedcss").innerHTML = "";
					};
				}
			}
			var buttons = document.getElementById("pb").childNodes;
			for(i = 0; i < buttons.length; i++) {
				buttons[i].innerHTML += \''.guest_action_popup().'\';
			}
			AttachEvents();
		})();
	</script>';
}

if(getUID() == $uid) {
	echo '<div class="profile-main-tab">
	<div id="avatar-custom" class="popup">
						<div class="popup_form" style="height:112px;margin-top:-56px;">
							<form method="post">
								<div class="userbox">Avatar<span class="popup_close" onclick="hideElem(\'avatar-custom\');">x</span></div>
								<ul>
									<li><h3>Personalizar avatar</h3>
										<input type="textbox" name="avatar_url" placeholder="URL de avatar" value="'.$my_stats['avatar'].'" style="margin-bottom:5px;" />
										<br>
										<label><input type="checkbox" name="delete_avatar" value="true" />Borrar avatar</label>
									</li>
								</ul>
								<input type="hidden" name="custom-part" value="only_avatar" />
								<div class="userbox"><center><input type="submit" value="Guardar cambios" name="custom" /></center></div>
							</form>
						</div>
					</div>
					<div id="banner-custom" class="popup">
						<div class="popup_form" style="height:157px;margin-top:-78.5px;">
							<form method="post">
								<div class="userbox">Banner<span class="popup_close" onclick="hideElem(\'banner-custom\');">x</span></div>
								<ul>
									<li><h3>Personalizar banner</h3>
										<input type="textbox" name="banner_url" placeholder="URL del banner" value="'.$my_stats['banner'].'" style="margin-bottom:5px;" />
										<br>
										<label>
											#<input type="textbox" name="banner_bcolor" placeholder="Color de fondo del banner" style="margin-bottom:5px;" class="color" value="'.$my_stats['banner_bcolor'].'" />
										</label>
										<br>
										<label><input type="checkbox" name="banner_mosaic"'.(($my_stats['banner_mosaic'] == 1) ? ' checked' : '').' value="1" />Mosáico (no recomendado en fondo sin patrones)</label>
										<br>
										<label><input type="checkbox" name="delete_banner" value="true" />Borrar banner</label>
									</li>
								</ul>
								<input type="hidden" name="custom-part" value="only_banner" />
								<div class="userbox"><center><input type="submit" value="Guardar cambios" name="custom" /></center></div>
							</form>
						</div>
					</div>
				</div>';
}

echo '<script>
function cssMenu() {
	if(typeof menunum === "undefined") {
		document.getElementById("injectedcss").innerHTML = "<style>.profile-nav-menu ul li {background:#eee;color:#555;}</style>";
	} else {
		document.getElementById("injectedcss").innerHTML = "<style>.profile-nav-menu ul li:not(:nth-child("+menunum+")) {background:#eee;color:#555;}</style>";
	}
}
window.addEventListener("load", cssMenu);
</script>';

?>