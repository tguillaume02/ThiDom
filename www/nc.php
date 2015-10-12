<?php
//mysqli_close ($mysqli);

//global $mysqli;

function execute_sql($req_sql)
{			
	$servername = "localhost";
	$username = "wdtest";
	$password = "m";
	$dbname = "test";
	$mysqli = new mysqli($servername,$username,$dbname,"test");
	if ($conn->connect_error) {
	    die("Erreur Connexion  : " . $conn->connect_error ."  !<br>'");
	} 
	$result_execute_sql = $mysqli->query($req_sql) or die('Erreur SQL !<br>') ;
	mysqli_close($mysqli);
	return $result_execute_sql;
}
?>
