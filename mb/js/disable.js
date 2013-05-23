function disableKeyCombination(e) {
	var keyComb;
	if(window.event) {
		if(window.event.ctrlKey||window.event.keyCode==123) keyComb=true;
		else keyComb=false;
	}
	else {
		if(e.ctrlKey||e.keyCode==123) keyComb=true;
		else keyComb=false;
	}
	if(keyComb)	return false;
	else return true;
}
//disable text select
function disableselect(e) {
	return false;
}
document.onselectstart=new Function ("return false");
if (window.sidebar) {
	document.onmousedown=disableselect;
}
//disable right-click
function clickIE4() {
	if(event.button==2) return false;
}
function clickNS4(e){
	if(document.layers||document.getElementById&&!document.all) {
		if(e.which==2||e.which==3) return false;
	}
}
if(document.layers) {
	document.captureEvents(Event.MOUSEDOWN);
	document.onmousedown=clickNS4;
}
else if(document.all&&!document.getElementById) {
	document.onmousedown=clickIE4;
}
document.oncontextmenu=new Function("return false");