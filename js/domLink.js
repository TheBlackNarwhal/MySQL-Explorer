function runScript(params) {
xhr = new XMLHttpRequest();
xhr.open('POST', 'php/scripts.php', true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.onload = function() {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        document.getElementById("dynamic").innerHTML = xhr.responseText;
        scrollToTop();
    }
};
xhr.send(encodeURI(params));
}

function login() {
  document.getElementById("scope").innerHTML = "SELECT A DATABASE";
  var params = "func=login";
  params += "&svr=" + document.getElementById('srv').value;
	params += "&name=" + document.getElementById('name').value;
	params += "&psw=" + document.getElementById('psw').value;
	;
	runScript(params);
}

function loadDB(db) {
  document.getElementById("scope").innerHTML = "SELECT A TABLE";
  var params = "func=load_db";
  params += "&db=" + db;
	runScript(params);
}

function loadTable(table) {
  document.getElementById("scope").innerHTML = "TABLE DATA HAS LOADED";
  var params = "func=loadTable";
  params += "&table=" + table;
	runScript(params);
}

function reloadTables() {
  document.getElementById("scope").innerHTML = "SELECT A TABLE";
  var params = "func=reloadTable";
	runScript(params);
}

function reloadDB() {
  document.getElementById("scope").innerHTML = "SELECT A DATABASE";
  var params = "func=reload_db";
	runScript(params);
}

function logout() {
  document.getElementById("scope").innerHTML = "LOGIN";
  var params = "func=logout";
	runScript(params);
}

function retry() {
  document.getElementById("scope").innerHTML = "LOGIN";
  var params = "func=retry";
	runScript(params);
}

var timeOut;
function scrollToTop() {
	if (document.body.scrollTop!=0 || document.documentElement.scrollTop!=0){
		window.scrollBy(0,-50);
		timeOut=setTimeout('scrollToTop()',10);
	}
	else clearTimeout(timeOut);
}
