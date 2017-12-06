<?php

require_once dirname(__FILE__)  .'/../../../Security.php';
require_once dirname(__FILE__) .'/../../../ListRequire.php';
require_once ('../Core/Webcam.class.php');


$act = filter_input(INPUT_POST, 'act');

if ($act == "getSnap")
{
    $Webcam = new Webcam();
    print $Webcam->getSnapshot();
}

if ($act == "action")
{
    $Webcam = new Webcam();
    $Webcam->action("right");
}
?>
