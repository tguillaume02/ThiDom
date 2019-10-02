<?php
$strEtat = $CmdDeviceValue . $CmdDeviceUnite;
$Pictures_device = '<img  id="Icons_'.$cmd_device_format.'" class="img-circle img_btn_device rounded-circle " alt="icons'.$IconsWidget.'" src="Core/pic/Widget/'.$IconsWidget.'" >';
echo  '<div class="">
            <table class="table table-borderless text-center WidgetContent">
                <tr>
                    <td>
                        <div class="div_btn_device Corner" name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'.$cmd_device_format.'" Cmd_device_Id="'.$Cmd_device_Id.'" device_id="'.$Device_id.'" data-type="'.$Cmd_type.'" data-role="'.$WidgetType.'">'.$Pictures_device.'</div>
                    </td>
                    <td class="WidgetStatus-left">
                        <table>
                            <tr>
                                <td class="WidgetStatus-left">
                                    <span id="InfoDevice_'.$cmd_device_format.'">'.$strEtat.'</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="WidgetStatus-left"> '.$AddDate.'</td>
                            </tr>
                            <tr>
                                <td class="WidgetStatus-left"> '.$AddBattery.'</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>';
?>