<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';
require_once ('../Core/NRF24.class.php');


$act = getParameter('act');
$NRF24 = new NRF24();

if ($act == "GetTree") 
{
  echo $NRF24->TreeNetwork();
}
?>