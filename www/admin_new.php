<div data-role="page" class="type-home conteneur" id="div_admin" data-theme="a">
	<div data-role="header" id="header" data-id="header_gen" class="ui-fixed-hidden" data-position="fixed" data-theme="b">
		<div>
			<table id="table_header" style="width:100%">
				<tr>
					<td>
						<a href="#" id="btn_back" class="ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-left ui-corner-all ui-btn-b" onclick="back();" data-icon="custom" >Back</a>
					</td>

					<td>
						<span>Administration</span>
					</td>
				</tr>
			</table>
			<!-- NavBar-->
			<div data-role="navbar" class="ui-bar-b" id="navbar" data-grid="d">
				<ul>
					<li><a href="#App" onclick="$('.admin').hide();$('#App').show();LoadEquipement();" style="white-space: normal !important;">Equipement</a></li>
					<li><a href="#piece" onclick="$('.admin').hide();$('#piece').show();Load_Lieux();" style="white-space: normal !important;">Pièce</a></li>
					<li><a href="#user" onclick="$('.admin').hide();$('#user').show();Load_User();"  style="white-space: normal !important;">Utilisateur</a></li>
					<li><a href="#scenario" onclick="$('.admin').hide();$('#scenario').show();LoadScenario();" style="white-space: normal !important;" >Scenario</a></li>
					<li><a href="#log" onclick="$('.admin').hide();$('#log').show();LoadLog()" style="white-space: normal !important;" >Log</a></li>
				</ul>
			</div>
		</div>
	</div>
	<br>
	<div id="App" class="Add_app admin">	
		<div id="div_AdminEquipement">
			<table style="width: 100%;">
				<tbody style="">
					<tr>
						<td>
	       					<div class="btnsmall" data-i18n="Edit" data-theme="a" id="rebootLivebox" style="width: 80%;"  onclick="EditEquipement();">Ajouter un équipement </div>
					
						</td>
						<td>
					        <div class="btnsmall" data-i18n="Edit" data-theme="a" id="reload" style="width: 80%;" onclick="LoadPlugins();">Ajouter un plugins </div>
				        </td>
					</tr>
				</tbody>
			</table>
			<br>
			<!--<input type="button" value="Ajouter un nouvel équipement" onclick="EditEquipement();"/><br>-->
			<table id="AdminEquipement">
				<thead>
					<tr>
						<th>ID</th>
						<th>cmd device. ID</th>
						<th>App. ID</th>
						<th>Carte ID</th>
						<th>Type ID</th>
						<th>Lieux ID</th>
						<th>RAZ</th>
						<th>Visible</th>
						<th>Pièce</th>
						<th>Appareil </th>
						<th>Type</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				
				<tbody id="tbody_AdminEquipement">
				</tbody>
			</table>
		</div>		
			<br>
			<table id="AdminPlugins">
			</table>
	</div>
	
	
	<div id="piece" class="Add_piece admin">	
		<input type="button" value="Ajouter une nouvelle pièce" onclick="EditPiece();"/><br>
		<table id="AdminPiece">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nom de la pièce</th>
					<th> </th>
					<th> </th>
				</tr>
			</thead>
			
			<tbody id="tbody_AdminPiece">
			</tbody>
		</table>		
	</div>
	
	
	<div id="user" class="Add_user admin">	
		<table id="AdminUser">
			<thead>
				<tr>
					<th>ID</th>
					<th>Pass</th>
					<th>Nom</th>
					<th>Background</th>
					<th> </th>
					<th> </th>
				</tr>
			</thead>
			
			<tbody id="tbody_AdminUser">
			</tbody>
		</table>		
	</div>

	<div id="log" class="Add_log admin">	
		<table id="AdminLog">
			<thead>
				<tr>
					<th>ID</th>
					<!--<th>pièce</th>
					<th>Nom de l'appareil </th>
					<th>App. ID</th>
					<th>Carte ID</th>
					<th>Type</th>-->
					<th>Date</th>
					<th>Action</th>
					<th>Message</th>
				</tr>
			</thead>
			
			<tbody id="tbody_AdminLog">
			</tbody>
		</table>		
	</div>

	<div id="scenario" class="Add_scenario admin">	
		<table >
			<tr>
				<td>
					<div id="BlocklyScenario">
						<!--<xml id="toolbox" style="display: none">
							<block type="controls_if"></block>
							<block type="controls_if">
								<mutation else="1"></mutation>
							</block>
							<block type="controls_if">
								<mutation elseif="1" else="1"></mutation>
							</block>
							<block type="logic_compare"></block>
							<block type="logic_operation"></block>
							<block type="logic_boolean_on_off"></block>
							<block type="variables_set"></block>
							<block type="variables_get"></block>
							<block type="math_number"></block>
						</xml>-->
						<xml id="toolbox" style="display: none">
							<category name="Control">
								<block type="controls_if"></block>
								<block type="controls_ifelseif"></block>
							</category>
							<category name="Logic">
								<block type="logic_compare"></block>
								<block type="logic_operation"></block>
								<block type="logic_states"></block>
								<block type="logic_set"></block>
								<block type="logic_setlevel"></block>
								<block type="logic_setdelayed"></block>
								<block type="logic_Execute"></block>
								<block type="math_number"></block>
							</category>
							<category name="Time">
								<block type="logic_timeofday"></block>
								<block type="logic_weekday"></block>
								<block type="logic_timevalue"></block>
								<block type="logic_sunrisesunset"></block>
							</category>
							<category name="Messages">
								<block type="send_notification"></block>
								<block type="send_email"></block>
								<block type="text"></block>
							</category>
							<!--<category name="Security">
								<block type="security_status"></block>
							</category>-->
							<category name="Devices">   		
								<category name="Switches">
									<block type="switchvariablesAF"></block>
									<block type="switchvariablesGL"></block>
									<block type="switchvariablesMR"></block>
									<block type="switchvariablesSZ"></block>
								</category>   		
								<category name="Temperature">
									<block type="temperaturevariables"></block>
								</category>	   		   		
								<!--<category name="Humidity">
									<block type="humidityvariables"></block>
								</category>	
								<category name="Barometer">
									<block type="barometervariables"></block>
								</category>	
								<category name="Weather">
									<block type="weathervariables"></block>
								</category>
								<category name="Utility">
									<block type="utilityvariables"></block>
								</category>
								<category name="Scenes/groups">
									<block type="scenevariables"></block>
									<block type="groupvariables"></block>
								</category>-->
							</category>	
						</xml>
					</div>
				</td>
				<td style="width:200px;/*padding-right:20px*/" valign="top">
					<label class="white_text">Nom du Scenario: </label><br>
					<input type="input" id="ScenarioName" name="ScenarioName">
					<p>
					<table style="width:68%">
						<tr>
							<td>
								<label for="eventActive" class="white_text">Activé:</label> 
							</td>
							<td>
								<input type="checkbox" id="ScenarioActive" name="ScenarioActive" style="margin: 0 0 0 0 !important; position: relative;">
							</td>
						</tr>
					</table>						
					</p><p>
					<table>
						<tr>
							<td>
								<a class="btnsmall" style=" font-weight: 100; " href="javascript:SaveScenario()">Sauvegarder</a>
							</td>
							<td>
								<a class="btnsmall" style=" font-weight: 100; " href="javascript:NewScenario()">Nouveau</a>
							</td>
						</tr>
					</table>					
				</p><p>
				<label for="savedScenarios" class="white_text">Scenario: </label>
				<select size="10" id="savedScenarios" name="savedScenarios" onchange="LoadScenarioXML(this.value);">
				</select>
				<br><a class="btnsmall" style="width: 80%;font-weight: 100; " href="javascript:delete_scenario()">Supprimer</a>
				<br>
				<br></p><div id="blockId" style="display:none;"></div>
			</td>
		</tr>
	</table>
