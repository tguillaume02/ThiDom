<?php
header('Content-Type: application/json');

include_once('../../../Security.php'); 
require_once ('../../../ListRequire.php');

$type = 8;
$Name_Script = "Domogeek";
$urldomogeek = "http://domogeek.entropialux.com/";
$urlmeteofrance = "http://www.meteofrance.com/mf3-rpc-portlet/rest/";
$urlvigilance = "http://vigilance.meteofrance.com/data/NXFR34_LFPW_.xml";
$urlvigilanceRisk = "http://vigilance.meteofrance.com/data/NXFR33_LFPW_.xml";
$Sunrise = "";
$Sunset = "";
$Conditions = "";
$vigilancecolor = "";
$vigilancerisk = "";
$SchoolHolidays = "";
$weekend = "";
$holiday = "";
$EjpToday = "";
$EjpTomorrow = "";
$Season = "";

$departement = Device::byNom($Name_Script)->get_Configuration("Departement",99);
$city = Device::byNom($Name_Script)->get_Configuration("City","Paris");
$holidayZone = Device::byNom($Name_Script)->get_Configuration("HolidaysZone","");
$EJPZone = Device::byNom($Name_Script)->get_Configuration("EJPZone","");


$SunSatus = json_decode(file_get_contents($urlmeteofrance.'ephemerides/DEPT'.$departement));

$Sunrise = $SunSatus->{'heureLeveSoleil'};
$Sunrise = str_replace("h", ":", $Sunrise);
$Sunset = $SunSatus->{'heureCoucheSoleil'};
$Sunset = str_replace("h", ":", $Sunset);
$Sunrise = date("H:i:s", strtotime($Sunrise));
$Sunset = date("H:i:s", strtotime($Sunset));

/*  ##############   GET WEATHER CONDITIONS ######## */

$WeatherStatus = file_get_contents($urldomogeek.'weather/'.$city.'/all/today');

$WeatherStatus = str_replace("u'", "'",$WeatherStatus);
$WeatherStatus = str_replace("'", "\"",$WeatherStatus);
$result = json_decode($WeatherStatus);
$Conditions =  "";
if (isset($result->{'weather'}[0]->{'description'}))
{
	$Conditions = $result->{'weather'}[0]->{'description'};
	$Conditions = ucwords($Conditions);
}

$WeatherVigilance = new DOMDocument();
$WeatherVigilance->load($urlvigilance);

$WeatherVigilanceRisk = new DOMDocument();
$WeatherVigilanceRisk->load($urlvigilanceRisk);

class color
{
	public $color;
	public $text;
}

function color($number)
{
	$colorObj = new Color();
	switch ($number) {
		case 0:
			$colorObj->color = "green";
			$colorObj->text = "vert";
			break;
		case 1:
			$colorObj->color = "green";
			$colorObj->text = "vert";
			break;
		case 2:
			$colorObj->color = "yellow";
			$colorObj->text = "jaune";
			break;
		case 3:
			$colorObj->color = "orange";
			$colorObj->text = "orange";
			break;
		case 4:
			$colorObj->color = "red";
			$colorObj->text = "rouge";
			break;
	}
	return $colorObj;
}

function risklong($number)
{
	switch ($number) {
		case 1:
		return "Vent";
		break;
		case 2:
		return "Pluie-inondation";
		break;
		case 3:
		return "Orages";
		break;
		case 4:
		return "Inondations";
		break;
		case 5:
		return "Neige-verglas";
		break;
		case 6:
		return "Canicule";
		break;
		case 7:
		return "Grand-froid";
		break;
		case 8:
		return "Avalanches";
		break;
		case 9:
		return "Vagues";
		break;
	}
}

$a = "@attributes";
$vigilancerisk = "";

foreach($WeatherVigilance->getElementsByTagName("datavigilance") as $item)
{
	if($item->getAttribute("dep") == $departement)
	{	
		$vigilancecolor = color($item->getAttribute("couleur"))->color;
		/*foreach($item->getElementsByTagName("risque") as $risque)
		{	
			$vigilancerisk .= risklong($risque->getAttribute("val"))." ".$vigilancecolorObject->text." ";
		}*/
	}
}

