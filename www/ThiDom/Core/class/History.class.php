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