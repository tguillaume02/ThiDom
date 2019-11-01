<?php
require_once dirname(__FILE__) .'/../../ListRequire.php';

$cmdList = CmdDevice::byId($Cmd_device_Id);
$Device_id =  $cmdList->get_Device_Id();
$DefaultName = $cmdList->get_Name();
$cmddetails = '';
$cmdTable = '';

//foreach($cmdList as $value)
//{
    //if ($DefaultName != $cmdList->get_Name())
    //{
        $cmddetails .= '<td>
                            <div id="'.$cmdList->get_Name().'_'.$cmdList->get_Id().'"  
                                class="btn btn-primary pull-left CommandEvent div_btn_device" 
                                data-i18n="Edit" 
                                data-theme="a" 
                                value="'.getJsonAttr($cmdList->get_Request(), "cmd")["value"].'" 
                                device_id="'.$Device_id.'"
                                cmd_device_id="'.getJsonAttr($cmdList->get_Request(), "cmd")["cmdDeviceId"].'"  
                                data-role="Commande" data-type="Action">'.$cmdList->get_Name()
                            .' </div>
                        </td>';
    //}
//}

if ($cmddetails != "")
{
    $cmdTable='<table class="table text-center table-borderless WidgetContent">
                <tbody>
                    <tr class="WidgetStatus-center">';
    $cmdTable .=    $cmddetails .'</tr>
                </tbody>
                </table>';
}

echo  '<div  id="Contentcmd_'.$Cmd_device_Id.'" class="ContentCmd" style="display:inline-flex">
'.$cmdTable.'           
</div>';
?>