<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';

$cmd_device_id = '';
$cmd_device_visible = 1;
$cmd_device_history = 0;
$cmd_device_notification = 0;
$cmd_device_Type = "Action";
$cmd_unite = "";
$cmd_RAZ = "";
$cmd_device_Deviceid = "";

$cmdDeviceObject = new CmdDevice();
$cmd_device =  $cmdDeviceObject->byId(getParameter("cmd_device_id"));
$cmd_device = $cmd_device == false ? $cmdDeviceObject : $cmd_device;
$cmd_device_id =  $cmd_device->get_Id();
$cmd_device_Deviceid = $cmd_device->get_DeviceId();
$cmd_device_visible = $cmd_device->get_Visible();
$cmd_device_history = $cmd_device->get_History();
$cmd_device_notification = $cmd_device->get_Notification();
$cmd_device_Type = $cmd_device->get_Type();
$cmd_device_Unite = $cmd_device->get_Unite();
$cmd_device_RAZ = $cmd_device->get_RAZ();
 

CmdDevice::showCommandeListHtml(getParameter("device_id"), true);
?>