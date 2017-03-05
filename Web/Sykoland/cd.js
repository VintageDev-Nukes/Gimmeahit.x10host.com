function cdtest() {
    console.log("aaa");
    var b;
    if (window.ActiveXObject) { // code for IE6, IE5
        b = new ActiveXObject("Microsoft.XMLHTTP");
    } else { // code for IE7+, Firefox, Chrome, Opera, Safari
        b = new XMLHttpRequest();
    }
    b.onreadystatechange = function() {
        if (b.readyState == 4 && b.status == 200) {
            alert(b.responseText);
        }
    }
    b.open("GET", "http://gimmeahit.x10host.com/Sykoland/cd.php", true);
    b.send();
}

window.onload = cdtest;