<?php
require_once '../Core/Security.php';
require_once '../Core/ListRequire.php';

	$TemperatureData = Temperature::GetTemperatureHistoryOnOneMonth();
	$cmd_device_Id_old = "";
	$result = "";
	$Series = "";
	$json = "";
	$temp_year_old = 0;
	$temp_year = 0; 

	foreach($TemperatureData as $TempData)
	{		
		$cmd_device_Id = $TempData["cmd_device_Id"];

		$date = new DateTime($TempData['date']);		
		$temp_year = $date->format('Y');		
		$d = $date->format('d');
		$m = $date->format('m');
		$date->setDate(date("Y") , $m , $d);
		$DateGraph =  strtotime($date->format('Y-m-d H:i'));

		if ($json != "" and $temp_year != $temp_year_old and $temp_year_old != 0 or ($cmd_device_Id != $cmd_device_Id_old and $cmd_device_Id_old != ""))
		{
			$Series .= "{ name: ".$temp_year_old.", color:". $color.", visible: true,	data: [".$json."]},";
			$json = "";
		}

		if ($cmd_device_Id != $cmd_device_Id_old)
		{		
			if ($Series != "")
			{
				$result .=  "<div id='data-charts' class='container-fluid'>
					<div id='ExtremeTemp".$lieuxWithouSpace.$cmd_device_Id_old."' class='ui-btn ui-btn-b ui-btn-corner-all navbar-default corner history-title'>
						<table style='width:100%' class='text-center'>
							<tr>
								<td>
									<span id='Min".$lieuxWithouSpace.$cmd_device_Id_old."' class='minHistory'>Min</span>
									<span id='Title".$lieuxWithouSpace.$cmd_device_Id_old."'  class='titleHistory'>".$cmd_deviceName." ".$lieux."</span>
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
					GenerateGraph(\"".$lieuxWithouSpace.$cmd_device_Id_old."\",[".$Series."])
				</script>
				";

				$Series ="";

				$temp_year_old = 0;
				$temp_year = 0;
			}
		}
		$lieux = $TempData["Nom"];
		$lieuxWithouSpace = SpaceToScore($TempData["Nom"]);
		$lieux_Id = $TempData["Lieux_Id"];
		$cmd_deviceName = $TempData["cmd_deviceName"];
		$temp = $TempData['temp'];

		$cmd_device_Id_old = $cmd_device_Id;
	 	
		$json .= "[".$DateGraph."000,".$temp."],";
		$color = "strToRGB((".$temp_year."*(".$temp_year."/3.14116)).toString())";

		$temp_year_old = $temp_year;
	}
		
	if ($Series != "")
	{
		$result .= "<script>GenerateGraph(\"".$lieuxWithouSpace.$cmd_device_Id_old."\",[".$Series."])</script>";
		$Series ="";
	}

	echo $result;

?>