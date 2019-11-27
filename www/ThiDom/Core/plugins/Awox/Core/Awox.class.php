<?php

class Awox
{    
	// Variable Configuration   
	private $Name_Script = "Awox";
    private $Device_id = "";    
	private $port;
    
    
	public function __construct()
	{
		$this->Device_id = getParameter('Device_id');
		if (!empty($this->Device_id))
		{
			$this->host = Device::byId($this->Device_id)->get_Configuration("host","null");
        }        
        
        if(empty($port))
        {
            $this->port = $this->sync();
        }
	}

	function __destruct() {
    }    

	public function Install($DeviceId="")
	{ 
		if ($DeviceId == "")
		{
			$DeviceId = $this->DeviceNewId()->get_Id();
		}

		$awoxCmd = new CmdDevice();
		$awoxCmd->set_Name('Light');
		$awoxCmd->set_device_Id($DeviceId);
		$awoxCmd->set_request('url', 'aw/DimmableLight_SwitchPower/control');
		$awoxCmd->set_request('Content-Type', 'text/xml');
		$awoxCmd->set_request('SOAPACTION', '#SetTarget');
		$awoxCmd->set_request('data', '<?xml version="1.0" encoding="utf-8"?><s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"><s:Body><u:SetTarget xmlns:u="urn:schemas-upnp-org:service:SwitchPower:1"><newTargetValue>$Value</newTargetValue></u:SetTarget></s:Body></s:Envelope>');
		$awoxCmd->set_unite('');
        $awoxCmd->set_raz('');
        $awoxCmd->set_WidgeId(4);
		$awoxCmd->set_visible(0);
		$awoxCmd->set_type('Action');
        $awoxCmd->save();        
        
		$awoxCmd->set_Name('Dimmer');
		$awoxCmd->set_device_Id($DeviceId);
		$awoxCmd->set_request('url', 'aw/DimmableLight_Dimming/control');
		$awoxCmd->set_request('Content-Type', 'text/xml');
		$awoxCmd->set_request('SOAPACTION', '#SetLoadLevelTarget');
		$awoxCmd->set_request('data', '<?xml version="1.0" encoding="utf-8"?><s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"><s:Body><u:SetLoadLevelTarget xmlns:u="urn:schemas-upnp-org:service:Dimming:1"><newLoadlevelTarget>81</newLoadlevelTarget></u:SetLoadLevelTarget></s:Body></s:Envelope>');
		$awoxCmd->set_unite('');
        $awoxCmd->set_raz('');
        $awoxCmd->set_WidgeId(2);
		$awoxCmd->set_visible(0);
		$awoxCmd->set_type('Action');
		$awoxCmd->save();
        
		$awoxCmd->set_Name('Color');
		$awoxCmd->set_device_Id($DeviceId);
		$awoxCmd->set_request('url', 'aw/DimmableLight_SwitchPower/control');
		$awoxCmd->set_request('Content-Type', 'text/xml');
		$awoxCmd->set_request('SOAPACTION', 'urn:schemas-upnp-org:service:SwitchPower:1#SetTarget');
		$awoxCmd->set_request('data', '<?xml version="1.0" encoding="utf-8"?><s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"><s:Body><u:SetTarget xmlns:u="urn:schemas-upnp-org:service:SwitchPower:1"><newTargetValue>$Value</newTargetValue></u:SetTarget></s:Body></s:Envelope>');
		$awoxCmd->set_unite('');
        $awoxCmd->set_raz('');
        $awoxCmd->set_WidgeId(3);
		$awoxCmd->set_visible(0);
		$awoxCmd->set_type('Action');
		return $awoxCmd->save();
	}
    
    function sync()
    {        
        return "";
    }
}


class AwoxCmd extends CmdDevice
{ 
}
?>