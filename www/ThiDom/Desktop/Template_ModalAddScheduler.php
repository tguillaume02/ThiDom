<?php include_once dirname(__FILE__) .'/../Core/Security.php';  ?>
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
						<div class="form-group">
							<input id="planning-cmddeviceId" type="text" name="cmddeviceId" style="display: none;"/>
							<input id="planning-deviceId" type="text" name="deviceId" style="display: none;"/>
							<input id="planning-planningId" type="text" name="planningId" style="display: none;"/>
						</div>
						
						<div class="form-group">
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
						</div>
						<hr>

						<h4><span><u>Action</u></span></h4>
						<table class="WidgetContent" style="width:100%">
							<tbody>
								<tr>
									<td id="DisplayDesign" class="widgetType"></td>
									
									<!-- FOR ON / OFF --><!--
									<td class="Binary Relay widgetType">
										<label class="btn btn-success">
											<input type="radio" name="commande" id="action-on" value="1" />On
										</label>
										<label class="btn btn-danger">
											<input type="radio" name="commande" id="action-off" value="0" />Off
										</label>
									</td>-->

									<!-- FOR COLOR-->
									<td class="Color RGB widgetType">
										<input type="color" name="commande" id="planning-color">
									</td>

									<!-- FOR SLIDER  -->
									<!--<td class="Slider Thermostat Dimmer widgetType">
										<div class="div_btn_device Corner" style="background-color: transparent; user-select: none; cursor: default;">
											<div id="planning-info-slider" class="img-circle img_btn_device circle" name="commande" readonly value="15">
										</div>
									</td>
									<td class="Slider Thermostat Dimmer widgetType" style="width: 80%;padding: 15px;">
										<input class="bar"  name="commande" value="0" type="range" step="0.5" min="15" max="30" id="planning-slider" oninput="$('#planning-info-slider').html(this.value); $('#planning-info-slider').attr('value',this.value)">
									</td>-->

									<!-- FOR TEXT-->
									<td class="Text Numeric widgetType">
										<input type="text" name="commande" id="planning-text" >
									</td>
								</tr>
							</tbody>
						</table>
						<hr>						
						
						<div class="form-group">
							<label style="" class="col-xs-4 col-sm-3 control-label">Jour / Heure</label>
							<div class="col-xs-7 col-sm-3 col-md-5">
								<input type="text" id="planning-datetime" name="dateheure" class="form-control">
							</div>
						</div>

						<div class="form-group">
							<label style="" class="col-xs-4 col-sm-3 control-label">Répéter tous les</label>
							<div class="col-xs-3 col-sm-3 col-md-2">
								<input class="form-control" name="eachTime">
							</div>
							<div class="col-xs-5 col-sm-5 col-md-5">
								<select class="form-control" name="eachPeriod">
									<option value="minutes">Minutes(s)</option>
									<option value="hours">Heure(s)</option>
									<option value="days" selected="">Jour(s)</option>
									<option value="month">Mois</option>
									<option value="years">Année(s)</option>
								</select>
							</div>
						</div>
						

						<!--<h4><span><u>Jour / Heure</u></span></h4>-->
						<table>
							<!--<tr>
								<td>
									<input type="text" id="planning-datetime" name="dateheure" class="form-control">											
								</td>
							</tr>-->
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
					<button type="button" class="btn btn-success" data-dismiss="modal" id="planning-save"><i class="far fa-save"></i><span id="text-button-save"> Save</span></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="planning-delete"><i class="fas fa-trash"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal" id=planning-close><i class="fas fa-times"></i> Close</button>
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
			//deviceConf = $(e.relatedTarget).data("conf");
			deviceName = $(e.relatedTarget).data("name");
			cmddeviceId = $(e.relatedTarget).data("cmddeviceid");
			deviceId = $(e.relatedTarget).data("deviceid");

			if (deviceWidgetType != "")
			{
				widgetDesign_url = "Core/widgetDesign/"+deviceWidgetType+"/"+deviceWidgetType+"DesignScheduler.php";
			}
			else if (deviceWidget != "")
			{
				widgetDesign_url = "Core/plugins/"+deviceWidget+"/Desktop/"+deviceWidget+"Scheduler.php";
			}

			
			var requestGetDesign = $.ajax({
				type: 'POST',
				url: widgetDesign_url,
				data: {
					mode: 'Schedule',
					deviceId: deviceId,
					deviceWidgetType: deviceWidgetType
				}
			});

			requestGetDesign.done(function (data) {
				$("#DisplayDesign").empty();
				$("#DisplayDesign").append(data);
				$("#DisplayDesign").show();
			});

			 		
			/*$("#planning-info-slider").val(deviceConf.min);
			$("#planning-info-slider").html(deviceConf.min);	
			$("#planning-slider").val(deviceConf.min);		
			$("#planning-slider").attr("min",deviceConf.min)
			$("#planning-slider").attr("max",deviceConf.max)	*/

			$("#planning-save").show(); 
			$("#planning-device-name").html(deviceName);
			$("#planning-deviceId").val(deviceId);
			$("#planning-cmddeviceId").val(cmddeviceId);
			$('#modal-planning-data .widgetType').hide();
			$('#modal-planning-data #DeleteScheduler').hide();
			$("#modal-planning-data ."+deviceWidgetType+".widgetType").show();
		}
	}).on("hidden.bs.modal", function (e)
	{ 		
		/*$("#planning-info-slider").val(deviceConf.min);
		$("#planning-info-slider").html(deviceConf.min);	
		$("#planning-slider").val(deviceConf.min);		
		$("#planning-slider").attr("min",deviceConf.min)
		$("#planning-slider").attr("max",deviceConf.max)	*/

		$("#text-button-save").html(" Add");
		$("#active").prop("checked", false);
		$(".Action input").prop("checked", false);
		$(".SchedulerDays").prop("checked", false);
		$("#planning-datetime").val("");
		$("#planning-deviceId").val();
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