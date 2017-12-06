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
						<div class="col-lg-8 col-md-6 col-sm-6 col-xs-6">
							<div class="form-group">
								<label for="list-room" class="col-lg-2 col-md-5 col-sm-5 col-xs-5 control-label">Emplacement :</label>
								<div class="col-lg-10 col-md-6 col-sm-6 col-xs-6">	
									<select id="list-room" name="LieuxId" class="form-control" required>
										<option value="">Nom de la pièce:</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="list-module-type" class="col-lg-2 col-md-5 col-sm-5 col-xs-5 control-label">Type de module: </label>
								<div class="col-lg-10 col-md-6 col-sm-6 col-xs-6">
									<select id="list-module-type" name="ModuleId" class="form-control" required>
										<option value="">Type de module:</option>
									</select>
								</div>
							</div>
							<div class="form-group grouplistType">
								<label for="list-type" class="col-lg-2 col-md-5 col-sm-5 col-xs-5 control-label">Categorie : </label>
								<div class="col-lg-10 col-md-6 col-sm-6 col-xs-6">
									<select id="list-type" name="TypeId" class="form-control" required>
										<option value="">Categorie:</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="device-name" class="col-lg-2 col-md-5 col-sm-5 col-xs-5 control-label">Nom : </label>
								<div class="col-lg-10 col-md-6 col-sm-6 col-xs-6">
									<input type="text" class="form-control" id="device-name"  name="DeviceName" placeholder="Nom de l'appareil (qui apparaitras sur le site):" required>
								</div>
							</div>	
							<div class="form-group dropdown">
							  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Icons
							  <span class="caret"></span></button>
							  <ul class="dropdown-menu" style="overflow: scroll;max-height: 408px;">
								<?php
									if ($handle = opendir('Core/pic/Widget/')) {
									    /* Ceci est la façon correcte de traverser un dossier. */
									    while (false !== ($entry = readdir($handle)))
									    {
									    	if ($entry != "." && $entry != ".." && (strpos($entry, '_on') === false && strpos($entry, '_On') === false && strpos($entry, '_off') === false && strpos($entry, '_Off') === false))
									    	{
									    		$filename = substr($entry, 0, strrpos($entry, "."));
									        	echo "<li><a href='#' onclick=\"$('#CustomIcons').val('$filename')\" title=\"$entry\"><img class='img-circle img_btn_device rounded-circle' src='Core/pic/Widget/$entry'>$filename</a></li>";
								        	}
									    }
									    closedir($handle);
									}
								?>
								</ul>
								<input id="CustomIcons" name="icons" type="text" class="form-control" style="display: none">
							</div>													
							<div class="row form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">  
									<label class="btn btn-success">
										<input type="checkbox" name="DeviceVisible" id="device-visible" value="1">Visible
									</label>
								</div>
							</div>	
							<button id='add-plugins' class='btn btn-success' type='button'><i class='fa fa-plus' aria-hidden='true'></i></button><br/>
						</div>
					</fieldset>

					<fieldset id="ModalEquipementConfiguration">
						<legend> Configuration</legend>
						<div id="ConfigurationDevice">	
						</div>
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
		$("#modal-manage-device #list-type").prop('disabled', true);
		$("#modal-manage-device #list-device").prop('disabled', true);
		$("#modal-manage-device #device-visible").prop( "checked", false);				
		$("#modal-manage-device #ConfigurationDevice").html("");			
		$("#modal-manage-device #CommandeDevice").html("");		
	})
	.on('show.bs.modal', function (e) {
		$("#ModalEquipementConfiguration").hide();
		$("#ModalEquipementCommande").hide();
		$("#modal-manage-device .grouplistType").hide();
		//$("#add-plugins").hide();		
		$("#modal-manage-device #ConfigurationDevice").html("");			
		$("#modal-manage-device #CommandeDevice").html("");	
		//$("#ModalEquipementGeneral").attr('class','col-xs-12 col-sm-12 col-md-12 col-lg-12');		
	})

	$("#add-plugins").click(function(event) {		
		DeviceId = $("#form-device #ModalEquipementGeneral #device-deviceid").val();
		bFormValidate = $('form#form-device')[0].reportValidity();
		if (bFormValidate)
		{
			if (DeviceId == "")
			{
				Device = $("#form-device #ModalEquipementGeneral, #form-device #ModalEquipementConfiguration input[id='carte-id']").serialize();
				//DeviceConfiguration = JSON.stringify($("#form-device #ModalEquipementConfiguration").find("input, select, textarea").not("#device-visible, #carte-id").serializeObject());
				//AddPlugins(Device,DeviceConfiguration);	
				SaveDevice(Device);	
				$("#add-plugins").hide();		
			}
		}
	})

	$("#list-module-type").change(function(event) {
			$("#modal-manage-device #list-type").prop('disabled', true);
			$("#modal-manage-device .grouplistType").hide();
			$moduleType = ","+$("#list-module-type option:selected").val()+",";
			if ($moduleType != ",,")
			{
				$("#modal-manage-device #list-type").val('');
				$('#modal-manage-device #list-type option').hide();
				$('#modal-manage-device #list-type option').filter(function()
				{
					if ($(this).data("module_Id") != undefined)
					{	
						if ($(this).data("module_Id").indexOf($moduleType) >= 0)
						{
							$("#modal-manage-device #list-type").prop('disabled', false);
							$("#modal-manage-device .grouplistType").show();	
							return $(this).data("module_Id")
						}
					}
					//return $(this).data("module_Id") == $moduleType
				}).show();
			}
		});

		$("#modal-manage-device #list-type").change(function(event) {
			$type = $("#modal-manage-device #list-type option:selected").val()
			if ($type != "")
			{
				$("#list-device").prop('disabled', false);
				$("#list-device").val('');
				$('#list-device option').hide();
				$('#list-device option').filter(function()
				{
					return $(this).data("Widget_Id") == $type
				}).show();
			}
		});

		$("#list-device").change(function(event)
		{
			/*data= { "newDevice": "true", "Type":$("#list-device option:selected").text(), "WidgetId":$("#list-type option:selected").val(), "ModuleName":$("#list-module-type option:selected").text()};
			EditDevice(data);*/

		})


	$("#device-save").click(function(event)
	{
		bFormValidate = $('form#form-device')[0].reportValidity();
		if (bFormValidate)
		{
			var Device = "";
			var CmdDevice = "";
			var DeviceConfiguration = "";
			var listcmd = new Array();

			Device = $("#form-device #ModalEquipementGeneral, #form-device #ModalEquipementConfiguration input[id='carte-id']").serialize();

			DeviceConfiguration = JSON.stringify($("#form-device #ModalEquipementConfiguration").find("input, select, textarea").not("#carte-id").add($("#CustomIcons")).serializeObject())

			if ($("#ModalEquipementCommande input"))
			{
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

				$("#ModalEquipementCommande input[class='toggle']").each(function( index )
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

			if (SaveDevice(Device, DeviceConfiguration, CmdDevice))
			{				
   				$('#modal-manage-device').modal('toggle');
			}

		}
	});

</script>