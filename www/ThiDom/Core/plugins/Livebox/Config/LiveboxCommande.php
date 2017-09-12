<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';

$Id = $_POST["device_id"]; 
    
CmdDevice::showCommandeListHtml($Id);


?>