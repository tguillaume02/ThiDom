<?php

header('Content-Type: text/event-stream');

require_once dirname(__FILE__) .'/Core/ListRequire.php';

$lastdate ="";
$Date = "";
$Date_Etat = "";
$Date_temp = "";
$lastdate_temp="";
$lastdate_Etat = "";
$lastdate_Sun = "";

while(1)
{
	/*$req = " SELECT temp_update_time.UPDATE_TIME AS update_temp, etat_update_time.UPDATE_TIME  AS update_etat
	FROM INFORMATION_SCHEMA.TABLES AS temp_update_time 
	LEFT JOIN INFORMATION_SCHEMA.TABLES AS etat_update_time ON etat_update_time.TABLE_SCHEMA = 'thidom' AND etat_update_time.TABLE_NAME = 'cmd_device'
	WHERE  (temp_update_time.TABLE_SCHEMA = 'thidom') AND (temp_update_time.TABLE_NAME = 'Temperature_Temp')";*/

	$req = "SELECT cmd_device.Id as cmd_deviceId, temp_update_time.UPDATE_TIME AS update_temp, etat_update_time.UPDATE_TIME  AS update_etat,  cmd_device.Nom as cmd_deviceNom, 
		cmd_device.Value as cmd_deviceValue, cmd_device.Etat as cmd_deviceEtat, 
		Lieux.Nom as LieuxNom, Module_Type.Id as Module_Type, Device.Configuration,
		cmd_device.Notification
	FROM INFORMATION_SCHEMA.TABLES AS temp_update_time 
	LEFT JOIN INFORMATION_SCHEMA.TABLES AS etat_update_time ON etat_update_time.TABLE_SCHEMA = 'thidom' AND etat_update_time.TABLE_NAME = 'cmd_device'
	LEFT JOIN cmd_device   on cmd_device.date = (select max(date) from cmd_device)
	LEFT join Device on Device.Id = cmd_device.Id
	LEFT join Lieux on Lieux.Id = Device.Lieux_Id
	LEFT join Module_Type on Device.Module_Id = Module_Type.Id
	WHERE  (temp_update_time.TABLE_SCHEMA = 'thidom') AND (temp_update_time.TABLE_NAME = 'Temperature_Temp')
	group by update_temp, update_etat";

	$result = db::execQuery($req,[]);

	foreach($result as $row)  
	{
		$Notification = 0;
		$Date_temp = $row["update_temp"];
		$Date_Etat = $row["update_etat"];
		$cmd_deviceNom = $row["cmd_deviceNom"];
		$cmd_deviceValue = $row["cmd_deviceValue"];
		$cmd_deviceEtat = $row["cmd_deviceEtat"];
		$LieuxNom = $row["LieuxNom"];
		$Module_Type = $row["Module_Type"];
		$Configuration =  $row["Configuration"];
		$Notification = $row["Notification"];
		$cmd_deviceId = $row["cmd_deviceId"];
		$curDate = date(DATE_ISO8601);
		/*if (isset(json_decode($Configuration)->Notification))
		{
			$Notification = json_decode($Configuration)->{'Notification'};
		}*/



		/*if($Date_temp != $lastdate_temp)
		{
			$info = "UpdateTempDetected";
			$lastdate_temp = $Date_temp;
			echo 'data:{"lastTypeupdate" :"'.$info.'", "deviceNom" : "'.$cmd_deviceNom.'", "deviceValue" : "'.$cmd_deviceValue.'", "deviceEtat" : "'.$cmd_deviceEtat.'", "LieuxNom": "'.$LieuxNom.'", "DeviceType" : "'.$Type_Device.'", "Notification":"'.$Notification.'"}';
			echo "\n\n";
		}*/

		if($Date_Etat != $lastdate_Etat)
		{
			$info = "UpdateDeviceDetected";
			$lastdate_Etat = $Date_Etat;
			echo 'data:{"cmd_deviceId":"'.$cmd_deviceId.'", "lastTypeupdate" :"'.$info.'", "deviceNom" : "'.$cmd_deviceNom.'", "deviceValue" : "'.$cmd_deviceValue.'", "deviceEtat" : "'.$cmd_deviceEtat.'", "LieuxNom": "'.$LieuxNom.'", "DeviceType" : "'.$Module_Type.'", "Notification":"'.$Notification.'"}';
			echo "\n\n";
		}
	}
	ob_flush();
	flush();
	sleep(1);
}
?>