foreach($WeatherVigilanceRisk->getElementsByTagName("DV") as $item)
{
	if($item->getAttribute("dep") == $departement)
	{
		foreach($item->getElementsByTagName("risque") as $risque)
		{		
			$vigilancecolorObject = color($item->getAttribute("coul"));
			$vigilancerisk .= "&bull;".risklong($risque->getAttribute("val"))." ".$vigilancecolorObject->text." ";
		}
	}
}

/*  ##############   GET HOLIDAY / WEEKEND / SCHOOLHOLIDAY ######## */

if ($holidayZone!= "")
{
	$WeatherStatus = file_get_contents($urldomogeek.'holidayall/'.$holidayZone.'/now');

	$SchoolHolidays = curl_init();
	curl_setopt_array($SchoolHolidays, array(
		CURLOPT_RETURNTRANSFER  => 1,
		CURLOPT_URL           => $urldomogeek.'holidayall/'.$holidayZone.'/now',
		CURLOPT_HTTPHEADER    => array('Content-type: application/json')
		));

	$status = curl_exec($SchoolHolidays);
	curl_close($SchoolHolidays); 
	$result = json_decode($status);
	$SchoolHolidays = $result->{'holiday'};
	$weekend = $result->{'weekend'};
	$holiday = $result->{'holiday'};
}

/*  ##############   GET EJP ######## */

if ($EJPZone!= "")
{
	$GetEJPToday = curl_init();
	curl_setopt_array($GetEJPToday, array(
		CURLOPT_RETURNTRANSFER  => 1,
		CURLOPT_URL           => $urldomogeek.'ejpedf/'.$EJPZone.'/today/json',
		CURLOPT_HTTPHEADER    => array('Content-type: application/json')
		));

	$status = curl_exec($GetEJPToday);
	curl_close($GetEJPToday); 
	$result = json_decode($status);
	$EjpToday = $result->{'ejp'};


	$GetEJPTomorrow = curl_init();
	curl_setopt_array($GetEJPTomorrow, array(
		CURLOPT_RETURNTRANSFER  => 1,
		CURLOPT_URL           => $urldomogeek.'ejpedf/'.$EJPZone.'/tomorrow/json',
		CURLOPT_HTTPHEADER    => array('Content-type: application/json')
		));

	$status = curl_exec($GetEJPTomorrow);
	curl_close($GetEJPTomorrow); 
	$result = json_decode($status);
	$EjpTomorrow = $result->{'ejp'};

	if ($EjpToday == "None")
	{
		$EjpToday = "Non";
	}

	if ($EjpTomorrow == "None")
	{
		$EjpTomorrow = "Non";
	}
}

/*  ##############   GET CURRENT SEASON ######## */

$GetEJPToday = curl_init();
curl_setopt_array($GetEJPToday, array(
	CURLOPT_RETURNTRANSFER  => 1,
	CURLOPT_URL           => $urldomogeek.'season/json',
	CURLOPT_HTTPHEADER    => array('Content-type: application/json')
	));

$status = curl_exec($GetEJPToday);
curl_close($GetEJPToday); 
$result = json_decode($status);
$Season = $result->{'season'};



