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
$departement = "";
$city = "";
$holidayZone = "";
$EJPZone = "";
$Device_id = getPost("Device_id");
$act = getpost('act');


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

if($Device_id)
{
	$departement = Device::byId($Device_id)->get_Configuration("Departement","");
	$city = Device::byId($Device_id)->get_Configuration("City","");
	$holidayZone = Device::byId($Device_id)->get_Configuration("HolidaysZone","");
	$EJPZone = Device::byId($Device_id)->get_Configuration("EJPZone","");
}

if ($departement != "")
{
	$SunSatus = json_decode(file_get_contents($urlmeteofrance.'ephemerides/DEPT'.$departement));

	$Sunrise = $SunSatus->{'heureLeveSoleil'};
	$Sunrise = str_replace("h", ":", $Sunrise);
	$Sunset = $SunSatus->{'heureCoucheSoleil'};
	$Sunset = str_replace("h", ":", $Sunset);
	$Sunrise = date("H:i:s", strtotime($Sunrise));
	$Sunset = date("H:i:s", strtotime($Sunset));
}

/*  ##############   GET WEATHER CONDITIONS ######## */
if ($city != "")
{
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
			$i = 0;
			foreach($item->getElementsByTagName("risque") as $risque)
			{		
				if ($i > 0)
				{
					$vigilancerisk .="<br>&nbsp;&nbsp;&nbsp;&nbsp;&bull;";
				}
				else
				{
					$vigilancerisk .="&bull;";
				}
				$vigilancecolorObject = color($item->getAttribute("coul"));
				$vigilancerisk .= risklong($risque->getAttribute("val"))." ".$vigilancecolorObject->text." ";
				$i++;
			}
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
if(is_object($result))
{
	$Season = $result->{'season'};
}

$a = "@attributes";

switch ($act) {
	case 'Sunrise':
		CmdDevice::Update_Device_Value($Device_id, $Sunrise, '', $act );
		break;
	case 'Sunset':
		CmdDevice::Update_Device_Value($Device_id, $Sunset, '', $act );
		break;
	case 'Conditions':
		CmdDevice::Update_Device_Value($Device_id, $Conditions, '', $act );
		break;
	case 'vigilancecolor':
		CmdDevice::Update_Device_Value($Device_id, $vigilancecolor, '', $act );
		break;
	case 'vigilancerisk':
		CmdDevice::Update_Device_Value($Device_id, $vigilancerisk, '', $act );
		break;
	case 'SchoolHolidays':
		CmdDevice::Update_Device_Value($Device_id, $SchoolHolidays, '', $act );
		break;
	case 'weekend':
		CmdDevice::Update_Device_Value($Device_id, $weekend, '', $act );
		break;
	case 'holiday':
		CmdDevice::Update_Device_Value($Device_id, $holiday, '', $act );
		break;
	case 'EjpToday':
		CmdDevice::Update_Device_Value($Device_id, $EjpToday, '', $act );
		break;
	case 'EjpTomorrow':
		CmdDevice::Update_Device_Value($Device_id, $EjpTomorrow, '', $act );
		break;
	case 'Season':
		CmdDevice::Update_Device_Value($Device_id, $Season, '', $act );
		break;	
	default:
		CmdDevice::Update_Device_Value($Device_id, $Sunrise, '', "Sunrise" );
		CmdDevice::Update_Device_Value($Device_id, $Sunset, '', "Sunset" );
		CmdDevice::Update_Device_Value($Device_id, $Conditions, '', "Conditions" );
		CmdDevice::Update_Device_Value($Device_id, $vigilancecolor, '', "vigilancecolor" );
		CmdDevice::Update_Device_Value($Device_id, $vigilancerisk, '', "vigilancerisk" );
		CmdDevice::Update_Device_Value($Device_id, $SchoolHolidays, '', "SchoolHolidays" );
		CmdDevice::Update_Device_Value($Device_id, $weekend, '', "weekend" );
		CmdDevice::Update_Device_Value($Device_id, $holiday, '', "holiday" );
		CmdDevice::Update_Device_Value($Device_id, $EjpToday, '', "EjpToday" );
		CmdDevice::Update_Device_Value($Device_id, $EjpTomorrow, '', "EjpTomorrow" );
		CmdDevice::Update_Device_Value($Device_id, $Season, '', "Season" );
		break;
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
