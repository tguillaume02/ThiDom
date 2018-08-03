<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';
?>


<div>
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group">
				<label for="url" class="col-lg-1 col-md-5 col-sm-6 col-xs-6 control-label">URL :</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="url" class="form-control" id="url" name="url" placeholder="url/IP de la webcam:" required />
				</div>
			</div>

			
			<div class="form-group">
				<label for="url" class="col-lg-1 col-md-5 col-sm-6 col-xs-6 control-label">URL deplacement droite :</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="url" class="form-control" id="urlMoveRight" name="url" placeholder="url deplacement droit:" required />
				</div>
			</div>

						
			<div class="form-group">
				<label for="url" class="col-lg-1 col-md-5 col-sm-6 col-xs-6 control-label">URL deplacement gauche :</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="url" class="form-control" id="urlMoveLeft" name="url" placeholder="url deplacement gauche:" required />
				</div>
			</div>

						
			<div class="form-group">
				<label for="url" class="col-lg-1 col-md-5 col-sm-6 col-xs-6 control-label">URL deplacement stop :</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="url" class="form-control" id="urlMoveStop" name="url" placeholder="url deplacement stop:" required />
				</div>
			</div>

			<!--<div class="form-group">
				<label for="device-visible" class="col-lg-1 col-md-5 col-sm-6 col-xs-6 control-label">Visible :</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="checkbox" class="form-control" id="device-visible" name="DeviceVisible" value="1"/>Visible
				</div>
			</div>-->
		</fieldset>
	</form>
</div>