<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';

?>

<div> <!--/* class="DeviceDetail Corner"*/-->
	<table class="table table-borderless WidgetContent">
		<tbody>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Up',$Device_id)->get_Id()?>" style="vertical-align: middle;">
					<img src="Core/pic/temp_up.png" alt="tempup"/>
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Up',$Device_id)->get_Id()?>"></span>
				</td>
				<td>
					<img id="Livebox_pic" src="Core/plugins/Livebox/pic/Livebox.png" alt="Livebox">
				</td>
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Down',$Device_id)->get_Id()?>" style="vertical-align: middle;">
					<img src="Core/pic/temp_down.png" alt="tempdown"/>
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Down',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="table table-borderless WidgetContent">
		<tbody>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Last Change',$Device_id)->get_Id()?>">	
					<img src="Core/pic/Synchronize.png" alt="Synchronize"/>
					<span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId('Last Change',$Device_id)->get_Id()?>"></span>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="table text-center table-borderless WidgetContent">
		<tbody>
			<tr class="WidgetStatus-center">
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Reboot Livebox',$Device_id)->get_Id()?>">
					<div class="btn btn-primary pull-left" data-i18n="Edit" data-theme="a" id="rebootLivebox" deviceId="<?php echo $Device_id ?>">Reboot </div>
				</td>
				<td id="Contentcmd_<?php echo CmdDevice::GetCmdId('Update Livebox',$Device_id)->get_Id()?>">
					<div class="btn btn-primary pull-right" data-i18n="Edit" data-theme="a" id="UpdateLivebox" deviceId="<?php echo $Device_id ?>">Rafraichir </div>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<script>

	function LoadLiveboxData($deviceId)
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/plugins/Livebox/Desktop/Livebox_ajax.php',            
			data: {
				act: "loadData",
				Device_id: $deviceId
			},
			cache: false,
			async: true
		});

		request.done(function (data) {
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
		});

	}

	$("#rebootLivebox").click(function(){
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/plugins/Livebox/Desktop/Livebox_ajax.php',             
			data: {
				act: "rebootLivebox",
				Device_id: $(this).attr("deviceId")
			},
			cache: false,
			async: true
		});


		request.done(function (data) {
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
		});
	})

	$("#UpdateLivebox").click(function(){
		LoadLiveboxData($(this).attr("deviceId"));
	})

	LoadPluginsData.push("LoadLiveboxData(<?php echo $Device_id?>)");

</script>
