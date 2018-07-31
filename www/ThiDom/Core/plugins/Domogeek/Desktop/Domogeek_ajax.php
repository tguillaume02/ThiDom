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
$Vigilancecolor = "";
$Vigilancerisk = "";
$SchoolHolidays = "";
$Weekend = "";
$Holiday = "";
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
	$insee = Device::byId($Device_id)->get_Configuration("Insee","");
}

/*  ##############   GET SUNRISE / SUNSET ######## */

if ($departement != "" and ($act == "Sunrise" or $act == "Sunset" or $act == ""))
{
	$SunSatus = file_get_contents($urlmeteofrance.'ephemerides/DEPT'.$departement);
	//$SunSatus = json_decode(file_get_contents($urlmeteofrance.'ephemerides/DEPT'.$departement));

	if ($act == "Sunrise" or $act == "")
	{
		$Sunrise = getJsonAttr($SunSatus,"heureLeveSoleil", "");// $SunSatus->{'heureLeveSoleil'};
		$Sunrise = str_replace("h", ":", $Sunrise);	
		$Sunrise = date("H:i:s", strtotime($Sunrise));	
	}
	
	if  ($act == "Sunset" or $act == "")
	{
		$Sunset = getJsonAttr($SunSatus, "heureCoucheSoleil","");//$SunSatus->{'heureCoucheSoleil'};
		$Sunset = str_replace("h", ":", $Sunset);
		$Sunset = date("H:i:s", strtotime($Sunset));

	}
}

/*  ############## GET RAIN TIME ################### */

if($insee != "" and ($act == "rain" or $act == ""))
{
	$RainStatus = file_get_contents($urlmeteofrance.'pluie/'.$insee);
	$niveauPluieText = getJsonAttr($RainStatus,"niveauPluieText", "");//$RainStatus->{"niveauPluieText"};

}

/*  ##############   GET WEATHER CONDITIONS ######## */
if ($city != "" and ($act == "Vigilancecolor" or $act == "Vigilancerisk" or $act == "Conditions" or $act == ""))
{
	$WeatherStatus = file_get_contents($urldomogeek.'weather/'.$city.'/all/today');

	$WeatherStatus = str_replace("u'", "'",$WeatherStatus);
	$WeatherStatus = str_replace("'", "\"",$WeatherStatus);
	$result = json_decode($WeatherStatus);


	if ( $act == "Conditions" or $act == "" )
	{	
		$Conditions =  "";
		if (isset($result->{'weather'}[0]->{'description'}))
		{
			$Conditions = $result->{'weather'}[0]->{'description'};
			$Conditions = ucwords($Conditions);
		}
	}

	if ($act == "Vigilancecolor" or $act == "")
	{
		$WeatherVigilance = new DOMDocument();
		$WeatherVigilance->load($urlvigilance);

		foreach($WeatherVigilance->getElementsByTagName("datavigilance") as $item)
		{
			if($item->getAttribute("dep") == $departement)
			{	
				$Vigilancecolor = color($item->getAttribute("couleur"))->color;
				/*foreach($item->getElementsByTagName("risque") as $risque)
				{	
					$vigilancerisk .= risklong($risque->getAttribute("val"))." ".$vigilancecolorObject->text." ";
				}*/
			}
		}
	}

	if ($act == "Vigilancerisk" or $act == "")
	{
		$WeatherVigilanceRisk = new DOMDocument();
		$WeatherVigilanceRisk->load($urlvigilanceRisk);

		$i = 0;
		foreach($WeatherVigilanceRisk->getElementsByTagName("DV") as $item)
		{
			if($item->getAttribute("dep") == $departement)
			{
				foreach($item->getElementsByTagName("risque") as $risque)
				{		
					if ($i > 0)
					{
						$Vigilancerisk .="<br>&bull;";
					}
					else
					{
						$Vigilancerisk .="&bull;";
					}
					$VigilancecolorObject = color($item->getAttribute("coul"));
					$Vigilancerisk .= risklong($risque->getAttribute("val"))." ".$VigilancecolorObject->text." ";
					$i++;
				}
			}
		}
	}

}

/*  ##############   GET HOLIDAY / WEEKEND / SCHOOLHOLIDAY ######## */

if ($holidayZone!= "" and ( $act == "Weekend" or $act == "Holiday" or $act == ""))
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
//	$result = json_decode($status);
	
	$SchoolHolidays = getJsonAttr($status,"schoolholiday", "");
	$Weekend = getJsonAttr($status,"weekend", "");
	$Holiday = getJsonAttr($status,"holiday", "");
}

/*  ##############   GET EJP ######## */

if ($EJPZone!= "" and ( $act == "EjpToday" or $act == "EjpTomorrow" or $act == ""))
{
	if ($act == "EjpToday")
	{
		$GetEJPToday = curl_init();
		curl_setopt_array($GetEJPToday, array(
			CURLOPT_RETURNTRANSFER  => 1,
			CURLOPT_URL           => $urldomogeek.'ejpedf/'.$EJPZone.'/today/json',
			CURLOPT_HTTPHEADER    => array('Content-type: application/json')
			));

		$status = curl_exec($GetEJPToday);
		curl_close($GetEJPToday); 
		//$result = json_decode($status);
		$EjpToday = getJsonAttr($status,"ejp", "");//$result->{'ejp'};

		if ($EjpToday == "None")
		{
			$EjpToday = "Non";
		}	
	}


	if ($act == "EjpTomorrow")
	{
		$GetEJPTomorrow = curl_init();
		curl_setopt_array($GetEJPTomorrow, array(
			CURLOPT_RETURNTRANSFER  => 1,
			CURLOPT_URL           => $urldomogeek.'ejpedf/'.$EJPZone.'/tomorrow/json',
			CURLOPT_HTTPHEADER    => array('Content-type: application/json')
			));

		$status = curl_exec($GetEJPTomorrow);
		curl_close($GetEJPTomorrow); 
		//$result = json_decode($status);

		$EjpTomorrow = getJsonAttr($status,"ejp", "");//$result->{'ejp'};

		if ($EjpTomorrow == "None")
		{
			$EjpTomorrow = "Non";
		}
	}
}

