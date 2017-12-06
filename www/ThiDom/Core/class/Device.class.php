<?php

class Device
{
	const table_name = 'Device';

	protected $Id;
	protected $Nom;
	protected $CarteId = '';
	protected $Configuration;
	protected $LieuxId = null;
	protected $ModuleId = null;
	protected $Visible = 0;
	protected $History = 0;
	protected $Type = '';

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

	public function byModule($ModuleId)
	{
		$values = array(
			':ModuleId' => $TypeId,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Module_Id = :ModuleId';
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
		( SELECT Device.Id as DeviceId, cmd_device.Id as Cmd_device_Id, cmd_device.DeviceId as Cmd_Device_DeviceId, Device.CarteId, Module_Type.ModuleName as ModuleName, Module_Type.Id as ModuleId, Lieux.Id as LieuxId, Lieux.Nom as NamePiece, Device.Nom as DeviceNom, Device.History, cmd_device.Widget_Id as WidgetId, widget.Name as WidgetName, widget.Type as WidgetType, cmd_device.Type as Cmd_type, RAZ,cmd_device.Value as Value, cmd_device.Unite, cmd_device.Etat as Etat, cmd_device.Date as Date, Device.visible as DeviceVisible, Device.Configuration
		FROM Device 
		LEFT JOIN cmd_device on cmd_device.Device_ID = Device.Id 
		LEFT JOIN Lieux on Device.Lieux_ID = Lieux.Id  
		LEFT JOIN Module_Type ON Module_Type.Id = Device.Module_Id
        LEFT JOIN widget on widget.Id = cmd_device.Widget_Id
		GROUP BY Device.Id, cmd_device.Id , cmd_device.DeviceId, Device.CarteId, Lieux.Id, Lieux.Nom, Device.Nom, Device.History, cmd_device.Type, RAZ, Device.visible 
		) 
		as T 
		GROUP BY DeviceId
		ORDER BY DeviceNom" ;
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);
	}

