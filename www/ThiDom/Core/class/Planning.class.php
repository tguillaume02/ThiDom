<?php
class Planning
{
	const table_name = 'Planning';

	public function GetAllPlanning() 
	{
		$sql = "SELECT Planning.Id,  Planning.Date, Planning.Days, Planning.Hours, Planning.Status, Device.Id as DeviceId, cmd_device.Id as CmdDevice_Id, cmd_device.Nom as DeviceName, Lieux.Nom as LieuxName, widget.Type as WidgetType, Planning.Activate
		from Planning 
		inner join cmd_device on cmd_device.Id  = Planning.CmdDevice_Id 
		inner join Device on Device.Id = cmd_device.Device_Id 
		inner join Lieux on Lieux.Id = Device.Lieux_Id 
		inner join widget on cmd_device.Widget_Id = widget.Id ";
		/*where Activate = 1";*/
		return db::execQuery($sql,[]);
	}

	public function AddPlanning($planningId, $deviceId, $active, $commande, $dateheure, $eachTime, $eachPeriod, $days)
	{   
		$date = $hours = "";
		$active = $active + 0;
		if($dateheure)
		{
			$time = new DateTime($dateheure);
			$date = $time->format('Y/m/d');
			$hours = $time->format('H:i');
		}
		$values = array(
			':deviceId' => $deviceId,
			':active' => $active,
			':commande' => $commande,
			':date' => $date,
			':hours' => $hours,
			':days' => $days
			);

		if ($commande != "" && $hours != "")
		{
			if ($planningId)
			{
				$values[':planningId'] = $planningId;
				$sql = " UPDATE ".self::table_name." SET CmdDevice_Id=:deviceId, Date=:date, Days=:days, Hours=:hours, Status=:commande, Activate=:active WHERE Id=:planningId";
			}
			else
			{
				$sql = " INSERT INTO ".self::table_name." (CmdDevice_Id, Date, Days, Hours, Status, Activate) VALUES (:deviceId, :date, :days, :hours, :commande, :active)";
			}

			db::execQuery($sql, $values);

			$msg = "L'evenement a bien été ajouté au calendrier";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
		else
		{
			$msg = "Une action ainsi qu'une date doivent être renseigné au minimum";
			$value=  Array( "msg"=>$msg, "clear"=>"on", "status"=>"error");
			return json_encode($value);
		}
		
	}

	public function DeletePlanning($planningId)
	{   
		if ($planningId)
		{
			$values[':planningId'] = $planningId;

			$sql = " DELETE FROM ".self::table_name." WHERE Id=:planningId";
			db::execQuery($sql, $values);

			$msg = "L'evenement a bien été supprimé du calendrier";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);			
		}
		else
		{
			$msg = "L'evenement n'a pu être supprimé, veuillez reesayer";
			$value = Array( "msg"=>$msg, "clear"=>"on", "status"=>"error");
			return json_encode($value);
		}

	}
}
?>