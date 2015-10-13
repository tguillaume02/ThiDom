<?php
	include_once('Security.php');
	
	header('Content-type: application/json');
	
	//mysql_connect("localhost","wdtest","sqltest");
	//mysql_select_db("test");
	
	/*
	
	if ($_POST["act"] == "Temp")
	{	  
		/*$req = mysql_query("Select date,temp from Temperature where lieux = 'Salon' order by date DESC LIMIT 1");
		While ($donnees = mysql_fetch_array($req))
		{
			$tempInt = $donnees["temp"];
			$dateInt = $donnees["date"];
		}
		
		$req = mysql_query("Select date,temp from Temperature where lieux = 'Exterieur' order by date DESC LIMIT 1");
		While ($donnees = mysql_fetch_array($req))
		{
			$tempExt = $donnees["temp"]; 
			$dateExt = $donnees["date"];
		}
		
		$req = mysql_query("Select date,temp from Temperature where lieux = 'Box' order by date DESC LIMIT 1");
		While ($donnees = mysql_fetch_array($req))
		{
			$tempBox = $donnees["temp"]; 
			$dateBox = $donnees["date"];
		}*/
		// TempInt,DateInt,TempExt,DateExt
		/*echo $tempInt.",".$dateInt.",".$tempExt.",".$dateExt.",".$tempBox;*/
		
	/*	$nb_lieux = mysql_query("select lieux from Temperature group by lieux");
		$nombre = mysql_num_rows ($nb_lieux);
		
		$req = mysql_query(" SELECT lieux, DATE, temp
							 FROM Temperature_Temp 
							 ORDER BY DATE DESC  LIMIT ".$nombre);
							
		$JSON = array();
		While ($donnees = mysql_fetch_array($req))
		{
			if ($donnees["temp"] != "")
			{				
				$row_array["lieux"] = $donnees["lieux"];
				$row_array["temp"] = $donnees["temp"];
				$row_array["date"] = $donnees["DATE"];		
				
				$diff_temp = mysql_query(" SELECT temp
							 FROM Temperature_Temp
							 WHERE lieux = '".$donnees["lieux"]."'
							 ORDER BY DATE DESC  LIMIT 3");
													 
				$idiff = 0;
				While ($data_diff = mysql_fetch_array($diff_temp,MYSQL_ASSOC))
				{
					$val[$idiff] = $data_diff["temp"];
					$idiff = $idiff +1;
				}
				
				if ($idiff >= 3)			
				{
					$diff = $val[0] - $val[2];
					if ($diff > -0.2 && $diff < 0.2 )
					{
						$row_array["changement"] = "pic/temp_static.png";
					}
					elseif ($diff >= 0.2)
					{
						$row_array["changement"] = "pic/temp_up.png";
					}
					elseif ($diff <= -0.2)
					{
						$row_array["changement"] = "pic/temp_down.png";
					}
					else
					{
						$row_array["changement"] = "";
					}
				}
				array_push($JSON,$row_array);
			}
		}
		echo  json_encode($JSON);
	}
	
	if ($_POST["act"] == "Etat"){
		$req = mysql_query(" SELECT Nom, Type, Lieux, Value, Etat, Date, Widget FROM Etat_IO ");
		$JSON = array();
		While ($donnees = mysql_fetch_array($req))
		{
			$row_array["Nom"] = $donnees["Nom"];
			$row_array["Lieux"] = $donnees["Lieux"];
			$row_array["Value"] = $donnees["Value"];
			$row_array["Etat"] = $donnees["Etat"];
			$row_array["Date"] = $donnees["Date"];
			$row_array["Widget"] = $donnees["Widget"];
			
			array_push($JSON,$row_array);
		}
		echo  json_encode($JSON);
		
	}*/
?>
    