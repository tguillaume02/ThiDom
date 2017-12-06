<?php
include_once dirname(__FILE__) .'/../Security.php'; 
include_once dirname(__FILE__) .'/../ListRequire.php';
	
CmdDevice::showCommandeListHtml($_POST['device_id']);
?>
