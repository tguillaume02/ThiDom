<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';
	
CmdDevice::showCommandeListHtml($_POST['device_id']);
?>
