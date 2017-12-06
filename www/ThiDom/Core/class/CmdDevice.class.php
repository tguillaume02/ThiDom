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
	protected $Widget_Id;
	protected $Visible;
	protected $Type;	
	protected $Unite;
	protected $History;
	protected $Notification;

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

	public static function byWidget_Id($Widget_Id)
	{
		$values = array(
			':Widget_Id' => $Widget_Id,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Widget_Id=:Widget_Id';
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
		$sql = 'SELECT cmd_device.nom as Cmd_nom, Device.nom as Device_nom, CarteId, DeviceId, Value, Etat, widget.Name as WidgetName
					FROM cmd_device 
					INNER JOIN Device on Device.Id = cmd_device.Device_Id 
					INNER JOIN widget on widget.Id = cmd_device.Widget_Id
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

	public function Update_Device_Value($DeviceId, $Value, $Etat, $Name1)
	{
		$values = array(
			":DeviceId" => $DeviceId,
			':Value' => $Value,
			':Etat' => $Etat,
			':Name1' => $Name1
			);	

		$req = "UPDATE cmd_device INNER JOIN Device on Device.Id = cmd_device.Device_Id  SET cmd_device.Value =:Value , cmd_device.Etat =:Etat, cmd_device.Date =now() WHERE cmd_device.Device_Id = :DeviceId and cmd_device.Nom = :Name1";
		db::execQuery($req,$values);

		/*if ($type == 8) // PLUGINS
		{*/
		/*	$values1 = array(
				":DeviceId" => $DeviceId,
				':Name1' => $Name1
				);	

			$req = "SELECT RAZ,cmd_device.Id as cmd_id  
			FROM cmd_device 
			INNER JOIN Device on cmd_device.Device_ID = Device.Id 
			WHERE cmd_device.Device_Id = :DeviceId and cmd_device.Nom = :Name1";

			$ResultFectAll =  db::execQuery($req,$values1);
			foreach ($ResultFectAll as $donnees)
			{
				$RAZ = $donnees["RAZ"];
				$cmd_id = $donnees["cmd_id"];

				$IntervRAZ = date("i")%$RAZ;

				$ValUpdate = array(
					':interval' => $IntervRAZ,
					':cmd_id' => $cmd_id
					);

				$req = "UPDATE cmd_device set date =(select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') - INTERVAL :interval MINUTE)  WHERE Id = :cmd_id";
				db::execQuery($req,$ValUpdate);
			}*/
		/*}*/
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

	public function set_Name($name)
	{
		$this->Nom = str_replace(array('&', '#', ']', '[', '%', "'"), '', $name);
		return $this;
	}

	public function set_device_Id($Id)
	{
		$this->Device_Id = $Id;
		return $this;
	}

	public function set_deviceId($Id)
	{
		$this->DeviceId = $Id;
		return $this;
	}

	public function set_sensorAttachId($id)
	{
		$this->Sensor_attachId = $id;
		return $this;
	}

	public function set_request($key, $value)
	{		
		$this->Request = setJsonAttr($this->Request, $key, $value);
	}

	public function set_value($value)
	{
		$this->Value = $value;
		return $this;
	}

	public function set_etat($etat)
	{
		$this->Etat = $etat;
		return $this;
	}

	public function set_date($date)
	{
		$this->Date = $date;
		return $this;
	}

	public function set_alertTime($alertTime)
	{
		$this->Alert_Time = $alertTime;
		return $this;
	}

	public function set_raz($raz)
	{
		$this->RAZ = $raz;
		return $this;
	}

	public function set_typeId($TypeId)
	{
		$this->Type_Id = $TypeID;
		return $this;
	}

	public function set_visible($visible)
	{
		$this->Visible = $visible;
		return $this;
	}

	public function set_type($type)
	{
		$this->Type = $type;
		return $this;
	}

	public function set_unite($unite)
	{
		$this->Unite = $unite;
		return $this;
	}

	public function set_history($history)
	{
		$this->History = $history;
		return $this;
	}

	public function set_notification($notification)
	{
		$this->Notification = $notification;
		return $this;
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
		return $this->Type;
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
		return $this->History;

	}

	public function get_Notification()
	{
		return $this->Notification;
	}

	public function save()
	{
		if ($this->get_Name() == '')
		{
			throw new Exception('Le nom de la commande ne peut pas être vide :' . print_r($this, true));
		}
		if ($this->get_Type() == '')
		{
			throw new Exception('Le type de la commande ne peut pas être vide :' . print_r($this, true));
		}

		if ($this->get_Device_Id() == '')
		{
			throw new Exception('Vous ne pouvez pas créer une commande sans la rattacher à un équipement' . print_r($this, true));
		}
		return db::save($this);
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
										<input type="checkbox" name="History" id="cmddevice-historiser" cmdid ="'.$Cmd->get_Id().'" '.($Cmd->get_History()? "checked" : "").'>
										Historiser
									</label>	
									<label class="btn btn-success">
										<input type="checkbox" name="Notification" id="cmddevice-notification" cmdid ="'.$Cmd->get_Id().'" '.($Cmd->get_Notification() ? "checked" : "").'>
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