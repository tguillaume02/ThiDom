<?php
include_once('../../Security.php');

//header('Content-Type: text/xml');


$type = 8;
$Name_Script = "Sunrise";

$WeatherStatus = curl_init();
curl_setopt_array($WeatherStatus, array(
  CURLOPT_RETURNTRANSFER  => 1,
  CURLOPT_URL           => 'http://weather.yahooapis.com/forecastrss?w=613814',
  CURLOPT_HTTPHEADER    => array('Content-type: text/xml')
));


$status = curl_exec($WeatherStatus);
curl_close($WeatherStatus); 


$xml = simplexml_load_string($status);
$LocationAstronomy = $xml->channel->xpath('yweather:astronomy');
$LocationCondition = $xml->channel->item->xpath('yweather:condition');
$Sunrise = $LocationAstronomy[0]['sunrise'];
$Sunset = $LocationAstronomy[0]['sunset'];
$Conditions = $LocationCondition[0]['text'];
$Sunrise = date("H:i:s", strtotime($Sunrise));
$Sunset = date("H:i:s", strtotime($Sunset));

if (isset($_POST['act']))
{
	$act = $_POST['act'];
	if ($act == "Sunrise")
	{
		Update_Device_Value($Sunrise,'',"Sunrise",$type,$Name_Script);
	}
	else if ($act == "Sunset")
	{
		Update_Device_Value($Sunset,'',"Sunset",$type,$Name_Script);
	}
	else if ($act == "Conditions")
	{
		Update_Device_Value($Conditions,'',"Conditions",$type,$Name_Script);
	}
	else
	{
		Update_Device_Value($Sunrise,'',"Sunrise",$type,$Name_Script);
		Update_Device_Value($Sunset,'',"Sunset",$type,$Name_Script);
		Update_Device_Value($Conditions,'',"Conditions",$type,$Name_Script);
	}
}
else
{
	Update_Device_Value($Sunrise,'',"Sunrise",$type,$Name_Script);
	Update_Device_Value($Sunset,'',"Sunset",$type,$Name_Script);
	Update_Device_Value($Conditions,'',"Conditions",$type,$Name_Script);
}


$JSON = array();
$row_array["Sunrise"] = $Sunrise ;
$row_array["Sunset"] = $Sunset;
$row_array["Conditions"] = $Conditions;
array_push($JSON,$row_array);
echo  json_encode($JSON);


?>
