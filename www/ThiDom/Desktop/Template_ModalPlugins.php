<?php include_once dirname(__FILE__) .'/../Core/Security.php';  ?>

<div id="modal-manage-plugins" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Manage plugins <span id="plugins-name"></span></h4>
			</div>

			<form class="white_text form_plugins form-horizontal" id="form-plugins">
				<div class="modal-body">
					<fieldset id="ModalPluginsConfiguration">
						<legend> Configuration</legend>
						<input id="plugins-pluginsid" type="text" name="pluginsId"  class="form-control" style="display: none;"/>
						<input id="plugins-pluginsName" type="text" name="pluginsName"  class="form-control" style="display: none;"/>
						<input id="plugins-pluginsType" type="text" name="pluginsType"  class="form-control" style="display: none;"/>
						<div id="ConfigurationPlugins">	
						</div>
					</fieldset>
				</div>
			</form>
			<div class="modal-footer">
				<button id="plugins-save" class="btn btn-success" type="button" >
					<i class="far fa-save"></i> Save
				</button>
				<button id="plugins-close" class="btn btn-primary" data-dismiss="modal" type="button" >
					<i class="fas fa-times"></i> Close
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	$("#modal-manage-plugins").on('show.bs.modal', function (e) {
        
	})
    
	$("#plugins-save").click(function(event)
	{
		PluginsId = $("#form-plugins #plugins-pluginsid").val();
		PluginsName = $("#form-plugins #plugins-pluginsName").val();
		PluginsType = $("#form-plugins #plugins-pluginsType").val();

		bFormValidate = $('form#form-plugins')[0].reportValidity();
		if (bFormValidate)
		{
			var PluginsConfiguration = new Array();
			var disabled = $("#form-plugins #ModalPluginsConfiguration").find(':input:disabled').removeAttr('disabled');			

			PluginsConfiguration = JSON.stringify($("#form-plugins #ModalPluginsConfiguration").find("input, select, textarea").not("#plugins-pluginsid, #plugins-pluginsName, #plugins-pluginsType").serializeObject())

			disabled.attr('disabled','disabled');
			SavePlugins(PluginsId, PluginsName, PluginsType, PluginsConfiguration);
		}
	});

</script>