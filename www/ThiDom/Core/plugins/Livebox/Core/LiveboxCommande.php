<?php
include_once dirname(__FILE__) .'/../../../Security.php'; 
include_once dirname(__FILE__) .'/../../../ListRequire.php';
	
CmdDevice::showCommandeListHtml(getParameter('device_id'));
?>
