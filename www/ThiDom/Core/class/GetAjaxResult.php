<?php 
require '../Security.php';

header('Content-type: application/json');

$mode ="";
$property = "";
$lieux = "";
$id = "";
$act = "";

$dbObject = new db();
$planningObject = new Planning();
$deviceObject = new Device();
$cmdDeviceObject = new CmdDevice();
$historyObject = new History();
$tempratureObject = new Temperature();
$scenarioObject = new Scenario();
$lieuxObject = new Lieux();
$UserObject = new User();
$ModuleObject= new Module();

if (isset($_POST['Mode']))
{
	$mode = $_POST['Mode'];
}

if (isset($_POST['Property']))
{
	$property = $_POST['Property'];
}

if (isset($_POST['Lieux']))
{
	$lieux = $_POST['Lieux'];
}

if (isset($_POST['Id']))
{
	$id = $_POST['Id'];
}
if (isset($_POST['Act']))
{
	$act = $_POST['Act'];
} 

if ($act == "CheckUpdate")
{
	$version = file_get_contents('../../version.php');
	$localVersion = trim(str_replace(array("<?php", "?>", '$version='), '', $version));

	$version = file_get_contents('https://raw.githubusercontent.com/tguillaume02/ThiDom/master/www/ThiDom/version.php');
	$newVersion = trim(str_replace(array("<?php", "?>", '$version='), '', $version));

	if(version_compare($newVersion,$localVersion) > 0)
	{
		echo '{"status": "true", "version":"'.$localVersion.'", "lastversion":"'.$newVersion.'"}';
	}
	else
	{		
		echo '{"status": "false", "version":"'.$localVersion.'", "lastversion":"'.$newVersion.'"}';
	}
}

if ($act == "GetStatusProcessus")
{	
	$read_usb = str_replace(array("\r\n","\n"),'', shell_exec('ps auxw | grep -v "grep" | grep -c "read_usb.py"'));
	$planning = str_replace(array("\r\n","\n"),'', shell_exec('ps auxw | grep -v "grep" | grep -c "planning.py"'));
	$thermostat = str_replace(array("\r\n","\n"),'', shell_exec('ps auxw | grep -v "grep" | grep -c "thermostat.py"'));
	$scenario = str_replace(array("\r\n","\n"),'', shell_exec('ps auxw | grep -v "grep" | grep -c "Scenario.py"'));

	if ($read_usb > 0)
	{
		$read_usb = "<span class='label label-success pull-center'>OK</span>";
	}
	else
	{	
		$read_usb = "<span class='label label-danger pull-center'>NOK</span>";
	}
	if ($planning > 0)
	{
		$planning = "<span class='label label-success pull-center'>OK</span>";
	}
	else
	{	
		$planning = "<span class='label label-danger pull-center'>NOK</span>";
	}
	if ($thermostat > 0)
	{
		$thermostat = "<span class='label label-success pull-center'>OK</span>";
	}
	else
	{	
		$thermostat = "<span class='label label-danger pull-center'>NOK</span>";
	}
	if ($scenario > 0)
	{
		$scenario = "<span class='label label-success pull-center'>OK</span>";
	}
	else
	{	
		$scenario = "<span class='label label-danger pull-center'>NOK</span>";
	}

	echo '[{"Process":"read_usb.py", "Status": "'.$read_usb.'"}, {"Process":"planning.py", "Status": "'.$planning.'"}, {"Process":"thermostat.py", "Status": "'.$thermostat.'"}, {"Process":"Scenario.py", "Status": "'.$scenario.'"}]';
}

if ($act == "GetAllEquipement")
{	
	$dbObject->ResultToJsonArray($deviceObject->GetAll());	
}