</div>

<!-- POPUP POUR LA PARTIE EQUIPEMENT   -->

<div data-role="popup" id="PopUpEditEquipement" data-overlay-theme="b" data-theme="b" data-corners="true" class="popup">
	<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
	<div class="PopUp_title">Ajouter un appareil</div>
	<br>
	<div id="ManageEquipement" >
		<input type="text" class="form-control" id="ID_EtatIO" style="display:none" data-mini="true" >
		<input type="text" class="form-control" id="cmd_device_ID" style="display:none" data-mini="true" >		
		<select id="add_app_name_piece">
			<option value="" >Nom de la pièce: </option>
		</select>
		<br>
		<select id="add_app_type_app">
			<option value="" >Type d'appareil:  </option>
		</select>
		<br>
		<input type="text" class="form-control" id="app_name" style="background-color:default" data-mini="true" placeholder="Nom de l'appareil (qui apparaitras sur le site):">
		<br>
		<input type="text" class="form-control" id="app_id" style="background-color:default"  data-mini="true" placeholder="Identifiant de l'appareil:">
		<br>
		<input type="text" class="form-control" id="carte_id" style="background-color:default"  data-mini="true" placeholder="ID de la carte:">
		<br>
		<input type="time" class="form-control" id="RAZ_value" style="background-color:default"  data-mini="true" placeholder="Remise à zero apres: HH:MM">
		<br>
		<input type="checkbox" name="visible" value="Visible" id="Visible_app" class="custom" style="display:none">
		<label for="Visible_app">Visible</label>
		<br>
		<div>
			<table class="tb_validate" >
				<tr>
					<td>
						<input type="button" value="Annuler" onclick="annule()"/>
					</td>
					<td>
						&nbsp;
					</td>
					<td>
						<input type="button"  class="validate" value="Ajouter" onclick="valid('add_app')" />
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<!-- POPUP POUR LA PARTIE PLUGINS   -->

