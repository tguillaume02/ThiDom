<?php
/* if ($_POST["act"] == "l")
{
	*/
	
	include_once('Security.php'); 
	
	$val_info = "";
	$device = $_POST["device"];
	$lieux = $_POST["lieux"];
	$val = $_POST["val"];
	$device_id = $_POST["device_id"];
	$carte_id = $_POST["carte_id"];
	$pinID = $_POST["pinID"];
	$nom = $device."_".$lieux;

//if ( $carte_id  != "007")
	if ( $carte_id  != "0")
	{
		$act = $carte_id."/";
	}
	else
	{
		$act = "";
	}

	if ($device == "Chauffage")
	{
	//$act = $nom."#".$val;
	// $req = $mysqli->query("select Temperature_Temp.temp as temp,Etat_IO.lieux from Temperature_Temp inner join Etat_IO on Etat_IO.lieux = Temperature_Temp.lieux where Etat_IO.deviceID=".$pinID." order by Temperature_Temp.date desc LIMIT 1") or die('Erreur SQL !<br>');
		
		$req = execute_sql("select value from Etat_IO where ID=(select sensor_attachID from Etat_IO where DeviceID=".$pinID." and Carte_ID=".$carte_id.")");
		
		While ($donnees = $req->fetch_array())
		{
			$temp = $donnees["value"];
		}
		
		if ($val > $temp)
		{
			$act = $act.$pinID."@".$val.":1";
		}
		else
		{	
			$act = $act.$pinID."@".$val.":0";
		}
		
	/*$device = "thermostat ".$device;
	$val_info = $val;*/
}
else
{
	$act = $act.$pinID."@".$val.":".$val;
	//$act = $nom.":".$val;
	
	/*if ($val == "0")
	{
		$val_info = "off" ;
	}
	elseif ($val == "1")
	{
		$val_info = "on" ;
	}*/
}

error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once "php_serial.class.php";
echo $act;
$serial = new phpSerial();
$serial->deviceSet("/dev/ttyACM0");
$serial->confBaudRate(9600);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();
$serial->sendMessage($act."\n");
$serial->deviceClose();

	// $req = mysql_query("Select Etat from Etat_IO where Nom = 'Led1'");
	// While ($donnees = mysql_fetch_array($req))
	// {
		// $etat = $donnees["Etat"];
	// }

	// if ($etat == "0")
	// {
		// $etat = "1";
	// }
	// else
	// {
		// $etat = "0";
	// }
	//mysql_query("Update Etat_IO set Etat='".$val."', date='".date("Y-m-d H:i:s")."' where ID ='".$device_id."'") or die (mysql_error());
//}


// $info = $device.' '.$lieux.' '.$val_info;
// $info = str_replace (' ', '%20', $info ) ;
// file_get_contents("http://ns9.firstheberg.com/~notify87/?id=20142788&notif=".$info."&id_notif=".$device_id);
?>