if ($act == "SaveDevice")
{
	$SensorAttach = "-1";
	$DeviceVisible = "0";
	$Id = "";
	$Unite = "";
	$CmdDeviceId = "";
	$ModuleType = "";

	$Id = getParameter('DeviceId'); 
	$LieuxId = getParameter('LieuxId'); // Id de la piece
	$ModuleId = getParameter('ModuleId'); 
	$DeviceName = getParameter('DeviceName'); // Nom du device
	$DeviceVisible = getParameter('DeviceVisible')+0; // Device Visible ou pas	
	$CmdDevice = getParameter('CmdDevice'); // Liste des actions des differentes commandes
	$DeviceHistoriser = getParameter('DeviceHistoriser');
	$CmdDeviceId = getParameter('CmdDeviceid');	
	$DeviceConfiguration = getParameter('DeviceConfiguration');
	$CmdDeviceConfiguration = getParameter('CmdDeviceConfiguration');
	$CarteID = getParameter('CarteId'); // Numero de la carte
	$ModuleType = getParameter("ModuleType");
	$TypeId = getParameter("TypeId");

	//$DeviceID = $_POST['CarteDeviceId']; // Id de la broche de la carte
	//$RAZDevice = $_POST['RAZ']; // Remise à zero apres X temps
	//$CmdDeviceId = $_POST['CmdDeviceid']; // Id de la commande correspondante

	if ($result =  $deviceObject->SaveDevice($Id, $CarteID, $DeviceConfiguration, $DeviceName, $DeviceVisible, $ModuleId, $LieuxId))
	{
		$Id = json_decode($result)->{'deviceId'};	 
		//$object = $cmdDeviceObject->InstallCmd($ModuleId, $Id);
		//$dbObject->ResultToJsonArray($deviceObject->AddPlugins($DeviceName, $Configuration, $LieuxId, $TypeId,  $ModuleId, $DeviceVisible, $TypeName));	
		
		if (!empty($CmdDevice))
		{
			$CmdDevice = json_decode($CmdDevice);
			foreach($CmdDevice as $v)
			{
				/*if (!property_exists($v, "cmdRequest"))
				{
					$v->cmdRequest = "0";
				}

				if ($v->cmdRequest == "1")
				{				
					var_dump($Colonne)	;
					$CmdId = $v->id;
					$Colonne = $v->cmdname;
					$Value = $v->value;	
					$cmdDeviceObject->Update_Request($CmdId, $Colonne, $Value);
				}
				else
				{*/
					if ($v->cmdname)
					{
						$CmdId = $v->id;
						$Colonne = $v->cmdname;
						$Value = $v->value;
						$cmdDeviceObject->Update_Any_Value_By_id($CmdId, $Colonne, $Value);
					}
				/*}*/
			}

			if (!empty($CmdDeviceConfiguration))
			{
				$CmdDeviceConfiguration = json_decode($CmdDeviceConfiguration);
				foreach($CmdDeviceConfiguration as $v)
				{
					$CmdId = $v->id;
					$Colonne = "Request";
					$Value = json_encode($v->data);
					$cmdDeviceObject->Update_Any_Value_By_id($CmdId, $Colonne, $Value);
				}
			}
		}
		elseif (empty($CmdDeviceId))
		{
			$object = $cmdDeviceObject->InstallCmd($ModuleId, $Id);
			if (json_decode($object)->{'cmddeviceId'})
			{
				$CmdDeviceId = json_decode($object)->{'cmddeviceId'};				
				$newresult = Array( "msg"=>json_decode($result)->{'msg'}, "clear"=>"on", "deviceId" => $Id , "cmddeviceId" => $CmdDeviceId , "refresh"=>true);
			}
			else
			{
				$cmdDeviceObject->set_Name($DeviceName);
				$cmdDeviceObject->set_device_Id($Id);
				$cmdDeviceObject->set_WidgeId($TypeId);
				$cmdDeviceObject->set_type('Action');
				$resultCmdDevice = $cmdDeviceObject->save(); 
				
				$CmdDeviceId = json_decode($resultCmdDevice)->{'cmddeviceId'};				
				$newresult = Array( "msg"=>json_decode($result)->{'msg'}, "clear"=>"on", "deviceId" => $Id , "cmddeviceId" => $CmdDeviceId , "refresh"=>true);
			}
			$result =  json_encode($newresult);
		}

		$cmdDeviceObject->Update_CmdDeviceName();
		
		if (method_exists($object, 'postSave'))
		{
			$object->postSave();		
		}	
	}

	if ($CmdDevice)
	{
		echo $result;
	}
	else
	{
		//$value = Array( "msg"=>json_decode($result)->{'msg'}, "clear"=>json_decode($result)->{'clear'}, "refresh"=>json_decode($result)->{'refresh'}, "deviceId"=>json_decode($result)->{'deviceId'});
		//echo json_encode($value);
		echo $result;
	}

}

if ($act == "AddCommande")
{	
	$ModuleId = getParameter('ModuleId');
	$DeviceId = getParameter('DeviceId');
	$object = $cmdDeviceObject->InstallCmd($ModuleId, $DeviceId);
}

