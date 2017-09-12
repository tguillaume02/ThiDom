<?php

class Device
{
	const table_name = 'Device';

	protected $Id;
	protected $Nom;
	protected $CarteId = '';
	protected $Configuration;
	protected $LieuxId = null;
	protected $TypeId = null;
	protected $Visible = 0;
	protected $History = 0;

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
		WHERE Nom=:Nom';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
		
	}

	public function byNode($Node)
	{
		$values = array(
			':Node' => $Node,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE CarteId=:Node';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public function byLieux($LieuxId)
	{
		$values = array(
			':LieuxId' => $LieuxId,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Lieux_ID = :LieuxId';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
		
	}

	public function byType($TypeId)
	{
		$values = array(
			':TypeId' => $TypeId,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Type_ID = :TypeId';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public function byVisible()
	{
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Visible = 1';
		return db::execQuery($sql, [], db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}	

	public function GetAll()
	{
		$sql = "SELECT * FROM 
		( SELECT Device.Id as DeviceId, cmd_device.Id as Cmd_device_Id, cmd_device.DeviceId as Cmd_Device_DeviceId, Device.CarteId, Type_Device.Id as TypeId, Type_Device.Type, Lieux.Id as LieuxId, Lieux.Nom as NamePiece, Device.Nom as DeviceNom, Device.History, cmd_device.Type as Cmd_type, RAZ,cmd_device.Value as Value, cmd_device.Unite, cmd_device.Etat as Etat, cmd_device.Date as Date, Type_Device.Widget_Id as WidgetId, Device.visible as DeviceVisible, Device.Configuration
		FROM Device 
		LEFT JOIN cmd_device on cmd_device.Device_ID = Device.Id 
		LEFT JOIN Lieux on Device.Lieux_ID = Lieux.Id  
		LEFT JOIN Type_Device on Type_Device.Id = Device.Type_ID 
		GROUP BY Device.Id, cmd_device.Id , cmd_device.DeviceId, Device.CarteId, Type_Device.Id, Lieux.Id, Lieux.Nom, Device.Nom, Device.History, TypeId, cmd_device.Type, RAZ, Device.visible 
		) 
		as T 
		GROUP BY DeviceId" ;
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);
	}

	public function getAllDeviceAndCmd()			    
	{
		$sql = "SELECT * FROM 
		(
		select Device.Id  AS Id, Device.Nom AS Nom, cmd_device.Id as Cmd_device_Id, cmd_device.deviceid AS PinId, cmd_device.visible as cmd_visible, Device.CarteId AS CarteId, cmd_device.Request as Request, cmd_device.Nom as Cmd_nom, Device.History, /*Type_Device.Type as Type, Type_Device.Id as Type_device_id*/ Type_Device.Id as TypeId, cmd_device.Type as Cmd_type, cmd_device.Value,cmd_device.Etat, cmd_device.Unite, Lieux.Nom AS Lieux, Lieux.Id as LieuxId, Lieux.Position AS Position, Type_device_cmd.Widget_Id as WidgetId, widget.Type as WidgetType, Lieux.Img, Type_Device.Template as TypeTemplate, Lieux.Backgd AS Backgd, Device.Configuration,
		cmd_device.Date,
		COUNT( Activate ) AS CountPlanning 
		FROM Lieux
		LEFT JOIN Device on Device.Lieux_ID= Lieux.Id
		LEFT JOIN cmd_device on cmd_device.Device_ID = Device.Id
		LEFT JOIN Type_Device on Type_Device.Id = Device.Type_Id
		LEFT JOIN Type_Device as Type_device_cmd on Type_device_cmd .Id = cmd_device.Type_ID
		LEFT JOIN Planning ON Planning.Cmddevice_Id = cmd_device.Id AND Planning.Activate =1 	
		LEFT JOIN widget on Type_Device.Widget_Id = widget.Id
		WHERE Lieux.visible=1 AND Device.Id IS NOT NULL 
		GROUP BY Device.Id, cmd_device.deviceid, Device.CarteId, cmd_device.Request,  Device.Nom, Device.History, TypeId, cmd_device.Type, Value, cmd_device.Etat, cmd_device.Unite, Lieux.Nom, Lieux.Position, WidgetId, Lieux.Img, Lieux.Backgd
		) as T 
		ORDER BY Id, Position, Lieux, TypeId,  Nom";
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);

	}

	public function getAllDeviceAndCmdVisible()			    
	{
		$sql = "SELECT * FROM 
		(
		SELECT Device.Id  AS Id, cmd_device.Id as Cmd_device_Id,  cmd_device.deviceid AS PinId,  Device.CarteId AS CarteId, cmd_device.Request as Request, cmd_device.Nom as Cmd_nom, Device.Nom AS Nom, Device.History, Type_Device.Id as TypeId, cmd_device.Type as Cmd_type, cmd_device.Value,cmd_device.Etat, cmd_device.Unite, Lieux.Nom AS Lieux, Lieux.Id as LieuxId, Lieux.Position AS Position, Type_device_cmd.Widget_Id as WidgetId, widget.Type as WidgetType, Lieux.Img, Lieux.Backgd AS Backgd, Device.Configuration, Type_Device.Template as TypeTemplate, cmd_device.Date, COUNT( Activate ) AS CountPlanning 
		FROM Lieux
		LEFT JOIN Device on Device.Lieux_ID= Lieux.Id
		LEFT JOIN cmd_device on cmd_device.Device_ID = Device.Id
		LEFT JOIN Type_Device on Type_Device.Id = Device.Type_Id
		LEFT JOIN Type_Device as Type_device_cmd on Type_device_cmd .Id = cmd_device.Type_ID
		LEFT JOIN Planning ON Planning.Cmddevice_Id = cmd_device.Id AND Planning.Activate =1 	
		LEFT JOIN widget on Type_Device.Widget_Id = widget.Id
		WHERE Lieux.visible=1 AND Device.Id IS NOT NULL AND Device.visible = 1 AND cmd_device.visible = 1
		GROUP BY Device.Id, cmd_device.deviceid, Device.CarteId, cmd_device.Request,  Device.Nom, Device.History, TypeId, cmd_device.Type, Value, cmd_device.Etat, Lieux.Nom, Lieux.Position, WidgetId, Lieux.Img, Lieux.Backgd
		) as T 
		ORDER BY Lieux, Position, TypeId, Id, Nom";
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);
	}

	public function getAllDeviceAndCmdVisibleByLieux($LieuxId)			    
	{
		$values = array(
			':LieuxId' => $LieuxId
			);
		$sql = "SELECT * FROM 
		(
		select Device.Id  AS Id, Device.Nom AS Nom, cmd_device.Id as Cmd_device_Id,  cmd_device.deviceid AS PinId,  Device.CarteId AS CarteId, cmd_device.Request as Request, Device.History, Type_Device.Id as TypeId, cmd_device.Type as Cmd_type, cmd_device.Value,cmd_device.Etat, cmd_device.Unite, Lieux.Nom AS Lieux, Lieux.Id as LieuxId, Lieux.Position AS Position, Type_Device.Widget_Id as WidgetId, widget.Name as Widget, widget.Type as WidgetType, Lieux.Img, Lieux.Backgd AS Backgd, cmd_device.date, Device.Configuration, Type_Device.Template as TypeTemplate, #Sunrise_set.Sunrise, Sunrise_set.Sunset,
		COUNT( Activate ) AS CountPlanning 
		FROM Lieux
		#LEFT JOIN Sunrise_set ON 1
		LEFT JOIN Device on Device.Lieux_ID= Lieux.Id
		LEFT JOIN cmd_device on cmd_device.Device_ID = Device.Id
		LEFT JOIN Type_Device on Type_Device.Id = Device.Type_Id
		LEFT JOIN Planning ON Planning.Cmddevice_Id = cmd_device.Id AND Planning.Activate =1 		
		LEFT JOIN widget on Type_Device.Widget_Id = widget.Id
		WHERE Lieux.visible=1 AND Device.Id IS NOT NULL AND Device.visible = 1 AND cmd_device.visible = 1
		GROUP BY Device.Id, cmd_device.deviceid, Device.CarteId, cmd_device.Request,  Device.Nom, Device.History, Type_Device.Type , cmd_device.Type, Value, cmd_device.Etat, Lieux.Nom, Lieux.Position, Widget_Id, Lieux.Img, Lieux.Backgd#, Sunrise_set.Sunrise, Sunrise_set.Sunset 
		) as T WHERE T.LieuxId= :LieuxId
		GROUP BY Id ORDER BY Position, Lieux, TypeId,  Nom";
		return db::execQuery($sql, $values, db::FETCH_TYPE_ALL);
	}

	public function GetDeviceWidgetVisible()
	{
		$sql = "SELECT Type_Device.Id as TypeId, widget.Id as WidgetId, widget.Name as Widget, Type_Device.Type, Device.Configuration, Type_Device.Template as TypeTemplate 
					FROM Device 
					INNER JOIN Type_Device on Type_Device.Id = Device.Type_ID
					INNER JOIN widget on widget.Id = Type_Device.Widget_Id
				WHERE Visible = 1
				GROUP BY Type";
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);
	}

	public function SaveDevice($Id, $CarteId,/* $DeviceId, $RAZDevice,*/ $DeviceName, $DeviceVisible, $TypeId, $LieuxId, $SensorAttach/*, $CmdDeviceId*/)
	{	
		if ($Id == "")
		{
			$values = array(
				':app_name' => $DeviceName,
				':Carte_id' => $CarteId,
				':app_type_id' => $TypeId,
				':Lieux_ID' => $LieuxId,
				':visible_app' => $DeviceVisible
				);

			$sql = "INSERT INTO Device (Nom, CarteId, Type_Id,Lieux_Id,Visible) VALUES (:app_name, :Carte_id, :app_type_id, :Lieux_ID, :visible_app)";
			db::execQuery($sql,$values);


			$values = array(
				':app_name' => $DeviceName,
				/*':app_id' => $DeviceId,
				':RAZ_value' => $RAZDevice,*/
				':visible_app' => $DeviceVisible,
				':sensor_attach' => $SensorAttach
				);

			$sql = "INSERT INTO cmd_device (Nom, Device_Id,/* DeviceId,*/ Type_Id, sensor_attachId, /*RAZ, */Visible) select :app_name, MAX(Id) , :app_id, :sensor_attach, :app_type_id, :RAZ_value, :visible_app FROM Device";
			db::execQuery($sql,$values);

			$msg = "Le Device "+$DeviceName+" a bien été ajouté";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
		else
		{
			$values = array(
				':Id' => $Id,
				':app_name' => $DeviceName,
				':Carte_id' => $CarteId,
				':app_type_id' => $TypeId,
				':Lieux_ID' => $LieuxId,
				':visible_app' => $DeviceVisible
				);

			$sql = "UPDATE Device SET CarteId =:Carte_id, Nom =:app_name, Type_ID =:app_type_id, Lieux_ID =:Lieux_ID, Visible =:visible_app WHERE Id =:Id";
			db::execQuery($sql,$values);


			/*$values = array(
				':Id' => $Id,
				':Cmd_device_Id' => $CmdDeviceId,
				':app_id' => $DeviceId,
				':app_name' => $DeviceName,
				':RAZ_value' => $RAZDevice,
				':visible_app' => $DeviceVisible,
				);

			$sql = "UPDATE cmd_device SET Nom =:app_name, DeviceId =:app_id , RAZ =:RAZ_value WHERE Device_ID = :Id and Id = :Cmd_device_Id";
			db::execQuery($sql,$values);		*/	

			$msg = "Le Device "+$DeviceName+" a bien été mis à jour";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
	}

	public function DeleteDevice($Id)
	{		
		$values = array(
			':Id' => $Id
			);	
		$sql = "DELETE FROM cmd_device where Device_ID =:Id";
		db::getNbResult($sql,$values);
		$sql = "DELETE FROM Device WHERE Id =:Id";
		$nbDeviceDelete = db::getNbResult($sql,$values);

		if ($nbDeviceDelete > 0)
		{
			$msg = "Le Device "+$DeviceName+" a bien été supprimé";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
	}

	public function AddPlugins($App_name,$Piece_id,$Request)
	{
		$values = array(
			':app_name' => $App_name,
			':piece_id' => $Piece_id
			);

		$sql= "INSERT INTO Device (Nom,Type_ID,Lieux_ID,Visible)  SELECT ':app_name', Id, ':piece_id', 1 FROM Type_Device WHERE Type = 'Plugins'";
		return db::execQuery($sql,$values);
		$lines = file('./plugins/'.$App_name.'/install.txt');
		/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
		foreach ($lines as $lineNumber => $lineContent)
		{
			$InstallArray = explode("#", $lineContent);

			$values = array(
				':Name' => $InstallraAry[0],
				':Request' => $InstallArray[1],
				':RAZ' => $InstallArray[2]
				);
			$sql = "INSERT INTO cmd_device (Nom,Device_ID,sensor_attachID,Request,Value,Etat,RAZ,Visible) select ':Name', MAX(Id) ,-1,':Request',0,0,':RAZ',1 FROM Device";
			return db::execQuery($sql,$values);
		}
	}

	public function DeletePlugins($Id)
	{	
		$values = array(
			':Id' => $Id
			);	
		$sql = "DELETE FROM cmd_device WHERE Device_ID =':Id'";
		return db::execQuery($sql,$values);
		$sql = "DELETE FROM Device WHERE Id =':Id'";
		return db::execQuery($sql,$values);
	}


	public function GetTypeDevice()
	{
		$sql = 'SELECT Id, Type, Widget_Id FROM Type_Device order by Type';
		return db::execQuery($sql, []);		
	}

	public function GetTypeWidget()
	{
		$sql = "SELECT Id, Name, Type from widget order by Name";
		return db::execQuery($sql,[]);
	}


	//######## CmdDevice ############


	// GETTER


	public function get_Id()
	{
		return $this->Id;
	}

	public function get_Name()
	{
		return $this->Nom;
	}

	public function get_carteId()
	{
		return $this->carteId;
	} 

	public function get_Configuration($key = "",$default = "")
	{
		if ($key != "")
		{
			return getJsonAttr($this->Configuration, $key, $default);
		}
		else
		{			
			return $this->Configuration;
		}
	}

	public function get_Lieux_Id()
	{
		return $this->Lieux_Id;
	}

	public function get_Type_Id()
	{
		return $this->Type_Id;
	}

	public function get_Visible()
	{
		return $this->Visible;
	}

	public function get_History()
	{
		return $this->History;
	}

}
?>