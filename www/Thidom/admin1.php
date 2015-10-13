<div data-role="page" class="type-home conteneur" id="div_admin" data-theme="a">
	<div data-role="header" id="header" data-id="header_gen" class="ui-fixed-hidden" data-position="fixed" data-theme="b">
		<div>
			<table id="table_header" style="width:100%">
				<tr>
					<td>
						<a href="#" id="btn_back" class="ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-left ui-corner-all ui-btn-b" onclick="window.history.back();LoadMaison();" data-icon="custom" >Back</a>
					</td>
					<td>
						<span>Administration</span>
					</td>
				</tr>
			</table>
			<!-- NavBar-->
			<div data-role="navbar" class="ui-bar-b" id="navbar" data-grid="c">
				<ul>
					<li><a href="#Add_app" onclick="$('.admin').hide();$('.Add_app').show();" style="white-space: normal !important;">Ajouter un appareil</a></li>
					<li><a href="#delete_app" onclick="$('.admin').hide();$('.delete_app').show();" style="white-space: normal !important;">Supprimer un appareil</a></li>
					<li><a href="#Add_piece" onclick="$('.admin').hide();$('.Add_piece').show();"  style="white-space: normal !important;">Ajouter une piéce </a></li>
					<li><a href="#delete_piece" onclick="$('.admin').hide();$('.delete_piece').show();" style="white-space: normal !important;" >Supprimer une piéce </a></li>
				</ul>
			</div>
		</div>
	</div>
	<br>
	<div data-role="content" id="Add_app" class="Add_app admin">	
		<div class="white_text">Ajouter un appareil</div>
		<br>
		<div>
			<select id="add_app_name_piece">
				<option value="" >Nom de la pièce: </option>
			</select>
			<br>
			<input type="text" class="form-control" id="app_name" style="background-color:default" data-mini="true" placeholder="Nom de l'appareil (qui apparaitras sur le site):">
			<br>
			<input type="text" class="form-control" id="app_id" style="background-color:default"  data-mini="true" placeholder="Identifiant de l'appareil:">
			<br>
			<input type="text" class="form-control" id="carte_id" style="background-color:default"  data-mini="true" placeholder="ID de la carte:">
			<br>
			<select id="add_app_type_app">
				<option value="" >Type d'appareil:  </option>
			</select>
			<br>
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
	
			
	<div data-role="content" id="delete_app" class="delete_app admin">	
		<div class="white_text">Supprimer un appareil </div>
		<br>
		<select id="delete_app_name_piece">
				<option value="" default selected>Nom de la pièce: </option>
		</select>
			<br>
		<select id="delete_app_name_app">
				<option value="" default selected>Nom de l'appareil:  </option>
		</select>
			<br>
			<table class="tb_validate" >
				<tr>
					<td>
						<input type="button" value="Annuler" onclick="annule()"/>
					</td>
					<td>
						&nbsp;
					</td>
					<td>
						<input type="button" data_theme="d" class="validate" value="Valider" onclick="valid('delete_app')"  />
					</td>
				</tr>
			</table>
	</div>
					
	<div data-role="content" id="Add_piece" class="Add_piece admin">	
		<div class="white_text">Ajouter une piéce </div>
		<br>
		<input type="text" id="name_piece" placeholder="Saisir le nom de la piéce à ajouter:">
		<br>
		<table class="tb_validate" >
			<tr>
				<td>
					<input type="button" value="Annuler"  onclick="annule()"/>
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
					
	<div data-role="content" id="delete_piece" class="delete_piece admin">	
		<div class="white_text">Supprimer une piéce </div>
		<br>
		<select id="delete_piece_name_piece">
				<option value="" default selected>Nom de la pièce: </option>
		</select>
			<br>
			<table class="tb_validate" >
				<tr>
					<td>
						<input type="button" value="Annuler" onclick="annule()"/>
					</td>
					<td>
						&nbsp;
					</td>
					<td>
						<input type="button"  class="validate" value="Valider" onclick="valid('delete_piece')"  />
					</td>
				</tr>
			</table>
	</div>
		
</div>