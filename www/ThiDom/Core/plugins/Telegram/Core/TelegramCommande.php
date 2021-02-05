<?php
include_once dirname(__FILE__) .'/../../../Security.php'; 
include_once dirname(__FILE__) .'/../../../ListRequire.php';

#CmdDevice::showCommandeListHtml($_POST['device_id'], "true", "ChannelId", "false", "true");
CmdDevice::showCommandeListHtml($_POST['device_id'], "", "", "false", "true");
?>
