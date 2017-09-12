<?php

class CmdDevice
{
	const table_name = 'cmd_device';

	protected $Id;
	protected $Nom;
	protected $Device_Id; // Device Parent
	protected $DeviceId; // Id du device attaché à la carte
	protected $Sensor_attachId;
	protected $Request;
	protected $Value;
	protected $Etat;
	protected $Date;
	protected $Alert_Time;
	protected $RAZ;
	protected $Type_Id;
	protected $Visible;
	protected $Type;	
	protected $Unite;



	public static function byId($Id)
	{
		$values = array(
			':Id' => $Id,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Id=:Id';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byNom($Nom)
	{
		$values = array(
			':Nom' => $Nom,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Id=:Id';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byDevice_Id($Device_Id)
	{
		$values = array(
			':Device_Id' => $Device_Id,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Device_Id=:Device_Id';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function bySensorAttachId($Sensor_attachId)
	{
		$values = array(
			':Sensor_attachId' => $Sensor_attachId,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Sensor_attachId=:Sensor_attachId';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byType_Id($Type_Id)
	{
		$values = array(
			':Type_Id' => $Type_Id,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Type_Id=:Type_Id';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byVisible($Visible)
	{
		$values = array(
			':Visible' => $Visible,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Visible=:Visible';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public function GetCmdId($Name,$Device_Id)
	{
		$values = array(
			':Name' => $Name,
			':Device_Id' => $Device_Id
			);

		$sql = 'SELECT Id, Unite FROM cmd_device WHERE Nom=:Name and Device_Id = :Device_Id';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public function GetValueSensorAttached($PinId, $Carte_Id)
	{

		$values = array(
			':PinId' => $PinId,
			':Carte_Id' => $Carte_Id
			);

		$sql = "SELECT Value 
					FROM cmd_device 
				WHERE Id= (select sensor_attachID 
							FROM cmd_device 
							INNER JOIN Device on Device.Id = cmd_device.Device_ID  
							WHERE DeviceId = :PinId and CarteId= :Carte_Id  and sensor_attachId <> -1
							)";
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}	

	public function byDevice_IdWithCmd($Device_Id)
	{
		$values = array(
			':Id' => $Device_Id
			);
		$sql = 'SELECT cmd_device.nom as Cmd_nom, Device.nom as Device_nom, CarteId, DeviceId, Value, Etat, Device.Type_Id 
					FROM cmd_device 
					INNER JOIN Device on Device.Id = cmd_device.Device_Id 
				WHERE cmd_device.Device_Id  = :Id ';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ALL);
	}

	public function Update_Status_By_id($Id,$value,$Etat)
	{
		$values = array(
			':Id' => $Id,
			':value' => $value,
			':Etat' => $Etat
			);	
		$req = "UPDATE cmd_device set Value=:value, Etat=:Etat WHERE Device_ID=:Id" ;
		db::execQuery($req,$values);
	}

	public function Update_Device_Value($Value,$Etat,$Name1,$type,$Name2)
	{
		$values = array(
			':Value' => $Value,
			':Etat' => $Etat,
			':Name1' => $Name1,
			':type' => $type,
			':Name2' => $Name2
			);	

		$req = "UPDATE cmd_device INNER JOIN Device on Device.Id = cmd_device.Device_Id  SET cmd_device.Value =:Value , cmd_device.Etat =:Etat, cmd_device.Date =now() WHERE cmd_device.Nom = :Name1 and Device.Type_ID =:type and Device.Nom = :Name2";
		db::execQuery($req,$values);

		if ($type == 8) // PLUGINS
		{
			$values1 = array(
				':Name1' => $Name1,
				':type' => $type,
				':Name2' => $Name2
				);	

			$req = "SELECT RAZ,cmd_device.Id as cmd_id  
			FROM cmd_device 
			INNER JOIN Device on cmd_device.Device_ID = Device.Id 
			WHERE cmd_device.Nom = :Name1 and Device.Type_ID =:type and Device.Nom = :Name2";

			$ResultFectAll =  db::execQuery($req,$values1);
			foreach ($ResultFectAll as $donnees) {
				$RAZ = $donnees["RAZ"];
				$cmd_id = $donnees["cmd_id"];

				$IntervRAZ = date("i")%$RAZ;

				$ValUpdate = array(
					':interval' => $IntervRAZ,
					':cmd_id' => $cmd_id
					);

				$req = "UPDATE cmd_device set date =(select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') - INTERVAL :interval MINUTE)  WHERE Id = :cmd_id";
				db::execQuery($req,$ValUpdate);
			}
		}
	}

	public function Update_Any_Value_By_id($Id,$colonne,$value)
	{
		if ($colonne == "RAZ")
		{
			if ($value != "")
			{
				$time = explode(':', $value);
				$value = ($time[0]*3600) + ($time[1]*60) + $time[2];
				$value = $value == 0 ? null : $value;
			}
			else
			{
				$value = null;
			}
		}
		//if ($colonne == "Visible")
		//{
			$values = array(
				':Id' => $Id,
				//':Colonne' => $colonne,
				':Value' => $value
				);	
			if ($colonne != "CmdDeviceid")
			{
				if ($Id != null)
				{
					$req = "UPDATE cmd_device set ".$colonne."=:Value WHERE Id=:Id" ;
					db::execQuery($req,$values);
				}
			}
		//}
	}

	public function get_Id()
	{
		return $this->Id;
	}

	public function get_Name()
	{
		return $this->Nom;
	}

	public function get_Device_Id()
	{
		return $this->Device_Id;
	}

	public function get_DeviceId()
	{
		return $this->DeviceId;
	}

	public function get_SensorAttachId()
	{
		return $this->sensor_attachId;
	}

	public function get_Type()
	{
		return $this->type;
	}

	public function get_Request()
	{
		return $this->Request;
	}

	public function get_Value()
	{
		return $this->Value;
	}

	public function get_Etat()
	{
		return $this->Etat;
	}

	public function get_Date()
	{
		return $this->Date;
	}

	public function get_Alert_Time()
	{
		return $this->Alert_Time;
	}

	public function get_RAZ()
	{
		return $this->RAZ;
	}

	public function get_TypeId()
	{
		return $this->Type_Id;
	}

	public function get_Visible()
	{
		return $this->Visible;
	}

	public function get_Unite()
	{
		return $this->Unite;
	}

	public function get_History()
	{

	}

	public function get_Notification()
	{

	}

	public function showCommandeListHtml($deviceId)
	{
		$CmdOfDevice = CmdDevice::byDevice_Id($deviceId);
		$CmdDeviceList = "";
		foreach($CmdOfDevice as $Cmd)
		{			
			$CmdDeviceList .= '
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" style="margin-bottom: 1vh;">
							<div class="row">
								<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">
								'.$Cmd->get_Name().'
								</div>
								<div class="col-xs-10 col-sm-12 col-md-12 col-lg-9">      
									<label class="btn btn-success">
										  <input type="checkbox" name="Visible" id="cmddevice-visible" cmdid ="'.$Cmd->get_Id().'" '.($Cmd->get_Visible() ? "checked" : "").'>
										  Visible
									</label>
									<label class="btn btn-success">
										<input type="checkbox" name="Historiser" id="device-historiser" cmdid ="'.$Cmd->get_Id().'" '.($Cmd->get_History()? "checked" : "").'>
										Historiser
									</label>	
									<label class="btn btn-success">
										<input type="checkbox" name="Notification" id="device-notification" cmdid ="'.$Cmd->get_Id().'" '.($Cmd->get_Notification() ? "checked" : "").'>
										Notification
									</label>
									<label class="col-sm-5 col-xs-6 col-md-12 col-lg-5" style="padding: 0;">Remise à Zero / màj : </label>
									<div class="col-sm-5 col-xs-5 col-md-5 col-lg-5" style="padding: 0;">
										<input type="time" class="form-control" id="raz-value" step="1" name="RAZ" placeholder="Remise à zero apres: HH:MM:SS" cmdid ="'.$Cmd->get_Id().'" value="'.gmdate("H:i:s", $Cmd->get_RAZ()).'">
									</div>
								</div>
							</div>
						</div>';		
							/*<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
								<div class="row">			
									<div class="class=" col-lg-12="" col-md-12="" col-sm-12="">
										<label class="btn btn-success">
											<input type="checkbox" name="DeviceHistoriser" id="device-historiser" value="0">Historiser
										</label>			
									</div>
								</div>
					      	</div>
							<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
								<div class="row">
									<div class="class=" col-lg-12="" col-md-12="" col-sm-12="">
										<label class="btn btn-success">
											<input type="checkbox" name="DeviceNotification" id="device-notification" value="0">Notification
										</label>			
									</div>
								</div>
							</div>*/
		}

		if ($CmdDeviceList != "")
		{
			echo '<div class="row">'.$CmdDeviceList.'</div>';
		}
	}

}
?>