<div data-role="popup" id="PopUpEditPlugins" data-overlay-theme="b" data-theme="b" data-corners="true" class="popup">
	<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
	<div class="PopUp_title">Ajouter une plugins</div>
	<br>
	<div id="ManagePlugins" >		
		<select id="add_plugins_name_piece">
			<option value="" >Nom de la pièce: </option>
		</select>
		<select id="Liste_plugins">
			<option value="" >Selectionner plugins:  </option>
		</select>
		<!--<input type="checkbox" name="visible" value="Visible" id="Visible_plugins" class="custom" style="display:none">
		<label for="Visible_plugins">Visible</label>-->

		<br>
		<div>
			<table class="tb_validate" >
				<tr>
					<td>
						<input type="button" value="Annuler" onclick="annule()"/>
					</td>
					<td>
						&nbsp;
					</td>
					<td>
						<input type="button"  class="validate" value="Ajouter" onclick="valid('add_plugins')" />
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<!-- POPUP POUR LA PARTIE PIECE   -->

<div data-role="popup" id="PopUpEditPiece" data-overlay-theme="b" data-theme="b" data-corners="true" class="popup">
	<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
	<div class="PopUp_title">Ajouter une Pièce</div>
	<br>
	<div id="ManagePiece" >
		<input type="text" class="form-control" id="ID_Piece" style="display:none" data-mini="true" >
		<input type="text" class="form-control" id="add_piece_name_piece" style="background-color:default" data-mini="true" placeholder="Nom de la pièce:">
		<input type="checkbox" name="visible" value="Visible" id="Visible_piece" class="custom" style="display:none">
		<label for="Visible_piece">Visible</label>

		<br>
		<div>
			<table class="tb_validate" >
				<tr>
					<td>
						<input type="button" value="Annuler" onclick="annule()"/>
					</td>
					<td>
						&nbsp;
					</td>
					<td>
						<input type="button"  class="validate" value="Ajouter" onclick="valid('add_piece')" />
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<!-- POPUP POUR LA PARTIE UTILISATEUR   -->

<div data-role="popup" id="PopUpEditUser" data-overlay-theme="b" data-theme="b" data-corners="true" class="popup">
	<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
	<div class="PopUp_title">Ajouter un Utilisateur</div>
	<br>
	<div id="ManageUser" >
		<input type="text" class="form-control" id="ID_User" style="display:none" data-mini="true" >
		<input type="text" class="form-control" id="add_User_name_User" style="background-color:default" data-mini="true" placeholder="Nom de l'utilisateur:">
		<input type="password" class="form-control" id="add_User_pass" style="background-color:default" data-mini="true" placeholder="password">
		<input type="password" class="form-control" id="add_User_pass2" style="background-color:default" data-mini="true" placeholder="verification password">
		<input type="file" class="form-control" id="add_User_background" accept="image/*" name="user_background" style="background-color:default;display:none;" data-mini="true" onchange="file_selected()">
		<!--<input type="text" readonly="readonly" id="file_background" placeholder="Fond d'ecran" onclick="select_background();"/>-->
		<br>
		<div>
			<table class="tb_validate" >
				<tr>
					<td>
						<input type="button" value="Annuler" onclick="annule()"/>
					</td>
					<td>
						&nbsp;
					</td>
					<td>
						<input type="button"  class="validate" value="Ajouter" onclick="valid('add_user')" />
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

</div>