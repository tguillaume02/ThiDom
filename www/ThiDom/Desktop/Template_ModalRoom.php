<?php include_once dirname(__FILE__) .'/../Core/Security.php';  ?>
<div id="modal-manage-room" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Manage Room <!--<span id="room-name"></span>--></h4>
			</div>

			<form class="white_text form_room form-horizontal" id="form-room">
				<div class="modal-body">
					<fieldset>
						<input id="room-id" type="text" name="Id" style="display: none;"/>
						<input id="room-position" type="text" name="Position" style="display: none;"/>						
						<div class="form-group">
								<label for="room-name" class="col-lg-2 col-md-2 col-sm-3 col-xs-6 control-label">Nom de la pièce: </label>
								<div class="col-lg-8 col-md-8 col-sm-10 col-xs-6">
									<input type="text" class="form-control"  name="Name" id="room-name" placeholder="Nom de la pièce" required>
								</div>
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-2 form-group dropdown">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Icons
							<span class="caret"></span></button>
							<img id="defaultRoomicons" src="" alt="defaultRoomicons" style="width: 50px; height: 50px;">
							<ul class="dropdown-menu" style="overflow: scroll;max-height: 408px;">
								<li>
									<a href='#' onclick="$('#CustomRoomIcons').val('')" title="">Default</a>
								</li>
							<?php
								if ($handle = opendir('Core/pic/Room/')) {
									while (false !== ($entry = readdir($handle)))
									{
										if ($entry != "." && $entry != ".." )
										{
											$filename = substr($entry, 0, strrpos($entry, "."));
											echo "<li>
													<a href='#' onclick=\"$('#CustomRoomIcons').val('Core/pic/Room/$filename');
														$('#defaultRoomicons').attr('src','Core/pic/Room/$filename');
														$('#defaultRoomicons').show();\" title=\"$entry\">
														<img class='img-circle img_btn_device rounded-circle' src='Core/pic/Room/$entry'>$filename</a>
												</li>";
										}
									}
									closedir($handle);
								}
							?>
							</ul>
							<input id="CustomRoomIcons" name="Icons" type="text" class="form-control" style="display: none">
						</div>
						<div class="form-group">
							<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 control-label">  
								<label class="btn btn-success">
									<input type="checkbox" name="Visible" id="room-visible" value="1"> Visible
								</label>
							</div>
						</div>
					</fieldset>
				</div>
			</form>

			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="room-save">
					<i class="far fa-save"></i> Save
				</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="room-close">
					<i class="fas fa-times"></i> Close
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	$("#modal-manage-room").on("hidden.bs.modal", function (e) { 
  		$("#form-room .form-control").each(function() {
			$(this).val('');
		})		
		$("#modal-manage-room #room-visible").prop( "checked", false);
	});

	$("#room-save").click(function(event) {
		bFormValidate = $('form#form-room')[0].reportValidity();
		if (bFormValidate)
		{
			data = $("#form-room").serialize()
			SaveLieux(data);
   			$('#modal-manage-room').modal('toggle')
		}
	});

</script>