<?php
class Temperature
{
	const table_name = 'Temperature';

	public function GetAllTemperatureByLieux($Lieux_ID) 
	{
		$values = array(
			':Lieux_ID' =>$Lieux_ID
			);
		$sql = " SELECT date, Temp FROM ".self::table_name." where Lieux_ID=:Lieux_ID ORDER BY date";		
		return db::execQuery($sql,$values);
	}
	
	public function GetTemperatureForAllLieux() 
	{
		$sql = "SELECT Lieux.Nom as Lieux, Device.Nom as DeviceName, date_format(cmd_device.DATE, '%Y-%m-%d %H:%i') as Date,Round(Value,1) as Temp, Type_Device.Widget, cmd_device.Id as cmd_device_id, Device.id  AS id  from Type_Device 
		inner join Device on Device.Type_Id = Type_Device.ID
		inner join cmd_device on cmd_device.Device_Id = Device.ID
		inner join Lieux on Device.Lieux_Id = Lieux.ID
		where Type_Device.Type like '%Temperature%'";
		return db::execQuery($sql,[]);
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
		$sql = " SELECT * FROM ".self::table_name." ORDER BY Temperature.date, Temperature.lieux";

		return db::execQuery($sql,null);
	}

	public function GetDifferenceTemperature($DeviceId)
	{
		$values = array(
			':DeviceId' =>$DeviceId
			);

		$sql = " SELECT Temperature.temp FROM Temperature where cmd_device_ID = :DeviceId order by date desc limit 1";
		return db::execQuery($sql,$values);
	}

}