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
	$id = "";
	$Unite = "";
	$CmdDeviceId = "";

	$Id = $_POST['deviceId']; 
	$LieuxID = $_POST['LieuxId']; // Id de la piece
	$TypeID = $_POST['ModelTypeId']; // Type de widget
	$DeviceName = $_POST['DeviceName']; // Nom du device
	$DeviceVisible = $_POST['DeviceVisible']; // Device Visible ou pas	
	$CmdDevice = $_POST['CmdDevice']; // Liste des actions des differentes commandes
	$DeviceHistoriser = $_POST['DeviceHistoriser'];

	$CarteID = $_POST['CarteId']; // Numero de la carte

	//$DeviceID = $_POST['CarteDeviceId']; // Id de la broche de la carte
	//$RAZDevice = $_POST['RAZ']; // Remise à zero apres X temps
	//$CmdDeviceId = $_POST['CmdDeviceid']; // Id de la commande correspondante

	$CmdDevice = json_decode($CmdDevice);
	foreach($CmdDevice as $v){
		$CmdId = $v->id;
		$Colonne = $v->cmdname;
		$Value = $v->value;
		$cmdDeviceObject->Update_Any_Value_By_id($CmdId, $Colonne, $Value);
	}

	echo $deviceObject->SaveDevice($Id, $CarteID,/* $DeviceID, $RAZDevice,*/ $DeviceName, $DeviceVisible, $TypeID, $LieuxID, $SensorAttach/*, $CmdDeviceId*/);

}

if ($act == "DeleteDevice")
{	
	$deviceId = $_POST['DeviceId'];
	echo $deviceObject->DeleteDevice($deviceId);
}

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
	$dbObject->ResultToJsonArray($tempratureObject->GetTemperatureHistory());
}

if ($act == "GetListScenario")
{
	$dbObject->ResultToJsonArray($scenarioObject->GetListScenario());
}

if($act == "SaveScenario")
{
	$UpdateScenario = $_POST['UpdateScenario'];
	$Scenario_Name = $_POST['Scenario_Name'];
	$Xml_Scenario = $_POST['Xml_Scenario'];
	$Xml_Status = $_POST['Xml_Status'];
	$logicArray = $_POST['LogicArray'];

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

if ($act == "GetTypeWidget") 
{	
	$dbObject->ResultToJsonArray($deviceObject->GetTypeWidget());
}

if ($act == "GetTypeDevice")
{	
	$dbObject->ResultToJsonArray($deviceObject->GetTypeDevice());
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
