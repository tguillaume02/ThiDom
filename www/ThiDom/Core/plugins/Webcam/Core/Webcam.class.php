<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';

class Webcam extends Device
{
	// Variable Configuration   
	public $Name_Script = "Webcam";
	private $Device_id = "";
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
			$this->host = Device::byId($this->Device_id)->get_Configuration("url","null");
			$this->urlMoveRight = Device::byId($this->Device_id)->get_Configuration("urlMoveRight","null");
			$this->urlMoveLeft = Device::byId($this->Device_id)->get_Configuration("urlMoveLeft","null");
			$this->urlMoveStop = Device::byId($this->Device_id)->get_Configuration("urlMoveStop","null");
		}
	}

	public function getSnapshot()
	{
		/*$inprogress = cache::bykey('camera' . $this->getId() . 'inprogress');
		$info = $inprogress->getValue(array('state' => 0, 'datetime' => strtotime('now')));
		if ($info['state'] == 1 && (strtotime('now') - 2) <= $info['datetime']) {
			$cahe = cache::bykey('camera' . $this->getId() . 'cache');
			if ($cahe->getValue() != '') {
				return $cahe->getValue();
			}
		}
		cache::set('camera' . $this->getId() . 'inprogress', array('state' => 1, 'datetime' => strtotime('now')));
		*/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->host);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		if ($this->user != '')
		{
			$userpwd = $this->user.":".$this->pass;
			curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
			$headers = array(
				'Content-Type:application/json',
				'Authorization: Basic ' . base64_encode($userpwd),
				'Cookie:  user='.$this->user.'; password='.$this->pass.'; usr='.$this->user.'; pwd='.$this->pass
			);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		$data = curl_exec($ch);
		curl_close($ch);

		if (empty($data))
		{
			return "empty";
		}
		else
		{
			return imageToBase64($data);
		}
		
		/*cache::set('camera' . $this->getId() . 'cache', $data);
		cache::set('camera' . $this->getId() . 'inprogress', array('state' => 0, 'datetime' => ''));*/
	}

	public function action($urlAction)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->urlAction /*"http://192.168.1.111:80/moveptz.xml?dir=right"*/);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		//if ($this->getConfiguration('username') != '') {
			//$userpwd = $this->getConfiguration('username') . ':' . $this->getConfiguration('password');
			$userpwd = $this->user.":".$this->pass;
			curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
			$headers = array(
				'Content-Type:application/json',
				'Authorization: Basic ' . base64_encode($userpwd),
				'Cookie:  user='.$this->user.'; password='.$this->pwpassd.'; usr='.$this->user.'; pwd='.$this->pass
			);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//}
		curl_exec($ch);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->urlMoveStop/*"http://192.168.1.111:80/moveptz.xml?dir=stop"*/);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		//if ($this->getConfiguration('username') != '') {
			//$userpwd = $this->getConfiguration('username') . ':' . $this->getConfiguration('password');
			$userpwd = $this->user.":".$this->pass;
			curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
			$headers = array(
				'Content-Type:application/json',
				'Authorization: Basic ' . base64_encode($userpwd),
				'Cookie:  user='.$this->user.'; password='.$this->pwpassd.'; usr='.$this->user.'; pwd='.$this->pass
			);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//}
		curl_exec($ch);
		curl_close($ch);
	}

	public function imageToBase64($image)
	{
		$imageData = base64_encode($image);
		$mime_types = array(
		'jpg' => 'image/jpg',
		'jpeg' => 'image/jpeg',
		'png' => 'image/png'
		);
		$ext = pathinfo($image, PATHINFO_EXTENSION);
		
		if (array_key_exists($ext, $mime_types)) {
			$a = $mime_types[$ext];
		}
		return 'data: '.$a.';base64,'.$imageData;
	}

	public function Install()
	{ 
		$WebcamCmd = new WebcamCmd;
		$WebcamCmd->set_Name('Refresh Snapshot');
		$WebcamCmd->set_device_Id($this->DeviceNewId()->get_Id());
		$WebcamCmd->set_request('url', 'plugins/Webcam/Desktop/Webcam.php');
		$WebcamCmd->set_request('url_ajax', 'plugins/Webcam/Desktop/Webcam_ajax.php');
		$WebcamCmd->set_request('data', 'act=getSnap');
		$WebcamCmd->set_raz('');
		$WebcamCmd->set_visible(1);
		$WebcamCmd->set_type('Info');
		$WebcamCmd->save();
	}
}


class WebcamCmd extends CmdDevice
{ 
}

?>