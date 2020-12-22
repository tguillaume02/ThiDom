<?php

class Virtuel extends Device
{
    public function action($device_id,$cmdDevice_Id,$device_role,$device_type,$value, $mode)
    {
		$host = $_SERVER['HTTP_HOST'];
        if (($_SESSION['userIsAdmin']) == 1 or ($host == "localhost" and $host == "127.0.0.1" and $host == "192.168.1.25"))
        {
            $device_id = isset($_REQUEST["Device_Id"]) ? $_REQUEST["Device_Id"] : '';
            $cmdDevice_Id = isset($_REQUEST["CmdDevice_Id"]) ? $_REQUEST["CmdDevice_Id"] : '';
            $type = isset($_REQUEST["Type"]) ? $_REQUEST["Type"] : '';
            $role = isset($_REQUEST["Role"]) ? $_REQUEST["Role"] : '';
            $defaultVal =isset($_REQUEST["Value"]) ? $_REQUEST["Value"] : '';

            if ($type == "Action")
            {
                $DeviceData = VirtuelCmd::byCmdId_WithCmd($cmdDevice_Id);
                if ($DeviceData)
                {
                    foreach($DeviceData as $donnees)
                    {
                        $Device_guid = $donnees["GUID"];
                        $cmd_nom = $donnees["Cmd_nom"];
                        $Device_nom = $donnees["Device_nom"];
                        $carte_id = $donnees["CarteId"];
                        $pinID = $donnees["DeviceId"];
                        $Value = empty($donnees["Value"]) ? 0 : $donnees["Value"];
                        $Etat = $donnees["Etat"];
                        $WidgetType = $donnees["WidgetName"];
                        $WidgetId = $donnees["WidgetId"];
                    }

                    if ($Etat == "0" or $Etat == null)
                    {
                        $Etat = "1";
                    }
                    else
                    {
                        $Etat = "0";
                    }

                    VirtuelCmd::Update_Status_By_CmdId($cmdDevice_Id, $Etat, $Etat);
                }
            }
        }
    }    
}

class VirtuelCmd extends CmdDevice
{

}
