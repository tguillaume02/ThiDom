<?php
class Temperature
{
	const table_name = 'Temperature';

	public function GetAllCmdDeviceIdWithTemp()
	{
		$sql = "select cmd_device_Id from Temperature group by cmd_device_Id";
		return db::execQuery($sql,null);
	}

	public function GetAllTemperatureByLieux($Lieux_ID) 
	{
		$values = array(
			':Lieux_ID' =>$Lieux_ID
			);
		$sql = " SELECT date, Temp FROM ".self::table_name." where Lieux_ID=:Lieux_ID ORDER BY date";		
		return db::execQuery($sql,$values);
	}

	public function GetTemperatureTempByLieux($Lieux,$cmd_device_id) 
	{
		$values = array(
			':Lieux' =>$Lieux,
			':cmd_device_id' => $cmd_device_id
			);

		$sql = " SELECT Temp
		FROM Temperature_Temp
		WHERE Lieux = :Lieux and Cmd_device_Id = :cmd_device_id
		ORDER BY DATE DESC  LIMIT 3";

		return db::execQuery($sql,$values);
	}

	public function GetTemperatureHistory()
	{
		$sql = " SELECT * FROM ".self::table_name." ORDER BY ".self::table_name.".date, ".self::table_name.".lieux";

		return db::execQuery($sql,null);
	}

	public function GetTemperatureHistoryOnOneMonth()
	{
		$sql = "SELECT t.*, Lieux.Nom, cmd_device.Nom as cmd_deviceName FROM (SELECT * FROM ".self::table_name." 
				WHERE ".self::table_name.".date > ( SELECT CURRENT_DATE - INTERVAL 2 MONTH )
				UNION ALL
				SELECT * FROM ".self::table_name." 
				WHERE (".self::table_name.".date BETWEEN CURRENT_DATE - INTERVAL 1 Year  - INTERVAL 2 Month AND  CURRENT_DATE - INTERVAL 1 Year  + INTERVAL 2 Month)
                ORDER BY Lieux_Id, cmd_device_Id, date
                ) AS t 
                LEFT JOIN cmd_device on cmd_device.Id = t.cmd_device_Id
                LEFT JOIN Device on Device.Id = cmd_device.Device_Id
                LEFT JOIN Lieux on Lieux.Id = Device.Lieux_Id";
		
		return db::execQuery($sql,null);
	}

	public function GetTemperatureHistoryByCmdDeviceId($cmdDeviceId)
	{
		$values = array(
			':cmdDeviceId' =>$cmdDeviceId
			);

		$sql = "SELECT t.*, Lieux.Nom, cmd_device.Nom as cmd_deviceName FROM (SELECT date, temp, Lieux_Id, cmd_device_Id FROM ".self::table_name." 
                WHERE cmd_device_Id = :cmdDeviceId
                ORDER BY Lieux_Id, cmd_device_Id, date
                ) AS t 
                LEFT JOIN cmd_device on cmd_device.Id = t.cmd_device_Id
                LEFT JOIN Device on Device.Id = cmd_device.Device_Id
                LEFT JOIN Lieux on Lieux.Id = Device.Lieux_Id";
		
		return db::execQuery($sql,$values);
	}

	public function GetAllTemperature()
	{

		$sql = "SELECT t.*, Lieux.Nom, cmd_device.Nom as cmd_deviceName FROM (
					SELECT date, temp, Lieux_Id, cmd_device_Id FROM ".self::table_name."
						WHERE year(date) = ".date("Y")." 
                		ORDER BY Lieux_Id, cmd_device_Id, date
                	) AS t 
                LEFT JOIN cmd_device on cmd_device.Id = t.cmd_device_Id
                LEFT JOIN Device on Device.Id = cmd_device.Device_Id
                LEFT JOIN Lieux on Lieux.Id = Device.Lieux_Id ";
		
		return db::execQuery($sql);
	}

	public function GetDifferenceTemperature($DeviceId)
	{
		$values = array(
			':DeviceId' =>$DeviceId
			);

		$sql = " SELECT Temperature.temp FROM ".self::table_name." WHERE cmd_device_ID = :DeviceId ORDER BY date DESC LIMIT 1";
		return db::execQuery($sql,$values);
	}

}