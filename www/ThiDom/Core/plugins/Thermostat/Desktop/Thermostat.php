<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';

$TheremostatMin = Device::byId($Device_id)->get_Configuration("ThermostatMin", "10");
$TheremostatMax = Device::byId($Device_id)->get_Configuration("ThermostatMax", "30");

$Pictures_device = '<div id="InfoDevice_'/*.$NomWithoutSpace.'_'*/.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" class="img-circle img_btn_device rounded-circle circle" value="'.$CmdDeviceValue.'" >'.$CmdDeviceValue.'</div>';

echo
		'<table class="table table-borderless text-center WidgetContent">
			<tr>
				<td>
					<div class="div_btn_device Corner" name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'/*.$NomWithoutSpace.'_'*/.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" Cmd_device_Id="'.$Cmd_device_Id.'" Device_id ="'.$Device_id.'" data-type="'.$Cmd_type.'" data-role="'.$WidgetType.'">'.$Pictures_device.
					'</div>
				</td>
				<td class="WidgetStatus-left">
					<table style="width:100%">
						<tr>
							<td class="WidgetStatus-left">
								<input class="bar" value="'.$CmdDeviceValue.'" type="range" step="0.5" min="'.$TheremostatMin.'" max="'.$TheremostatMax.'" Cmd_device_Id="'.$Cmd_device_Id.'" Device_id ="'.$Device_id.'" name="Range_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'"  id="Range_'./*$NomWithoutSpace.'_'.*/$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" data-type="'.$Cmd_type.'" data-role="'.$WidgetType.'" oninput="$(InfoDevice_'./*$NomWithoutSpace.'_'.*/$LieuxWithoutSpace.'_'.$Cmd_device_Id.').html(this.value);$(InfoDevice_'./*$NomWithoutSpace.'_'.*/$LieuxWithoutSpace.'_'.$Cmd_device_Id.').attr(\'value\',this.value)"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>';		
?>

