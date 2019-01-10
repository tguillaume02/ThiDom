<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';

?>

<div> <!--/* class="DeviceDetail Corner"*/-->
	<table class="table text-center table-borderless WidgetContent" id="Contentcmd_<?php echo CmdDevice::GetCmdId('SendMessage',$Device_id)->get_Id()?>">
		<tbody>
			<?php
				$listCmd = CmdDevice::byDevice_Id($Device_id);
				$nbIter = 0;
				foreach($listCmd as $value)
				{
					if ( sizeof($listCmd) > 1)
					{
						if ($nbIter % 2 == 0)
						{
							echo "<tr>";						
						}
						echo "<td><table><tbody>";
					}
			?>					
					<tr class="WidgetStatus-center">
						<td>
							<textarea id="TelegramMessage_<?php echo $value->get_Id() ?>" type="text" class="form-control"  placeholder="Message"></textarea>
						</td>
					</tr>
					<tr class="WidgetStatus-center">
						<td>
							<div class="btn btn-primary pull-center TelegramSendMessage" data-i18n="Edit" data-theme="a" id="SendMessage_<?php echo $value->get_Id() ?>" cmdDeviceId="<?php echo $value->get_Id() ?>"><?php echo $value->get_Name() ?></div>
						</td>
					</tr>
			<?php
					if (sizeof($listCmd) > 1)
					{
						if (($nbIter % 2 == 0 && $nbIter != 0) || $nbIter  == sizeof($listCmd)-1)
						{
							echo "</tr>";						
						}
						echo "</tbody></table></td>";
					}
					$nbIter++;
				}
			?>
			</tr>
		</tbody>
	</table>
</div>

<script>
	$(".TelegramSendMessage").click(function()
	{
		$msg = $("#TelegramMessage_"+$(this).attr("cmdDeviceId")).val();
        if ($msg != "")
        {
		    sendMessage($(this).attr("cmdDeviceId"), $msg );
        }
	})

    function sendMessage(cmdDeviceId, msg)
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/plugins/Telegram/Desktop/Telegram_ajax.php',            
			data: {
				act: "sendMessage",
				msg: msg,
				cmdDeviceId: cmdDeviceId
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
