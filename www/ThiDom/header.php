<header data-role="header" id="header" data-id="header_gen" class="ui-fixed-hidden" data-position="fixed" data-theme="b">
	<!-- En tete-->
	<!--<div>
		<table id="table_header" style="width:100%">
			<tr>
				<td>
					<span class="ui-btn-text" style="color:white">Extérieur</span>
					<span id="DateTemp_Exterieur" class="ui-btn-b ui-btn-corner-all DateTemp_Exterieur DateTemp" style="color:rgb(160,255,0);text-align:center;margin-left:auto;margin-right:auto;display:none; padding:0" >
					</span>
					<span id="Temp_Exterieur" class="ui-listview ui-btn-b ui-btn-corner-all Temp_Exterieur Temp" style="color:rgb(160,255,0);text-align:center;margin-left:auto;margin-right:auto;display:none; padding:0">
					</span>
					<span id="StatusTemp_Exterieur" class="ui-listview ui-btn-b ui-btn-corner-all StatusTemp_Exterieur StatusTemp" style="color:rgb(160,255,0);text-align:center;margin-left:auto;margin-right:auto;display:none; padding:0;width:40px;height:21px;">
						<img src=""/>
					</span>					
				</td>
				<td id="td_time">
					<span class="ui-btn-text" style="color:white" class="time" id="time"></span>
				</td>
			</tr>
		</table>
	</div>-->
	<!-- NavBar-->
	<nav data-role="navbar" class="nav-glyphish-example ui-bar-b" id="navbar" data-grid="b">
		<ul>
			<!--<li><a href="#">Batiments</a></li> 
			// <li><a href="#" id="serrer">Serre</a></li>-->
			<li><a href="#div_home" id="maison" onclick="LoadMaison();" data-icon="custom"  >Maison</a></li>
			<li><a href="#div_graph" id="Graphique" data-icon="custom" onclick="$(window).resize();">Graphique</a></li>
			<li><a href="#div_admin" id="Parametre"  onclick="init_admin()" data-icon="custom" >Administration</a></li>
		</ul>
	</nav><!-- /navbar --> 
</header>
<!--<br>-->