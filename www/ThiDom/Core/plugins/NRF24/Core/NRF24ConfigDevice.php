<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';

$device_id = getPArameter("device_id");

$deviceObject = new Device();
$device = $deviceObject->byId($device_id);
$device_carteId = $device->get_carteId();
?>

<!--<input id="CmdDeviceid" type="text" name="CmdDeviceid"  class="form-control" style="display: none;"/>-->


<div class="form-group">
	<label for="carte-id" class="col-lg-1 col-md-2 col-sm-2 col-xs-4 control-label">Id de la carte </label>
	<div class="col-lg-3 col-md-8 col-sm-8 col-xs-8">
		<input type="number" min="0" class="form-control" id="carte-id" device_id="<?php echo $device_id ?>" name="CarteId" placeholder="Identifiant de la carte:"  value="<?php echo $device_carteId ?>" required>
	</div>
</div>

<!--<div class="row">
	<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
		<label>Visible : </label>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<label class="btn btn-success">
			<input type="checkbox" name="DeviceVisible" id="device-visible" checked/>Visible
		</label>			
	</div>
</div>
-->