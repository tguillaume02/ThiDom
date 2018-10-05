<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';
?>

<div>
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group">
				<label class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label">Bot Token</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="text" class="DeviceAttr form-control" id="BotToken" name="BotToken" placeholder="Bot Token" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label">Channel Id</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="text" class="DeviceAttr form-control" id="ChannelId" name="ChannelId" placeholder="Channel Id" />
				</div>
			</div>
		</fieldset>
	</form>
</div>
