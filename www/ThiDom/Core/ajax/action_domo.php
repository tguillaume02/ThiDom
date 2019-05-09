<?php

if (isset($_REQUEST["hash"]))
{
	$hash = $_REQUEST["hash"];
}

require dirname(__FILE__)  .'/../Security.php'; 
require_once dirname(__FILE__) .'/../ListRequire.php';


$val_info = "";
$device_id = isset($_REQUEST["Device_Id"]) ? $_REQUEST["Device_Id"] : '';
$cmdDevice_Id = isset($_REQUEST["CmdDevice_Id"]) ? $_REQUEST["CmdDevice_Id"] : '';
$type = isset($_REQUEST["Type"]) ? $_REQUEST["Type"] : '';
$role = isset($_REQUEST["Role"]) ? $_REQUEST["Role"] : '';
$defaultVal =isset($_REQUEST["Value"]) ? $_REQUEST["Value"] : '';
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';

	
if ($type == "" && $device_id != "")
{
	$type = Device::byId($device_id)->get_Type_Name();	
}

if ($type == "interact")
{
	$reply = Interact::reply($defaultVal);
	echo $reply;
}
else
{
	$moduleType = Module::GetModuleTypeByDevice($device_id)->get_ModuleName();
	echo $moduleType::action($device_id, $cmdDevice_Id, $type, $role, $defaultVal, $mode);
}
?>
