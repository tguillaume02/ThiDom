<?php
	require_once dirname(__FILE__) .'/Core/Security.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="Description" content="Home automation ThiDom by T.GUILLAUME">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
	<meta name="google" content="notranslate">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-touch-fullscreen" content="yes">
	<meta name="format-detection" content="telephone=no">
	<meta name="HandheldFriendly" content="true" />
	<meta name="theme-color" content="#20B2AA">
	<title>ThiDom</title>
	<link rel="shortcut icon" type="image/png" href="Core/design/common/images/house/House1.png" />
	<link rel="apple-touch-icon" href="Core/design/common/images/house/House1.png">
	<?php
		require_once dirname(__FILE__) .'/css/include_css.php';
	?>

	<link rel="manifest" href="manifest.json">
</head>
<body>
	<header>
		<nav class="nav navbar-default navbar-fixed-top" >
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>	
					<a class="navbar-brand" href="index.php">				
						<img src="Core/pic/home.png" alt="icone" style='height:34px;position:relative;top:-10px'>
					</a>
				</div>
				<div class="collapse navbar-collapse" id="header-navbar" >
					<ul class="nav navbar-nav" role="tablist">		
						<li role="tab" class="active" tabindex="0"><a href="#home" id="home-link"><i class="fas fa-home"></i> Maison</a></li>
						<li role="tab"tabindex="-1"><a href="#calendar" id="calendar-link"><i class="fas fa-calendar-alt"></i> Calendrier</a></li>
						<li role="tab"tabindex="-1" id='analyse-tab' class="dropdown" aria-controls="analyse-panel" ><a id="analyse" href="#analyse" data-toggle="dropdown"><i class="fas fa-wrench"></i> Analyse <span class="caret"></span></a>
							<ul role="tabpanel" id="analyse-panel" tabindex="0" class="dropdown-menu" aria-labelledby="analyse-tab">  	
								<li><a href="#graph" id="graphic-link"><i class="fas fa-chart-area"></i> Graphique</a></li>
								<li><a href="#log" id="log-link"><i class="fas fa-file" aria-hidden="true"></i> Log</a></li>
							</ul>
						</li>
						<li role="tab" id="tools-tab" class="dropdown" aria-controls="tools-panel" tabindex="-1"><a id="tools" href="outils" data-toggle="dropdown"><i class="fas fa-wrench"></i> Outils <span class="caret"></span><span class='count' style='display:none'>1</span></a>
							<ul role="tabpanel" id="tools-panel" tabindex="0" class="dropdown-menu" aria-labelledby="tools-tab">  	
								<li><a href="#manage-plugins" id="manage-plugins-link"> Plugins </a></li>
								<li><a href="#manage-equipement"  id="manage-equipement-link"> Manage Equipement</a></li>
								<li><a href="#manage-room" id="manage-room-link"> Manage Piece</a></li>
								<li><a href="#scenario" id="scenario-link"><i class="fas fa-puzzle-piece" aria-hidden="true"></i> Scenario</a></li>
								<li><a href="#sante" id="sante-link"><i class="fas fa-briefcase-medical" aria-hidden="true"></i> Santé</a></li>
								<li><a href="#update" id="update-link"><i class="fa fa-sync" aria-hidden="true"></i> Mise à jour<span class='count' style='display:none'>1</span></a></li>
								<li><a href="#user" id="user-link"><i class="fas fa-user" aria-hidden="true"></i> Utilisateur</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right" role="tablist">		
						<li role="tab" tabindex="-1"><a href="#"><span id="pencilEdit"><i class="fas fa-pencil-alt"></i></span></a></li>
						<li role="tab" tabindex="-1"><a href="#"><span id="hour"><?php echo date('H:i:s');?></span></a></li>
						<!--<li><img id="icons-weather" src="" ></li>-->
						<li role="tab" tabindex="-1"><a href="logout.php" id="disconnect">Deconnection <i class="fas fa-sign-out-alt"></i></a></li>
					</ul>
				</div>
			</div>
		</nav>
	</header>

	<div class="container-fluid" id="Container">
		<main id="main">
			<div style="display: none;width : 100%" id="error-success-information">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<span id="error-success-detail"></span>
			</div>
			<div class="row row-overflow tab-content">
				<div id="home"  role="tabpanel" class="tab-pane in active">
					<div id="content-home"  data-role="content"  class="col-xs-12 col-lg-12 col-md-12 col-sm-12"></div>
				</div>
				<div id="calendar" role="tabpanel" class="tab-pane ">
					<div id="content-calendar"  data-role="content"  class="col-xs-12 col-lg-12 col-md-12 col-sm-12"></div>
				</div>
				<div id="graph" role="tabpanel" class="tab-pane ">
					<div id="content-graph"  data-role="content"  class="col-xs-12 col-lg-12 col-md-12 col-sm-12"></div>
				</div>
				<div id="log" role="tabpanel" class="tab-pane ">
					<div id="content-log"  data-role="content"  class="col-xs-12 col-lg-12 col-md-12 col-sm-12 ">
						<table id="table-content-log" class="table table-striped dt-responsive nowrap dataTable text-center">
							<thead> 
								<tr> 
									<th class="text-center">Date</th> 
									<th class="text-center">Action</th> 
									<th class="text-center">Message</th> 
								</tr> 
							</thead>
							<tbody id="tbody-content-log">
							</tbody>
						</table>
						<br>
						<button type="button" id="remove-all-log" class="btn btn-danger pull-right absolute"><i class="fas fa-trash"></i> Remove all log</button>
					</div>
				</div>

				<div id="manage-plugins" role="tabpanel" class="tab-pane ">
					<button type="button" id="add-plugins" class="btn-add btn-bottom-right btn-success btn-md pull-right absolute" data-toggle="modal" data-target="#modal-manage-plugins"><i class="fas fa-plus"></i></button>
					<div id="content-manage-plugins"  data-role="content"  class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
						<table id="table-content-plugins" class="table table-striped dataTable dt-responsive nowrap display text-center">
							<thead> 
								<tr>
									<th class="text-center">Id</th> 
									<th class="text-center">Name</th> 
									<th class="text-center"></th>
								</tr> 
							</thead>
							<tbody id="tbody-content-plugins">
							</tbody>
						</table>
					</div>
				</div>

				<div id="manage-equipement" role="tabpanel" class="tab-pane ">
					<button type="button" id="add-device" class="btn-add btn-bottom-right btn-success btn-md pull-right absolute" data-toggle="modal" data-target="#modal-manage-device"><i class="fas fa-plus"></i></button>
					<div id="content-manage-equipement"  data-role="content"  class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
						<table id="table-content-equipement" class="table table-striped dt-responsive nowrap display text-center" style="width:100%">
							<thead> 
								<tr>
									<th class="text-center">Appareil</th> 
									<th class="text-center">Pièce</th>
									<th class="text-center">Visible</th> 
									<th class="text-center"></th>
									<th class="text-center">Device</th> 
									<th class="text-center">Lieux</th> 
									<th class="text-center">ModuleId</th> 
									<th class="text-center">ModuleName</th>
									<th class="text-center">Widget</th> 
									<th class="text-center">Carte</th> 
									<th class="text-center">Raz</th> 
									<th class="text-center">Configuration</th> 
								</tr> 
							</thead>
							<tbody id="tbody-content-equipement">
							</tbody>
						</table>
					</div>
				</div>

				<div id="manage-room" role="tabpanel" class="tab-pane">
					<button id="add-room" type="button" class="btn-add btn-bottom-right btn-success btn-md pull-right absolute" data-toggle="modal" data-target="#modal-manage-room"><i class="fas fa-plus"></i></button>
					<div id="content-manage-room"  data-role="content"  class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
						<table id="table-content-room" class="table table-striped dataTable dt-responsive nowrap display text-center">
							<thead> 
								<tr>
									<th class="text-center">ID</th> 
									<th class="text-center">Icons</th> 
									<th class="text-center">Nom de la pièce</th>
									<!--<th class="text-center">Position</th>-->
									<th class="text-center">Visible</th>
									<th></th>
								</tr> 
							</thead>
							<tbody id="tbody-content-room">
							</tbody>
						</table>
					</div>
				</div>

				<div id="scenario" role="tabpanel" class="tab-pane">
					<div id="content-scenario"  data-role="content" class="col-xs-9 col-lg-9 col-md-9 col-sm-9">
						<div id="blockly-scenario"></div>
					</div>
					<div data-role="content"  class="col-xs-3 col-lg-3 col-md-3 col-sm-3">
						<div class="container-fluid">
							<button type="button" class="btn-add btn-bottom-right btn-success btn-md pull-right" id="scenario-new"><i class="fas fa-plus"></i></button>
						</div>
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-12 col-lg-4 col-md-12 col-sm-12">
									<label>Nom du scenario:</label>
								</div>
								<div class="col-xs-12 col-lg-6 col-md-12 col-sm-12">
									<input type="text" id="scenario-name" class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
								</div>
								<input style="display: none" id="scenario-id">
							</div>
							<div class="row">
								<div class="col-xs-8 col-lg-4 col-md-12 col-sm-12">
									<label>Activé:</label>
								</div>
								<div class="col-xs-3 col-lg-8 col-md-12 col-sm-12">
									<input type="checkbox" id="scenario-active">
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
									<label class="text-center">Scenario:</label>
								</div>
								<div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
									<select id="scenario-list" size="13" class="col-xs-12 col-lg-12 col-md-12 col-sm-12"></select>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-lg-6 col-md-12 col-sm-12">
									<button type="button" class="btn btn-success btn-md col-md-12" id="scenario-save"><i class="fas fa-save"></i> Save</button>
								</div>
								<div class="col-xs-12 col-lg-6 col-md-12 col-sm-12">
									<button type="button" class="btn btn-danger btn-md col-lg-12" id="scenario-delete"><i class="fas fa-trash"></i> Delete</button>
								</div>
							</div>
						</div>
					</div>			
				</div>

				<div id="sante" role="tabpanel" class="tab-pane">
					<table id="table-content-sante" class="table table-striped dataTable dt-responsive nowrap display text-center">
						<thead> 
							<tr> 
								<th class="text-center">Nom</th> 
								<th class="text-center">Status</th> 
							</tr> 
						</thead>
						<tbody id="tbody-content-sante">
						</tbody>
					</table>
				</div>

				<div id="user" role="tabpanel" class="tab-pane">
					<button type="button" id="add-user" class="btn-add btn-success btn-md pull-right absolute" data-toggle="modal" data-target="#modal-manage-user"><i class="fas fa-plus"></i></button>
					<div id="content-user"  data-role="content"  class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
						<table id="table-content-user" class="table table-striped dataTable dt-responsive nowrap display text-center">
							<thead> 
								<tr> 
									<th class="text-center">Id</th> 
									<!-- <th class="text-center">Token</th> -->
									<th class="text-center">Nom</th> 
									<th class="text-center">Last connection</th> 
									<th></th> 
								</tr> 
							</thead>
							<tbody id="tbody-content-user">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</main>
	</div>

	<footer class="navbar-default ">
		<div class="container-fluid">
			<!--<p class="jqm-version"></p>-->
			<p>&copy; 2013 - <?php echo  date('Y');?> Thibault &nbsp;</p>
		</div>
	</footer>


	<xml id="toolbox" style="display: none">
		<category name="Control">
			<block type="controls_if"></block>
			<block type="controlsCalling"></block>
		</category>
		<category name="Logic">
			<block type="logic_compare"></block>
			<block type="logic_operation"></block>
			<block type="logic_states"></block>
			<block type="logic_set"></block>
			<block type="logic_setlevel"></block>
			<block type="logic_setdelayed"></block>
			<block type="logic_LastExecute"></block>
			<block type="logic_LastUpdate"></block>
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
			<block type="text_join"></block>
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
	<?php  
		require_once dirname(__FILE__) .'/js/include_js.php';
		require_once dirname(__FILE__) .'/Core/class/CallAjax.php';
		include_once dirname(__FILE__) .'/js/sse.php';
		require_once('./Desktop/Template_ModalUser.php');
		require_once('./Desktop/Template_ModalAddScheduler.php');
		require_once('./Desktop/Template_ModalEquipement.php'); 
		require_once('./Desktop/Template_ModalConfirmation.php');
		require_once('./Desktop/Template_ModalPlugins.php');
		require_once('./Desktop/Template_ModalRoom.php');
		require_once('./Desktop/Template_ModalEnlarge.php');
		require_once('./Desktop/Template_ModalUpdate.php');
	?>
</body>

<script>
  if ('serviceWorker' in navigator) {
    console.log("Will the service worker register?");
    navigator.serviceWorker.register('../service-worker.js')
      .then(function(reg){
        console.log("Yes, it did.");
      }).catch(function(err) {
        console.log("No it didn't. This happened: ", err)
      });
  }
</script>

</html>