if (isset($_POST['act']))
{
	$act = $_POST['act'];
	if ($act == "Sunrise")
	{
		CmdDevice::Update_Device_Value($Sunrise,'',"Sunrise",$type,$Name_Script);
	}
	else if ($act == "Sunset")
	{
		CmdDevice::Update_Device_Value($Sunset,'',"Sunset",$type,$Name_Script);
	}
	else if ($act == "Conditions")
	{
		CmdDevice::Update_Device_Value($Conditions,'',"Conditions",$type,$Name_Script);
	}
	else if ($act == "vigilancecolor")
	{
		CmdDevice::Update_Device_Value($vigilancecolor,'',"vigilancecolor",$type,$Name_Script);
	}
	else if ($act == "vigilancerisk")
	{
		CmdDevice::Update_Device_Value($vigilancerisk,'',"vigilancerisk",$type,$Name_Script);
	}
	else if ($act == "SchoolHolidays")
	{
		CmdDevice::Update_Device_Value($SchoolHolidays,'',"SchoolHolidays",$type,$Name_Script);
	}
	else if ($act == "weekend")
	{
		CmdDevice::Update_Device_Value($weekend,'',"weekend",$type,$Name_Script);
	}
	else if ($act == "holiday")
	{
		CmdDevice::Update_Device_Value($holiday,'',"holiday",$type,$Name_Script);
	}
	else if ($act == "EjpToday")
	{
		CmdDevice::Update_Device_Value($EjpToday,'',"EjpToday",$type,$Name_Script);
	}
	else if ($act == "EjpTomorrow")
	{
		CmdDevice::Update_Device_Value($EjpTomorrow,'',"EjpTomorrow",$type,$Name_Script);
	}
	else if ($act == "Season")
	{
		CmdDevice::Update_Device_Value($Season,'',"Season",$type,$Name_Script);
	}
	else
	{
		CmdDevice::Update_Device_Value($Sunrise,'',"Sunrise",$type,$Name_Script);
		CmdDevice::Update_Device_Value($Sunset,'',"Sunset",$type,$Name_Script);
		CmdDevice::Update_Device_Value($Conditions,'',"Conditions",$type,$Name_Script);
		CmdDevice::Update_Device_Value($vigilancecolor,'',"vigilancecolor",$type,$Name_Script);
		CmdDevice::Update_Device_Value($vigilancerisk,'',"vigilancerisk",$type,$Name_Script);
		CmdDevice::Update_Device_Value($SchoolHolidays,'',"SchoolHolidays",$type,$Name_Script);
		CmdDevice::Update_Device_Value($weekend,'',"weekend",$type,$Name_Script);
		CmdDevice::Update_Device_Value($holiday,'',"holiday",$type,$Name_Script);
		CmdDevice::Update_Device_Value($EjpToday,'',"EjpToday",$type,$Name_Script);
		CmdDevice::Update_Device_Value($EjpTomorrow,'',"EjpTomorrow",$type,$Name_Script);
		CmdDevice::Update_Device_Value($Season,'',"Season",$type,$Name_Script);
	}
}
else
{
	CmdDevice::Update_Device_Value($Sunrise,'',"Sunrise",$type,$Name_Script);
	CmdDevice::Update_Device_Value($Sunset,'',"Sunset",$type,$Name_Script);
	CmdDevice::Update_Device_Value($Conditions,'',"Conditions",$type,$Name_Script);
	CmdDevice::Update_Device_Value($vigilancecolor,'',"vigilancecolor",$type,$Name_Script);
	CmdDevice::Update_Device_Value($vigilancerisk,'',"vigilancerisk",$type,$Name_Script);
	CmdDevice::Update_Device_Value($SchoolHolidays,'',"SchoolHolidays",$type,$Name_Script);
	CmdDevice::Update_Device_Value($weekend,'',"weekend",$type,$Name_Script);
	CmdDevice::Update_Device_Value($holiday,'',"holiday",$type,$Name_Script);
	CmdDevice::Update_Device_Value($EjpToday,'',"EjpToday",$type,$Name_Script);
	CmdDevice::Update_Device_Value($EjpTomorrow,'',"EjpTomorrow",$type,$Name_Script);
	CmdDevice::Update_Device_Value($Season,'',"Season",$type,$Name_Script);
}


$JSON = array();
$row_array["Sunrise"] = $Sunrise ;
$row_array["Sunset"] = $Sunset;
$row_array["Conditions"] = $Conditions;
$row_array["vigilancecolor"] = $vigilancecolor;
$row_array["vigilancerisk"] = $vigilancerisk;
$row_array["SchoolHolidays"] = $SchoolHolidays;
$row_array["weekend"] = $weekend;
$row_array["holiday"] = $holiday;
$row_array["EjpToday"] = $EjpToday;
$row_array["EjpTomorrow"] = $EjpTomorrow;
$row_array["Season"] = $Season;

array_push($JSON,$row_array);
echo  json_encode($JSON);


?>
