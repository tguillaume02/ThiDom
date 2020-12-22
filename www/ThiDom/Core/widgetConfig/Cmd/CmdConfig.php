<?php
include_once dirname(__FILE__) .'/../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../Core/ListRequire.php';

$cmd_device_id = getParameter("cmd_device_id");
$Device_id = getParameter("device_id");

$DeviceData =  Device::byId($Device_id);
$cmdDeviceData =  CmdDevice::byDevice_Id($cmd_device_id);
$cmdDeviceToAttachArray = CmdDevice::GetAllCmdDeviceWithLieux();
$json_data = "";
$cmdValue = "";

if ($cmdDeviceData)
{
    var_dump($cmdDeviceData);
    foreach($cmdDeviceData as $value)
    {
        var_dump($value);
        $request =  $value->get_Request();
    }
    $json_data = json_decode($request, true);
    $cmdValue = $json_data["cmd"]["value"];
}

$tplWidgetConfig = '<div class="form-group grouplistType cmdEvent">
<label for="list-cmd" class="col-sm-5 col-xs-6 col-md-5 col-lg-2 control-label">Commande</label>
<div class="control-group">
    <div class="controls form-inline">
        <select id="list-cmdDevice-cmd" name="cmdDeviceId" cmdid="'.$cmd_device_id.'" class="form-control">
        <option value="">-</option>';

foreach($cmdDeviceToAttachArray as $donneesCmdDevice)
{								
    $isDefined = "";
    if ($json_data != "" && $json_data["cmd"]["cmdDeviceId"] == $donneesCmdDevice["Id"])
    {
        $isDefined = "selected";
    }
    $tplWidgetConfig.= "<option value='" . $donneesCmdDevice["Id"] . "' ".$isDefined.">" . $donneesCmdDevice["Nom"] . " - ". $donneesCmdDevice["LieuxNom"] ."</option>"; 
}				

$tplWidgetConfig .= ' </select>
<select id="list-cmdDevice-cmd-valueEtat" cmdid="'.$cmd_device_id.'" class="form-control">
    <option value="value">Value =</option>
    <option value="Etat">Etat = </option>
</select>
<input id="Data-valueEtat" name="Value" cmdid="'.$cmd_device_id.'" class="form-control" value="'.$cmdValue.'"></input>
</div>
    </div>
        </div>'; 

if (getparameter("mode") == "echo")
{
    echo $tplWidgetConfig;
}

?>