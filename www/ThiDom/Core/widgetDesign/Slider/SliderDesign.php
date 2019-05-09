<?php
require_once dirname(__FILE__) .'/../../ListRequire.php';

if (getParameter("mode") == "Schedule")
{
    $Device_id = getParameter("deviceId");
    $cmd_device_format = "";
    $CmdDeviceValue =  CmdDevice::byId($Cmd_device_Id)->get_Request("Min", "0");
}

$min = CmdDevice::byId($Cmd_device_Id)->get_Request("Min", "0");
$max = CmdDevice::byId($Cmd_device_Id)->get_Request("Max", "100");

$Pictures_device = '<div id="InfoDevice_'.$cmd_device_format.'" class="img-circle img_btn_device rounded-circle  circle" value="'.$CmdDeviceValue.'" >'.$CmdDeviceValue.'</div>';
if ($WidgetName == "Dimmer")
{
    $class = "dimmerbar";
}
else
{
    $class = "bar";
}
echo  '<div class="">
            <table class="table table-borderless text-center WidgetContent">
            <tr>
                <td>
                    <div class="div_btn_device Corner" name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'.$cmd_device_format.'" Cmd_device_Id="'.$Cmd_device_Id.'" device_id ="'.$Device_id.'" data-type="'.$Cmd_type.'" data-role="'.$WidgetType.'" data-mode="manu">'.$Pictures_device.'
                    </div>
                </td>
                <td class="WidgetStatus-left">
                    <table style="width:100%">
                        <tr>
                            <td class="WidgetStatus-left">
                                <input class="'.$class.'" value="'.$CmdDeviceValue.'" type="range" step="0.5" min="'.$min.'" max="'.$max.'" style="margin-top: 7px;" Cmd_device_Id="'.$Cmd_device_Id.'" device_id="'.$Device_id.'" name="Range_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'"  id="Range_'.$cmd_device_format.'" data-type="'.$Cmd_type.'" data-role="'.$WidgetType.'" oninput="$(InfoDevice_'.$cmd_device_format.').html(this.value);$(InfoDevice_'.$cmd_device_format.').attr(\'value\',this.value)"/>
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
    </div>';
    
?>