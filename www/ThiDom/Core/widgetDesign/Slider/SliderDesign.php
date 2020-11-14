<?php
require_once dirname(__FILE__) .'/../../ListRequire.php';

$CmdDeviceData = CmdDevice::byId($Cmd_device_Id);

if (getParameter("mode") == "Schedule")
{
    $Device_id = getParameter("deviceId");
    $cmd_device_format = "";
    $CmdDeviceValue = $CmdDeviceData->get_Request("Min", "0");
}

$min = $CmdDeviceData->get_Request("Min", "0");
$max = $CmdDeviceData->get_Request("Max", "100");

$Pictures_device = '<div id="InfoDevice_'.$cmd_device_format.'" class="img-circle img_btn_device circle" value="'.$CmdDeviceValue.'" >'.$CmdDeviceValue.'</div>';

if ($WidgetName == "Dimmer")
{
    $class = "dimmerbar";
}
else
{
    $class = "bar";
}

$Device_id =  $CmdDeviceData->get_Device_Id();
$cmdList = CmdDevice::byDevice_Id($Device_id);
$DefaultName = $CmdDeviceData->get_Name();
$cmddetails = '';
$cmdTable = '';

/*
foreach($cmdList as $value)
{
    if ($DefaultName != $value->get_Name())
    {
        $cmddetails .= '<td id="Contentcmd_'. $value->get_Id() .'">
        <div id="'.$value->get_Name().'_'.$value->get_Id().'"  class="btn btn-primary pull-left CommandEvent div_btn_device" data-i18n="Edit" data-theme="a" value="'.getJsonAttr($value->get_Request(), "cmd")["value"].'" cmd_device_id="'.getJsonAttr($value->get_Request(), "cmd")["cmdDeviceId"].'" device_id="'.CmdDevice::byId(getJsonAttr($value->get_Request(), "cmd")["cmdDeviceId"])->get_Device_Id().'" data-role="Commande" data-type="Action">'.$value->get_Name().' </div>
        </td>';
    }
}

if ($cmddetails != "")
{
    $cmdTable='<table class="table text-center table-borderless WidgetContent">
                <tbody>
                    <tr class="WidgetStatus-center">';
    $cmdTable .=    $cmddetails .'</tr>
                </tbody>
                </table>';
}
*/

echo  '<div id="Contentcmd_'.$Cmd_device_Id.'" class="ContentCmd">
            <table class="table table-borderless text-center WidgetContent">
                <tr>
                    <td>
                        <div class="div_btn_device img-circle transparent" name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'.$cmd_device_format.'" Cmd_device_Id="'.$Cmd_device_Id.'" device_id ="'.$Device_id.'" data-type="'.$Cmd_type.'" data-role="'.$WidgetType.'" data-mode="manu">'.$Pictures_device.'
                        </div>
                    </td>
                    <td class="WidgetStatus-left">
                        <table style="width:100%">
                            <tr>
                                <td class="WidgetStatus-left">
                                    <label style="width:100%">
                                        <input class="'.$class.'" value="'.$CmdDeviceValue.'" type="range" step="0.5" min="'.$min.'" max="'.$max.'" style="margin-top: 7px;" Cmd_device_Id="'.$Cmd_device_Id.'" device_id="'.$Device_id.'" name="Range_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'"  id="Range_'.$cmd_device_format.'" data-type="'.$Cmd_type.'" data-role="'.$WidgetType.'" oninput="$(InfoDevice_'.$cmd_device_format.').html(this.value);$(InfoDevice_'.$cmd_device_format.').attr(\'value\',this.value)"/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="WidgetStatus-left">
                                '.$AddDate.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table> 
            '.$cmdTable.'           
        </div>';
    
?>