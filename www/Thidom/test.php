	<?php	
		include_once('Security.php');
		//include_once('connect.php');
		$nb_device = -1;
		$nb_year = 0;
		$tb_device  ;
		$RS = execute_sql("SELECT lieux FROM Temperature  GROUP BY lieux") ;
			while ($data_lieux = $RS->fetch_array())
			{
				$nb_device +=1;
				$lieux = $data_lieux['lieux'];
				$tb_device[$nb_device] = $lieux;
				echo "<div id='ExtremeTemp".$lieux."' class='ui-btn ui-btn-b ui-btn-corner-all'></div>";
				echo "<div id='Temp".$lieux."'></div><br>";
			
		$RS_Year = execute_sql(" select * from (SELECT EXTRACT( YEAR FROM DATE ) as year  FROM  `Temperature` ) as t   GROUP BY t.year")  ;	
			while ($data_year = $RS_Year->fetch_array())
			{	
				$nb_year +=1;		
				$tb_year[$nb_year] = $data_year['year'];	
				
			}
		$requete = execute_sql("SELECT UNIX_TIMESTAMP(replace(date,2014,2014)) as date,temp,lieux FROM Temperature where Temperature.date like '%2014%' and lieux ='Exterieur' order by date");
		$rows = array();
		while ($data = $requete->fetch_assoc())
		{	
			$sequential = array($data["date"], $data["temp"]);
			$rows[] = $sequential;
		}

echo json_encode($rows);
}
	?>
	
	
