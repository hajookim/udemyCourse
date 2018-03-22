var start = new Date().getTime()
function getRandomColor() {
  	var letters = '0123456789ABCDEF';
  	var color = '#';
  	for (var i = 0; i < 6; i++) {
    	color += letters[Math.floor(Math.random() * 16)];
  	}
  	return color;
}

function makeShapeAppear() {
	var width = Math.random() * 300; 
	document.getElementById("shape").style.width = width + "px"; 
	document.getElementById("shape").style.height = width + "px"; 
	document.getElementById("shape").style.display = "block"; 
	document.getElementById("shape").style.top = Math.random() * 600 + 1 + "px";
	document.getElementById("shape").style.left = Math.random() * 600 + 1 + "px";
	document.getElementById("shape").style.backgroundColor = getRandomColor(); 
	if (Math.random() > 0.5) {
		document.getElementById("shape").style.borderRadius = "50%"; 
	} else {
		document.getElementById("shape").style.borderRadius = "0%"; 
	}
	start = new Date().getTime()
}

document.getElementById("shape").onclick = function() {
	document.getElementById("shape").style.display = "none"; 
	var end = new Date().getTime();
	var time = (end - start) / 1000; 
	document.getElementById("yourTime").innerHTML = time + "s";
	setTimeout(makeShapeAppear, time);
}
