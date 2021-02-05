<?php

$ChannelId = $Cmd->get_Request("ChannelId", "");

$url = $Cmd->get_Request("url", "plugins/Telegram/Desktop/Telegram.php");
$url_ajax = $Cmd->get_Request("url_ajax", "plugins/Telegram/Desktop/Telegram_ajax.php");
$data = $Cmd->get_Request("data", "act=SendMessage");

$tplWidgetConfig .= '
<div class="row">
	<div class="form-group">
		<div class="col-lg-12">		
			<label for="min" class="col-lg-2  control-label">Channel ID</label>	
			<div class="col-lg-2">
				<input class="form-control" id="channel'.$Cmd->get_Id().'" name="ChannelId" cmdid ="'.$Cmd->get_Id().'" value="'.$ChannelId.'" placeholder="ChannelId" request=1>
			</div>
		</div>
	</div>
</div>

<div style="display:none">
<div class="form-group">
<input class="form-control" id="url'.$Cmd->get_Id().'" name="url" cmdid ="'.$Cmd->get_Id().'" value="'.$url.'"  request=1>
<input class="form-control" id="url_ajax'.$Cmd->get_Id().'" name="url_ajax" cmdid ="'.$Cmd->get_Id().'" value="'.$url_ajax.'"  request=1>
<input class="form-control" id="data'.$Cmd->get_Id().'" name="data" cmdid ="'.$Cmd->get_Id().'" value="'.$data.'"  request=1>
</div>
</div>
';