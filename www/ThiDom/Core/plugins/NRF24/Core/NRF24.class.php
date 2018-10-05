<?php

class NRF24 extends Device
{
    public function action($device_id,$device_role,$device_type,$value)
    {
        if (($_SESSION['userIsAdmin']) == 1 or ($host == "localhost" and $host == "127.0.0.1" and $host == "192.168.1.25"))
        {
            $val_info = "";
            $device_id = isset($_REQUEST["Device_Id"]) ? $_REQUEST["Device_Id"] : '';
            $type = isset($_REQUEST["Type"]) ? $_REQUEST["Type"] : '';
            $role = isset($_REQUEST["Role"]) ? $_REQUEST["Role"] : '';
            $defaultVal =isset($_REQUEST["Value"]) ? $_REQUEST["Value"] : '';
        
            $act = "";
            
            $DeviceData = CmdDevice::byDevice_IdWithCmd($device_id);
            if ($DeviceData)
            {
                foreach($DeviceData as $donnees)
                {
                    $cmd_nom = $donnees["Cmd_nom"];
                    $Device_nom = $donnees["Device_nom"];
                    $carte_id = $donnees["CarteId"];
                    $pinID = $donnees["DeviceId"];
                    $Value = empty($donnees["Value"]) ? 0 : $donnees["Value"];
                    $Etat = $donnees["Etat"];
                    $WidgetType = $donnees["WidgetName"];
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
                        if ($WidgetType == "Thermostat" && $defaultVal != "undefined")
                        {
                            $ResultFectAll = CmdDevice::GetValueSensorAttached($pinID, $carte_id);
        
                        /*	foreach ($ResultFectAll as $donnees) {
                                $temp = $donnees["Value"];
                            }*/
                            
                            $temp = $ResultFectAll->get_value();
        
                            if ($defaultVal > $temp)
                            {
                                $act = $act.$pinID."@".$defaultVal.":1";
                            }
                            else
                            {	
                                $act = $act.$pinID."@".$defaultVal.":0";
                            }
                        }
                        else
                        {
                            if ($Etat == "0" or $Etat == null)
                            {
                                $val = "1";
                            }
                            else
                            {
                                $val = "0";
                            }
        
                            if ( $carte_id  != "0")
                            {
                                $act = $carte_id."/";
                            }
                            else
                            {
                                $act = "";
                            } 
                            
                            $act = $act.$pinID."@".$Value.":".$val; 
                        }
                        $comPort = Module::GetModuleTypeByDevice($device_id)->get_ModuleSpecificConf("com");
                        $fp =fopen($comPort, "w");
                        fwrite($fp, $act."\n");
                        fclose($fp);
                        //echo $act;
                    }
            }
        }
    }
}

class NRF24Cmd extends CmdDevice
{

}
