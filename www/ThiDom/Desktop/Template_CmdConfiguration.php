<?php

$CmdOfDevice = CmdDevice::byDevice_Id($deviceId);
$widgetList = Device::GetTypeWidget();
$cmdDeviceToAttachArray = CmdDevice::GetAllCmdDeviceWithLieux();
$Template_CmdConfiguration = "";

foreach($CmdOfDevice as $Cmd)
{			
    $paramView = "";
    if ($dataParam != "")
    {
        $dataParamArray = explode(",", $dataParam);	
        foreach($dataParamArray as $Param)
        {
            $paramView .='<div class="form-group">
                <label class="col-sm-5 col-xs-2 col-md-5 col-lg-2 control-label">'.$Param.'</label>
                <div class="col-sm-6 col-xs-5 col-md-5 col-lg-5">
                    <input type="text" class="DeviceAttr form-control" id="'.$Param.'" cmdid ="'.$Cmd->get_Id().'" name="'.$Param.'" placeholder="'.$Param.'"  request="1" value="'.$Cmd->get_Request($Param).'" />
                </div>
            </div>';
        }
    }

    $Template_CmdConfiguration .= '
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin-bottom: 1vh;">
                    <div class="form-group">
                        <label id="CommandeName'.$Cmd->get_Id().'" cmdId='.$Cmd->get_Id().' class="CommandeName" style="text-decoration:underline">
                            '.$Cmd->get_Name().'
                        </label>
                        <span class="EditCommandeName" onclick="EditCommandeName(\'#CommandeName'.$Cmd->get_Id().'\')" style="cursor:pointer"><i class="fas fa-pencil-alt" style="color:lightseagreen"></i></span>
                        <span class="DeleteCommandeName" onclick="DeleteCommandeDevice(\''.$Cmd->get_Name().'\','.$Cmd->get_Id().')"  style="cursor:pointer"><i class="fa fa-trash" style="color:red"></i></span>
                    </div>
                    <div class="form-horizontal">
                        <div class="form-group" style="display:none">
                            <label for="device-id" class="col-sm-5 col-xs-6 col-md-5 col-lg-2 control-label">Identifiant de l\'appareil :</label>						
                            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                <input class="form-control" id="device-id'.$Cmd->get_Id().'"  name="DeviceId" cmdid="'.$Cmd->get_Id().'" placeholder="Identifiant de l\'appareil:" value="'.$Cmd->get_DeviceId().'" required disabled>
                            </div>
                        </div>';					
                        if ($displayCategorie != "false")			
                        {
                            $Template_CmdConfiguration .= '<div class="form-group grouplistType">
                            <label for="list-type" class="col-sm-5 col-xs-2 col-md-5 col-lg-2 control-label">Categorie</label>
                            <div class="col-sm-6 col-xs-5 col-md-5 col-lg-5">
                                <select id="list-type" name="Widget_Id" class="form-control" cmdid="'.$Cmd->get_Id().'" required>';										
                                    $isDefined = "";
                                    $linkWidgetConfig = "";
                                    if ($Cmd->get_WidgetId() == "")
                                    {
                                        $isDefined = "selected";
                                    }

                                    $Template_CmdConfiguration.= "<option value='' ".$isDefined.">Categorie:</option>";

                                    foreach ($widgetList as $widgetListData)
                                    {
                                        $isDefined = "";
                                        if ($Cmd->get_WidgetId() == $widgetListData["Id"])
                                        {
                                            $isDefined = "selected";													
                                            $linkWidgetConfig = __DIR__ ."/../Core/widgetConfig/".$widgetListData["Type"]."/".$widgetListData["Type"]."Config.php";
                                        }
                                        $Template_CmdConfiguration.= "<option value='" . $widgetListData["Id"] . "' data-module_Id='".$widgetListData["ModuleType_Id"]."' data-module_Type='".$widgetListData["Type"]."' ".$isDefined.">" . $widgetListData["Name"] ."</option>"; 
                                    }											
                            $Template_CmdConfiguration .='
                                        </select>
                                    </div>
                                </div>';
                        }
                        else
                        {
                            $linkWidgetConfig = "";
                            $ModuleId = Module::GetModuleTypeByDevice($deviceId);                            
                            $link = __DIR__ ."/../Core/plugins/".$ModuleId->get_ModuleName()."/Desktop/".$ModuleId->get_ModuleName()."Config.php";
                            if (file_exists($link)) {
                                $linkWidgetConfig = $link;
                            }
                        }					

                    $Template_CmdConfiguration .='</div>';

                    if ($linkWidgetConfig)
                    {
                        $Template_CmdConfiguration .='<div id="ConsigneContent'.$Cmd->get_Id().'" class="ModalEquipementConsignContent" cmdid="'.$Cmd->get_Id().'" >';
                        $tplWidgetConfig = "";
                        include($linkWidgetConfig);
                        $Template_CmdConfiguration .=  $tplWidgetConfig;
                        $Template_CmdConfiguration .= '</div>';
                    }

                    $request = $Cmd->get_Request();
                    $json_data = json_decode($request, true);
                    if (isset($json_data["cmd"]))
                    {
                    /*   $cmdList = '<div class="form-group grouplistType">
                                        <label for="list-cmd" class="col-sm-5 col-xs-6 col-md-5 col-lg-2 control-label">Commande</label>
                                        <div class="control-group">
                                            <div class="controls form-inline">
                                                <select id="list-cmdDevice-cmd" cmdid="'.$Cmd->get_Id().'" class="form-control">
                                                <option value="">-</option>';
                        foreach($cmdDeviceToAttachArray as $donneesCmdDevice)
                        {								
                            $isDefined = "";
                            if ($json_data["cmd"]["cmdDeviceId"] == $donneesCmdDevice["Id"])
                            {
                                $isDefined = "selected";
                            }
                            $cmdList.= "<option value='" . $donneesCmdDevice["Id"] . "' ".$isDefined.">" . $donneesCmdDevice["Nom"] . " - ". $donneesCmdDevice["LieuxNom"] ."</option>"; 
                        }									
                        $cmdList .= ' </select>
                                        <select id="list-cmdDevice-cmd-valueEtat" cmdid="'.$Cmd->get_Id().'" class="form-control">
                                            <option value="value">Value =</option>
                                            <option value="Etat">Etat = </option>
                                        </select>
                                        <input id="Data-valueEtat"  cmdid="'.$Cmd->get_Id().'" class="form-control" value="'.$json_data["cmd"]["value"].'"></input>
                                        </div>
                                            </div>
                                                </div>';
                        $Template_CmdConfiguration .=  $cmdList;*/
                    }
                    else
                    {
                        $Template_CmdConfiguration .= '
                            <div class="form-group">
                                <label class="col-sm-5 col-xs-6 col-md-5 col-lg-2 control-label">Remise à Zero / màj : </label>
                                <div class="col-sm-6 col-xs-5 col-md-5 col-lg-5">
                                    <input type="time" class="form-control" id="raz-value'.$Cmd->get_Id().'" step="1" name="RAZ" placeholder="Remise à zero apres: HH:MM:SS" cmdid ="'.$Cmd->get_Id().'" value="'.gmdate("H:i:s", $Cmd->get_RAZ()).'">
                                </div>	
                            </div>															
                            <div class="form-group">
                                <label for="unite-value" class="col-sm-5 col-xs-6 col-md-5 col-lg-2 control-label">Unité : </label>
                                <div class="col-sm-6 col-xs-5 col-md-5 col-lg-5">
                                    <input class="form-control" id="cmddevice-unit'.$Cmd->get_Id().'" name="unite" cmdid ="'.$Cmd->get_Id().'" value="'.$Cmd->get_Unite().'" placeholder="°c, °f, MW, Mb , %, ...">
                                </div>
                            </div>';
                    }
                    
    $Template_CmdConfiguration .=get_iconsButtonList($Cmd->get_Id(), getJsonAttr($request,"icons", ""));
    

    $Template_CmdConfiguration .= 
                    $paramView.'
                    <div class="form-inline">
                        <div class="form-group">
                            <label class="btn btn-success">
                                <input type="checkbox" name="Visible" id="cmddevice-visible'.$Cmd->get_Id().'" cmdid="'.$Cmd->get_Id().'" '.($Cmd->get_Visible() ? "checked" : "").'>Visible
                            </label>	
                            <label class="btn btn-success">
                                <input type="checkbox" name="History" id="cmddevice-historiser'.$Cmd->get_Id().'" cmdid="'.$Cmd->get_Id().'" '.($Cmd->get_History()? "checked" : "").'>Historiser
                            </label>
                            <label class="btn btn-success">
                                <input type="checkbox" name="Notification" id="cmddevice-notification'.$Cmd->get_Id().'" cmdid="'.$Cmd->get_Id().'" '.($Cmd->get_Notification() ? "checked" : "").'>Notifications
                            </label>
                            ';
                            if ($toggleValue != "")
                            {
                                $Template_CmdConfiguration .= '<input type="checkbox" class="toggle" cmdid="'.$Cmd->get_Id().'" name="Type" data-toggle="toggle" data-on="Action" data-off="Info" data-onstyle="success" data-offstyle="info" '.(ucwords($Cmd->get_Type()) == "Action" ? "checked" : "").'>';
                            }
                            $Template_CmdConfiguration .= '
                        </div>
                    </div>
                </div>';
}                            

//    echo '<div class="row">'.$Template_CmdConfiguration.'</div>';
    if ($displayAddCommad == "true")
    {
        echo '<div class="col-lg-12 text-right" style="height:30px"> <button type="button" id="add-command" onclick="InstallCommand();"class="btn-add btn-bottom-right btn-success btn-md pull-right absolute" data-toggle="modal" style="width:30px; height:30px"><i class="fas fa-plus"></i></button>
            </div>';
    }
    
if ($Template_CmdConfiguration != "")
{
    echo '<div >'.$Template_CmdConfiguration.'</div>';
}
?>