<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';
?>
<div> <!--/* class="DeviceDetail Corner"*/-->
	<table class="table table-borderless WidgetContent">
		<tbody>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Sunrise',$Device_id)->get_Id()?>">
					<img src="Core/pic/Sunrise.png" style="width: 28px;">
					<span id="InfoDeviceSunrise_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Sunrise',$Device_id)->get_Id()?>"></span>
				</td>
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Sunset',$Device_id)->get_Id()?>">
					<img src="Core/pic/Sunset.png" style="width: 28px;">
					<span id="InfoDeviceSunset_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Sunset',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Conditions',$Device_id)->get_Id()?>">
					Conditions: <span id="InfoDeviceConditions_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Conditions',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('SchoolHolidays',$Device_id)->get_Id()?>">
					SchoolHolidays: <span id="InfoDeviceSchoolHolidays_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('SchoolHolidays',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('weekend',$Device_id)->get_Id()?>">
					weekend: <span id="InfoDeviceweekend_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('weekend',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('holiday',$Device_id)->get_Id()?>">
					holiday: <span id="InfoDeviceholiday_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('holiday',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('EjpToday',$Device_id)->get_Id()?>">
					EjpToday: <span id="InfoDeviceEjpToday_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('EjpToday',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('EjpTomorrow',$Device_id)->get_Id()?>">
					EjpTomorrow: <span id="InfoDeviceEjpTomorrow_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('EjpTomorrow',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Season',$Device_id)->get_Id()?>">
					Season: <span id="InfoDeviceSeason_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Season',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
		</tbody>
	</table>

	<table class="table table-borderless WidgetContent">
		<tbody>
			<tr class="WidgetStatus-center">
				<td>
					<table class="table table-borderless WidgetContent">
						<tbody>
							<tr>
								<td>
									<span id="Contentcmd_<?php echo CmdDevice::GetCmdId('vigilancecolor',$Device_id)->get_Id()?>">
										<img id="InfoDevicevigilancecolor_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('vigilancecolor',$Device_id)->get_Id() ?>" src="Core/plugins/Domogeek/Desktop/weatherwarning.png" class="img-circle" style="width: 40px;">
									</span>
									<span id="Contentcmd_<?php echo CmdDevice::GetCmdId('vigilancerisk',$Device_id)->get_Id()?>">
										<span id="InfoDevicevigilancerisk_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('vigilancerisk',$Device_id)->get_Id()?>"></span>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<script>

	function LoadDomogeekData()
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/plugins/Domogeek/Desktop/Domogeek_ajax.php',
			data: {
				act: ""
			},
			cache: false,
			async: true
		});

		request.done(function (data) {
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
		});
	}

	LoadDomogeekData();


</script>

