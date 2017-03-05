function ready() {
	document.getElementById('frame_chatbox').onload = function() {
		alert('aaa');
	}
	/*if(document.getElementById('frame_chatbox').readyState == 'complete')
    {
        alert('Guay');
    }*/
}

window.onload = ready;