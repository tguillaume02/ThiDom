<?php include_once dirname(__FILE__) .'/../Core/Security.php';  ?>

<div id="modal-manage-user" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Manage User <!--<span id="user-name"></span>--></h4>
			</div>

			<form class="white_text form_user form-horizontal" id="form-user">
				<div class="modal-body">
					<fieldset>
						<input id="user-id" type="text" name="Id" class="form-control" style="display: none;"/>
						<div class="form-group">
							<label for="user-name" class="col-lg-2 col-md-3 col-sm-4 col-xs-7 control-label">Nom d'utilisateur : </label>
							<div class="col-lg-10 col-md-9 col-sm-8 col-xs-5">
								<input type="text" class="form-control"  name="Name" id="user-name" placeholder="Nom d'utilisateur" required>
							</div>
						</div>						
						<div class="form-group">
							<label for="user-password" class="col-lg-2 col-md-3 col-sm-4 col-xs-7 control-label">Mot de passe: </label>	
							<div class="col-lg-10 col-md-9 col-sm-8 col-xs-5">
								<input type="text" class="form-control"  name="Password" id="user-password" placeholder="mot de passe" required>
							</div>
						</div>						
						<div class="form-group">
							<label for="user-hash" class="col-lg-2 col-md-3 col-sm-4 col-xs-7 control-label"> Hash: </label>
							<div class="col-lg-10 col-md-9 col-sm-8 col-xs-5">
								<div class="input-group">
									<input type="text" class="form-control"  name="Hash" id="user-hash" placeholder="hash" readonly>								
									<span class="input-group-addon" id="newHash"><i class="fas fa-sync"></i></span>		
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-7 control-label"></label>
							<div class="col-lg-10 col-md-9 col-sm-8 col-xs-5">
								<label class="btn btn-success">
									<input type="checkbox" name="UserIsAdmin" id="user-isAdmin" value="1">
									Administrateur
								</label>
							</div>
						</div>
					</fieldset>
				</div>
			</form>

			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="user-save">
					<i class="far fa-save"></i> Save
				</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="user-close">
					<i class="fas fa-times"></i> Close
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	$("#modal-manage-user").on("hidden.bs.modal", function (e) { 
  		$("#form-user .form-control").each(function() {
			$(this).val('');
		})		
		$("#modal-manage-user #user-visible").prop( "checked", false);
	});

	$("#user-save").click(function(event) {
		bFormValidate = $('form#form-user')[0].reportValidity();
		if (bFormValidate)
		{
			data = $("#form-user").serialize()
			SaveUser(data);
   			$('#modal-manage-user').modal('toggle')
		}
	});

	$("#newHash").click(function(event) {
		getNewHash();
	});

</script>
