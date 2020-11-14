<?php

$Pictures_device = '<input id="InfoDevice_'.$cmd_device_format.'" class="img-circle img_btn_device circle" type="color" >';
echo '<div id="Contentcmd_'.$Cmd_device_Id.'" class="ContentCmd">
            <table class="table table-borderless text-center WidgetContent">
                <tr>
                    <td>
                        <div class="div_btn_device img-circle" name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'.$cmd_device_format.'" Cmd_device_Id="'.$Cmd_device_Id.'" device_id="'.$Device_id.'" data-type="'.$Cmd_type.'" data-role="'.$WidgetType.'">'.$Pictures_device.'
                        </div>
                    </td>
                    <td class="WidgetStatus-left">
                        <table>
                            <tr>
                                <td class="WidgetStatus-left">'.$AddDate.'</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
    </div>';

?>