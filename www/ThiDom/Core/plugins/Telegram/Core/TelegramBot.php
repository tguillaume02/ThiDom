<?php

http_response_code(200);
header("Content-Type: application/json");

//include_once dirname(__FILE__) .'/../../../Security.php'; 
include_once dirname(__FILE__) .'/../../../ListRequire.php';
  

if (!Module::apiAccess(getParameter('apiKey'), "telegram"))
{
	echo 'Not Authorized';
	die();
}

$Islog = User::isLoggedLocal(); 
$ScenarioObject = new Scenario();
$content = file_get_contents('php://input');
if ($content)
{
	$json = json_decode($content, true);

	if (isset($json["edited_message"]))
	{
		$json["message"] = $json["edited_message"];
	}

	if (array_key_exists('message', $json))
	{
		$channelId = $json["message"]["from"]["id"];
		if ($json["message"]["chat"]["type"] == 'private')
		{
			$username = isset($json["message"]["from"]["username"]) ? $json["message"]["from"]["username"] : $json["message"]["from"]["first_name"];
		}
		else if ($json["message"]["chat"]["type"] == 'group')
		{
			$username = $json["message"]["chat"]["title"];
		}
		else
		{
			die();
		}
	}
	else if(array_key_exists('channel_post', $json))
	{
		if ($json["channel_post"]["chat"]["type"] == 'channel')
		{
			$username = $json["channel_post"]["chat"]["title"];
			$channelId = $json["channel_post"]["chat"]["id"];			
		}
		else
		{
			die();
		}
	}
	else
	{
		die();
	}
	
	if (CmdDevice::byDataRequest($channelId, 1))
	{
		$fp = fopen('data.txt', 'w');
		fwrite($fp, "exist");
		fclose($fp);
		if (isset($json["message"]["text"]) || isset($json["channel_post"]["text"]))
		{
			$text = isset($json["message"]["text"]) ? $json["message"]["text"] : $json["channel_post"]["text"];
			$ScenarioObject->ExecIfTrigger($text);
		}
	}
	else {
		$Device_Id = getParameter('Device_id');
		$telegramCmd = new CmdDevice();	
		$telegramCmd->set_Name($username);
		$telegramCmd->set_device_Id($Device_Id);
		$telegramCmd->set_request('url', 'plugins/Telegram/Desktop/Telegram.php');
		$telegramCmd->set_request('url_ajax', 'plugins/Telegram/Desktop/Telegram_ajax.php');
		$telegramCmd->set_request('data', 'act=SendMessage');
		$telegramCmd->set_request('ChannelId', $channelId);
		$telegramCmd->set_visible(1);
		$telegramCmd->set_type('Action');
		$telegramCmd->save();
		
		$telegram = new Telegram();
		$telegram->SendMessage("Welcome ".$username, $json["message"]["from"]["id"]);		
	}
}
?>
