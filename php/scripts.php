<?php

session_start();
$func = $_POST["func"];
$connection;

switch ($func) {
	case 'login':
		getDatabases(TRUE);
		break;
	case 'load_db':
		load_db(TRUE);
		break;
	case 'loadTable':
		loadTable();
		break;
	case 'reloadTable':
		load_db(FALSE);
		break;
	case 'reload_db':
		getDatabases(FALSE);
		break;
	case 'logout':
		logout();
		break;
	default: echo 'Function Error';
	break;
}

function setSession() {
	$loginParams = array('svr' => $_POST["svr"], 'name' => $_POST["name"], 'psw' => $_POST["psw"]);
	$_SESSION["loginParams"] = $loginParams;
}

function getSession() {
	$loginParams = $_SESSION["loginParams"];
	return $loginParams;
}

function login($loginParams) {
	$connection = @mysqli_connect($loginParams['svr'], $loginParams['name'], $loginParams['psw'])
	or die("<p>initial host/db connection problem</p>"."<br/><br/><button type='button' id='nav' onclick='logout()'>Try again</button>"); //button runs logout so user can return to login screen

	if(errorCheck($connection)) {
		return $connection;
	}
}

function errorCheck($connection) {
	if (!$connection) {
		echo "internal error " . mysqli_errno();
		return FALSE;
	} else {
		return TRUE;
	}
}

function getDatabases($first) {

	if($first) {
		setSession();
	}

	$connection = login(getSession());

	$result = mysqli_query($connection, "SHOW DATABASES");
	$available = array();
	$index = 0;

	echo "<button type='button' id='nav' onclick='logout()'>LOGOUT</button>";

	while( $row = mysqli_fetch_row( $result ) ){
    	if (($row[0]!="information_schema") && ($row[0]!="mysql")) {
        	$index += 1;
        	echo "<button type='button' onclick='loadDB(&quot;" . $row[0] . "&quot;)'>" . $row[0] . "</button>";
        	echo "<br>";
    	}
	}
}

function setDbSession() {
	$_SESSION["db"] = $_POST["db"];
}

function getDbSession() {
	return $_SESSION["db"];
}

function load_db($first) {

	if($first) {
		setDbSession();
	}

	echo "<button type='button' id='nav' onclick='reloadDB()'>RETURN TO DATABASES</button>";

	$connection = login(getSession());
	$db = getDbSession();

    mysqli_select_db($connection, $db);

    $result = mysqli_query($connection, "show tables");  // run the query and assign the result to $result
    if (!$result) {
        echo 'MySQL Error: ' . mysqli_error();
        exit();
    }

    while($table = mysqli_fetch_array($result)) { // go through each row that was returned in $result
        echo "<button type='button' onclick='loadTable(&quot;" . $table[0] . "&quot;)'>" . $table[0]  . "</button>";
        echo "<br>";    // print the table that was returned on that row.
     }
}

function setTableSession() {
	$_SESSION["table"] = $_POST["table"];
}

function getTableSession() {
	return $_SESSION["table"];
}

function loadTable() {

    $connection = login(getSession());
	$db = getDbSession();
    mysqli_select_db($connection, $db);
	setTableSession();
	$table = getTableSession();

	echo "<button type='button' id='nav' onclick='reloadTables()'>RETURN TO TABLES</button>";

	$query = "SELECT * FROM " . $table;
    $result1 = mysqli_query($connection, $query) or die (mysqli_error($connection));

    //iterate over all the rows

	$collumns = 0;
	$index = 0;
	$data = array();

	echo "<table id='data' ><tr>";

	$fields = mysqli_query($connection, "SHOW columns FROM " . $table);
	while($row = mysqli_fetch_array($fields)) {
    	echo "<th>" . $row["Field"] . "</th>";
		$collumns += 1;
	}

	echo "</tr>";

    while($row = mysqli_fetch_assoc($result1)) {  //iterate over all the fields

        foreach($row as $key => $val){  //generate output
            $data[$index] = $val;
			$index += 1;
        }
    }

	$subIndex = 0;

	for($i = 0; $i < count($data);) {
		echo "<tr>";

		for($j = 0; $j < $collumns; $j++) {
			echo "<td>";

			echo $data[$subIndex];
			$subIndex += 1;
			$i++;

			echo "</td>";
		}

		echo "</tr>";

	}

	echo "</table>";
}

function logout() {
	echo "<form action='javascript:login();' method='post'>

        <input type='text' id='srv' placeholder='Server' /> <br/>
        <input type='text' id='name' placeholder='Username' /> <br/>
        <input type='password' id='psw' placeholder='Password' /> <br/>

        <hr>

        <button type='submit' id='connect' >CONNECT</button>

        </form>";
}

?>
