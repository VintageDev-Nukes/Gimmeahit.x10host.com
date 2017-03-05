function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}

function colorToHex(color) {
	if(color === "") {
		return "nothing";
	}
    if (color.substr(0, 1) === '#') {
        return color;
    }
    var digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);

    var rgb = digits[4] | (digits[3] << 8) | (digits[2] << 16);
    return '#' + (0x1000000 + rgb).toString(16).slice(1);
}

function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

if(typeof rankColors === 'undefined') {
	var rankColors = ["#ab00ab", "#0000ab", "#5454ff", "#ffff54", "#ffaa00"];
}

if(typeof rankNames === 'undefined') {
	var rankNames = ["Admin", "Moderador", "Redactor", "Colaborador", "VIP"];
}

window.addEventListener ? 
window.addEventListener("load",prefix,false) : 
window.attachEvent && window.attachEvent("onload",prefix);

function prefix() {
    var forum = document.getElementsByTagName('A');
    var prof = document.getElementsByTagName('SPAN');
    var allElem = [];
    allElem.push.apply(allElem, forum);
    allElem.push.apply(allElem, prof);
    for (var i = 0; i < allElem.length; i++) {
        if((allElem[i].tagName.toString() == "A" && 
            allElem[i].href.indexOf("/u") > -1 && 
            allElem[i].getElementsByTagName('SPAN')[0] != undefined && 
            rankColors.indexOf(colorToHex(allElem[i].getElementsByTagName('SPAN')[0].style.color)) > -1) || 
            (allElem[i].tagName.toString() == "SPAN" && 
            rankColors.indexOf(colorToHex(allElem[i].style.color)) > -1)) {
            var content = allElem[i].innerHTML;
            allElem[i].innerHTML = '<strong style="color:'+rankColors[((allElem[i].tagName.toString() == "A") ? rankColors.indexOf(colorToHex(allElem[i].getElementsByTagName('SPAN')[0].style.color)) : rankColors.indexOf(colorToHex(allElem[i].style.color)))]+';">['+rankNames[((allElem[i].tagName.toString() == "A") ? rankColors.indexOf(colorToHex(allElem[i].getElementsByTagName('SPAN')[0].style.color)) : rankColors.indexOf(colorToHex(allElem[i].style.color)))]+']</strong>';
            allElem[i].innerHTML += content;
            allElem[i].getElementsByTagName('strong')[1].style.color = 'white';
        }
    }
}