<?php
	include_once('Security.php');
	$delais = 60 * 60 * 24 * 7;   // Une semaine
	//$widget = $_POST['widget'];
?>
	<div id="div_planning">
		<div class="white_text text_center" id="title_planning">Planning <span id="Device_name"></span> <span id="Lieux_name"></span></div><br>
<!--		<table data-role="table" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a" id="table_planning">-->
		<table id="table_planning">
			<thead>
				   <tr class="ui-bar-d">
					 <th data-priority="persist">ID</th>
					 <th data-priority="persist">Activé</th>
					 <th data-priority="persist">Commande</th>
					 <th data-priority="persist">Heure</th>
					 <th data-priority="persist">Jour</th>
				   </tr>
			 </thead>
			 <tbody id="tbody_planning">
			 </tbody>
		</table>
		<br>
		<table>
			<tr>
				<td>
					<div class="btnsmall" id="DeletePlanning" data-i18n="Edit" data-theme="a">Supprimer</div>
				</td>
				<td>
					<div class="btnsmall" id="ModifyPlanning" data-i18n="Edit" data-theme="a">Modifier</div>
				</td>
			</tr>
		</table>
		
		<form class="white_text form_planning" id="form_planning">
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup"  data-iconpos="right" style="display:none">
					<label for="id_device" id="label_activate"></label>
					<input type="text" name="id_device" id="id_device">
					<input type="text" name="id_planning" id="id_planning">
				</fieldset>
				<fieldset data-role="controlgroup"  data-iconpos="right" style="display:none">
					<label for="mode" id="label_activate"></label>
					<input type="text" name="mode" id="mode">
				</fieldset>
				<fieldset data-role="controlgroup"  data-iconpos="right">
					<legend>Activé :</legend> 				
					<input type="checkbox" name="Check_active" id="Check_active">
					<label for="Check_active" id="label_activate">Activé</label>
				</fieldset><br>
				<fieldset data-role="controlgroup" data-type="horizontal" id="fieldset_slider">
					<legend>Commande :</legend>
						<table>
							<tr>
								<td>									
									<label for="commande" class="circle" id="info_slider_planning" >10</label>
								</td>
								<td>
									<input name="commande" class="slider_numeric" id="slider_planning"  min="10" max="35" step="0.5" type="hidden">	
								</td>
							</tr>
						</table>
				</fieldset><br>
				<fieldset data-role="controlgroup" data-type="horizontal" id="fieldset_on_off">
					<legend>Commande :</legend>
							<input type="radio" name="commande" id="Action_On" value="on">
							<label for="Action_On">On</label>
							<input type="radio" name="commande" id="Action_Off" value="off">
							<label for="Action_Off">Off</label>
				</fieldset><br>
				<fieldset data-role="controlgroup" data-type="horizontal">
					<legend>Jour :</legend>
					<label for="jour_Lundi">Lundi</label>
					<input type="checkbox" name="Lundi" id="jour_Lundi" class="custom" />
					<label for="jour_Mardi">Mardi</label>
					<input type="checkbox" name="Mardi" id="jour_Mardi" class="custom" />
					<label for="jour_Mercredi">Mercredi</label>
					<input type="checkbox" name="Mercredi" id="jour_Mercredi" class="custom" />
					<label for="jour_Jeudi">Jeudi</label>
					<input type="checkbox" name="Jeudi" id="jour_Jeudi" class="custom" />
					<label for="jour_Vendredi">Vendredi</label>
					<input type="checkbox" name="Vendredi" id="jour_Vendredi" class="custom" />
					<label for="jour_Samedi">Samedi</label>
					<input type="checkbox" name="Samedi" id="jour_Samedi" class="custom" />
					<label for="jour_Dimanche">Dimanche</label>
					<input type="checkbox" name="Dimanche" id="jour_Dimanche" class="custom" />
				</fieldset><br>
				<fieldset data-role="controlgroup">
					<legend>Heure :</legend>
					<label for="Hours_planning" placeholder="00:00"></label>
					<input type="time" id="Hours_planning" name="hours" placeholder="00:00"  value="--:--:--" data-mini="true" data-inline="true" class="ui-btn-corner-all" style="border-radius: inherit;width: auto!important;">
				</fieldset>
			</div>
			
		<table id="tb_valid_planning">
			<tr>
				<td>					
					<div class="btnsmall" id="SavePlanning" data-i18n="Edit" data-theme="a">Valider</div>
				</td>
			</tr>
		</table>
		</form>
	<div>	
<br>		
<br>