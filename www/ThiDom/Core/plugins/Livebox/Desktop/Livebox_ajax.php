<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';
require_once ('../Core/Livebox.class.php');

$act = getParameter('act');

if ($act != "")
{
  $Livebox = new Livebox();

  if ($act == "loadData") 
  {
    $Livebox->getStatus();
  }

  if ($act == "rebootLivebox")
  {
    $Livebox->rebootLivebox();
  }
}
?>
