<?php
	require_once '../Core/Security.php'; 
	require_once ('../ListRequire.php');
?>
<div id="data-charts" class="container-fluid">
	<?php
	$LieuxWithTemp = Lieux::withHistoric();
	foreach($LieuxWithTemp as $Lieux)
	{
		$lieux =  SpaceToScore($Lieux["lieux"]);
		$lieux_id = $Lieux["lieux_id"];

		echo "<div id='ExtremeTemp".$lieux."' class='ui-btn ui-btn-b ui-btn-corner-all navbar-default corner history-title'>
				<table style='width:100%' class='text-center'>
					<tr>
						<td>
							<span id='Min".$lieux."' class='minHistory'></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span id='Title".$lieux."' class='titleHistory'>".."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span id='Max".$lieux."' class='maxHistory'/span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input id='CheckBox".$lieux."'' class='pull-right' type='checkbox' checked>
						</td>
					</tr>
				</table>
			</div>
			<div id='Temp".$lieux."'></div>
			<br>";


		/*$getTemp = Temperature::GetAllTemperatureByLieux($lieux_id);
		foreach ($getTemp as $data) 
		{

			$temp_year = $data['temp_year'];
			$lieux = $data['lieux'];
			$lieux_without_space = str_replace(" ","_",$lieux);
			$Bisvisible = "true";

			$data[] .= "[".$data['date']."000,".$data['temp']."]";
		}
		echo $data[];*/
	}
		?>
</div>