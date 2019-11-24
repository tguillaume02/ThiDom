<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';

$ListCmdDeviceByDeviceId = CmdDevice::byDevice_Id_WithCmd($Device_id);
?>
<div>
	<table class="table table-borderless WidgetContent">
		<tbody>
            <?php            
            foreach($ListCmdDeviceByDeviceId as $donneesDevice)
            {?>
                <tr class="WidgetStatus-center">
                    <td id="Contentcmd_<?php echo CmdDevice::GetCmdId($donneesDevice["Cmd_nom"],$Device_id)->get_Id()?>">
                        <?php echo $donneesDevice["Cmd_nom"]. ": "?>
                        <span id="InfoDevice_<?php echo $LieuxWithoutSpace ?>_<?php echo CmdDevice::GetCmdId($donneesDevice["Cmd_nom"],$Device_id)->get_Id()?>"></span>
                    </td>
                </tr>
            <?php
            }
            ?>
		</tbody>
	</table>
</div>
