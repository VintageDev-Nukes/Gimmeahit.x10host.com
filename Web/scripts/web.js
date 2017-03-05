var lastpage = localStorage.getItem("lastpage");

function isHover(e) {
    return !!(e.querySelector(":hover") || e.parentNode.querySelector(":hover") === e);
}

function insertParam(key, value)
{

	key = escape(key); value = escape(value);

    var kvp = document.location.search.substr(1).split('&');
    if (kvp == '') {
        var returnvalue = '?' + key + '=' + value;
        console.log(returnvalue);
        return returnvalue;
    }
    else {

    	var i = kvp.length; var x; while (i--) {
            x = kvp[i].split('=');

            if (x[0] == key) {
                x[1] = value;
                kvp[i] = x.join('=');
                break;
            }
        }

        if (i < 0) { kvp[kvp.length] = [key, value].join('='); }

        //this will reload the page, it's likely better to store this until finished
        var returnvalue = '?' + kvp.join('&'); 
	   	console.log(returnvalue);
	   	return returnvalue;
    }

}

function getQueryVariable(variable) {
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    if (pair[0] == variable) {
      return pair[1];
    }
  } 
  alert('Query Variable ' + variable + ' not found');
  return -1;
}

String.format = function() {
      var s = arguments[0];
      for (var i = 0; i < arguments.length - 1; i++) {       
          var reg = new RegExp("\\{" + i + "\\}", "gm");             
          s = s.replace(reg, arguments[i + 1]);
      }
      return s;
  }

function calculateAge(birthDay, birthMonth, birthYear)
{
  todayDate = new Date();
  todayYear = todayDate.getFullYear();
  todayMonth = todayDate.getMonth();
  todayDay = todayDate.getDate();
  age = todayYear - birthYear; 

  if (todayMonth < birthMonth - 1)
  {
    age--;
  }

  if (birthMonth - 1 == todayMonth && todayDay < birthDay)
  {
    age--;
  }
  return age;
}

function showId(id, display) {
    if(display === undefined) {display = "block";}
    document.getElementById(id).style.display = display;
}

function hideId(id) {
    document.getElementById(id).style.display = "none";
}

function showElem(elem, display) {
    if(display === undefined) {display = "block";}
    elem.style.display = display;
}

function hideElem(elem) {
    elem.style.display = "none";
}

function resize(text) {
   var scrollLeft = window.pageXOffset ||
   (document.documentElement || document.body.parentNode || document.body).scrollLeft;

   var scrollTop  = window.pageYOffset ||
   (document.documentElement || document.body.parentNode || document.body).scrollTop;

   text.style.height = "auto";
   text.style.height = text.scrollHeight + 'px';

   if(text.hasAttribute("maxlength")) {
    var textInfo = text.parentNode.getElementsByClassName("textinfo")[0];
    if(typeof textinfo === "undefined") {
        text.parentNode.innerHTML += '<div class="textinfo"></div>';
    }
    textinfo.innerHTML = text.value.length+"/"+text.maxLength;
   }

   window.scrollTo(scrollLeft, scrollTop);
}

function lostChanges() {
    return "Si abandonas la actual p\xE1gina se perder\xE1n todos los cambios realizados.";
}

var UID = {
    _current: 0,
    getNew: function(){
        this._current++;
        return this._current;
    }
};

HTMLElement.prototype.pseudoStyle = function(element,prop,value){
    var _this = this;
    var _sheetId = "pseudoStyles";
    var _head = document.head || document.getElementsByTagName('head')[0];
    var _sheet = document.getElementById(_sheetId) || document.createElement('style');
    _sheet.id = _sheetId;
    var className = "pseudoStyle" + UID.getNew();
    
    _this.className +=  " "+className; 
    
    _sheet.innerHTML += "\n."+className+":"+element+"{"+prop+":"+value+"}";
    _head.appendChild(_sheet);
    return this;
};

function cbox_focus(form) {
    showElem(form.children[3], 'inline-block'); 
    form.children[1].children[0].style.minHeight = '52px'; 
    form.children[1].children[0].placeholder = ''; 
    form.children[1].children[0].parentNode.pseudoStyle('before','border-color','transparent #1B7FCC'); 
    form.children[1].children[0].parentNode.style.border = '#1B7FCC 1px solid';
}

function cbox_blur(form) {
    if(typeof window.onclick != 'function') {
        window.addEventListener("click", function() {
            if(!isHover(form)) {
                hideElem(form.children[3], 'inline-block'); 
                form.children[1].children[0].style.minHeight = '40px'; 
                form.children[1].children[0].placeholder = form.children[1].children[0].getAttribute("PH"); 
                form.children[1].children[0].parentNode.pseudoStyle('before','border-color','transparent #aaa'); 
                form.children[1].children[0].parentNode.style.border = '#aaa 1px solid';
            }
        });
    }
}

