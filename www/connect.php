<?php
	//mysqli_close ($mysqli);
	
	//global $mysqli;

	function execute_sql($req_sql)
	{			
		$servername = "localhost";
		$username = "wdtest";
		$password = "sqltest";
		$dbname = "test";

		$mysqli = new mysqli($servername,$username,$password,"test");
		if ($mysqli->connect_error) {
		    die("Erreur Connexion  : " . $mysqli->connect_error ."  !<br>'");
		} 
		$result_execute_sql = $mysqli->query($req_sql) or die('Erreur SQL !<br>') ;
		mysqli_close($mysqli);
		return $result_execute_sql;
	}
?>