<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';
?>

<div>
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group">
				<label class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label">Departement</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="text" class="DeviceAttr form-control" id="Departement" name="Departement" placeholder="Departement" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label">Ville</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="text" class="DeviceAttr form-control" id="City" name="City" placeholder="Ville"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label">Zone Scolaire</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<select id="HolidaysZone" name="HolidaysZone" class="DeviceAttr form-control" required="">
						<option value="">Zone Scolaire:</option>
						<option value="A">A</option>
						<option value="B">B</option>
						<option value="C">C</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label">Zone EJP</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="text" class="DeviceAttr form-control" id="EJPZone" name="EJPZone" placeholder="Zone EJP"/>
				</div>
			</div>
		</fieldset>
	</form>
</div>
