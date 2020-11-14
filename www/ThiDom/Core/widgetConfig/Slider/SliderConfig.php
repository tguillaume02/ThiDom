<?php
include_once dirname(__FILE__) .'/../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../Core/ListRequire.php';

$cmd_device_id = getParameter("cmd_device_id");
$Device_id = getParameter("device_id");

$DeviceData =  Device::byId($Device_id);
$cmdDeviceData =  CmdDevice::byId($cmd_device_id);

$min = $cmdDeviceData->get_Request("Min", "0");
$max = $cmdDeviceData->get_Request("Max", "100");
$hysteresis = $cmdDeviceData->get_Request("Hysteresis", "0");

$widgetId = $cmdDeviceData->get_WidgetId();
$sensorAttachId = $cmdDeviceData->get_SensorAttachId();

$tplWidgetConfig = '
<div class="row">
	<div class="col-lg-12">
		<div class="form-group">
			<label for="min" class="col-lg-2  control-label">Min</label>	
			<div class="col-lg-2">
				<input type="number" class="form-control" id="min"  cmdid="'.$cmd_device_id.'"  name="Min" value="'.$min.'" placeholder="Min" step="0.1" request=1>
			</div>
			<label for="max" class="col-lg-2 control-label">Max</label>	
			<div class="col-lg-2">		
				<input type="number" class="form-control" id="max"  cmdid="'.$cmd_device_id.'"  name="Max" value="'.$max.'" placeholder="Max" step="0.1" request=1>
			</div>
		</div>
	</div>
</div>';

	if ($widgetId == 5)
	{
		$tplWidgetConfig .= '
		<div class="row">
			<div class="col-lg-12">
				<div class="form-group">
					<label for="hysteresis" class="col-lg-2 control-label">Hystérésis</label>	
					<div class="col-lg-2">
						<input type="number" class="form-control" id="hysteresis"  cmdid="'.$cmd_device_id.'"  name="Hysteresis" value="'.$hysteresis.'" placeholder="Hysteresis" step="0.1"  request=1>
					</div>
					<label for="sensorAttach" class="col-lg-2 control-label">capteur</label>
					<div class="col-lg-4">	
						<select id="sensorAttach" name="sensor_attachId"  cmdid="'.$cmd_device_id.'" class="form-control" required="">
							<option value="">-</option>';
							$cmdDeviceToAttachArray = CmdDevice::GetAllCmdDevice();
							foreach($cmdDeviceToAttachArray as $donneesCmdDevice)
							{								
								$isDefined = "";
								if ($sensorAttachId == $donneesCmdDevice["Id"])
								{
									$isDefined = "selected";
								}
								if ($donneesCmdDevice["WidgetName"] != "")
								{
									$widgetName = "(". $donneesCmdDevice["WidgetName"] .")";
								}
								$tplWidgetConfig.= "<option value='" . $donneesCmdDevice["Id"] . "' ".$isDefined.">" . $donneesCmdDevice["Nom"] . " - ". $donneesCmdDevice["LieuxNom"] . $widgetName." </option>"; 
							}
		$tplWidgetConfig.= '</select>
					</div>
				</div>';
		$tplWidgetConfig.= CmdDevice::getDesactivateConditions($cmd_device_id);
		$tplWidgetConfig.='</div>
		</div>';
	}
	
	if (getparameter("mode") == "echo")
	{
		echo $tplWidgetConfig;
	}
?>
