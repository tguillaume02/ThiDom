<?php
require_once dirname(__FILE__) .'/../../ListRequire.php';

$Device_id = getParameter("deviceId");
$WidgetName = getParameter("deviceWidgetType");
$status = getParameter("status");
$cmd_device_format = "";
$min = Device::byId($Device_id)->get_Configuration("Min", "0");
$max = Device::byId($Device_id)->get_Configuration("Max", "100");
$CmdDeviceValue = $status ? $status : $min;

$Pictures_device = '<div id="InfoDevice_SliderSheduler" class="img-circle img_btn_device rounded-circle  circle" value="'.$CmdDeviceValue.'" >'.$CmdDeviceValue.'</div>';
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
                <td style="width: 15%;">
                    <div class="div_btn_device Corner" data-mode="manu">'.$Pictures_device.'
                    </div>
                </td>
                <td class="WidgetStatus-left">
                    <table style="width:50%">
                        <tr>
                            <td class="WidgetStatus-left">
                                <input name="commande" class="'.$class.'" value="'.$CmdDeviceValue.'" type="range" step="0.5" min="'.$min.'" max="'.$max.'" style="margin-top: 7px;" Cmd_device_Id="'.$Cmd_device_Id.'" device_id="'.$Device_id.'" name="Range_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'"  id="Range_'.$cmd_device_format.'" data-type="'.$Cmd_type.'" data-role="'.$WidgetType.'" oninput="$(InfoDevice_SliderSheduler).html(this.value);$(InfoDevice_SliderSheduler).attr(\'value\',this.value)"/>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>';
    
?>