<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';

$cmd_device_id = $_POST["cmd_device_id"];
?>


<div class="form-group">
	<label for="thermostat-min" class="col-lg-1 col-md-5 col-sm-5 col-xs-5 control-label">Temperature Min</label>						
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<input type="number" class="form-control" id="thermostat-min"  name="ThermostatMin"  placeholder="Temp. min" step="0.1" required>
	</div>
</div>
<div class="form-group">
	<label for="thermostat-max" class="col-lg-1 col-md-5 col-sm-5 col-xs-5 control-label">Temperature Max</label>						
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<input type="number" class="form-control" id="thermostat-max"  name="ThermostatMax"  placeholder="Temp. max" step="0.1" required>
	</div>
</div>