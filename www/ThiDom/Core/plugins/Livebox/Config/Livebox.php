<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';
?>

<div>
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group">
				<label class="col-lg-1 col-md-5 col-sm-6 col-xs-6 control-label">IP de la livebox :</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="text" class="DeviceAttr form-control" id="host" placeholder="IP de la livebox"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-1 col-md-5 col-sm-6 col-xs-6 control-label">Utilisateur :</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="text" class="DeviceAttr form-control" id="user" placeholder="Utilisateur"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-1 col-md-5 col-sm-6 col-xs-6 control-label">Mot de passe :</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="password" class="DeviceAttr form-control" id="pwd" placeholder="Mot de passe"/>
				</div>
			</div>
		</fieldset>
	</form>
</div>
