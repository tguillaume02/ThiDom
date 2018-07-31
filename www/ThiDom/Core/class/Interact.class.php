<?php

class Interact
{
	private
	static 	$device = "",
	$lieux = "",
	$lieuxId = "",
	$deviceId = "";

	public static function reply($query)
	{
		if (trim($query) == '')
		{
			return 'empty';
		}
		else
		{

			$keyWordsGet = array("quelle", "quel", "a quelle", "a quel", "en quelle", "en quel", "a combien");
			$keyWordsSet = array("mets", "met", "passe", "allume", "eteind", "ouvre", "ferme");

			$query = strtolower($query);
			$words = str_word_count($query, 1);


			$DeviceArray = Device::getAllDevice();
			$LieuxArray = Lieux::GetAll();

			if(strposa($query, $keyWordsGet) == true)
			{
				//return in_array("Temperature", $words);

				foreach($LieuxArray as $donneesLieux)
				{	
					$lieuxName = $donneesLieux["Nom"];
					//if (in_array(strtolower($lieuxName), $words))
					if (stripos($query, strtolower(" ".$lieuxName)))
					{
						self::$lieux = $lieuxName;
						self::$lieuxId = $donneesLieux["Id"];
						$DeviceArray = Device::byLieux(self::$lieuxId);
						break;
					}
				}

				foreach($DeviceArray as $donneesDevice)
				{								
					$deviceName = $donneesDevice["Nom"];
					//if (in_array(strtolower($deviceName), $words))
					if (stripos($query, strtolower(" ".$deviceName)))
					{
						self::$device =  $deviceName;
						self::$deviceId =  $donneesDevice["Id"];
						break;
					}
				}

				if (self::$deviceId != "" and self::$lieuxId !="")
				{
					$ValueEtat = CmdDevice::GetValueAndEtatByIdAndLieux(self::$deviceId, self::$lieuxId);
					return self::action($query, $ValueEtat[0]["Value"], $ValueEtat[0]["Unite"], $ValueEtat[0]["Etat"]);
				}
				elseif (self::$lieuxId == "" and self::$deviceId =="")
				{
					$CmdDeviceArray = CmdDevice::GetAllCmdDevice();
					foreach($CmdDeviceArray as $donneesCmdDevice)
					{								
						$cmdDeviceName = $donneesCmdDevice["Nom"];
						//if (in_array(strtolower($deviceName), $words))
						//var_dump($query. " // ". $cmdDeviceName ." -- ". stripos($query, strtolower(" ".$cmdDeviceName)));
						if (stripos($query, strtolower(" ".$cmdDeviceName)))
						{
							//var_dump($cmdDeviceName); 
							self::$device =  $cmdDeviceName;
							$deviceId =  $donneesCmdDevice["Id"];
							$ValueEtat = cmdDevice::byId($deviceId);
							return self::action($query, $ValueEtat->get_Value(), $ValueEtat->get_Unite(), $ValueEtat->get_Etat());
							break;
						}
					}
				}
				elseif(self::$lieuxId != "" and self::$deviceId == "")
				{
					return "Je n'ai pas compris quelle donnée vous souhaitez avoir dans ".self::$lieux;
				}
				elseif (self::$lieuxId == "" and self::$deviceId !="")
				{
					return "Je n'ai pas compris dans quel lieux je dois faire cette action.";
				}
				else
				{					
					return "Je n'ai pas compris ce que vous voulez	.";
				}
			}
			elseif(strposa($query, $keyWordsSet) == true)
			{
				foreach($LieuxArray as $donneesLieux)
				{	
					$lieuxName = $donneesLieux["Nom"];
					//if (in_array(strtolower($lieuxName), $words))
					if (stripos($query, strtolower(" ".$lieuxName)))
					{
						self::$lieux = $lieuxName;
						self::$lieuxId = $donneesLieux["Id"];
						$DeviceArray = Device::byLieux(self::$lieuxId);
						break;
					}
				}
				
				foreach($DeviceArray as $donneesDevice)
				{								
					$deviceName = $donneesDevice["Nom"];
					//if (in_array(strtolower($deviceName), $words))
					if (stripos($query, strtolower(" ".$deviceName)))
					{
						self::$device =  $deviceName;
						self::$deviceId =  $donneesDevice["Id"];
						break;
					}
				}
				return " Je comprend que je dois set le ".self::$device. " de ".self::$lieux." à ";
			}
		}
	}

	private function action($query, $value, $unite, $etat)
	{
		if (self::$lieux)
		{
			self::$lieux = " de ".self::$lieux;
		}

		if (stripos($query, strtolower(" l'etat")))
		{
			return strval(self::$device.self::$lieux." est de ".$etat);
		}
		else
		{
      $value = str_replace(".", ",", $value);
			return strval(self::$device.self::$lieux." est de ".$value." ".$unite);
		}
		//return "je comprend que je dois get ".$device." de ".$lieux;
	}
}


?>