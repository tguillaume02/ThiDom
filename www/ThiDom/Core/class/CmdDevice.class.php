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
	protected $DateRAZ;
	protected $RAZ;
	protected $Widget_Id;
	protected $Visible;
	protected $Type;	
	protected $Unite;
	protected $History;
	protected $Notification;

/*	public function __construct() {
		$this->Id = "";
		$this->Nom = "";
		$this->Device_Id = "";
		$this->DeviceId = "";
		$this->Sensor_attachId = "";
		$this->Value = "";
		$this->Etat = "";
		$this->Date = "";
		$this->DateRAZ = "";
		$this->RAZ = "";
		$this->Widget_Id = "";
		$this->Visible = 0;
		$this->Type = "";
		$this->Unite = "";
		$this->History = 0;
		$this->Notification = 0;
	}*/

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
	
	public static function byDataRequest($data, $contains=0)
	{
		if ($contains == 1)
		{
			$data = '%'.$data.'%';
		}

		$values = array(
			':data' => $data,
			);
			
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name;

		if ($contains == 1)
		{
			$sql .= ' WHERE Request like :data';
		}
		else
		{
			$sql .= ' WHERE Request=:data';
		}
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public function GetCmdId($Name,$Device_Id)
	{
		$values = array(
			':Name' => $Name,
			':Device_Id' => $Device_Id
			);

		$sql = 'SELECT Id, Unite, RAZ FROM '.self::table_name.' WHERE Nom=:Name and Device_Id = :Device_Id';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public function GetAllCmdDevice()
	{		
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
				FROM '.self::table_name;

		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);
	}	

	public function GetAllCmdDeviceWithLieux()
	{		
		$sql = "SELECT cmd_device.*, Lieux.Nom as LieuxNom
				FROM ".self::table_name ."				
				INNER JOIN Device on Device.Id =  ".self::table_name.".Device_Id
				INNER JOIN Lieux on Lieux.Id = Device.Lieux_Id";
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);
	}

	public function GetValueSensorAttached($PinId, $Carte_Id)
	{

		$values = array(
			':PinId' => $PinId,
			':Carte_Id' => $Carte_Id
			);

		$sql = "SELECT Value 
					FROM ".self::table_name."
				WHERE Id= (select sensor_attachID 
							FROM ".self::table_name."
							INNER JOIN Device on Device.Id = ".self::table_name.".Device_ID  
							WHERE DeviceId = :PinId and CarteId= :Carte_Id  and sensor_attachId <> -1
							)";
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}	

	public function GetValueAndEtatByIdAndLieux($Id, $LieuxId)
	{
		$values = array(
			':Id' => $Id,
			':LieuxId' => $LieuxId
		);

		$sql = " SELECT cmd_device.Value, cmd_device.Etat, cmd_device.Unite, Lieux.Nom as LieuxNom, Device.Nom as DeviceNom
					FROM Device 
						INNER JOIN cmd_device ON cmd_device.Device_Id = Device.Id
						LEFT JOIN Lieux ON Lieux.Id = Device.Lieux_Id
					WHERE Lieux.Id =:LieuxId AND Device.Id = :Id";
		return db::execQuery($sql, $values, db::FETCH_TYPE_ALL);
	}

	public function byCmdId_WithCmd($CmdId)
	{
		$values = array(
			':Id' => $CmdId
			);
		$sql = 'SELECT cmd_device.nom as Cmd_nom, Device.nom as Device_nom, CarteId, DeviceId, Value, Etat, widget.Name as WidgetName
					FROM cmd_device 
					INNER JOIN Device on Device.Id = cmd_device.Device_Id 
					INNER JOIN widget on widget.Id = cmd_device.Widget_Id
				WHERE cmd_device.Id  = :Id ';
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

	public function Update_CmdDeviceName()
	{
		$req = "UPDATE cmd_device INNER JOIN Device ON Device.Id = cmd_device.Device_Id SET cmd_device.Nom = Device.Nom  WHERE cmd_device.Nom = 'New Device';";
		db::execQuery($req, []);
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
		db::execQuery($req, $values);

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

	public function Update_Request($Id,$colonne,$value)
	{
		/*
		$RequestJson = json_decode($Request, true);		
		$RequestJson[$colonne] = $value;
		$json = json_encode($RequestJson);*/

		$values = array(
			":Id" => $Id,
			':value' => cmdDevice::byId($Id)->set_request($colonne, $value)->get_Request()
			);			
		$req = "UPDATE cmd_device set Request=:value WHERE Id=:Id" ;		
		db::execQuery($req,$values);
		
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

	public function CmdDeviceNewId()
	{		
		$sql = "SELECT MAX(Id) as Id FROM cmd_device ";
		
		return db::execQuery($sql,[], db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
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
		return $this;
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

	public function set_dateRAZ($dateRaz)
	{
		$this->DateRAZ = $dateRaz;
		return $this;
	}

	public function set_raz($raz)
	{
		$this->RAZ = $raz;
		return $this;
	}

	public function set_visible($visible)
	{
		$this->Visible = $visible;
		return $this;
	}

	public function set_WidgeId($widget_Id)
	{
		$this->Widget_Id = $widget_Id;
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

	public function get_Request($RequestName="")
	{
		if ($RequestName != "")
		{
			return json_decode($this->Request)->$RequestName;
		}
		else
		{
			return $this->Request;
		}
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

	public function get_DateRAZ()
	{
		return $this->DateRAZ;
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

	public function get_WidgetId()
	{
		return $this->Widget_Id;
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
		
		$column = "";
		$val = "";
		foreach($this as $key => $value)
		{
			if ($value != '')
			{
				$column .= $key.",";
				$val .= "'".$value."',";
			}
		}

		if ($column != "" && $val != "")
		{
			$column = rtrim($column,",");
			$val = rtrim($val,",");
			$dbInsert =  "INSERT INTO ".$this::table_name." (". $column ." ) VALUES ( ". $val ." )";
			db::execQuery($dbInsert,[]);

			$value = Array("cmddeviceId" => $this->CmdDeviceNewId()->get_Id());
			return json_encode($value);
		}
		//echo "INSERT INTO ".$object::table_name." SET ";

		//return db::save($this);
	}

	public function showCommandeListHtml($deviceId, $toggleValue="", $dataParam="")
	{
		require(__DIR__."/../../Desktop/Template_CmdConfiguration.php");
	}

	public function getDesactivateConditions($cmd_device_id)
	{
		$dataDesactivateConditions =  '<div class="form-group">
		<label for="hysteresis" class="col-lg-2 control-label">Desactiver si </label>	
		<div class="control-group">
			<div class="controls form-inline">
				<select id="sensorToDesactivate" name="sensorToDesactivate"  cmdid="'.$cmd_device_id.'" class="form-control">
					<option value="">-</option>';
					$cmdDeviceToAttachArray = self::GetAllCmdDeviceWithLieux();
					foreach($cmdDeviceToAttachArray as $donneesCmdDevice)
					{								
						$isDefined = "";
						/*if ()
						{
							$isDefined = "selected";
						}*/
						$dataDesactivateConditions.= "<option value='" . $donneesCmdDevice["Id"] . "' ".$isDefined.">" . $donneesCmdDevice["Nom"] . " - ". $donneesCmdDevice["LieuxNom"] ."</option>"; 
					}
		$dataDesactivateConditions.= '</select>
					<select id="list-valueEtat-desactivateConditions" cmdid="'.$cmd_device_id.'" class="form-control">
						<option value="value">Value =</option>
						<option value="Etat">Etat = </option>
					</select>
					<input id="Data-valueEtat-desactivateConditions"  cmdid="'.$cmd_device_id.'" class="form-control"></input>
				</div>
			</div>
		</div>';
		return $dataDesactivateConditions;
	}
}
?>