if ($act == "NewCommandeName")
{
	$cmddeviceId = getParameter('cmddeviceId');
	$NewName = getParameter('NewName');
	$cmdDeviceObject->Update_Any_Value_By_id($cmddeviceId, 'Nom', $NewName);
}

if ($act == "DeleteDevice")
{	
	$deviceId = getParameter('DeviceId');
	echo $deviceObject->DeleteDevice($deviceId);
}

if ($act == "DeleteCmdDevice")
{	
	$CmdDeviceId = getParameter('cmddeviceId');	
	echo $cmdDeviceObject->DeleteCmdDevice($CmdDeviceId);
}

if ($act == "ReorderDevice")
{
	echo $deviceObject->ReorderDevice(json_decode(getParameter('deviceList')), getParameter("lieux"));
}

/*if ($act == "AddPlugins")
{
	$Id = getParameter('deviceId');
	$LieuxId = getParameter('LieuxId'); // Id de la piece
	$TypeId = getParameter('ModelTypeId'); // Type de widget
	$ModuleId = getParameter('ModuleTypeId'); 
	$DeviceName = getParameter('DeviceName'); // Nom du device
	$DeviceVisible = getParameter('DeviceVisible'); // Device Visible ou pas	
	$CmdDevice = getParameter('CmdDevice'); // Liste des actions des differentes commandes
	$DeviceHistoriser = getParameter('DeviceHistoriser');
	$Configuration = getParameter('DeviceConfiguration');
	$CarteID = getParameter('CarteId'); // Numero de la carte
	
	$deviceObject->SaveDevice("", "", $Configuration , $DeviceName, $DeviceVisible , $TypeId,  $ModuleId , $LieuxId , "");
	$ModelType = $deviceObject->GetTypeDevice($TypeId)->get_Type_Name();
	$dbObject->ResultToJsonArray($deviceObject->AddPlugins($DeviceName, $Configuration, $LieuxId, $TypeId,  $ModuleId, $DeviceVisible, $ModelType));
}*/

if ($act == "SavePlugins")
{
	$id = "";
	$name = "";
	$type = "";
	$configuration = "";
	$id = getParameter("Id");
	$name = getParameter("Name");
	$type = getParameter("Type");
	$configuration = getParameter("Configuration");

	if ($type == "")
	{
		$type = file_get_contents('../plugins/'.$name.'/Core/type.txt');
	}

	//if ($id != -1 && $configuration != "")
	//{
		echo $ModuleObject->SaveModule($id, $name, $type, $configuration);
	//}
}

if ($act == "DeletePlugins")
{	
	$Name = getParameter('ModuleName');
	echo $ModuleObject->DeleteModule($id, $Name);
}

if ($act == "SaveLieux")
{
	$Img = "";
	$Position = "";
	$Backgd = "";
	$Visible = ""; 

	$Icons = getParameter('Icons');
	$Name = getParameter('Name');
	$Backgd = getParameter('Backgd');
	$Position = getParameter('Position');
	$Visible = getParameter('Visible')+0;

	echo $lieuxObject->SaveLieux($id, $Name, $Visible, $Position, $Icons);
}

if ($act == "DeleteLieux")
{	
	$Name = getParameter('Nom');
	echo $lieuxObject->DeleteLieux($id, $Name);
}

if ($act == "SaveUser")
{
	$Name = "";
	$Password = "";
	$Hash = "";

	$Name = getParameter('Name');
	$Password = getParameter('Password');
	$Hash = getParameter('Hash');
	echo $UserObject->SaveUser($id, $Name, $Password, $Hash);
}

if ($act == "GetListOfNewPlugins")
{	
	echo json_encode(ls('../plugins/', '*', false,['folders']));
}

if ($act == "AllDeviceAndCmd")
{
	$dbObject->ResultToJsonArray($deviceObject->getAllDeviceAndCmd());
}

if ($act == "Etat")
{
	$CmdDeviceId = getParameter('CmdDeviceid');
	if (empty($CmdDeviceId))
	{
		$dbObject->ResultToJsonArray($deviceObject->getAllDeviceAndCmd());
	}
	else
	{
		$dbObject->ResultToJsonArray($deviceObject->getAllDeviceAndCmdByCmdId($CmdDeviceId));
	}
}

if ($act == "AllLog")
{
	$dbObject->ResultToJsonArray($historyObject->AllLog());
}

