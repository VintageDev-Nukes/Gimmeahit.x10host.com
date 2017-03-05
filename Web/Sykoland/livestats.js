if(typeof showCPP === 'undefined') {
var showCPP = true; //Comments per post
}

if(typeof showOnlineUsers === 'undefined') {
var showOnlineUsers = true;
}

if(typeof showPPD === 'undefined') {
var showPPD = true; //Posts per day
}

if(typeof showCPD === 'undefined') {
var showCPD = true; //Comments per day
}

if(typeof showUPD === 'undefined') {
var showUPD = true; //User per day
}

var mStatsShown = false;

document.write('<div id="hiddenStats" style="display:none;"></div><div id="live-stats"></div>');

function updateStats() {
        document.getElementById('hiddenStats').innerHTML = "<span class=\"FORUMURL\" id=\"FORUMURL\"></span><span class=\"FORUMURLINK\" id=\"FORUMURLINK\"></span><span class=\"FORUMNAME\" id=\"FORUMNAME\"></span><span class=\"FORUMNAMELINK\" id=\"FORUMNAMELINK\"></span><span class=\"FORUMDESC\" id=\"FORUMDESC\"></span><span class=\"FORUMBIRTHDAY\" id=\"FORUMBIRTHDAY\"></span><span class=\"FORUMAGE\" id=\"FORUMAGE\"></span><span class=\"FORUMCOUNTFORUM\" id=\"FORUMCOUNTFORUM\"></span><span class=\"FORUMCOUNTOPIC\" id=\"FORUMCOUNTOPIC\"></span><span class=\"FORUMCOUNTPOST\" id=\"FORUMCOUNTPOST\"></span><span class=\"FORUMCOUNTUSER\" id=\"FORUMCOUNTUSER\"></span><span class=\"FORUMONLINEUSER\" id=\"FORUMONLINEUSER\"></span><span class=\"FORUMONLINEDATE\" id=\"FORUMONLINEDATE\"></span><span class=\"FORUMLASTUSER\" id=\"FORUMLASTUSER\"></span><span class=\"FORUMLASTUSERLINK\" id=\"FORUMLASTUSERLINK\"></span><span class=\"USERNAME\" id=\"USERNAME\"></span><span class=\"USERLINK\" id=\"USERLINK\"></span><span class=\"USERBIRTHDAY\" id=\"USERBIRTHDAY\"></span><span class=\"USERAGE\" id=\"USERAGE\"></span><span class=\"USERREGDATE\" id=\"USERREGDATE\"></span><span class=\"USERLASTVISIT\" id=\"USERLASTVISIT\"></span><span class=\"USERCOUNTPOST\" id=\"USERCOUNTPOST\"></span>";
        var b, portal;
        if (window.ActiveXObject) { // code for IE6, IE5
            b = new ActiveXObject("Microsoft.XMLHTTP");
        } else { // code for IE7+, Firefox, Chrome, Opera, Safari
            b = new XMLHttpRequest();
        }
        if (window.ActiveXObject) { // code for IE6, IE5
            portal = new ActiveXObject("Microsoft.XMLHTTP");
        } else { // code for IE7+, Firefox, Chrome, Opera, Safari
            portal = new XMLHttpRequest();
        }
        b.onreadystatechange = function() {
            if (b.readyState == 4 && b.status == 200) {
                var d, g = "FORUMURL FORUMURLINK FORUMNAME FORUMNAMELINK FORUMDESC FORUMBIRTHDAY FORUMAGE FORUMCOUNTFORUM FORUMCOUNTOPIC FORUMCOUNTPOST FORUMCOUNTUSER FORUMONLINEUSER FORUMONLINEDATE FORUMLASTUSER FORUMLASTUSERLINK USERNAME USERLINK USERBIRTHDAY USERAGE USERREGDATE USERLASTVISIT USERCOUNTPOST NOW NOWWITHTIME".split(" ");
                for (d = 0; d < g.length; d++) {
                    var h = g[d],
                        e = b.responseText.replace(RegExp('^.+<li style="margin-bottom:5px;direction:ltr;text-align:left;"><strong>&#123;' + h + "&#125;</strong>&nbsp;:&nbsp;(.*?)&nbsp;<span style='direction:ltr'>(.*?)</span><br /></li>.+$"), "$1");
                    if (b.responseText != e) {
                        for (var j = document.getElementsByTagName("*") || document.all, c = [], a = -1; ++a < j.length;)
                            for (var k = j[a], l = k.className.split(" "), f = 0; f < l.length; f++)
                                if (l[f] == h) {
                                    c.push(k);
                                    break
                                }
                        for (a = -1; ++a != c.length;) "INPUT" == c[a].tagName || "TEXTAREA" ==
                            c[a].tagName ? c[a].value += e : c[a].innerHTML += e
                    }
                }
                var v1 = document.getElementById("FORUMURL").innerHTML;
                var v2 = document.getElementById("FORUMURLINK").innerHTML;
                var v3 = document.getElementById("FORUMNAME").innerHTML;
                var v4 = document.getElementById("FORUMNAMELINK").innerHTML;
                var v5 = document.getElementById("FORUMDESC").innerHTML;
                var v6 = document.getElementById("FORUMBIRTHDAY").innerHTML; //[BUG]: Creo que el formato de la fecha cambia según la versión del foro
                var v7 = document.getElementById("FORUMAGE").innerHTML;
                var v8 = document.getElementById("FORUMCOUNTFORUM").innerHTML;
                var v9 = document.getElementById("FORUMCOUNTOPIC").innerHTML;
                var v10 = document.getElementById("FORUMCOUNTPOST").innerHTML;
                var v11 = document.getElementById("FORUMCOUNTUSER").innerHTML;
                var v12 = document.getElementById("FORUMONLINEUSER").innerHTML;
                var v13 = document.getElementById("FORUMONLINEDATE").innerHTML;
                var v14 = document.getElementById("FORUMLASTUSER").innerHTML;
                var v15 = document.getElementById("FORUMLASTUSERLINK").innerHTML;
                var v16 = document.getElementById("USERNAME").innerHTML;
                var v17 = document.getElementById("USERLINK").innerHTML;
                var v18 = document.getElementById("USERBIRTHDAY").innerHTML;
                var v19 = document.getElementById("USERAGE").innerHTML;
                var v20 = document.getElementById("USERREGDATE").innerHTML;
                var v21 = document.getElementById("USERLASTVISIT").innerHTML;
                var v22 = document.getElementById("USERCOUNTPOST").innerHTML;
                var DaysFromStart = Math.abs((new Date().getTime() - Date.parse(v6.substring(3).replace(" -", ""))) / 1000)/86400;
                var UsersPerDay = (v11 / DaysFromStart).toFixed(2).toString();
                var PostsPerDay = (v10 / DaysFromStart).toFixed(2).toString();
                var CommentsPerDay = (v9 / DaysFromStart).toFixed(2).toString();
                var CommentsPerPost = ((v10 > 0) ? (v9 / v10).toFixed(2).toString() : "0.00");
                portal.onreadystatechange = function() {
                    if (portal.readyState == 4 && portal.status == 200) {
                        var strOnline = portal.responseText.match(new RegExp("<div class=\"left\">.+</strong> .+<a href=\"/viewonline")).toString();
                        var Conectados = strOnline.substring(strOnline.indexOf("<strong>")+8, strOnline.indexOf("</strong>"));
                        var finalString = v11 + " Usuarios<br>" + v10 + " Temas creados<br>" + v9 + " Comentarios";
                        if(showOnlineUsers) {
                            finalString += "<br>" + Conectados + " Usuarios conectados";
                        }
                        finalString += "<input id=\"mStatsButton\" type=\"button\" value=\""+((mStatsShown == true) ? "Menos estad\xEDsticas" : "M\xE1s estad\xEDsticas")+"\" onClick=\"moreStats()\" /><span id=\"moreStats\" style=\"display:"+((mStatsShown == true) ? "block" : "none")+";\">";
                        if(showCPP) {
                            finalString += CommentsPerPost + " Comentarios/Post";
                        }
                        if(showUPD) {
                            finalString += "<br>" + UsersPerDay + " Usuarios/d\xEDa";
                        }
                        if(showPPD) {
                            finalString += "<br>" + PostsPerDay + " Posts/d\xEDa";
                        }
                        if(showCPD) {
                            finalString += "<br>" + CommentsPerDay + " Comentarios/d\xEDa";
                        }
                        finalString += "</span>";
                        /*if(window.location.hostname != "sykoland.foroactivo.com") {
                            finalString += "<br>&copy 2014 - "+(new Date().getFullYear()).toString()+" Ikillnukes - <a href=\"http://sykoland.foroactivo.com/\">http://sykoland.foroactivo.com/</a>";
                        }*/
                        document.getElementById('live-stats').innerHTML = finalString;
                    }
                }
            }
        }
    b.open("GET", "/popup_help.forum?l=miscvars", true);
    b.send();
    portal.open("GET", "/forum", true);
    portal.send();
}
function moreStats() {
    mStatsShown = (mStatsShown == true) ? false : true;
    if(mStatsShown) {
        document.getElementById('moreStats').style.display = "block";
        document.getElementById('mStatsButton').value = "Menos estad\xEDsticas";
    } else {
        document.getElementById('moreStats').style.display = "none";
        document.getElementById('mStatsButton').value = "M\xE1s estad\xEDsticas";
    }
}
updateStats();
setInterval(function() {
    updateStats()
}, 5000);