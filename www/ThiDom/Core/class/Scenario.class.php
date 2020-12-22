<?php
class Scenario
{
	const table_name = 'Scenario';

	protected $Id;
	protected $status;

	public function GetListScenario()
	{
		$sql =  " SELECT Id, Name,XML,Status FROM Scenario_Xml";
		return db::execQuery($sql,[]);
	}

	public function SaveScenario($id, $name, $update, $xmlscenario, $status, $logicArray )
	{
		
		if ($id-1 == -1)
		{
			$sql = "select COALESCE(max(Id)+1,1) as max from Scenario_Xml";
			$IdMaxScenario = db::execQuery($sql,[]);

			foreach($IdMaxScenario as $donnees)
			{
				$id = $donnees['max'];
			}
		}
		$SequenceNo = 1;
		$error = 0;
		$obj_LogiArray = json_decode( $logicArray, true);
		foreach ($obj_LogiArray as $key => $value)
		{
			foreach ($value as $k => $v)
			{
				foreach ($v as $x => $z)
				{
					if ($x."" == "conditions")
					{
						$conditions = $z;
					}
					elseif ($x."" == "actions")
					{
						$actions = $z;
					}
				}
				if ($conditions."" != "" and $actions."" != "")
				{
					$valuesScenario = array(
						':id' => $id,
						':conditions' => $conditions,
						':actions' => $actions,
						':SequenceNo' => $SequenceNo
						);

					$valuesScenarioXML = array(
						':id' => $id,
						':name' => $name,
						':xmlscenario' => $xmlscenario,
						':status' => $status
						);

			    	//echo"insert into Scenario (XmlID, Conditions, Actions,SequenceNo ) values((select MAX(Id) from Scenario_Xml), '$conditions', '$actions','$SequenceNo')";
					if ($update == "false")
					{
						$sql = "INSERT INTO Scenario_Xml(Id,Name,XML,Status) VALUES( :id, :name, :xmlscenario, :status )" ;
						$nbScenarioXmlInsert = db::getNbResult($sql,$valuesScenarioXML);
						$sql = "INSERT INTO Scenario (XmlID, Conditions, Actions,SequenceNo ) VALUES (:id, :conditions, :actions, :SequenceNo);" ;
						$nbScenarioInsert = db::execQuery($sql, $valuesScenario);

						if ($nbScenarioXmlInsert > 0 && $nbScenarioInsert > 0 && $error == 0)
						{
							$msg = "Scenario enregistré";
							$valueResult = Array( "msg"=>$msg, "clear"=>"on");
//							return json_encode($value);
						}
						else
						{
							$error = 1;
						}
					}
					else
					{
						if ($SequenceNo == 1)
						{
							$sql = "DELETE from Scenario where xmlID = :id;";
							db::execQuery($sql,  array(':id' => $id));
						}
						$sql = "UPDATE Scenario_Xml set XML = :xmlscenario ,Status = :status, Name=:name where Id = :id; " ;
						$nbScenarioXmlUpdate = db::getNbResult($sql,$valuesScenarioXML);
						$sql = "INSERT INTO Scenario (XmlID, Conditions, Actions,SequenceNo ) values (:id, :conditions, :actions, :SequenceNo);";
						$nbScenarioInsert = db::getNbResult($sql,$valuesScenario);
						if ($nbScenarioXmlUpdate > 0 || $nbScenarioInsert > 0)
						{
							$msg = "Scenario mis à jour";
							$valueResult = Array( "msg"=>$msg, "clear"=>"on");
//							return json_encode($value);
						}
					}
				}
				$SequenceNo  = $SequenceNo+1;
			}
		}
	return json_encode($valueResult);
	}

	public function DeleteScenario($id)
	{
		$values = array(
			':id' => $id
		);
		$sql = 'DELETE FROM Scenario WHERE XmlID=:id; DELETE FROM Scenario_Xml Where Id = :id';
		$nbScenarioDelete = db::getNbResult($sql,$values);
		if ($nbScenarioDelete > 0)
		{
			$msg = "Scenario supprimé";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
//		$sql = "DELETE Scenario WHERE XmlID = :id; Delete Scenario_Xml Where Id = :id" ;
//		return db::execQuery($sql, $values);
	}

	public function ExecIfTrigger($text)
	{
		$Trigger = $this->getScenarioByTriggerName($text);
		if (is_a($Trigger, self::table_name))
		{
			$id = $Trigger->get_ScenarioId();
			if ($id)
			{
				$this->ForceExecution($id);
			}
		}	
	}

	public function getScenarioByTriggerName($name)
	{
		$values = array(
			':keyword' => '%ControlCalling = '.$name
		);		
		$sql = "SELECT * FROM Scenario WHERE conditions LIKE :keyword";
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public function ForceExecution($id)
	{
		$values = array(
			':id' => $id
		);
		$sql = 'UPDATE Scenario set ToExecute=1 WHERE Id=:id';
		$nbScenarioUpdate = db::getNbResult($sql,$values);
	}

	
	public function get_ScenarioId()
	{
		return $this->Id;
	}
}
