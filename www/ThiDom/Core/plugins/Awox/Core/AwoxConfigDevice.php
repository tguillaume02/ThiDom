<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';
?>

<div>
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group">
				<label for="host" class="col-lg-1 col-md-5 col-sm-6 col-xs-6 control-label">IP :</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="text" class="DeviceAttr form-control" id="host" name="host" placeholder="IP" required/>
				</div>
			</div>
		</fieldset>
	</form>
</div>