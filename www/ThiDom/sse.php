<?php

include_once('Security.php'); 

header('Content-Type: text/event-stream');
 

$lastdate ="";

$Date = "";

$lastdate_temp="";

$lastdate_Etat = "";

$lastdate_Sun = "";

while(1)

{

	$req = execute_sql(" SELECT temp_update_time.UPDATE_TIME AS update_temp, etat_update_time.UPDATE_TIME  AS update_etat
					FROM INFORMATION_SCHEMA.TABLES AS temp_update_time 
					LEFT JOIN INFORMATION_SCHEMA.TABLES AS etat_update_time ON etat_update_time.TABLE_SCHEMA = 'test' AND etat_update_time.TABLE_NAME = 'cmd_device'
					WHERE  (temp_update_time.TABLE_SCHEMA = 'test') AND (temp_update_time.TABLE_NAME = 'Temperature_Temp')"

				   ) ;



	//While ($donnees = mysql_fetch_array($req,MYSQL_ASSOC)) 

	While ($donnees = $req->fetch_array()) 

	{

		$Date_temp = $donnees["update_temp"];
		$Date_Etat = $donnees["update_etat"];
		//$Date_Sun = $donnees["update_sun"];

	}   

 

	$curDate = date(DATE_ISO8601);

	if($Date_temp != $lastdate_temp)

	{

		$info = "Temp";

		$lastdate_temp = $Date_temp;

		echo 'data:'.$info ."\n\n";

	}

	

	else if($Date_Etat != $lastdate_Etat)

	{

		$info = "Etat";

		$lastdate_Etat = $Date_Etat;		

		echo 'data:'.$info . "\n\n";

	}

	

	/*else if($Date_Sun != $lastdate_Sun)

	{

		$info = "Conditions";

		$lastdate_Sun = $Date_Sun;

		echo 'data:'.$info . "\n\n";

	}*/

	

	ob_flush();

	flush();

	sleep(1);

}

?>