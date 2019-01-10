<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';
require_once ('../Core/Telegram.class.php');


$act = getParameter('act');
$msg = getParameter('msg');
$cmdDeviceId = getParameter('cmdDeviceId');
$channel_id = getParameter('channelId')."";

$Telegram = new Telegram();
if ($act == "sendMessage" && $msg != '') 
{
  if ($channel_id == "")
  {
    $channel_id = CmdDevice::byId($cmdDeviceId)->get_Request("ChannelId");
  }

  $Telegram->sendMessage($msg, $channel_id);
}
?>