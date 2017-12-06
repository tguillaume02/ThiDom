<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';
require_once ('../Core/Livebox.class.php');

$act = filter_input(INPUT_POST, 'act');

if ($act == "")
{
  $act = filter_input(INPUT_GET, 'act');  
}

$Livebox = new Livebox();

if ($act == "loadData") 
{
  $Livebox->getStatus();
}

if ($act == "rebootLivebox")
{
  $Livebox->rebootLivebox();
}
?>
