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
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Sunrise',$Device_id)->get_Id()?>"></span>
				</td>
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Sunset',$Device_id)->get_Id()?>">
					<img src="Core/pic/Sunset.png" style="width: 28px;">
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Sunset',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Conditions',$Device_id)->get_Id()?>">
					Conditions: 
				</td>
				<td>
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Conditions',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('SchoolHolidays',$Device_id)->get_Id()?>">
					SchoolHolidays: 
				</td>
				<td>
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('SchoolHolidays',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Weekend',$Device_id)->get_Id()?>">
					Weekend: 
				</td>
				<td>
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Weekend',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Holiday',$Device_id)->get_Id()?>">
					Holiday: 
				</td>
				<td>
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Holiday',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('EjpToday',$Device_id)->get_Id()?>">
					EjpToday: 
				</td>
				<td>
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('EjpToday',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('EjpTomorrow',$Device_id)->get_Id()?>">
					EjpTomorrow: 
				</td>
				<td>
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('EjpTomorrow',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Season',$Device_id)->get_Id()?>">
					Season: 
				</td>
				<td>
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Season',$Device_id)->get_Id()?>"></span>
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
								<td style="text-align: center; width: 29%; vertical-align:middle;">
									<span id="Contentcmd_<?php echo CmdDevice::GetCmdId('Vigilancecolor',$Device_id)->get_Id()?>">
										<img id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Vigilancecolor',$Device_id)->get_Id() ?>" src="Core/plugins/Domogeek/Desktop/weatherwarning.png" class="img-circle rounded-circle" style="width: 40px;">
									</span>
								</td>
								<td style="text-align: left">
									<span id="Contentcmd_<?php echo CmdDevice::GetCmdId('Vigilancerisk',$Device_id)->get_Id()?>">
										<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Vigilancerisk',$Device_id)->get_Id()?>"></span>
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

	function LoadDomogeekData($Id)
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/plugins/Domogeek/Desktop/Domogeek_ajax.php',
			data: {
				Device_id: $Id
			},
			cache: false,
			async: true
		});

		request.done(function (data) {
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
		});
	}

	LoadDomogeekData(<?php echo $Device_id?>);


</script>

