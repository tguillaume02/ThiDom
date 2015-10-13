<?php
include_once('Security.php'); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html> 
<head>
	<meta  charset="utf-8" HTTP-EQUIV="Pragma" name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
	<meta name="google" value="notranslate">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="HandheldFriendly" content="true" />
	<title>My home</title>
	<link rel='stylesheet' href='font/pacifico/stylesheet.css'>
	<link rel='stylesheet' href='font/nunito/stylesheet.css'>
	<link rel='stylesheet' href='font/sofia/stylesheet.css'>
	<link rel='stylesheet' href='font/digital/stylesheet.css'>
	<link rel="icon shortcut" type="image/png" href="design/common/images/house/House1.png" />
	<!--<script type="text/javascript" src="temperaturedata.php"></script>-->
		<?php 		$delais = 60 * 60 * 24 * 7;   // Une semaine
		header("Pragma: public");
			//header("Cache-Control: maxage=".$delais);
		header("Cache-Control: maxage=0");
		header("Expires: " . gmdate('D, d M Y H:i:s', time() + $delais) . " GMT");
		include_once('css/include_css.php');
		include_once('js/include_js.php');
		include_once ('js/sse.php');
			//include("function_recup_donnees.php");
		?>
		
	</head>
	<body>
		<div id="colorpicker" class="ColorPicker" ></div>
		<div data-role="page" class="type-home conteneur" id="div_home">
			<?php include("header.php"); ?>
			<!--<iframe scrolling="no" frameborder="0" allowTransparency="true" style="padding-top: 0.7%;" src="http://www.deezer.com/plugins/player?autoplay=false&playlist=true&width=200&height=600&cover=true&type=playlist&id=880865271&title=&format=vertical&app_id=undefined" width="200" height="600" style="position:absolute" id="deezer_plugin"></iframe>-->
			<div data-role="content" id="content_home"></div><!--/content-->	
			
			<?php include("footer.php");?>
		</div>
		
		<?php include("admin_new.php"); ?>

		<div data-role="page" class="type-home conteneur" id="div_graph">
			<?php include("header.php"); ?>
			
			<div data-role="content" id="content_graph"></div><!--/content-->	
			
			<?php include("footer.php");?>
		</div>

		<div data-role="page" class="type-home conteneur" id="div_planning">
			<?php 
			include("header.php");			
			include("Planning.php");
			include("footer.php");
			?>
		</div>
		<audio preload="auto" id="son_sonette">
			<source src="son/sonette.mp3" type="audio/mp3">
		</audio>
		<audio preload="auto" id="son_BAL">
			<source src="son/BAL.mp3" type="audio/mp3">
		</audio>
		
		</body>
		</html>


		<script>
		function load_windows()
		{
		addEvent(window , "LoadMaison", LoadMaison());
		addEvent(window , "LoadGraph", LoadGraph());
		addEvent(window , "init_admin", init_admin());
		addEvent(window , "LoadScenario", LoadScenario());
		// addEvent(window , "InitTablePlanning", InitTablePlanning());
	}

	window.onload=function(){load_windows()};
	</script>