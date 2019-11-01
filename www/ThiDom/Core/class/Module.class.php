<?php
class Module
{
	const table_name = 'Module_Type';

	public $Id;
	public $ModuleName;
	public $ModuleType;
	public $ModuleConfiguration;
	public $ApiKey;

	public function byId($id)
	{
		$values = array(
			':id' => $id,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE id=:id';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);		
	}

	public function byName($name)
	{
		$values = array(
			':name' => $name,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE ModuleName=:name';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);		
	}

	public function GetModuleType()
	{
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . ' FROM '.self::table_name.' order by ModuleName';
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public function GetModuleTypeByDevice($DeviceId)
	{
		$values = array(
			':DeviceId' => $DeviceId
			);	
		$sql = "SELECT Module_Type.Id
					, ModuleName
					, ModuleType
					, ModuleConfiguration 
				FROM Device
				INNER JOIN Module_Type ON Module_Type.id = Device.Module_Id
				WHERE Device.Id=:DeviceId";
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public function apiAccess($apikey="", $module="")
	{
		if (trim($apikey) == '')
		{
			return false;
		}
				
		$_apikey = self::byName($module)->get_apiKey();
		if ($apikey == $_apikey)
		{
			return true;
		}
		else
		{
			return false;
		}

	}
	
	public function SaveModule(int $Id, string $Name, string $Type, string $Configuration = "")
	{	
		if ($Id == -1)
		{
			$values = array(
				':Name' => $Name,
				':Type' => $Type,
				':Configuration' => $Configuration,
				':ApiKey' => bin2hex(random_bytes(22))
				);

			$sql = "INSERT INTO Module_Type (ModuleName, ModuleType, ModuleConfiguration, ApiKey) VALUES (:Name, :Type, :Configuration, :ApiKey)";
			db::execQuery($sql,$values);

			$msg = "Le Module ".$Name." a bien été ajouté";
			$value = Array( "msg"=>$msg, "clear"=>"on", "moduleId" => $this->ModuleNewId()->get_Id(), "refresh"=>false);
			return json_encode($value);
		}
		else
		{
			$values = array(
				':Id' => $Id,
				':Name' => $Name,
				':Type' => $Type,
				':Configuration' => $Configuration
				);

			$sql = "UPDATE Module_Type SET ModuleName =:Name, ModuleType =:Type, ModuleConfiguration =:Configuration WHERE Id =:Id";
			db::execQuery($sql,$values);

			$msg = "Le Module ".$Name." a bien été mis à jour";
			$value = Array( "msg"=>$msg, "clear"=>"on", "moduleId" => $Id, "refresh"=>true);
			return json_encode($value);
		}
	}

	function DeleteModule(int $Id, string $Name)
	{
		$values = array(
			':Id' => $Id
			);

		$sql = "DELETE FROM Module_Type WHERE ID=:Id";
		db::execQuery($sql, $values);

		$msg = "Le Module ".$Name." a bien été supprimé";
		$value = Array( "msg"=>$msg, "clear"=>"on", "moduleId" => $Id, "refresh"=>true);
		return json_encode($value);
	}

	public function ModuleNewId()
	{		
		$sql = "SELECT MAX(Id) as Id FROM Module_Type";
		return db::execQuery($sql, [], db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public function List_usb($_name="")
	{
		$usbMapping = array();
		foreach (ls('/dev/', 'ttyUSB*') as $usb)
		{
			$vendor = '';
			$model = '';
			$baudrate = '';
			foreach (explode("\n", shell_exec('/sbin/udevadm info --name=/dev/' . $usb . ' --query=all')) as $line)
			{
				if (strpos($line, 'E: ID_MODEL_FROM_DATABASE=') !== false)
				{
					$model = trim(str_replace(array('E: ID_MODEL_FROM_DATABASE=', '"'), '', $line));
				}
				if (strpos($line, 'E: ID_VENDOR_FROM_DATABASE=') !== false)
				{
					$vendor = trim(str_replace(array('E: ID_VENDOR_FROM_DATABASE=', '"'), '', $line));
				}
				if (strpos($line, 'E: ID_MODEL_ID=') !== false)
				{
					$modelid = trim(str_replace(array('E: ID_MODEL_ID=', '"'), '', $line));
				}
				if (strpos($line, 'E: ID_VENDOR_ID=') !== false)
				{
					$vendorid = trim(str_replace(array('E: ID_VENDOR_ID=', '"'), '', $line));
				}
			}
			if ($vendor != '' && $model != '')
			{
				$baudrate = shell_exec('stty -F /dev/' . $usb . ' speed');
				$usbMapping[] = array("link"=>'/dev/' . $usb, "model"=>$model, "vendor"=>$vendor, "modelid"=>$modelid, "vendorid"=>$vendorid, "baudrate"=>$baudrate);
			}

		}				

		foreach (ls('/dev/', 'ttyACM*') as $usb)
		{
			$vendor = '';
			$model = '';
			$baudrate = '';
			foreach (explode("\n", shell_exec('/sbin/udevadm info --name=/dev/' . $usb . ' --query=all')) as $line)
			{
				if (strpos($line, 'E: ID_MODEL_FROM_DATABASE=') !== false)
				{
					$model = trim(str_replace(array('E: ID_MODEL_FROM_DATABASE=', '"'), '', $line));
				}
				if (strpos($line, 'E: ID_VENDOR_FROM_DATABASE=') !== false)
				{
					$vendor = trim(str_replace(array('E: ID_VENDOR_FROM_DATABASE=', '"'), '', $line));
				}
				if (strpos($line, 'E: ID_MODEL_ID=') !== false)
				{
					$modelid = trim(str_replace(array('E: ID_MODEL_ID=', '"'), '', $line));
				}
				if (strpos($line, 'E: ID_VENDOR_ID=') !== false)
				{
					$vendorid = trim(str_replace(array('E: ID_VENDOR_ID=', '"'), '', $line));
				}
			}
			if ($vendor != '' && $model != '')
			{
				$baudrate = shell_exec('stty -F /dev/' . $usb . ' speed');
				$usbMapping[] = array("link"=>'/dev/' . $usb, "model"=>$model, "vendor"=>$vendor, "modelid"=>$modelid, "vendorid"=>$vendorid, "baudrate"=>$baudrate);
			}
		}
			
		return $usbMapping;
	}

	public function saveUsbController($moduleId, $moduleCom)
	{
		shell_exec("sudo chmod 666 ".$moduleCom);
	}

	/* ******** GETTER ******* */

	public function get_Id()
	{
		return $this->Id;
	}
	
	public function get_ModuleName()
	{
		return $this->ModuleName;
	}

	public function get_ModuleType()
	{
		return $this->ModuleType;
	}

	public function get_ModuleConfiguration()
	{
		return $this->ModuleConfiguration;
	}

	public function get_apiKey()
	{
		return $this->ApiKey;
	}

	public function get_ModuleSpecificConf($specificConf)
	{
		return json_decode($this->ModuleConfiguration, true)[$specificConf];
	}
}