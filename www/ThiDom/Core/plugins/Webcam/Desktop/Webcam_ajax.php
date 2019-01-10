<?php

require_once dirname(__FILE__)  .'/../../../Security.php';
require_once dirname(__FILE__) .'/../../../ListRequire.php';
require_once ('../Core/Webcam.class.php');


$act = getParameter('act');

if ($act)
{
    $Webcam = new Webcam();
    switch ($act)
    {
        case "getSnap":
            print $Webcam->getSnapshot();
            break;
        case "action":
            $Webcam->action("right");
            break;
    }
}
?>
