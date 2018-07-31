<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';
require_once ('../Core/Telegram.class.php');


$act = filter_input(INPUT_POST, 'act');
$msg = filter_input(INPUT_POST, 'msg');


$Telegram = new Telegram();

if ($act == "sendMessage") 
{
  $Telegram->sendMessage($msg);
}
?>