function sendAjax(evt) {
    var dataString = getDataString(evt.currentTarget);
    console.log(dataString);
    $.ajax({
        type: "POST",
        url: '../ajax/allforms.php',
        data: dataString,
        cache: false,
        success: function(html) {
            if(html.length > 0) {
                localStorage.setItem("infoHTML", html);
                if(evt.target.hasAttribute('onajaxsuccess')) {
                    var f = evt.target.getAttribute('onajaxsuccess');
                    eval(f);
                } else {
                    showInfo(html);
                }
            }
        }
    });
    evt.preventDefault(); //Avoid refreshing after submit
    evt.currentTarget.addEventListener("submit", sendAjax); //Reset event (because this one is inusable)
    return false;
}

//Send Ajax without form
function sendCustomAjax(el, dataString, onajaxsuccess) {
    console.log(dataString);
    $.ajax({
        type: "POST",
        url: '../ajax/allforms.php',
        data: dataString,
        cache: false,
        success: function(html) {
            if(html.length > 0) {
                localStorage.setItem("infoHTML", html);
                if(el != null) {
                    if(el.hasAttribute('onajaxsuccess')) {
                        var f = el.getAttribute('onajaxsuccess');
                        eval(f);
                    } else {
                        if(onajaxsuccess != null) {
                            eval(onajaxsuccess);
                        }
                    }
                } else {
                    if(onajaxsuccess == null) {
                        showInfo(html);
                    } else {
                        eval(onajaxsuccess);
                    }
                }
            }
        }
    });
    return false;
}

function getDataString(form) {
    //Get all posible $_POST by checking the childs that have value and name defined
    var e = form.querySelectorAll('[name]:not([name=""])');
    var s = "";
    for(i = 0; i < e.length; i++) {
        s += e[i].getAttribute("name")+"="+e[i].value+((i < (e.length-1)) ? '&' : '');
    }
    return s;
}

function attachAllForms() {
    //Get all forms in body except those that have 'notattch' or 'redirect' attributes defined
    var f = document.querySelectorAll('form[method="post"]:not([noattach]):not([onsubmit])');
    for(i = 0; i < f.length; f++) {
        f[i].addEventListener("submit", sendAjax);
        //f[i].onsubmit = sendAjax;
    }
}

window.addEventListener("load", attachAllForms);

function showInfo(html) {
    if(typeof html === 'object' || html == null) {html = localStorage.getItem("infoHTML");}
    document.getElementById('HTMLInfo').innerHTML = html;
    localStorage.removeItem("infoHTML");
}

function ajaxReload() {
    window.location = document.URL; 
    localStorage.setItem('redirect', '');
}

//Profile buttons

function profile_buttons() {
    var buttons = document.getElementById("pb").children;
    for(i = 0; i < buttons.length; i++) {
       buttons[i].innerHTML += guest_action_popup;//\''.guest_action_popup().'\';
    }
    AttachEvents();
}

function AttachEvents() {
    var bt = document.getElementsByClassName("innerbutton");
    var close = document.getElementsByClassName("plogin_close_button");
    var css2 = document.getElementById("injectedcss2");
    for(i = 0; i < bt.length; i++) {
        bt[i].setAttribute("name", i);
        bt[i].onclick = function() {
            this.parentNode.parentNode.children[2].style.display = "block";
            //css2.innerHTML = "";
            css2.innerHTML = "<style>#pb > .groupbutton:nth-child("+(parseInt(this.getAttribute("name"))+1)+") > .button {display:block!important;}#pb > .groupbutton:not(:nth-child("+(parseInt(this.getAttribute("name"))+1)+")) > .button {display:none!important;}</style>";
        };
        close[i].onclick = function() {
            this.parentNode.parentNode.children[2].style.display = "none";
            //css2.innerHTML = "";
        };
    }
}

//Tabs
function cssMenu() {
    if(document.URL.indexOf("action=account") && document.getElementById("injectedcss") != null) {
        if(typeof menunum === "undefined") {
            document.getElementById("injectedcss").innerHTML = "<style>.profile-nav-menu ul li {background:#eee;color:#555;}</style>";
        } else {
            document.getElementById("injectedcss").innerHTML = "<style>.profile-nav-menu ul li:not(:nth-child("+menunum+")) {background:#eee;color:#555;}</style>";
        }
    }
}

if(document.URL.indexOf("action=account")) {
    window.addEventListener("load", cssMenu);
}

//Falta hacer que cambie el div
function changeSocial(user_id, reltype, el, success) {
    var dataString = "member_id="+user_id+"&rel_type="+reltype;
    sendCustomAjax(el, dataString, success);
}

//'Form' que actualiza las stats en el perfil
function sendSForm(el) {
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
    var dataString = "real_name="+real_name+"&date_day="+date_day+"&date_month="+date_month+"&date_year="+date_year+"&gender="+gender+"&location="+location+"&website_title="+website_title+"&website_url="+website_url+"&skype="+skype+"&email="+email+"&edited=a&uid="+getQueryVariable('id');
    var n = el.getAttribute("num");
    sendCustomAjax(null, dataString, "hideUpdateNode('"+n+"'); showInfo(html);");
}