if ($act == "GetLastLog")
{
	$dbObject->ResultToJsonArray($historyObject->LastLogbyId($id));
}

if ($act == "RemoveLog")
{
	$dbObject->ResultToJsonArray($historyObject->RemoveLog());
}

if ($act == "Temp")
{
	$dbObject->ResultToJsonArray($tempratureObject->GetTemperatureHistoryOnOneMonth());
}

if ($act == "GetListScenario")
{
	$dbObject->ResultToJsonArray($scenarioObject->GetListScenario());
}

if($act == "SaveScenario")
{
	$UpdateScenario = getParameter('UpdateScenario');
	$Scenario_Name = getParameter('Scenario_Name');
	$Xml_Scenario = getParameter('Xml_Scenario');
	$Xml_Status = getParameter('Xml_Status');
	$logicArray = getParameter('LogicArray');

	echo $scenarioObject->SaveScenario($id, $Scenario_Name, $UpdateScenario, $Xml_Scenario, $Xml_Status, $logicArray);
}

if ($act == "DeleteScenario")
{
	echo $scenarioObject->DeleteScenario($id);
}

if ($act == "GetAllPlanning")
{	
	$dbObject->ResultToJsonArray($planningObject->GetAllPlanning());
}

if ($act == "GetLieux")
{	
	$dbObject->ResultToJsonArray($lieuxObject->GetAll());
}

if ($act == "GetUser")
{	
	if (isset($_SESSION['userIsAdmin']) and $_SESSION['userIsAdmin'] == 1)
	{
		$dbObject->ResultToJsonArray($UserObject->getUser());
	}
	else
	{
		/*$msg = "Mode demo";
		$value = Array( "msg"=>$msg, "clear"=>"on");
		echo json_encode($value);*/		
		$value = Array( "Id"=>"99", "UserHash"=>"", "UserName"=>"Mode Demo", "LastLog"=>"Mode Demo", "UserIsAdmin"=>"");
		echo json_encode([$value]);
	}
}

if ($act == "GetNewHash")
{
	echo $UserObject->getNewHash();
}

if ($act == "GetModuleType") 
{
	$jsonArray = array();
	$jsonresult =$ModuleObject->GetModuleType();// $dbObject->ResultToJsonArray($ModuleObject->GetModuleType());
	foreach($jsonresult as $key => $value) {		
		$bFileExist = true;
		if (!file_exists("/var/www/ThiDom/Core/plugins/".$value->ModuleName."/Core/".$value->ModuleName."ConfigPlugins.php"))
		{
			$bFileExist = false;
		}

		$jsonArray[] = array(
			'Id' => $value->Id,
			'ModuleName' => $value->ModuleName,
			'ModuleConfiguration' => $value->ModuleConfiguration,
			'ModuleName' => $value->ModuleName,
			'ModuleType' => $value->ModuleType,
			'bFileExist' => $bFileExist
		);
	}	
	echo json_encode($jsonArray);
}

if ($act == "GetTypeWidget") 
{	
	$dbObject->ResultToJsonArray($deviceObject->GetTypeWidget());
}


if ($act == "AddPlanning")
{
	$commande = "";
	$dateheure = "";
	$planningId = "";
	$active = "";
	$days = "";
	$eachTime = "";
	$eachPeriod = "";

	$cmddeviceId = getParameter('cmddeviceId');


	if (isset($_POST['commande']) )
	{
		$commande = $_POST['commande'];
	}

	if (isset($_POST['dateheure']) )
	{
		$dateheure = $_POST['dateheure'];
	}
	if (isset($_POST['planningId']) )
	{	
		$planningId = $_POST['planningId'];
	}
	if (isset($_POST['active']) )
	{
		$active = $_POST['active'];
	}	
	if (isset($_POST['eachTime']) )
	{	
		$eachTime = $_POST['eachTime'];
	}
	if (isset($_POST['eachPeriod']) )
	{	
		$eachPeriod = $_POST['eachPeriod'];
	}

	if (isset($_POST['days']) )
	{	
		$days = $_POST['days'];
	}

	echo $planningObject->AddPlanning($planningId, $cmddeviceId, $active, $commande, $dateheure, $eachTime, $eachPeriod, $days);

}

if ($act == "DeletePlanning")
{
	$planningId = getParameter('planningId');
	echo $planningObject->DeletePlanning($planningId);
}
?>
