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

	$Id = getPost('deviceId'); 
	$LieuxId = getPost('LieuxId'); // Id de la piece
	$ModuleId = getPost('ModuleId'); 
	$DeviceName = getPost('DeviceName'); // Nom du device
	$DeviceVisible = getPost('DeviceVisible'); // Device Visible ou pas	
	$CmdDevice = getPost('CmdDevice'); // Liste des actions des differentes commandes
	$DeviceHistoriser = getPost('DeviceHistoriser');
	$Configuration = getPost('DeviceConfiguration');
	$CarteID = getPost('CarteId'); // Numero de la carte

	//$DeviceID = $_POST['CarteDeviceId']; // Id de la broche de la carte
	//$RAZDevice = $_POST['RAZ']; // Remise à zero apres X temps
	//$CmdDeviceId = $_POST['CmdDeviceid']; // Id de la commande correspondante

	if ($result =  $deviceObject->SaveDevice($Id, $CarteID, $Configuration, $DeviceName, $DeviceVisible, $ModuleId, $LieuxId, $SensorAttach/*, $CmdDeviceId*/))
	{
		//return  $CmdDevice;
		if (!empty($CmdDevice))
		{
			$CmdDevice = json_decode($CmdDevice);
			foreach($CmdDevice as $v)
			{
				$CmdId = $v->id;
				$Colonne = $v->cmdname;
				$Value = $v->value;
				$cmdDeviceObject->Update_Any_Value_By_id($CmdId, $Colonne, $Value);
			}
		}
		else
		{		
			$ModelType = $ModuleObject->byId($ModuleId)->get_ModuleName();
			$object = new $ModelType;
			$object->Install();		
			//$dbObject->ResultToJsonArray($deviceObject->AddPlugins($DeviceName, $Configuration, $LieuxId, $TypeId,  $ModuleId, $DeviceVisible, $TypeName));		
		}
	}

	if ($CmdDevice)
	{
		echo $result;
	}
	else
	{
		$value = Array( "msg"=>json_decode($result)->{'msg'}, "clear"=>"off","refresh"=>False);
		echo json_encode($value);
	}

}

if ($act == "DeleteDevice")
{	
	$deviceId = $_POST['DeviceId'];
	echo $deviceObject->DeleteDevice($deviceId);
}

/*if ($act == "AddPlugins")
{
	$Id = getPost('deviceId');
	$LieuxId = getPost('LieuxId'); // Id de la piece
	$TypeId = getPost('ModelTypeId'); // Type de widget
	$ModuleId = getPost('ModuleTypeId'); 
	$DeviceName = getPost('DeviceName'); // Nom du device
	$DeviceVisible = getPost('DeviceVisible'); // Device Visible ou pas	
	$CmdDevice = getPost('CmdDevice'); // Liste des actions des differentes commandes
	$DeviceHistoriser = getPost('DeviceHistoriser');
	$Configuration = getPost('DeviceConfiguration');
	$CarteID = getPost('CarteId'); // Numero de la carte
	
	$deviceObject->SaveDevice("", "", $Configuration , $DeviceName, $DeviceVisible , $TypeId,  $ModuleId , $LieuxId , "");
	$ModelType = $deviceObject->GetTypeDevice($TypeId)->get_Type_Name();
	$dbObject->ResultToJsonArray($deviceObject->AddPlugins($DeviceName, $Configuration, $LieuxId, $TypeId,  $ModuleId, $DeviceVisible, $ModelType));
}*/

if ($act == "SaveLieux")
{
	$Img = "";
	$Position = "";
	$Backgd = "";
	$PieceVisible = ""; 

	$Img = $_POST['Img'];
	$Name = $_POST['Name'];
	$Backgd = $_POST['Backgd'];
	$Position = $_POST['Position'];
	$Visible = $_POST['Visible'];

	echo $lieuxObject->SaveLieux($id, $Name, $Visible, $Position, $Img);
}

if ($act == "DeleteLieux")
{	
	$Name = $_POST['Nom'];
	echo $lieuxObject->DeleteLieux($id, $Name);
}

if ($act == "SaveUser")
{
	$Name = "";
	$Password = "";
	$Hash = "";

	$Name = $_POST['Name'];
	$Password = $_POST['Password'];
	$Hash = $_POST['Hash'];
	echo $UserObject->SaveUser($id, $Name, $Password, $Hash);
}

if ($act == "AllDeviceAndCmd")
{
	$dbObject->ResultToJsonArray($deviceObject->getAllDeviceAndCmd());
}

if ($act == "Etat")
{
	$dbObject->ResultToJsonArray($deviceObject->getAllDeviceAndCmd());
}

if ($act == "AllLog")
{
	$dbObject->ResultToJsonArray($historyObject->AllLog());
}

if ($act == "GetLastLog")
{
	$dbObject->ResultToJsonArray($historyObject->LastLogbyId($id));
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
	$UpdateScenario = getPost('UpdateScenario');
	$Scenario_Name = getPost('Scenario_Name');
	$Xml_Scenario = getPost('Xml_Scenario');
	$Xml_Status = getPost('Xml_Status');
	$logicArray = getPost('LogicArray');

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
	$dbObject->ResultToJsonArray($UserObject->getUser());
}

if ($act == "GetNewHash")
{
	echo $UserObject->getNewHash();
}

if ($act == "GetModuleType") 
{	
	$dbObject->ResultToJsonArray($deviceObject->GetModuleType());
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

	$cmddeviceId = $_POST['cmddeviceId'];


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
	if (isset($_POST['days']) )
	{	
		$days = $_POST['days'];
	}

	echo $planningObject->AddPlanning($planningId, $cmddeviceId, $active, $commande, $dateheure, $days);

}

if ($act == "DeletePlanning")
{
	$planningId = $_POST['planningId'];
	echo $planningObject->DeletePlanning($planningId);
}
?>
