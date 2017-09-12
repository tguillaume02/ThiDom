<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';

$cmd_device_id = $_POST["cmd_device_id"];
?>

<!--<input id="CmdDeviceid" type="text" name="CmdDeviceid"  class="form-control" style="display: none;"/>-->


<div class="form-group">
	<label for="device-id" class="col-lg-1 col-md-5 col-sm-5 col-xs-5 control-label">Identifiant de l'appareil :</label>						
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<input class="form-control" id="device-id"  name="DeviceId" cmdid="<?php echo $cmd_device_id ?>" placeholder="Identifiant de l'appareil:" required>
	</div>
</div>
<div class="form-group">
	<label for="carte-id" class="col-lg-1 col-md-5 col-sm-5 col-xs-5 control-label">Identifiant de la carte :</label>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<input type="number" min="0" class="form-control" id="carte-id" cmdid="<?php echo $cmd_device_id ?>" name="CarteId" carteIdplaceholder="Identifiant de la carte:" required>
	</div>
</div>
<div class="form-group">
	<label for="raz-value" class="col-lg-1 col-md-5 col-sm-5 col-xs-5 control-label">Remise à zero apres: HH:MM:SS : </label>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<input type="time" class="form-control" id="raz-value" step="1" name="RAZ" cmdid="<?php echo $cmd_device_id ?>" placeholder="Remise à zero apres: HH:MM:SS">
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


<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="row">   
	  		<div class="col-lg-1 col-md-2 col-sm-2 col-xs-5">
	      		<label class="btn btn-success">
	      			<input type="checkbox" name="Visible" id="device-visible" cmdid="<?php echo $cmd_device_id ?>"  value="1">Visible
				</label>			
	      	</div>		      
			<div class="col-lg-1 col-md-2 col-sm-2 col-xs-5">   
				<input type="checkbox" class="toggle" cmdid="<?php echo $cmd_device_id ?>" name="Type" data-toggle="toggle" data-on="Action" data-off="Info" data-onstyle="success" data-offstyle="info">
	<!--
			   	<label  class="radio-inline" for="cmdtype-action">Action</label>
			    <input type="radio" name="Type" id="cmdtype-action" cmdid="<?php echo $cmd_device_id ?>" value="Action">
			    <label class="radio-inline" for="cmdtype-info">Info</label>
			    <input type="radio" name="Type" id="cmdtype-info" cmdid="<?php echo $cmd_device_id ?>" value="Info">
			    -->
			</div>	
<!--		<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
				<div class="row">			
					<div class="class=" col-lg-12="" col-md-12="" col-sm-12="">
						<label class="btn btn-success">
							<input type="checkbox" name="DeviceHistoriser" id="device-historiser" value="1">Historiser
						</label>			
					</div>
				</div>
	      	</div>
			<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
					<div class="class=" col-lg-12="" col-md-12="" col-sm-12="">
						<label class="btn btn-success">
							<input type="checkbox" name="DeviceNotification" id="device-notification" value="1">Notification
						</label>			
					</div>
				</div>
			</div>
			-->
		</div>
	</div>
</div>