/*  ##############   GET CURRENT SEASON ######## */

if ($act == "Season" or $act == "" )
{
	$GetSeason = curl_init();
	curl_setopt_array($GetSeason, array(
		CURLOPT_RETURNTRANSFER  => 1,
		CURLOPT_URL           => $urldomogeek.'season/json',
		CURLOPT_HTTPHEADER    => array('Content-type: application/json')
	));

	$status = curl_exec($GetSeason);
	curl_close($GetSeason); 
	//$result = json_decode($status);

	/*if(is_object($result))
	{
		$Season = $result->{'season'};
	}*/
	$Season = getJsonAttr($status,"season", "");
}


$a = "@attributes";

switch ($act) {
	case 'Sunrise':
		CmdDevice::Update_Device_Value($Device_id, $Sunrise, '', $act );
		$row_array["Sunrise"] = $Sunrise ;
		break;
	case 'Sunset':
		CmdDevice::Update_Device_Value($Device_id, $Sunset, '', $act );
		$row_array["Sunset"] = $Sunset;
		break;
	case 'Conditions':
		CmdDevice::Update_Device_Value($Device_id, $Conditions, '', $act );
		$row_array["Conditions"] = $Conditions;
		break;
	case 'Vigilancecolor':
		CmdDevice::Update_Device_Value($Device_id, $Vigilancecolor, '', $act );
		$row_array["Vigilancecolor"] = $Vigilancecolor;
		break;
	case 'Vigilancerisk':
		CmdDevice::Update_Device_Value($Device_id, $Vigilancerisk, '', $act );
		$row_array["Vigilancerisk"] = $Vigilancerisk;
		break;
	case 'SchoolHolidays':
		CmdDevice::Update_Device_Value($Device_id, $SchoolHolidays, '', $act );
		$row_array["SchoolHolidays"] = $SchoolHolidays;
		break;
	case 'Weekend':
		CmdDevice::Update_Device_Value($Device_id, $Weekend, '', $act );
		$row_array["Weekend"] = $Weekend;
		break;
	case 'Holiday':
		CmdDevice::Update_Device_Value($Device_id, $Holiday, '', $act );
		$row_array["Holiday"] = $Holiday;
		break;
	case 'EjpToday':
		CmdDevice::Update_Device_Value($Device_id, $EjpToday, '', $act );
		$row_array["EjpToday"] = $EjpToday;
		break;
	case 'EjpTomorrow':
		CmdDevice::Update_Device_Value($Device_id, $EjpTomorrow, '', $act );
		$row_array["EjpTomorrow"] = $EjpTomorrow;
		break;
	case 'Season':
		CmdDevice::Update_Device_Value($Device_id, $Season, '', $act );
		$row_array["Season"] = $Season;
		break;	
	default:
		CmdDevice::Update_Device_Value($Device_id, $Sunrise, '', "Sunrise" );
		CmdDevice::Update_Device_Value($Device_id, $Sunset, '', "Sunset" );
		CmdDevice::Update_Device_Value($Device_id, $Conditions, '', "Conditions" );
		CmdDevice::Update_Device_Value($Device_id, $Vigilancecolor, '', "Vigilancecolor" );
		CmdDevice::Update_Device_Value($Device_id, $Vigilancerisk, '', "Vigilancerisk" );
		CmdDevice::Update_Device_Value($Device_id, $SchoolHolidays, '', "SchoolHolidays" );
		CmdDevice::Update_Device_Value($Device_id, $Weekend, '', "Weekend" );
		CmdDevice::Update_Device_Value($Device_id, $Holiday, '', "Holiday" );
		CmdDevice::Update_Device_Value($Device_id, $EjpToday, '', "EjpToday" );
		CmdDevice::Update_Device_Value($Device_id, $EjpTomorrow, '', "EjpTomorrow" );
		CmdDevice::Update_Device_Value($Device_id, $Season, '', "Season" );

		$row_array["Sunrise"] = $Sunrise ;
		$row_array["Sunset"] = $Sunset;
		$row_array["Conditions"] = $Conditions;
		$row_array["Vigilancecolor"] = $Vigilancecolor;
		$row_array["Vigilancerisk"] = $Vigilancerisk;
		$row_array["SchoolHolidays"] = $SchoolHolidays;
		$row_array["Weekend"] = $Weekend;
		$row_array["Holiday"] = $Holiday;
		$row_array["EjpToday"] = $EjpToday;
		$row_array["EjpTomorrow"] = $EjpTomorrow;
		$row_array["Season"] = $Season;
		break;
}

$JSON = array();
array_push($JSON,$row_array);
echo  json_encode($JSON);


?>
