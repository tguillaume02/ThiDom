<?php

class Livebox extends Device
{	
	// Variable Configuration   
	public $Name_Script = "Livebox";
	private $Device_id = "";
	private $cookieFile = "/tmp/LIVEBOX";
	private $post = '{"parameters":{}}';
	private $contextID;
	private $host;
	private $user;
	private $pass;

	public function __construct()
	{
		$this->Device_id = filter_input(INPUT_POST, 'Device_id');
		if (!empty($this->Device_id))
		{
			$this->user = Device::byId($this->Device_id)->get_Configuration("user","null");
			$this->pass = Device::byId($this->Device_id)->get_Configuration("pwd","null");
			$this->host = Device::byId($this->Device_id)->get_Configuration("host","null");

			if(empty($contextID))
			{
				$this->contextID = $this->Login();
			}
		}
	}

	function __destruct() {
	}


	private function Login()
	{

		if ($this->user && $this->pass)
		{
			$cLogin = curl_init(); 
			curl_setopt_array($cLogin, array(
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_URL             => 'http://'.$this->host.'/authenticate?username='.$this->user.'&password='.$this->pass,
				CURLOPT_HTTPHEADER      => array("content-type: application/json") ,
				CURLOPT_COOKIEJAR       => $this->cookieFile,
				CURLOPT_POST            => 1
			));    
			$auth_result = curl_exec($cLogin);
			curl_close($cLogin);
			$auth = json_decode($auth_result); 

			return $auth->data->contextID;
		}
	}


	public function Install()
	{ 
		$domogeekCmd = new LiveboxCmd();
		$domogeekCmd->set_Name('Up');
		$domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
		$domogeekCmd->set_request('data', 'Up');
		$domogeekCmd->set_unite('Mb');
		$domogeekCmd->set_raz('');
		$domogeekCmd->set_visible(1);
		$domogeekCmd->set_type('Info');
		$domogeekCmd->save();

		$domogeekCmd = new LiveboxCmd();
		$domogeekCmd->set_Name('Down');
		$domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
		$domogeekCmd->set_request('data', 'Down');
		$domogeekCmd->set_unite('Mb');
		$domogeekCmd->set_raz('');
		$domogeekCmd->set_visible(1);
		$domogeekCmd->set_type('Info');
		$domogeekCmd->save();

		$domogeekCmd = new LiveboxCmd();
		$domogeekCmd->set_Name('Last Change');
		$domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
		$domogeekCmd->set_request('data', 'LastChange');
		$domogeekCmd->set_unite('');
		$domogeekCmd->set_raz('');
		$domogeekCmd->set_visible(1);
		$domogeekCmd->set_type('Info');
		$domogeekCmd->save();


		$domogeekCmd = new LiveboxCmd();
		$domogeekCmd->set_Name('Update Livebox');
		$domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
		$domogeekCmd->set_request('data', 'Update');
		$domogeekCmd->set_unite('');
		$domogeekCmd->set_raz('');
		$domogeekCmd->set_visible(1);
		$domogeekCmd->set_type('Action');
		$domogeekCmd->save();

	}


	public function getStatus()
	{
		if ($this->contextID)
		{
			$cStatus = curl_init();
			curl_setopt_array($cStatus, array(
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_URL           => 'http://'.$this->host.'/sysbus/NeMo/Intf/data:getMIBs',
				CURLOPT_HTTPHEADER    => array('Content-type: application/json', 'X-Context: '.$this->contextID),
				CURLOPT_COOKIEFILE    => $this->cookieFile,
				CURLOPT_POST          => 1, 
				CURLOPT_POSTFIELDS    => $this->post
			));

			$status = curl_exec($cStatus);
			curl_close($cStatus);   
			$parsed_json = json_decode($status);
			if ($parsed_json != "")
			{
				$UpStream = $parsed_json->{'result'}->{'status'}->{'dsl'}->{'dsl0'}->{'UpstreamCurrRate'};
				$DownStream = $parsed_json->{'result'}->{'status'}->{'dsl'}->{'dsl0'}->{'DownstreamCurrRate'};
				$LastChange = $parsed_json->{'result'}->{'status'}->{'dsl'}->{'dsl0'}->{'LastChange'};
				$UpStream = round(($UpStream/1000)*0.88,2) ;      
				$DownStream = round(($DownStream/1000)*0.88,2);
				$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"); 
				$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"); 
				$w = date("w", strtotime('now -'.$LastChange.' Seconds')); 
				$j = date("j", strtotime('now -'.$LastChange.' Seconds')); 
				$m = date("n", strtotime('now -'.$LastChange.' Seconds')); 
				$Y = date("Y", strtotime('now -'.$LastChange.' Seconds')); 
				$LastChange = $j." ".$mois[$m]." ".$Y."-".date("H \h i", strtotime('now -'.$LastChange.' Seconds')); 

				CmdDevice::Update_Device_Value($this->Device_id, $UpStream,$UpStream,"Up");
				CmdDevice::Update_Device_Value($this->Device_id, $DownStream,$DownStream,"Down");
				CmdDevice::Update_Device_Value($this->Device_id, $LastChange,"","Last Change");

				$JSON = array();
				$row_array["UpStream"] = $UpStream;
				$row_array["DownStream"] = $DownStream;
				$row_array["Last Change"] = $LastChange;
				array_push($JSON,$row_array);
				echo json_encode($JSON);
			}
		}
	}

	public function rebootLivebox()
	{
		if ($Livebox->contextID)
		{
		  // Reboot
			$cReboot = curl_init();
			curl_setopt_array($cReboot, array(
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_URL           => 'http://'.$Livebox->host.'/sysbus/NMC:reboot',
				CURLOPT_HTTPHEADER    => array('Content-type: application/json', 'X-Context: '.$Livebox->contextID),
				CURLOPT_COOKIEFILE    => $Livebox->cookieFile,
				CURLOPT_POST          => 1, 
				CURLOPT_POSTFIELDS        => $Livebox->post
			));
			$reboot = curl_exec($cReboot);
			curl_close($cReboot);
		}
	}
}

class LiveboxCmd extends CmdDevice
{ 
}
?>