	public function getAllDeviceAndCmd()			    
	{
		$sql = "SELECT * FROM 
		(
		SELECT Device.Id  AS Id, Device.Nom AS Nom, cmd_device.Id as Cmd_device_Id, cmd_device.deviceid AS PinId, Device.visible as DeviceVisible, Device.CarteId AS CarteId, cmd_device.Visible as cmd_visible, cmd_device.Request as Request, cmd_device.Nom as Cmd_nom, Device.History,
	        cmd_device.Type as Cmd_type, cmd_device.Value,cmd_device.Etat, cmd_device.Unite, Lieux.Nom AS Lieux, Lieux.Id as LieuxId, Lieux.Position AS Position,
			Module_Type.ModuleName, Module_Type.Id as ModuleId, widget.Id as WidgetId, widget.Name as WidgetName, widget.Type as WidgetType, Lieux.Img, Lieux.Backgd AS Backgd, Device.Configuration, cmd_device.Date, COUNT( Activate ) AS CountPlanning 
		FROM Lieux
		LEFT JOIN Device on Device.Lieux_ID= Lieux.Id
		LEFT JOIN cmd_device on cmd_device.Device_ID = Device.Id
		LEFT JOIN Planning ON Planning.Cmddevice_Id = cmd_device.Id AND Planning.Activate =1 	
		LEFT JOIN widget on cmd_device.Widget_Id = widget.Id
        LEFT JOIN Module_Type on Module_Type.Id = Device.Module_Id
		WHERE Lieux.visible=1 AND Device.Id IS NOT NULL 
		GROUP BY Device.Id, cmd_device.deviceid, Device.CarteId, cmd_device.Request,  Device.Nom, Device.History,  cmd_device.Type, Value, cmd_device.Etat, cmd_device.Unite, Lieux.Nom, Lieux.Position, 
        WidgetId, WidgetName, WidgetType, Lieux.Img, Lieux.Backgd
		) as T 
		ORDER BY Id, Position, Lieux, Nom";
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);

	}

	public function getAllDeviceAndCmdVisible()			    
	{
		$sql = "SELECT * FROM 
		(
		SELECT Device.Id  AS Id, Device.Nom AS Nom, cmd_device.Id as Cmd_device_Id, cmd_device.deviceid AS PinId, cmd_device.visible as cmd_visible, Device.CarteId AS CarteId, cmd_device.Request as Request, cmd_device.Nom as Cmd_nom, Device.History,
	        cmd_device.Type as Cmd_type, cmd_device.Value,cmd_device.Etat, cmd_device.Unite, Lieux.Nom AS Lieux, Lieux.Id as LieuxId, Lieux.Position AS Position,
			Module_Type.ModuleName, Module_Type.Id as ModuleId, widget.Id as WidgetId, widget.Name as WidgetName, widget.Type as WidgetType, Lieux.Img, Lieux.Backgd AS Backgd, Device.Configuration, cmd_device.Date, COUNT( Activate ) AS CountPlanning 
		FROM Lieux
		LEFT JOIN Device on Device.Lieux_ID= Lieux.Id
		LEFT JOIN cmd_device on cmd_device.Device_ID = Device.Id
		LEFT JOIN Planning ON Planning.Cmddevice_Id = cmd_device.Id AND Planning.Activate =1 	
		LEFT JOIN widget on cmd_device.Widget_Id = widget.Id
        LEFT JOIN Module_Type on Module_Type.Id = Device.Module_Id
		WHERE Lieux.visible=1 AND Device.Id IS NOT NULL AND Device.visible = 1 AND cmd_device.visible = 1
		GROUP BY Device.Id, cmd_device.deviceid, Device.CarteId, cmd_device.Request,  Device.Nom, Device.History,  cmd_device.Type, Value, cmd_device.Etat, cmd_device.Unite, Lieux.Nom, Lieux.Position, 
        WidgetId, WidgetName, WidgetType, Lieux.Img, Lieux.Backgd
		) as T 
		ORDER BY Id, Position, Lieux, Nom";
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);
	}

	public function getAllDeviceAndCmdVisibleByLieux($LieuxId)			    
	{
		$values = array(
			':LieuxId' => $LieuxId
			);
		$sql = "SELECT * FROM 
		(
		SELECT Device.Id  AS Id, Device.Nom AS Nom, cmd_device.Id as Cmd_device_Id, cmd_device.deviceid AS PinId, Device.CarteId AS CarteId, cmd_device.Request as Request, cmd_device.Nom as Cmd_nom, Device.History,
	        cmd_device.Type as Cmd_type, cmd_device.Value,cmd_device.Etat, cmd_device.Unite, Lieux.Nom AS Lieux, Lieux.Id as LieuxId, Lieux.Position AS Position,
			Module_Type.ModuleName, Module_Type.Id as ModuleId, widget.Id as WidgetId, widget.Name as WidgetName, widget.Type as WidgetType, Lieux.Img, Lieux.Backgd AS Backgd, Device.Configuration, cmd_device.Date, COUNT( Activate ) AS CountPlanning 
		FROM Lieux
		LEFT JOIN Device on Device.Lieux_ID= Lieux.Id
		LEFT JOIN cmd_device on cmd_device.Device_ID = Device.Id
		LEFT JOIN Planning ON Planning.Cmddevice_Id = cmd_device.Id AND Planning.Activate =1 	
		LEFT JOIN widget on cmd_device.Widget_Id = widget.Id
        LEFT JOIN Module_Type on Module_Type.Id = Device.Module_Id
		WHERE Lieux.visible=1 AND Device.Id IS NOT NULL AND Device.visible = 1 AND cmd_device.visible = 1
		GROUP BY Device.Id, cmd_device.deviceid, Device.CarteId, cmd_device.Request,  Device.Nom, Device.History,  cmd_device.Type, Value, cmd_device.Etat, cmd_device.Unite, Lieux.Nom, Lieux.Position, 
        WidgetId, WidgetName, WidgetType, Lieux.Img, Lieux.Backgd
		) as T WHERE T.LieuxId= :LieuxId
		GROUP BY Id, Position, Lieux, Nom";
		return db::execQuery($sql, $values, db::FETCH_TYPE_ALL);
	}

	public function GetDeviceWidgetVisible()
	{
		$sql = "SELECT widget.Id as widgetId, widget.Name as widgetName, '' as ModuleId, '' as ModuleName FROM Device 
					INNER JOIN cmd_device on Device.Id = cmd_device.Device_Id
					INNER JOIN widget on widget.Id = cmd_device.Widget_Id
					WHERE Device.Visible = 1
				UNION
				SELECT '' as widgetId, '' as widgetName, Module_Type.Id as moduleId, Module_Type.ModuleName as ModuleName FROM Device 
					INNER JOIN cmd_device on Device.Id = cmd_device.Device_Id
					LEFT JOIN widget on widget.Id = cmd_device.Widget_Id
					LEFT JOIN Module_Type on Module_Type.Id = Device.Module_Id
					WHERE Device.Visible = 1
				GROUP BY widgetId,widgetName, moduleId, ModuleName
				ORDER BY widgetId,widgetName, moduleId, ModuleName";
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);
	}

	public function SaveDevice($Id, $CarteId = "", $Configuration = "", $DeviceName = "", $DeviceVisible = "", $ModuleId = "", $LieuxId = "", $SensorAttach = "")
	{	
		if ($Id == "")
		{
			$values = array(
				':app_name' => $DeviceName,
				':Carte_id' => $CarteId,
				':Configuration' => $Configuration,
				':ModuleId' => $ModuleId,
				':Lieux_Id' => $LieuxId,
				':visible_app' => $DeviceVisible
				);

			$sql = "INSERT INTO Device (Nom, CarteId, Configuration, Lieux_Id, Module_Id, Visible) VALUES (:app_name, :Carte_id, :Configuration, :Lieux_Id, :ModuleId, :visible_app)";
			db::execQuery($sql,$values);

			$msg = "Le Device ".$DeviceName." a bien été ajouté";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
		else
		{
			$values = array(
				':Id' => $Id,
				':app_name' => $DeviceName,
				':Carte_id' => $CarteId,
				':Configuration' => $Configuration,
				':Lieux_ID' => $LieuxId,
				':visible_app' => $DeviceVisible
				);

			$sql = "UPDATE Device SET Nom =:app_name, CarteId =:Carte_id, Configuration =:Configuration, Lieux_ID =:Lieux_ID, Visible =:visible_app WHERE Id =:Id";
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

			$msg = "Le Device ".$DeviceName." a bien été mis à jour";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
	}

	public function DeleteDevice($Id)
	{		
		$getdata = self::byId($Id);
		$values = array(
			':Id' => $Id
			);	 

		$sql = "DELETE FROM cmd_device where Device_ID =:Id";
		db::getNbResult($sql,$values);
		$sql = "DELETE FROM Device WHERE Id =:Id";
		$nbDeviceDelete = db::getNbResult($sql,$values);

		if ($nbDeviceDelete > 0)
		{
			$msg = "Le Device ". $getdata->Nom." a bien été supprimé";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
	}

	/*
	public function AddPlugins($DeviceName, $Configuration, $LieuxId,  $TypeId, $ModuleId, $DeviceVisible, $ModelType)
	{
		$installPath = '../plugins/'.$ModelType.'/install.txt';
		$values = array(
			':app_name' => $DeviceName,
			':configuration' => $Configuration,
			':piece_id' => $LieuxId,
			':type_id' => $TypeId,
			':module_id' => $ModuleId,
			':visible' => $DeviceVisible
			);

		//On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro		
		if (file_exists($installPath))
		{
			$lines = file($installPath);
			foreach ($lines as $lineNumber => $lineContent)
			{
				$InstallArray = explode("#", $lineContent);

				$values = array(
					':Name' => $InstallArray[0],
					':Request' => $InstallArray[1],
					':RAZ' => $InstallArray[2]
					);
				$sql = "INSERT INTO cmd_device (Nom, Device_ID, Request, RAZ, Visible) select :Name, MAX(Id) , :Request, :RAZ ,1 FROM Device";
				db::execQuery($sql,$values);
			}
		}
		$sql = "SELECT MAX(Id) as Id FROM Device";
		return db::execQuery($sql,[], db::FETCH_TYPE_ALL);
	}
	*/

	public function DeviceNewId()
	{		
		$sql = "SELECT MAX(Id) as Id FROM Device";
		return db::execQuery($sql, [], db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
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

	public function GetModuleType()
	{
		$sql = 'SELECT Id, ModuleName FROM Module_Type order by ModuleName';
		return db::execQuery($sql, []);	
	}

	public function GetTypeWidget()
	{
		$whereConditions = "";
		$sql = "SELECT Id, Name, Type, ModuleType_Id from widget order by Name";
		return db::execQuery($sql,[]);
	}

	/* ******** SETTER ******* */

	public function set_Id($Id)
	{
		$this->Id = $Is;
		return $this;
	}

	public function set_Name($Name)
	{
		$this->Nom = $Name;
		return $this;
	}

	public function set_CarteId($CarteId)
	{
		$this->CarteId = $CarteId;
		return $this;
	}

	public function set_Configuration($Configuration)
	{
		$this->Configuration = $Configuration;
		return $this;
	}

	public function set_LieuxId($LieuxId)
	{
		$this->LieuxId = $LieuxId;
		return $this;
	}

	public function set_TypeId($TypeId)
	{
		$this->TypeId = $TypeId;
		return $this;
	}

	public function set_ModuleId($ModuleId)
	{
		$this->ModuleId = $ModuleId;
		return $this;
	}
	
	public function set_Visible($Visible)
	{
		$this->Visible = $Visible;
		return $this;
	}
	
	public function set_History($History)
	{
		$this->History = $History;
		return $this;
	}

	/* ******** GETTER ******* */

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
		return $this->LieuxId;
	}

	public function get_Module_Id()
	{
		return $this->ModuleId;
	}

	public function get_Type_Name()
	{
		return $this->Type;
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