<?php
class Module
{
	const table_name = 'Module_Type';

	public $Id;
	public $ModuleName;
	public $ModuleType;
	public $ModuleConfiguration;

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
		WHERE ModuelName=:name';
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
	
	public function List_usb($_name="")
	{
		foreach (ls('/dev/', 'ttyUSB*') as $usb)
		{
			$vendor = '';
			$model = '';
			foreach (explode("\n", shell_exec('/sbin/udevadm info --name=/dev/' . $usb . ' --query=all')) as $line)
			{
				if (strpos($line, 'E: ID_MODEL=') !== false)
				{
					$model = trim(str_replace(array('E: ID_MODEL=', '"'), '', $line));
				}
				if (strpos($line, 'E: ID_VENDOR=') !== false)
				{
					$vendor = trim(str_replace(array('E: ID_VENDOR=', '"'), '', $line));
				}
			}
			if ($vendor == '' && $model == '')
			{
				$usbMapping['/dev/' . $usb] = '/dev/' . $usb;
			} else
			{
				$name = trim($vendor . '_' . $model);
				$number = 2;
				while (isset($usbMapping[$name])) {
					$name = trim($vendor . '_' . $model . '_' . $number);
					$number++;
				}
				$usbMapping[$name] = '/dev/' . $usb;
			}
		}				

		foreach (ls('/dev/', 'ttyACM*') as $value)
		{
			$usbMapping['/dev/' . $value] = '/dev/' . $value;
		}
				
		if ($_name != '')
		{
			if (isset($usbMapping[$_name]))
			{
				return $usbMapping[$_name];
			}
			$usbMapping = self::List_usb('');
			if (isset($usbMapping[$_name]))
			{
				return $usbMapping[$_name];
			}
			if (file_exists($_name))
			{
				return $_name;
			}
			return 'no found';
		}
		return $usbMapping;
	}

	public function saveUsbController($moduleId, $moduleCom)
	{

		shell_exec("sudo chmod 666 ".$moduleCom);
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

	public function get_ModuleSpecificConf($specificConf)
	{
		return json_decode($this->ModuleConfiguration, true)[$specificConf];
	}
}