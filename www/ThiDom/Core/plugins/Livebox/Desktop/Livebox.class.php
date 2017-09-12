<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';

class LiveboxClient
{
  // Variable Configuration   
  public $Name_Script = "Livebox";
  public $type = 8;

  private $cookieFile = "/tmp/LIVEBOX";
  private $post = '{"parameters":{}}';
  private $contextID;
  private $host;
  private $user;
  private $pass;

  public function __construct()
  {
    $this->user = Device::byNom($this->Name_Script)->get_Configuration("user","null");
    $this->pass = Device::byNom($this->Name_Script)->get_Configuration("pwd","null");
    $this->host = Device::byNom($this->Name_Script)->get_Configuration("host","null");

    if(empty($contextID))
    {
      $this->contextID = $this->Login();
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
        CURLOPT_HTTPHEADER      => array("ontent-type: application/json") ,
        CURLOPT_COOKIEJAR       => $this->cookieFile,
        CURLOPT_POST            => 1
        ));    
      $auth_result = curl_exec($cLogin);
      curl_close($cLogin);
      $auth = json_decode($auth_result); 

      return $auth->data->contextID; 
    }
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

        CmdDevice::Update_Device_Value($UpStream,$UpStream,"Up",$this->type,$this->Name_Script);
        CmdDevice::Update_Device_Value($DownStream,$DownStream,"Down",$this->type,$this->Name_Script);
        CmdDevice::Update_Device_Value($LastChange,"","Last Change",$this->type,$this->Name_Script);

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
    if ($this->contextID)
    {
      // Reboot
      $cReboot = curl_init();
      curl_setopt_array($cReboot, array(
        CURLOPT_RETURNTRANSFER  => 1,
        CURLOPT_URL           => 'http://'.$this->host.'/sysbus/NMC:reboot',
        CURLOPT_HTTPHEADER    => array('Content-type: application/json', 'X-Context: '.$this->contextID),
        CURLOPT_COOKIEFILE    => $this->cookieFile,
        CURLOPT_POST          => 1, 
        CURLOPT_POSTFIELDS        => $this->post
        ));
      $reboot = curl_exec($cReboot);
      curl_close($cReboot);
    }
  } 
}

if(empty($Livebox))
{
  $Livebox = new LiveboxClient();  
}

$act = filter_input(INPUT_POST, 'act');

if ($act == "")
{
  $act = filter_input(INPUT_GET, 'act');  
}

if ($act == "loadData") 
{
  $Livebox->getStatus();
}

if ($act == "rebootLivebox")
{
  $Livebox->rebootLivebox();
}
?>
