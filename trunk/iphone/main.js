function settingsSave() {
	alert('settingsSave');
}
function settingsSayCallerId() {
	alert('settingsSayCallerId');
}
function settingsEnvelope() {
	alert('settingsEnvelope');
}
function settingsSendToEmail() {
	alert('settingsSendToEmail');
}
function settingsDeleteAfterEmail() {
	alert('settingsDeleteAfterEmail');
}

function doDelete(file, filepath) {
	makePostRequest("file.php", 'action=delete&filepath='+filepath);
}

function doMove(file, filepath, newfolder) {
	makePostRequest("file.php", 'action=move&file='+file+'&filepath='+filepath+'&newfolder='+newfolder);
}

function doReload() {
	window.location.replace = './';
}

function makePostRequest(url, params) {

	http_request = false;

	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		http_request = new XMLHttpRequest();
		if (http_request.overrideMimeType) {
			http_request.overrideMimeType('text/xml');
		}
	} else if (window.ActiveXObject) { // IE
		try {
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}

	if (!http_request) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}
	http_request.onreadystatechange = alertContents;
	http_request.open('POST',url,false);
	http_request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	http_request.send(params);
}

function alertContents() {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {	
		} else {
			alert('There was a problem with the request.');
		}
	}
}

	