<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';

?>

<div> <!--/* class="DeviceDetail Corner"*/-->
	<table class="table text-center table-borderless WidgetContent" id="Contentcmd_<?php echo CmdDevice::GetCmdId('SendMessage',$Device_id)->get_Id()?>">
		<tbody>        
			<tr class="WidgetStatus-center">
				<td>
                    <input id="TelegramMessage" type="text" class="form-control"  placeholder="Message">
				</td>
			</tr>
			<tr class="WidgetStatus-center">
				<td>
					<div class="btn btn-primary pull-center" data-i18n="Edit" data-theme="a" id="SendMessage" deviceId="<?php echo $Device_id ?>">Send Message </div>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<script>
	$("#SendMessage").click(function(){
        if ($("#TelegramMessage").val() != "")
        {
		    sendMessage($(this).attr("deviceId"), $("#TelegramMessage").val());
        }
	})

    function sendMessage($deviceId, $msg)
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/plugins/Telegram/Desktop/Telegram_ajax.php',            
			data: {
				act: "sendMessage",
				msg: $msg,
				Device_id: $deviceId
			},
			cache: false,
			async: true
		});

		request.done(function (data) {
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
		});
		
		$("#TelegramMessage").val("")

	}

</script>
