<?php

class NRF24 extends Device
{
    public function action($device_id,$cmdDevice_Id,$device_role,$device_type,$value, $mode)
    {
		$host = $_SERVER['HTTP_HOST'];
        if (($_SESSION['userIsAdmin']) == 1 or ($host == "localhost" and $host == "127.0.0.1" and $host == "192.168.1.25"))
        {
            $val_info = "";
            $device_id = isset($_REQUEST["Device_Id"]) ? $_REQUEST["Device_Id"] : '';
            $cmdDevice_Id = isset($_REQUEST["CmdDevice_Id"]) ? $_REQUEST["CmdDevice_Id"] : '';
            $type = isset($_REQUEST["Type"]) ? $_REQUEST["Type"] : '';
            $role = isset($_REQUEST["Role"]) ? $_REQUEST["Role"] : '';
            $defaultVal =isset($_REQUEST["Value"]) ? $_REQUEST["Value"] : '';
        
            $act = "";
            
            $DeviceData = CmdDevice::byCmdId_WithCmd($cmdDevice_Id);
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
        
                /*if($role == "Alerte")
                {
                    if ($Etat == "1")
                    {
                        Device::Update_Device_Status_By_id($device_id,0,0);		
                    }
                }
                else
                {*/
        
                    if ($type == "Action")
                    {
                        if ($WidgetType == "Thermostat" && $defaultVal != "undefined" && $mode != "manu")
                        {
                            $ResultFectAll = CmdDevice::GetValueSensorAttached($pinID, $carte_id);
                            $temp = $ResultFectAll->get_value();
                            $Value = $defaultVal;

                            if ($defaultVal > $temp)
                            {
                                $Etat = "1";
                            }
                            else
                            {	
                                $Etat = "0";
                            }
                        } 
                        else
                        {
                            if ($Etat == "0" or $Etat == null)
                            {
                                $Etat = "1";
                            }
                            else
                            {
                                $Etat = "0";
                            }
                        }

                        if ( $carte_id  != "0")
                        {
                            $act = $carte_id."/";
                        }
                        else
                        {
                            $act = "";
                        }

                        
                        $guid = $Device_guid;
                        
                        $act = $act.$guid."_".$WidgetId."_".$pinID."@".$Value.":".$Etat; 

                        $comPort = Module::GetModuleTypeByDevice($device_id)->get_ModuleSpecificConf("com");
                        $fp =fopen($comPort, "w");
                        fwrite($fp, $act."\n");
                        fclose($fp);
                        echo $act;
                    }
            }
        }
    }

    public function TreeNetwork()
    {
        $SQLTreeNetwork = " select cast(CarteId as int) as CarteId, group_concat(CarteId,' : ',Lieux.Nom, ' - ' ,Device.Nom SEPARATOR ',<br>') as title from Device
                            INNER JOIN Lieux on Lieux.Id = Lieux_Id 
                            WHERE CarteId is not null and CarteId != '' 
                            GROUP BY CarteId 
                            ORDER BY REVERSE(CarteId)  ";
        $result = db::execQuery($SQLTreeNetwork);
        
        $CarteId_prec = 0;
        $globalsNodes= array();
        $nodes= array();
        $nodes_all= array();
        $childs= array();
        foreach($result as $key=>$data)
        {
            $CarteId = $data['CarteId'];
            $title = $data['title'];
            
            if  (strlen($CarteId) > 1)
            {
                $str1 = substr($CarteId,1);
            }
            else
            {
                $str1 = 0;
            }
            
            if ( !in_array($CarteId, $nodes_all, true) )
            { 
                $node = array("id"=>$CarteId, "label"=>"0".$CarteId, "title"=>$title);
                array_push($nodes, $node);
            }

            if (!in_array($CarteId, $nodes_all, true) && $str1 != $CarteId )
            {              
                $child = array("from"=>$str1, "to"=>$CarteId);
                array_push($childs, $child);
            }
            
            
            array_push($nodes_all, $CarteId);
            $CarteId_prec = $CarteId;
        }
        array_push($globalsNodes, $nodes);
        array_push($globalsNodes, $childs);
        return json_encode($globalsNodes);
    }
}

class NRF24Cmd extends CmdDevice
{

}
