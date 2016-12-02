<?php
include_once('Security.php');

header('Content-Type: application/json');

$mode ="";
$property = "";
$lieux = "";
$id = "";
$act = "";

if (isset($_POST['mode']))
{
	$mode = $_POST['mode'];
}

if (isset($_POST['property']))
{
	$property = $_POST['property'];
}

if (isset($_POST['lieux']))
{
	$lieux = $_POST['lieux'];
}

if (isset($_POST['id']))
{
	$id = $_POST['id'];
}
if (isset($_POST['act']))
{
	$act = $_POST['act'];
}


if ($act == "Temp")
{	  
	/*$req = mysql_query("Select date,temp from Temperature where lieux = 'Salon' order by date DESC LIMIT 1");
	While ($donnees = mysql_fetch_array($req))
	{
		$tempInt = $donnees["temp"];
		$dateInt = $donnees["date"];
	}
	
	$req = mysql_query("Select date,temp from Temperature where lieux = 'Exterieur' order by date DESC LIMIT 1");
	While ($donnees = mysql_fetch_array($req))
	{
		$tempExt = $donnees["temp"]; 
		$dateExt = $donnees["date"];
	}
	
	$req = mysql_query("Select date,temp from Temperature where lieux = 'Box' order by date DESC LIMIT 1");
	While ($donnees = mysql_fetch_array($req))
	{
		$tempBox = $donnees["temp"]; 
		$dateBox = $donnees["date"];
	}*/
	// TempInt,DateInt,TempExt,DateExt
	/*echo $tempInt.",".$dateInt.",".$tempExt.",".$dateExt.",".$tempBox;*/
	
	/*$nb_lieux = $mysqli->query("select lieux from Temperature group by lieux")or die('Erreur SQL !<br>');
	$nombre = $nb_lieux->num_rows;*/
	
	/*$req = execute_sql(" SELECT * FROM ( SELECT Temperature_Temp.lieux, date_format(Temperature_Temp.DATE, '%Y-%m-%d %H:%i') as DATE, ROUND(Temperature_Temp.temp,1) as temp, Etat_IO.widget, Etat_IO.Lieux as Etat_IO_Lieux FROM Temperature_Temp inner join Etat_IO on Etat_IO.ID = Temperature_Temp.Etat_IO_ID ORDER BY DATE DESC ) as t GROUP BY t.lieux");*/

	$req = execute_sql("select Lieux.Nom as Lieux, Device.Nom as DeviceName, date_format(cmd_device.DATE, '%Y-%m-%d %H:%i') as DATE,Round(Value,1) as temp, Type_Device.widget, cmd_device.ID as cmd_device_id  from Type_Device 
						inner join Device on Device.Type_Id = Type_Device.ID
						inner join cmd_device on cmd_device.Device_Id = Device.ID
						inner join Lieux on Device.Lieux_Id = Lieux.ID
						where Type_Device.Type like '%Temperature%'");
						
	$JSON = array();
	While ($donnees = $req->fetch_array())
	{
		if ($donnees["temp"] != "")
		{				
			$row_array["lieux"] = $donnees["Lieux"];
			$row_array["DeviceName"] = $donnees["DeviceName"];
			$row_array["temp"] = $donnees["temp"];
			$row_array["date"] = $donnees["DATE"];	
			$row_array["widget"] = $donnees["widget"];	

			$diff_temp = execute_sql(" SELECT temp
						 FROM Temperature_Temp
						 WHERE lieux = '".$donnees["Lieux"]."' and Etat_IO_ID = '".$donnees["cmd_device_id"]."'
						 ORDER BY DATE DESC  LIMIT 3");
												 
			$idiff = 0;
			While ($data_diff =  $diff_temp->fetch_array())
			{
				$val[$idiff] = $data_diff["temp"];
				$idiff = $idiff +1;
			}

			if ($idiff >= 3)			
			{
				$diff = $val[0] - $val[2];
				if ($diff == 0 )
				{
					$row_array["changement"] = "pic/temp_static.png";
				}
				elseif ($diff > 0)
				{
					$row_array["changement"] = "pic/temp_up.png";
				}
				elseif ($diff < 0)
				{
					$row_array["changement"] = "pic/temp_down.png";
				}
				else
				{
					$row_array["changement"] = "";
				}
			}
			array_push($JSON,$row_array);
		}
	}
	echo  json_encode($JSON);
}
	
if ($act == "Etat"){
	/*$req =  execute_sql(" SELECT Nom, Type, Lieux, Value, Etat, Date, Widget FROM Etat_IO ");*/
	$req = execute_sql("SELECT cmd_device.Nom as Nom, Type as Type , Lieux.Nom as Lieux, cmd_device.Value as Value, cmd_device.Etat as Etat, cmd_device.Date as Date, Type_Device.widget as Widget 
						from Device 
						inner join cmd_device on cmd_device.Device_ID = Device.ID 
						inner join Type_Device on Device.Type_ID = Type_Device.ID 
						inner join Lieux on Device.Lieux_ID = Lieux.ID");
	$JSON = array();
	While ($donnees =  $req->fetch_array())
	{
		$row_array["Nom"] = $donnees["Nom"];
		$row_array["Lieux"] = $donnees["Lieux"];
		$row_array["Value"] = $donnees["Value"];
		$row_array["Etat"] = $donnees["Etat"];
		$row_array["Date"] = $donnees["Date"];
		$row_array["Widget"] = $donnees["Widget"];
		
		array_push($JSON,$row_array);
	}
	echo  json_encode($JSON);	
}

if($mode == "Equipement")
{
	/*$req = execute_sql("SELECT Etat_IO.ID,Etat_IO.DeviceID,Etat_IO.Carte_ID, Type_Device.ID as TypeID,Lieux.ID as LieuxID, Lieux.Nom as NamePiece, Etat_IO.Nom, Type_Device.Type,RAZ,Etat_IO.Visible from Etat_IO left join Lieux on Etat_IO.LieuxID = Lieux.ID left join Type_Device on Type_Device.ID = Etat_IO.TypeID");*/
	$req = execute_sql("SELECT * from 
						( SELECT Device.ID,cmd_device.Id as cmd_device_ID, cmd_device.DeviceID,Device.CarteID, Type_Device.ID as TypeID,Lieux.ID as LieuxID, Lieux.Nom as NamePiece, Device.Nom as DeviceNom, Type_Device.Type,RAZ,Device.visible 
							from Device 
							inner join cmd_device on cmd_device.Device_ID = Device.ID 
							left join Lieux on Device.Lieux_ID = Lieux.ID  
							left join Type_Device on Type_Device.ID = Device.Type_ID 
							GROUP BY Device.ID, cmd_device.Id , cmd_device.DeviceID, Device.CarteID, Type_Device.ID, Lieux.ID, Lieux.Nom, Device.Nom, Type_Device.Type, RAZ, Device.visible 
						) 
						as T 
						group by ID");
	$value = array();
	While ($donnees = $req->fetch_array())
	{
		$value[] = Array("ID"=>$donnees['ID'],"cmd_device_ID"=>$donnees['cmd_device_ID'],"DeviceID"=>$donnees['DeviceID'],"Carte_ID"=>$donnees['CarteID'],"TypeID"=>$donnees['TypeID'],"LieuxID"=>$donnees['LieuxID'],"Piece"=>$donnees['NamePiece'],"Nom"=>$donnees['DeviceNom'],"Type"=>$donnees['Type'],"RAZ"=>$donnees['RAZ'],"Visible"=>$donnees['visible']);
		
	}
	
	echo json_encode($value);
}

if ($mode == "lieux" )
{
	$req = execute_sql("SELECT ID,Nom,Visible FROM Lieux ORDER BY Nom");
	$value = array();
	While ($donnees = $req->fetch_array())
	{
		$value[] = Array( "ID"=>$donnees['ID'],"Nom"=>$donnees['Nom'],"Visible"=>$donnees['Visible']);
		
	}
	
	echo json_encode($value);
}

if ($mode == "user" )
{
	$req = execute_sql("SELECT ID,USERNAME,USERPASS,BACKGROUND FROM User ORDER BY USERNAME");
	$value = array();
	While ($donnees = $req->fetch_array())
	{
		$value[] = Array( "ID"=>$donnees['ID'],"Nom"=>$donnees['USERNAME'],"Pass"=>$donnees['USERPASS'],"Background"=>$donnees['BACKGROUND']);
		
	}
	
	echo json_encode($value);
}


if ($mode == "Log" )
{
	$req = execute_sql("SELECT ID, DATE_FORMAT(DATE,'%d %b %Y %T') AS DATE ,ACTION, Message FROM Log ORDER BY ID desc LIMIT 20");
	$value = array();
	While ($donnees = $req->fetch_array())
	{
		$value[] = Array( "ID"=>$donnees['ID'],"Date"=>$donnees['DATE'],"Action"=>$donnees['ACTION'],"Message"=>$donnees['Message']);
		
	}
	
	echo json_encode($value);
}


if ($mode == "type_app" )
{

	$req = execute_sql("SELECT Type_Device.ID,Type,widget FROM Type_Device ORDER BY Type");
	$value = array();
	While ($donnees = $req->fetch_array())
	{
		$value[] = Array( "type"=>$donnees['Type'],"widget"=>$donnees['widget'],"id"=>$donnees['ID']);
	}
	
	echo json_encode($value);
}
	

if ($mode == "LoadDevice") 
{
	/*$req = execute_sql("SELECT ID,Nom FROM Etat_IO where LieuxID = '$lieux'");*/
	$req = execute_sql("select ID,NOM 
						FROM Device 
						where Device.Lieux_ID = '$lieux'");
	$value = array();
	While ($donnees = $req->fetch_array())
	{
		$value[] = Array( "ID"=>$donnees['ID'],"Nom"=>$donnees['Nom']);
	}	
	echo json_encode($value);
}	
	
if ($mode == "valide")
{
	$piece_name = $_POST['piece_name']."";
	$piece_id = $_POST['piece_id']."";
	$app_name = $_POST['app_name'];
	$app_id = $_POST['app_id'];
	$carte_id = $_POST['carte_id']."";
	$add_type = $_POST['app_type'];
	$app_type_id = $_POST['app_type_id'];	
	$add_widget = $_POST['app_widget'];
	$Request = $_POST['Request'];
	$id = $_POST['id'];
	$cmd_device_id = $_POST['cmd_device_ID'];
	$user_name= $_POST['user_name'];
	$user_pass=$_POST['user_pass'];
	$user_pass2=$_POST['user_pass2'];
	$user_background=$_POST['user_background'];
	$RAZ_value = $_POST['RAZ_value'];
	$piece_visible = $_POST['piece_visible'];
	$visible_app = $_POST['visible_app'];
	
	if ($piece_visible == "true")
	{
		$piece_visible = 1;
	}
	else
	{
		$piece_visible = 0;
	}
	
	if ($visible_app == "true")
	{
		$visible_app = 1;
	}
	else
	{		
		$visible_app = 0;
	}
	
	
	
	if ($RAZ_value != "NULL")
	{
		$RAZ_value = hoursToMinutes($RAZ_value);
	}

	if ($property == "add_app")
	{
		//$req = $mysqli->query("SELECT NOM FROM Etat_IO WHERE Nom = '$app_name' and Lieux = '$piece_name'") or die('Erreur SQL !<br>');	
			
		$find = 0;
		if ($id != '')
		{
			/*$req = execute_sql("SELECT NOM FROM Etat_IO WHERE id = '$id'") ;*/
			$req = execute_sql("SELECT NOM FROM Device WHERE id = '$id'") ;
			while ($data = $req->fetch_array())
			{
				$find = 1;
			}
		}
		
		$app_type_id = $add_widget;
		$req = execute_sql(" select widget from Type_Device where id = '$add_widget'");		
		while ($data = $req->fetch_array())
		{
			$add_widget = $data['widget'];
		}
					
	/*	$req = $mysqli->query("SELECT NOM FROM Etat_IO WHERE id = '$id'") or die('Erreur SQL !<br>');		
		$find = 0;
		while ($data = $req->fetch_array())
		{
			$find = 1;
		}*/
		if ($find == 0)
		{		
			$req = execute_sql("SELECT ID FROM Lieux WHERE Nom = '$piece_name'");		
			while ($donnees = $req->fetch_array())
			{
				$LieuxId = $donnees['ID'];
			}

			/*$req = execute_sql("INSERT INTO Etat_IO (DeviceID,Carte_Id,Nom,Type,TypeID, Lieux,LieuxID,Value,Etat,widget,RAZ,Visible) 
								VALUES ($app_id,'$carte_id','$app_name','$add_type','$add_widget_id','$piece_name','$LieuxId',0,0,'$add_widget','$RAZ_value','$visible_app')");*/
		
			$req = execute_sql("INSERT INTO Device (CarteID,Nom,Type_ID,Lieux_ID,Visible) VALUES ('$carte_id','$app_name','$app_type_id','$LieuxId','$visible_app')");

			$req1 = execute_sql("INSERT INTO cmd_device (Nom,Device_ID,DeviceID,Value,Etat,RAZ,Visible) select '$app_name', MAX(ID) ,'$app_id',0,0,$RAZ_value,'$visible_app' from Device");

			
			if($req == TRUE and $req1 == TRUE)
			{	
				$value = array();
				$msg = $app_name." a bien été ajouté dans ".$piece_name;
				$value[] =  Array( "msg"=>$msg,"clear"=>"on");
				echo json_encode($value);
			}
		}
		else
		{
			
			$req = execute_sql("SELECT ID FROM Lieux WHERE Nom = '$piece_name'") ;		
			while ($donnees = $req->fetch_array())
			{
				$LieuxId = $donnees['ID'];
			}
			
			/*$req = execute_sql("UPDATE Etat_IO set DeviceID ='$app_id', Carte_Id='$carte_id', Nom ='$app_name', Type ='$add_type',Lieux ='$piece_name', LieuxID ='$LieuxId', widget ='$add_widget', RAZ=$RAZ_value, Visible='$visible_app' where id = '$id' ");*/

			$req = execute_sql("UPDATE Device  SET CarteID = '$carte_id',Nom='$app_name',Type_ID='$app_type_id',Lieux_ID='$LieuxId',Visible= '$visible_app' where ID =  '$id' ");
			$req1 = execute_sql("UPDATE cmd_device SET Nom ='$app_name' ,DeviceID ='$app_id' ,RAZ =$RAZ_value ,Visible  ='$visible_app'  where Device_ID = '$id' and ID =  '$cmd_device_id' ");


			if($req == TRUE and $req1 == TRUE)
			{	
				$value = array();
				$msg = $app_name." a bien été ajouté dans ".$piece_name;
				$value[] =  Array( "msg"=>$msg,"clear"=>"on");
				echo json_encode($value);
			}
			/*$value = array();
			$msg = "Ce nom d'appareil existe déjà, veuillez renomer cette appareil";
			$value[] =  Array( "msg"=>$msg,"clear"=>"off");
			echo json_encode($value);*/
		}
	}
	elseif ($property == "delete_app")
	{
		/*$req = execute_sql("DELETE FROM Etat_IO where ID ='$id'");	*/
		$req = execute_sql("DELETE FROM cmd_device where ID ='$cmd_device_id' and Device_ID ='$id'");
		$req1 = execute_sql("DELETE FROM Device where ID ='$id'");

		if($req == TRUE and $req1 == TRUE)
		{	
			$value = array();
			$msg = $app_name." a bien été supprimé de ".$piece_name;
			$value[] =  Array( "msg"=>$msg,"clear"=>"on");
			echo json_encode($value);
		}		
	}
	elseif ($property == "delete_plugins_app")
	{
		/*$req = execute_sql("DELETE FROM Etat_IO where ID ='$id'");	*/
		$req = execute_sql("DELETE FROM cmd_device where Device_ID ='$id'");
		$req1 = execute_sql("DELETE FROM Device where ID ='$id'");

		if($req == TRUE and $req1 == TRUE)
		{	
			$value = array();
			$msg = $app_name." a bien été supprimé de ".$piece_name;
			$value[] =  Array( "msg"=>$msg,"clear"=>"on");
			echo json_encode($value);
		}		
	}
	elseif ($property == "add_piece")
	{
		if ($id != "")
		{
			$req = execute_sql("UPDATE Lieux set Nom='$piece_name', Visible='$piece_visible' where ID ='$id'");
			if($req == TRUE)
			{	
				$value = array();
				$msg = "La pièce ".$piece_name." a bien été ajoutée";
				$value[] =  Array( "msg"=>$msg,"clear"=>"on");
				echo json_encode($value);
			}	
		}
		else
		{
			$req = execute_sql("select IFNULL(max(Position)+1,1) as max from Lieux");	
			while ($donnees = $req->fetch_array())
			{
				$max_pos = $donnees['max'];
			}
			$req = execute_sql("INSERT INTO Lieux (Nom,Position) VALUES ('$piece_name','$max_pos')");	
			if($req == TRUE)
			{	
				$value = array();
				$msg = "La pièce ".$piece_name." a bien été ajoutée";
				$value[] =  Array( "msg"=>$msg,"clear"=>"on");
				echo json_encode($value);
			}	
		}
	}	
	elseif ($property  == "delete_piece")
	{		
		$check_app = execute_sql("SELECT count(*) as nb FROM Device where Lieux_ID ='$piece_id'");	
		While ($donnees = $check_app->fetch_array())
		{
			$nb = $donnees['nb'];
		}
		
		if ($piece_name == "")
		{
			$value = array();
			$msg = "Veuillez selectionner une pièce ";
			$value[] =  Array( "msg"=>$msg,"clear"=>"on");
			echo json_encode($value);
		}
		elseif ($nb == 0)
		{
			$req = execute_sql("DELETE FROM Lieux where Nom ='$piece_name'");
			if($req == TRUE)
			{	
				$value = array();
				$msg = "La pièce ".$piece_name." a bien été supprimée";
				$value[] =  Array( "msg"=>$msg,"clear"=>"on");
				echo json_encode($value);
			}
		}
		else
		{
			$value = array();
			$msg = "Impossible de supprimer cette pièce, supprimez tout d'abord les éléments contenus dans celle-ci ";
			$value[] =  Array( "msg"=>$msg,"clear"=>"on");
			echo json_encode($value);			
		}
	}
	elseif($property == "add_user")
	{		
		if ($user_pass == $user_pass2)
		{
			$pass = hash('sha256', $user_pass);
			$req_pass = execute_sql("SELECT USERPASS FROM User WHERE ID = '$id'") ;			
			while ($donnees = $req_pass->fetch_array())
			{
				$USERPASS = $donnees['USERPASS'];
				if ($USERPASS == $user_pass)
				{
					$req = execute_sql("UPDATE User set USERNAME='$user_name', BACKGROUND='$background' where ID ='$id'") ;
				}
				else
				{
					$req = execute_sql("UPDATE User set USERNAME='$user_name', USERPASS = '$pass', BACKGROUND='$background' where ID ='$id'") ;
				}
				
				if($req == TRUE)
				{	
					$value = array();
					$msg = "User ajouté";
					$value[] =  Array( "msg"=>$msg,"clear"=>"on");
					echo json_encode($value);		
				}
			}
		}
	}
	elseif($property == "add_plugins")
	{
		if ($app_name != "" && $piece_name != "" && $Request !="")
		{
			$req = execute_sql("INSERT INTO Device (Nom,Type_ID,Lieux_ID,Visible)  SELECT '$app_name', ID, '$piece_id', 1 from Type_Device where Type = 'Plugins';");

			$lines = file('./plugins/'.$app_name.'/install.txt');
			/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
			foreach ($lines as $lineNumber => $lineContent)
			{
				$InstallArray = explode("#", $lineContent);
				$req1 = execute_sql("INSERT INTO cmd_device (Nom,Device_ID,Request,Value,Etat,RAZ,Visible) select '$InstallArray[0]', MAX(ID) ,'$InstallArray[1]','$InstallArray[2]',0,0,'$visible_app' from Device");
			}

			if($req == TRUE and $req1 == TRUE)
			{	
				$value = array();
				$msg = "Le plugins ".$app_name." a bien été ajouté";
				$value[] =  Array( "msg"=>$msg,"clear"=>"on");
				echo json_encode($value);		
			}
		}
	}
}


if ($mode == "temp_graph")
{	
	$requete = execute_sql("SELECT UNIX_TIMESTAMP(date) as date,temp,lieux FROM Temperature_Temp where lieux ='".$lieux."' order by date desc LIMIT 1");
	$JSON = array();
	while ($data = $requete->fetch_array())
	{
		$row_array["lieux"] = 'serie_Temp'.$lieux;
		$row_array["date"] = $data['date']."000";
		$row_array["temp"] = $data['temp'];
		
		array_push($JSON,$row_array);
	}
	echo  json_encode($JSON);
}

if ($mode == "load_planning")
{	

	$req_widget =  execute_sql("SELECT Type_Device.widget from Device inner join Type_Device on Type_Device.ID = Device.Type_ID  where Device.ID='".$id."'");
	while ($resul_widget = $req_widget->fetch_array())
	{
		$type_widget = $resul_widget['widget'];
	}
	
	$requete = execute_sql("SELECT ID,DAYS,HOURS,STATUS,ACTIVATE from planning where ETAT_IO_ID='".$id."' order by DAYS,HOURS");
	$JSON = array();
	$row_array["ID"] ="SELECT ID,DAYS,HOURS,STATUS,ACTIVATE from planning where ETAT_IO_ID ='".$id."'";
	while ($data = $requete->fetch_array())
	{
		$Days = "";
		switch ($data['ACTIVATE']) {
			case 0:
				$activate =  "Non";
				$color = "red";
				break;
			case 1:
				$activate = "Oui";
				$color = "black";
				break;
		}
		
		if ($type_widget != "plus_moins")
		{
			switch ($data['STATUS']) {
				case 0:
					$status =  "Off";
					break;
				case 1:
					$status = "On";
					break;
				default:
					$status = $data['STATUS'];
					break;
			}
		}
		else
		{
			$status = $data['STATUS'];
		}		
		
		$tab_days = explode(",", $data['DAYS']);
		
		foreach ($tab_days as $key=>$nb) 
		{ 
			if ($Days != "")
			{
				$Days .=", ";
			}
			
			switch ($nb) {
				case 0 :
					$Days .= "Lundi";
					break ;
				case 1 :
					$Days .= "Mardi";
					break ;
				case 2 :
					$Days .= "Mercredi";
					break ;
				case 3 :
					$Days .= "Jeudi";
					break ;
				case 4 :
					$Days .= "Vendredi";
					break ;
				case 5 :
					$Days .= "Samedi";
					break ;
				case 6 :
					$Days .= "Dimanche";
					break ;
			 }
		}
		
		$row_array["ID"] = $data['ID'];
		$row_array["DAYS"] = $Days;
		$row_array["HOURS"] = $data['HOURS'];
		$row_array["STATUS"] = $status;
		$row_array["ACTIVATE"] = $activate;
		$row_array["color"] = $color;
		
		array_push($JSON,$row_array);
	}
	echo  json_encode($JSON);
}


if ($mode == "Save_planning")
{	
	$deviceid = $_POST['id_device'];
	$planningid = $_POST['id_planning'];
	$active = $_POST['Check_active'];
	$commande = $_POST['commande'];
	$lundi = $_POST['Lundi'];
	$mardi = $_POST['Mardi'];
	$mercredi = $_POST['Mercredi'];
	$jeudi = $_POST['Jeudi'];
	$vendredi = $_POST['Vendredi'];
	$samedi = $_POST['Samedi'];
	$dimanche = $_POST['Dimanche'];
	$hours = $_POST['hours'];
	$days = "";
	
	if ($active == "on")
	{
		$active = "1";
	}
	elseif ($active == "off")
	{
		$active = "0";
	}
	
	if ($commande == "on")
	{
		$commande = "1";
	}
	elseif ($commande == "off")
	{
		$commande = "0";
	}
	elseif ($commande != "")
	{
		$commande = $commande;
	}
	
	if ($lundi == "on") 
	{
		$days .= "0";
	}
	if ($mardi == "on") 
	{
		if ($days <> "")
		{
			$days .= ",";
		}
		$days .= "1";
	}
	if ($mercredi == "on") 
	{
		if ($days <> "")
		{
			$days .= ",";
		}
		$days .= "2";
	}
	if ($jeudi == "on") 
	{
		if ($days <> "")
		{
			$days .= ",";
		}
		$days .= "3";
	}
	if ($vendredi == "on") 
	{
		if ($days <> "")
		{
			$days .= ",";
		}
		$days .= "4";
	}
	if ($samedi == "on") 
	{
		if ($days <> "")
		{
			$days .= ",";
		}
		$days .= "5";
	}
	if ($dimanche == "on") 
	{
		if ($days <> "")
		{
			$days .= ",";
		}
		$days .= "6";
	}
	if ($days <> "" and $hours <> "" and $commande <> "" )
	{
		if ($planningid <> "")
		{
			$req = execute_sql("UPDATE planning set ETAT_IO_ID = $deviceid, DAYS = '$days', HOURS = '$hours', STATUS = '$commande', ACTIVATE = '$active' where ID = $planningid") ;
		}
		else
		{
			$req = execute_sql("INSERT INTO planning (ETAT_IO_ID,DAYS,HOURS,STATUS,ACTIVATE) VALUES ($deviceid,'$days','$hours','$commande','$active')") ;
		}
		
		if($req == TRUE)
		{	
			$value = array();
			$msg = "Ajout effectué au planning";
			$value[] =  Array( "msg"=>$msg,"clear"=>"on");
			echo json_encode($value);		
		}
	}
	else
	{
		$value = array();
		$msg = "empty";
		$value[] =  Array( "msg"=>$msg);
		echo json_encode($value);		
	}
	
}

if($mode == "delete_planning")
{
	$req = execute_sql("DELETE FROM planning where ID ='$id'");
	if($req == TRUE)
	{	
		$value = array();
		$value[] =  Array("clear"=>"on");
		echo json_encode($value);
	}	
}

if($mode == "update_position")
{
	$pos =  $_POST['pos'];
	$pos_lieux = $_POST['pos_lieux'];
	$pos1 = $_POST['pos1'];
	$pos_lieux1 = $_POST['pos_lieux1'];
	
	$req = execute_sql("UPDATE Lieux set Position = $pos where Nom = '$pos_lieux'") ;
	$req = execute_sql("UPDATE Lieux set Position = $pos1 where Nom = '$pos_lieux1'") ;
}

if ($mode == "Create_Scenario")
{
	$Scenario_Name = $_POST['Scenario_Name'];
	$Xml_Scenario = $_POST['Xml_Scenario'];
	$Xml_Status = $_POST['Xml_Status'];
	$Scenario_id = $_POST['id'];
	$UpdateScenario = $_POST['UpdateScenario'];

	if ($Scenario_id-1 == -1)
	{
		$req = execute_sql("select IFNULL(max(ID)+1,1) as max from Scenario_Xml");
			while ($donnees = $req->fetch_array())
			{
				$Scenario_id = $donnees['max'];
			}
	}
	/*if ($UpdateScenario == "false")
	{
		$req = $mysqli->query("INSERT INTO Scenario_Xml(Name,XML,Status) values('$Scenario_Name', '$Xml_Scenario', $Xml_Status )") or die('Erreur SQL !<br>');
	}
	else
	{
		$req = $mysqli->query("UPDATE Scenario_Xml set XML = '$Xml_Scenario' ,Status = $Xml_Status where ID = '$Scenario_id' ") or die('Erreur SQL !<br>');
	}*/
		$SequenceNo = 1;
		$obj_LogiArray = json_decode( $_POST['logicArray'], true);
		foreach ($obj_LogiArray as $key => $value) {
		    foreach ($value as $k => $v) {
			    foreach ($v as $x => $z) { 
			    	if ($x."" == "conditions")
			    	{
    					$conditions = $z;
			    	}
			    	elseif ($x."" == "actions")
			    	{
    					$actions = $z;
			    	}
			    }
			    if ($conditions."" != "" and $actions."" != "")
			    {
			    	//echo"insert into Scenario (XmlID, Conditions, Actions,SequenceNo ) values((select MAX(ID) from Scenario_Xml), '$conditions', '$actions','$SequenceNo')";
			    	if ($UpdateScenario == "false")
			    	{ 
			    		$req = execute_sql("INSERT INTO Scenario (XmlID, Conditions, Actions,SequenceNo ) values($Scenario_id, '$conditions', '$actions','$SequenceNo')") ;
			    		//echo "INSERT INTO Scenario_Xml(ID,Name,XML,Status) values('$Scenario_id', '$Scenario_Name', '$Xml_Scenario', $Xml_Status )";
						$req = execute_sql("INSERT INTO Scenario_Xml(ID,Name,XML,Status) values($Scenario_id, '$Scenario_Name', '$Xml_Scenario', $Xml_Status )") ;
			    	}	
			    	else
			    	{		
			    		if ($SequenceNo == 1)
			    		{
			    			$req = execute_sql("DELETE from Scenario where xmlID = '$Scenario_id'") ;
			    		}
			    		$req = execute_sql("INSERT INTO Scenario (XmlID, Conditions, Actions,SequenceNo ) values ('$Scenario_id', '$conditions', '$actions','$SequenceNo')") ;

						$req = execute_sql("UPDATE Scenario_Xml set XML = '$Xml_Scenario' ,Status = $Xml_Status where ID = '$Scenario_id' ") ;
					}
			    }
			    $SequenceNo  = $SequenceNo+1;
			}
		}
}

if ($mode == "DeleteScenario")
{
	if(isset($_POST['idScenario']))
	{
		$Scenario_id = $_POST['idScenario'];
		$req = execute_sql("DELETE from Scenario where xmlID = '$Scenario_id'") ;
		$req = execute_sql("DELETE from Scenario_Xml where ID = '$Scenario_id'") ;
	}
}

if($mode == "ListScenario")
{
	if(isset($_POST['idScenario']))
	{
		$idScenario = $_POST['idScenario'];
		$req =  execute_sql(" SELECT ID, Name,XML,Status FROM Scenario_Xml where ID='$idScenario'");
	}
	else
	{
		$req =  execute_sql(" SELECT ID, Name,XML,Status FROM Scenario_Xml");
	}
	$JSON = array();
	While ($donnees =  $req->fetch_array())
	{
		$row_array["ID"] = $donnees["ID"];
		$row_array["Name"] = $donnees["Name"];
		$row_array["XML"] = $donnees["XML"];
		$row_array["Status"] = $donnees["Status"];
		
		array_push($JSON,$row_array);
	}
	echo  json_encode($JSON);	
}


if($mode == "LoadDeviceName")
{
	$req = execute_sql("SELECT Device.ID,cmd_device.ID as cmd_device_id, Device.Nom,cmd_device.Nom as cmd_Nom, Lieux.Nom as Lieux FROM Device inner join Lieux on Device.Lieux_ID = Lieux.ID inner join Type_Device on Type_Device.ID = Device.Type_ID inner join cmd_device on cmd_device.Device_ID = Device.ID WHERE widget != 'Temperature' /*and widget !='Alert'*/  ");
	$JSON = array();
	While ($donnees =  $req->fetch_array())
	{
		if ($donnees["Nom"] != $donnees["cmd_Nom"])
		{
			$cmd_Nom = "_".$donnees["cmd_Nom"];
		}
		else
		{
			$cmd_Nom ="";
		}
		$row_array["ID"] = $donnees["ID"];
		$row_array["cmd_device_id"] = $donnees["cmd_device_id"];
		$row_array["Nom"] = $donnees["Nom"];
		$row_array["cmd_Nom"] = $cmd_Nom;
		$row_array["Lieux"] = $donnees["Lieux"];
		
		array_push($JSON,$row_array);
	}
	echo  json_encode($JSON);	
}

if ($mode == "LoadTemperatureName")
{
	$req =  execute_sql("SELECT Device.ID, Device.Nom,Lieux.Nom as Lieux FROM Device inner join Lieux on Device.Lieux_ID = Lieux.ID inner join Type_Device on Type_Device.ID = Device.Type_ID WHERE widget = 'Temperature' and widget !='Alert' ");

	$JSON = array();
	While ($donnees =  $req->fetch_array())
	{
		$row_array["ID"] = $donnees["ID"];
		$row_array["Nom"] = $donnees["Nom"];
		$row_array["Lieux"] = $donnees["Lieux"];
		
		array_push($JSON,$row_array);
	}
	echo  json_encode($JSON);	
}


if ($mode == "LoadConditions")
{
	$req =  execute_sql(" SELECT cmd_device.Nom,cmd_device.Value from Device inner join cmd_device on Device_ID = Device.ID where Device.Nom = 'Sunrise' AND cmd_device.Nom = 'Conditions'  ");
	$JSON = array();
	While ($donnees =  $req->fetch_array())
	{
		$row_array[$donnees["Nom"]] = $donnees["Value"];
		/*$row_array["Sunrise"] = $donnees["sunrise"];
		$row_array["Sunset"] = $donnees["sunset"];*/
		
		array_push($JSON,$row_array);
	}
	echo  json_encode($JSON);	
}

if ($mode == "reinit")
{
	$req =  execute_sql(" Update cmd_device set Value=0, Etat=0 where Device_ID='$id'");
}


if ($mode == "change_value")
{
	$value = $_POST['value'];
	$req =  execute_sql(" Update cmd_device set Value='$value' where Device_ID='$id'") ;
}


if ($mode == "last_log")
{
	$req =  execute_sql("SELECT date,message from Log where DeviceID = '$id' order by date desc  LIMIT 3 ");
	$JSON = array();
	$content =  "<div ><span style='font-size:11px;font-weight:bold;color:#3399CC'>Last Log(s)</span></div>";	
	While ($donnees =  $req->fetch_array())
	{
		$content .= "<div style='margin:3px;margin-top: 10px'>";
			$content .= "<span style='margin:5px;font-size:12px;font-weight:bold;color:grey;border-bottom:1px solid grey;'>";
			$content .= $donnees["date"];
			$content .= "</span><br><br>";
		$content .= "<span style='margin:10px;font-size:15px'>".$donnees["message"]."</span>";
		$content .= "</div>";
	}

	$row_array["content"] = $content;
	array_push($JSON,$row_array);
	echo  json_encode($JSON);	

}

if ($mode == "LoadPlugins")
{
	$req =  execute_sql("SELECT Request from cmd_device where Request is not null");
	$liste_plugins = "|";
	While ($donnees =  $req->fetch_array())
	{
		$parsed_json = json_decode($donnees["Request"]);
		$liste_plugins .= $parsed_json->{'url'};		
		$liste_plugins .= "|";
	}
	$plugins_array = array();
	$nom_repertoire = './plugins/';
	if($dossier = opendir($nom_repertoire))
	{
		while(false !== ($fichier = readdir($dossier)))
		{			
			if($fichier != '.' && $fichier != '..' && $fichier != 'index.php' && stripos($liste_plugins, "|plugins/" . $fichier.'/'.$fichier.".php|") === false)
			{
				$plugins_array[] = Array( "Nom"=>str_replace('.php','',$fichier),"url"=>"plugins/" . $fichier.'/'.$fichier.'.php');
			}
		}
		 
		closedir($dossier);
 
	}	
	echo json_encode($plugins_array);

}

?>
