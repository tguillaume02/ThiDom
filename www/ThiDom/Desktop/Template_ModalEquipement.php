<?php include_once dirname(__FILE__) .'/../Core/Security.php';  ?>
<div id="modal-manage-device" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Manage Equipement <span id="device-name"></span></h4>
			</div>

			<form class="white_text form_device form-horizontal" id="form-device">
				<div class="modal-body">
					<fieldset id="ModalEquipementGeneral">
						<legend> Général</legend>
						<input id="device-deviceid" type="text" name="deviceId"  class="form-control" style="display: none;"/>
						<div class="form-group">
							<label for="list-room" class="col-lg-1 col-md-5 col-sm-5 col-xs-5 control-label">Emplacement :</label>
							<div class="col-lg-10 col-md-6 col-sm-6 col-xs-6">	
								<select id="list-room" name="LieuxId" class="form-control">
									<option value="">Nom de la pièce:</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="list-type" class="col-lg-1 col-md-5 col-sm-5 col-xs-5 control-label">Type : </label>
							<div class="col-lg-10 col-md-6 col-sm-6 col-xs-6">
								<select id="list-type" name="TypeId" class="form-control" required>
									<option value="">Type d'appareil:</option>
								</select>
							</div>
						</div>							
						<div class="form-group">
							<label for="list-device" class="col-lg-1 col-md-5 col-sm-5 col-xs-5 control-label">Model : </label>
							<div class="col-lg-10 col-md-6 col-sm-6 col-xs-6">
								<select id="list-device" name="ModelTypeId" class="form-control" required>
									<option value="">Model d'appareil:</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="device-name" class="col-lg-1 col-md-5 col-sm-5 col-xs-5 control-label">Nom : </label>
							<div class="col-lg-10 col-md-6 col-sm-6 col-xs-6">
								<input type="text" class="form-control" id="device-name"  name="DeviceName" placeholder="Nom de l'appareil (qui apparaitras sur le site):" required>
							</div>
						</div>
						<div class="row form-group"> 
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">      
								<label class="btn btn-success">
									<input type="checkbox" name="DeviceVisible" id="device-visible" value="1">Visible
								</label>
								<label class="btn btn-success">
									<input type="checkbox" name="DeviceHistoriser" id="device-historiser" value="1">Historiser
								</label>
								<label class="btn btn-success">
									<input type="checkbox" name="DeviceNotification" id="device-notification" value="1">Notification
								</label>
							</div>
						</div>	
					</fieldset>

					<fieldset id="ModalEquipementConfiguration">
						<legend> Configuration</legend>
						<div id="ConfigurationDevice"></div>
					</fieldset>

					<fieldset id="ModalEquipementCommande">
						<legend> Commande</legend>
						<div id="CommandeDevice"></div>
					</fieldset>
				</div>
			</form>
			<div class="modal-footer">
				<button id="device-save" class="btn btn-success" type="button" >
					<i class="fa fa-floppy-o"></i> Save
				</button>
				<button id="device-close" class="btn btn-primary" data-dismiss="modal" type="button" >
					<i class="fa fa-times"></i>Close
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	$("#modal-manage-device").on("hidden.bs.modal", function (e) { 
  		$("#ModalEquipementGeneral .form-control").each(function() {
			$(this).val('');
		})
		$("#modal-manage-device #list-device").prop('disabled', true);
		$("#modal-manage-device #device-visible").prop( "checked", false);				
		$("#modal-manage-device #ConfigurationDevice").html("");
	})
	.on('show.bs.modal', function (e) {
		$("#ModalEquipementConfiguration").hide();
		$("#ModalEquipementCommande").hide();
		$("#ConfigurationDevice").html("");
		//$("#ModalEquipementGeneral").attr('class','col-xs-12 col-sm-12 col-md-12 col-lg-12');		
	})

	$("#device-save").click(function(event) {
		bFormValidate = $('form#form-device')[0].reportValidity();
		if (bFormValidate)
		{
			var Device = "";
			var CmdDevice = "";
			var listcmd = new Array();

			Device = $("#form-device").serialize()
			
			if ($("#ModalEquipementCommande input"))
			{
				if ($("#ModalEquipementCommande #carte-id"))
				{
					CarteId = $("#ModalEquipementCommande #carte-id").val()
					Device += "&CarteId="+CarteId
				}

				$("#ModalEquipementCommande input:not([type='radio'],[class='toggle'])").each(function( index )
				{
					id = $( this ).attr("cmdid");
					cmdName = $(this).attr("name");
					if ($( this ).attr("type") == "checkbox")
					{
						value = ($(this).is(":checked")? 1:0);
					}
					else
					{
						value = $(this).val();
					}

					listcmd.push({
			            'id' : id,
			            'cmdname': cmdName,
			            'value' : value
		            });
				});

				$("#ModalEquipementCommandeinput[class='toggle']").each(function( index )
				{
					id = $( this ).attr("cmdid");
					cmdName = $(this).attr("name");
					value = $(this).is(':checked') ? $(this).attr('data-on') : $(this).attr('data-off');

					listcmd.push({
			            'id' : id,
			            'cmdname': cmdName,
			            'value' : value
		            });
				});

				$("#ModalEquipementCommande input[type='radio']:checked").each(function( index )
				{
					id = $( this ).attr("cmdid");
					cmdName = $(this).attr("name");
					value = $(this).val();

					listcmd.push({
			            'id' : id,
			            'cmdname': cmdName,
			            'value' : value
		            });
				});

				CmdDevice = JSON.stringify(listcmd);
			}

			SaveDevice(Device, CmdDevice);
   			$('#modal-manage-device').modal('toggle');
		}
	});

</script>