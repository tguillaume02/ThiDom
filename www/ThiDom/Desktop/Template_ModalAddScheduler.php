<?php 
	include_once dirname(__FILE__) .'/../Core/Security.php';
?>
<div id="modal-planning-data"  class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center" id="planning-title">Ajouter un evenemt pour <span id="planning-device-name"></span></h4>
			</div>

			<form class="white_text form_planning form-horizontal" id="form-planning">
				<div class="modal-body">
					<div>
						<input id="planning-cmddeviceId" type="text" name="cmddeviceId" style="display: none;"/>
						<input id="planning-planningId" type="text" name="planningId" style="display: none;"/>
						<h4><span><u>Activé : </u></span></h4>
						<table>
							<tr>
								<td>									
									<label class="btn btn-success">
										<input type="checkbox" name="active" id="active" value="1"/>Active
									</label>
								</td>
							</tr>
						</table>
						<hr>
						<h4><span><u>Action</u></span></h4>
						<table class="WidgetContent">
							<tbody>
								<tr>

									<!-- FOR ON / OFF -->
									<td class="Binary Relay widgetType">
										<label class="btn btn-success">
											<input type="radio" name="commande" id="action-on" value="1" />On
										</label>
										<label class="btn btn-danger">
											<input type="radio" name="commande" id="action-off" value="0" />Off
										</label>
									</td>

									<!-- FOR COLOR-->
									<td class="Color RGB widgetType">
										<input type="color" name="commande" id="planning-color">
									</td>

									<!-- FOR SLIDER  -->
									<td class="Slider Thermostat Dimmer widgetType">
										<div class="div_btn_device Corner" style="background-color: transparent; user-select: none; cursor: default;">
											<input class="img_btn_device circle Corner" id="planning-info-slider" name="commande" readonly value="15">
										</div>
									</td>
									<td class="Slider Thermostat Dimmer widgetType" style="width: 80%;padding: 15px;">
										<input class="bar" value="0" type="range" step="0.5" min="15" max="30" id="planning-slider" oninput="$('#planning-info-slider').html(this.value);$('#planning-info-slider').attr('value',this.value)">
									</td>

									<!-- FOR TEXT-->
									<td class="Text Numeric widgetType">
										<input type="text" name="commande" id="planning-text" >
									</td>
								</tr>
							</tbody>
						</table>
						<hr>
						<h4><span><u>Jour / Heure</u></span></h4>
						<table>
							<tr>
								<td>
									<input type="text" id="planning-datetime" name="dateheure">											
								</td>
							</tr>
							<tr>
								<td>
									<h4><span><u>Chaque</u></span></h4>
									<table>
										<tr>
											<td>
												<label>
													<input type="checkbox" name="days" class="SchedulerDays" id="planning-days-monday" value="0" /> Lundi
												</label>
												&nbsp; &nbsp;
											</td>
											<td>
												<label>
													<input type="checkbox" name="days" class="SchedulerDays" id="planning-days-thuesday" value="1" /> Mardi
												</label>
												&nbsp; &nbsp;	
											</td>
											<td>													
												<label>
													<input type="checkbox" name="days" class="SchedulerDays" id="planning-days-wednesday" value="2" /> Mercredi
												</label>
												&nbsp; &nbsp;														
											</td>
										</tr>
										<tr>
											<td>
												<label>
													<input type="checkbox" name="days" class="SchedulerDays" id="planning-days-thursday" value="3" /> Jeudi
												</label>
												&nbsp; &nbsp;
											</td>
											<td>											
												<label>
													<input type="checkbox" name="days" class="SchedulerDays" id="planning-days-friday" value="4" /> Vendredi
												</label>
												&nbsp; &nbsp;													
											</td>
											<td>
												<label>
													<input type="checkbox" name="days" class="SchedulerDays" id="planning-days-saturday" value="5" /> Samedi
												</label>
												&nbsp; &nbsp;													
											</td>
										</tr>
										<tr>
											<td>
												<label>
													<input type="checkbox" name="days" class="SchedulerDays" id="planning-days-sunday" value="6" /> Dimanche
												</label>
												&nbsp; &nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>			
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="planning-save"><i class="fa fa-floppy-o"></i><span id="text-button-save"> Save</span></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="planning-delete"><i class="fa fa-trash-o"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal" id=planning-close><i class="fa fa-times"></i> Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$("#modal-planning-data").on("show.bs.modal", function (e) {
		if ($(e.relatedTarget).data("target") == "#modal-planning-data")
		{
			deviceWidget = $(e.relatedTarget).data("widget");
			deviceWidgetType = $(e.relatedTarget).data("widgettype");
			deviceName = $(e.relatedTarget).data("name");
			cmddeviceId = $(e.relatedTarget).data("cmddeviceid");
			$("#planning-save").show(); 
			$("#planning-device-name").html(deviceName);
			$("#planning-cmddeviceId").val(cmddeviceId);
			$('#modal-planning-data .widgetType').hide();
			$('#modal-planning-data #DeleteScheduler').hide();
			$("#modal-planning-data ."+deviceWidget+".widgetType").show();
		}
	}).on("hidden.bs.modal", function (e) { 
		$("#planning-info-slider").val("15");
		$("#planning-slider").val("15");		
		$("#text-button-save").html(" Add");
		$("#active").prop("checked", false);
		$(".Action input").prop("checked", false);
		$(".SchedulerDays").prop("checked", false);
		$("#planning-datetime").val("");
		$("#planning-cmddeviceId").val("");
		$("#planning-device-name").html("");
		$("#planning-planningId").val("");
	})
	$("#planning-datetime").datetimepicker({format: "dd-mm-yyyy hh:ii"});

	$("#planning-save").click(function(event) {
		SaveCalendar();
	});
	$("#planning-delete").click(function(event) {
		DeleteCalendar();
	});
</script>