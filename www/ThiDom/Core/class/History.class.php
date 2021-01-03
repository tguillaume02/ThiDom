<?php
class History
{
	protected $Id;
	protected $DeviceId;
	protected $Date;
	protected $Action;
	protected $Message;

	const table_name = 'Log';

	public function AllLog()
	{
		$sql = 'SELECT Id, /*DATE_FORMAT(Date,"%d %b %Y %T") AS Date*/ Date ,Action, Message FROM Log ORDER BY ID desc ';
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);
	}

	public function LastLogbyId($id)
	{
		$values = array(
			':id' => $id,
			);

		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE DeviceID=:id order by date desc  LIMIT 3 ';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ALL);
	}
	
	public function RemoveLog()
	{
		$sql = "DELETE FROM Log";		
		$nbLogDelete = db::getNbResult($sql,[]);

		if ($nbLogDelete > 0)
		{
			$msg = "Les Logs ont bien été supprimé";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
	}

	public function AddLog($cmdDeviceId, $etat, $value)
	{
		if ($value == "")
		{
			$value = $etat;
		}
		$values = array(
			':year' => date("Y"),
			':id' => $cmdDeviceId
		);

		$sql = 'SELECT cmd_device.History, data, Device.Lieux_Id FROM cmd_device INNER JOIN Device ON Device.id = cmd_device.Device_Id  LEFT JOIN HistoryData ON Cmd_device_Id = cmd_device.Id AND YEAR=:year where cmd_device.Id =:id';
		$result = db::execQuery($sql, $values, db::FETCH_TYPE_ALL);
		if ($result[0]["History"] == 1)
		{
			$lieuxId = $result[0]["Lieux_Id"];
			$date = new DateTime();
			$date =new DateTime($date->format("1987-m-d H:i:s"));
			$timestamp =  $date->getTimestamp()*1000;
			if ($result[0]["data"] != null)
			{
				$dataNew = strval($result[0]["data"]).",[".$timestamp.",".strval($value)."]";
				
				$values = array(
					":data" => $dataNew,
					':lieuxId' => $lieuxId,
					':year' => date("Y"),
					':id' => $cmdDeviceId
				);

				$sql = "UPDATE HistoryData set Data =:data where Lieux_Id =:lieuxId and Year =:year and Cmd_device_Id =:id";
				db::execQuery($sql, $values, db::FETCH_TYPE_ALL);
			}
			else
			{
				$value = "[".$timestamp.",".strval($value)."]";
				$values = array(
					':year' => date("Y"),
					":data" => $value,
					':lieuxId' => $lieuxId,
					':id' => $cmdDeviceId
				);
				$sql = "INSERT INTO HistoryData ( Year, Data, Lieux_id, Cmd_device_Id) VALUES (:year, :data, :lieuxId, :id)";
				db::execQuery($sql,$values);
			}
		}
	}

	public function getId()
	{
		return $this->Id;
	}

	public function getDeviceId()
	{
		return $this->DeviceId;
	}

	public function getDate()
	{
		return $this->Date;
	}
	
	public function getAction()
	{
		return $this->Action;
	}
	
	public function getMessage()
	{
		return $this->Message;
	}
}