//Profile buttons
function sendForm(str, opposite, arg) {
    console.log("aaa");
    var dataString = "go="+str+"&uid="+getQueryVariable('id');
    if(typeof opposite != "undefined") {
        sendCustomAjax(null, dataString, "afterForm('"+str+"', '"+opposite+"', '"+arg+"'); showInfo(html);");
    } else {
        sendCustomAjax(null, dataString);
    }
}

/*After ajax send*/

function nodeHack(str, arg) {
    switch(str) {
        case 'profile_buttons':
            if(typeof arg != undefined && localStorage.getItem("friend_button") != arg) {
                localStorage.setItem("friend_button", arg);
            }
            return String.format('<div class="groupbutton" id="gbutton" style="height:32px;float:right;"><div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/unlock_user.png" style="margin-top:8px;width:16px;height:16px;"></div> <div class="button" id="unblock" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'unblock\', \'block\');">Desbloquear</span></div> </div> <div class="groupbutton" id="gbutton" style="height:32px;float:right;"> <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/block_user.png" style="margin-top:8px;width:16px;height:16px;"></div> <div class="button" id="block" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'block\', \'unblock\');">Bloquear</span></div> </div> <div class="groupbutton" id="gbutton" style="height:32px;float:right;"> <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/cross.png" style="margin-top:8px;width:16px;height:16px;"></div> <div class="button" id="unfollow" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'unfollow\', \'follow\');">Dejar de seguir</span></div> </div> <div class="groupbutton" id="gbutton" style="height:32px;float:right;"> <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/tick.png" style="margin-top:8px;width:16px;height:16px;"></div> <div class="button" id="follow" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'follow\', \'unfollow\');">Seguir</span></div> </div> <div class="groupbutton" id="gbutton" style="height:32px;float:right;"> <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/buddy_delete.png" style="margin-top:8px;width:16px;height:16px;"></div> <div class="button" id="delbuddy" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'delbuddy\', \'addbuddy\');">Quitar como amigo</span></div> </div> <div class="groupbutton" id="gbutton" style="height:32px;float:right;"> <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/clock.png" style="margin-top:8px;width:16px;height:16px;"></div> <div class="button" id="cancelrequest" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:12px;" onclick="sendForm(\'cancelrequest\', \'addbuddy\');">Pendiente de aprobación</span></div> </div> <div class="groupbutton" id="gbutton" style="height:32px;float:right;"> <div class="iconbutton" style="border-radius:0px;"><img src="/images/icons/buddy_add.png" style="margin-top:8px;width:16px;height:16px;"></div> <div class="button" id="addbuddy" style="border-radius:0px;"><span class="innerbutton" style="line-height:32px;text-decoration:none;font-size:16px;" onclick="sendForm(\'addbuddy\', \'{0}\', \'{0}\');">A\xF1adir como amigo</span></div></div>', localStorage.getItem("friend_button"));
            break;
    }
}

//Actualiza los nodos (oculta unos y muestra otros) al editar una stat en el perfil
function showUpdateNode(elem) {
    elem.parentNode.children[1].style.display = "none";
    elem.parentNode.children[2].style.display = "inline";
    elem.parentNode.children[3].style.display = "none";
}

function hideUpdateNode(n) {
    n = parseInt(n)-1;
    var arr = document.getElementsByClassName("edit-node");
    if(n != 2) {
        arr[n].parentNode.children[1].innerHTML = arr[n].children[0].value;
    } else {
        arr[n].parentNode.children[1].innerHTML = arr[n].children[0].value+"-"+arr[n].children[1].value+"-"+arr[n].children[2].value+" ["+calculateAge(arr[n].children[0].value, arr[n].children[1].value, arr[n].children[2].value)+" a\xF1os]";
    }
    arr[n].parentNode.children[1].style.display = "inline";
    arr[n].parentNode.children[2].style.display = "none";
    arr[n].parentNode.children[3].style.display = "";
}

function afterForm(rtype, op, arg) {
    console.log(rtype+" "+op);
    var html = nodeHack('profile_buttons', arg)
               , parser = new DOMParser()
               , doc = parser.parseFromString(html, "text/html");
    localStorage.setItem(rtype, document.getElementById(rtype).parentNode.innerHTML);
    localStorage.setItem(op, doc.getElementById(op).parentNode.innerHTML);
    document.getElementById(rtype).parentNode.innerHTML = localStorage.getItem(op);
}

if(localStorage.getItem("redirect") != null) {
    window.addEventListener("load", showInfo);
    localStorage.removeItem("redirect");
}

if(document.URL.indexOf("login") === -1 && document.URL.indexOf("logout") === -1) {
    localStorage.setItem("lastpage", document.URL);
}