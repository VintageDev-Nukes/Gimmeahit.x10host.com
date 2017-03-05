if(typeof rankType === 'undefined') {
	var rankType = 'post';
}

window.addEventListener ? 
window.addEventListener("load",rank,false) : 
window.attachEvent && window.attachEvent("onload",rank);

function rank() {
	var rankImgs = [];
	var allElem = document.getElementsByTagName('IMG');
	for(var i = 0; i < allElem.length; i++) {
		if(allElem[i].src.toString() == 'http://gimmeahit.x10host.com/Sykoland/rank-img.png') {
			rankImgs.push(allElem[i]);
		}
	}
	for(var j = 0; j < rankImgs.length; j++) {
		var strStats = rankImgs[j].parentNode.parentNode.getElementsByTagName('DD')[2].innerHTML;
		var matches = strStats.match(/\/span>\d+</g);
		var posts = -1;
		var points = -1;
		var rep = -1;
		for(var k = 0; k < matches.length; k++) {
			matches[k] = matches[k].substring(6, matches[k].indexOf('<'));
			if(k==0) {
				posts = matches[k];
			} else if(k==1) {
				points = matches[k];
			} else if(k==2) {
				rep = matches[k];
			}
		}
		var finalUrl = "http://gimmeahit.x10host.com/Sykoland/rank-img.php";
		switch(rankType) {
			case 'post':
				if(posts > -1) {
					finalUrl += "?type=post&qnt="+posts;
				}
				break;
			case 'points':
				if(points > -1) {
					finalUrl += "?type=points&qnt="+points;
				}
				break;
			case 'reputation':
				if(rep > -1) {
					finalUrl += "?type=rep&qnt="+rep;
				}
				break;
		}
		rankImgs[j].src = finalUrl;
		rankImgs[j].parentNode.parentNode.getElementsByTagName('DD')[1].innerHTML = '';
		rankImgs[j].parentNode.parentNode.getElementsByTagName('DD')[0].innerHTML = '<br><img src="'+rankImgs[j].src+'" alt="Rango" title="Rango" /><br>';
	}
}