<?php
require_once '../Core/Security.php';
require_once '../Core/ListRequire.php';

	//$TemperatureData = Temperature::GetTemperatureHistoryOnOneMonth();
	//$TemperatureData = Temperature::GetAllTemperature();
	$TemperatureData = Temperature::GetTemperatureTest();
	$cmd_device_Id_old = "";
	$result = "";
	$Series = "";
	$json = "";
	$temp_year_old = 0;
	$temp_year = 0; 
	$unite = "";
	$Lieux_id_old = "";
/*
$rows = array();
while($r = $TemperatureData) {
    $rows[] = $r;
}
print json_encode($rows);*/


	foreach($TemperatureData as $TempData)
	{		
		$temp_year = $TempData['Year'];
		$cmd_device_Id = $TempData["cmd_device_Id"];
		$lieux_Id = $TempData["Lieux_Id"];

		/*$date = new DateTime($TempData['date']);		
		$temp_year = $date->format('Y');		
		$d = $date->format('d');
		$m = $date->format('m');
		$date->setDate(date("Y") , $m , $d);
		$DateGraph =  strtotime($date->format('Y-m-d H:i'));
		$DateGraph = $TempData['date'];*/

		/*if ($json != "" and $temp_year != $temp_year_old and $temp_year_old != 0 or ($cmd_device_Id != $cmd_device_Id_old and $cmd_device_Id_old != ""))
		{
			$Series .= "{ name: ".$temp_year_old.", color:". $color.", visible: true,	data: [".$json."]},";
			$json = "";
		}*/	

		if (($cmd_device_Id != $cmd_device_Id_old  && $cmd_device_Id_old != "") || ($lieux_Id != $Lieux_id_old && $Lieux_id_old != ""))
		{		
			if ($Series != "")
			{
				$result .=  "<div id='data-charts".$lieuxWithouSpace.$cmd_device_Id_old."' class='container-fluid'>
					<div id='ExtremeTemp".$lieuxWithouSpace.$cmd_device_Id_old."' class='ui-btn ui-btn-b ui-btn-corner-all navbar-default corner history-title'>
						<table style='width:100%' class='text-center'>
							<tr>
								<td>
									<span id='Min".$lieuxWithouSpace.$cmd_device_Id_old."' class='minHistory'>Min</span>
									<!--<span id='Title".$lieuxWithouSpace.$cmd_device_Id_old."'  class='titleHistory'>".$cmd_deviceName." ".$lieux."</span>-->
									<span id='Max".$lieuxWithouSpace.$cmd_device_Id_old."' class='maxHistory'>Max</span>
									<input id='CheckBox".$lieuxWithouSpace.$cmd_device_Id_old."' class='pull-right' type='checkbox' checked>
								</td>
							</tr>
						</table>
					</div>
					<div id='History_".$lieuxWithouSpace.$cmd_device_Id_old."'></div>
					<br>
				</div>

				<script>
					GenerateGraph(\"".$lieuxWithouSpace.$cmd_device_Id_old."\",\"".$cmd_deviceName." ".$lieux."\",\"".$unite."\",[".$Series."])
				</script>
				";

				$Series ="";

				//$temp_year_old = 0;
				//$temp_year = 0;
			}
		}

		$color = "strToRGB((".$temp_year."*(".$temp_year."/3.14116)).toString())";
		$Series .= "{ name: ".$temp_year.", color:". $color.", visible: true,	data: [".$TempData['Data']."]},";

		$lieux = $TempData["LieuxNom"]."";
		$lieuxWithouSpace = SpaceToScore($TempData["LieuxNom"]);
		$cmd_deviceName = $TempData["cmd_deviceName"];
		$temp = $TempData['Data'];
		$unite = $TempData["cmd_deviceUnite"];

		$cmd_device_Id_old = $cmd_device_Id;
		$Lieux_id_old = $lieux_Id;
	 	
		$json .= $temp; // "[".$DateGraph."000,".$temp."],";

		//$temp_year_old = $temp_year;
	}
		
	if ($Series != "")
	{	
		$result .=  "<div id='data-charts".$lieuxWithouSpace.$cmd_device_Id_old."' class='container-fluid'>
			<div id='ExtremeTemp".$lieuxWithouSpace.$cmd_device_Id_old."' class='ui-btn ui-btn-b ui-btn-corner-all navbar-default corner history-title'>
				<table style='width:100%' class='text-center'>
					<tr>
						<td>
							<span id='Min".$lieuxWithouSpace.$cmd_device_Id_old."' class='minHistory'>Min</span>
							<!--<span id='Title".$lieuxWithouSpace.$cmd_device_Id_old."'  class='titleHistory'>".$cmd_deviceName." ".$lieux."</span>-->
							<span id='Max".$lieuxWithouSpace.$cmd_device_Id_old."' class='maxHistory'>Max</span>
							<input id='CheckBox".$lieuxWithouSpace.$cmd_device_Id_old."' class='pull-right' type='checkbox' checked>
						</td>
					</tr>
				</table>
			</div>
			<div id='History_".$lieuxWithouSpace.$cmd_device_Id_old."'></div>
			<br>
		</div>

		<script>
			GenerateGraph(\"".$lieuxWithouSpace.$cmd_device_Id_old."\",\"".$cmd_deviceName." ".$lieux."\",\"".$unite."\",[".$Series."])
		</script>
		";

		$Series ="";

		//$temp_year_old = 0;
		//$temp_year = 0;
	}

	echo $result;

?>