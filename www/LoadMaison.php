<?php

	include_once('Security.php');

	$delais = 60 * 60 * 24 * 7;   // Une semaine

	?>

	<div id="data-maison" >	

		<?php

		/*$req = execute_sql("SELECT Etat_IO.id AS id, Etat_IO.deviceID AS pinID, Etat_IO.Carte_ID AS CarteID, Etat_IO.Request as Request, Etat_IO.Nom AS Nom, Etat_IO.Type as Type , Value, Etat, Lieux.Nom AS Lieux, Lieux.Position AS Position, widget, Lieux.img, Lieux.Backgd AS Backgd, Sunrise_set.Sunrise, Sunrise_set.Sunset, COUNT( ACTIVATE ) AS CountPlanning FROM Lieux LEFT JOIN Sunrise_set ON 1 LEFT JOIN Etat_IO ON Lieux.ID = Etat_IO.LieuxID AND Etat_IO.visible =1 LEFT JOIN planning ON planning.ETAT_IO_ID = Etat_IO.id AND planning.ACTIVATE =1 WHERE Lieux.visible =1 AND Etat_IO.id IS NOT NULL GROUP BY Etat_IO.id, Etat_IO.deviceID, Etat_IO.Carte_ID, Etat_IO.Nom, Type , Value, Etat, Lieux.Nom, Lieux.Position, widget, Lieux.img, Lieux.Backgd, Sunrise_set.Sunrise, Sunrise_set.Sunset ORDER BY Position, Lieux, Nom");*/

		$req = execute_sql("SELECT * from (
								select Device.id  AS id, cmd_device.deviceid AS pinID,  Device.CarteID AS CarteID, cmd_device.Request as Request, Device.Nom AS Nom, Type_Device.Type as Type, cmd_device.Value,cmd_device.Etat, Lieux.Nom AS Lieux, Lieux.Position AS Position, widget, Lieux.img, Lieux.Backgd AS Backgd, #Sunrise_set.Sunrise, Sunrise_set.Sunset,
								 COUNT( ACTIVATE ) AS CountPlanning 
								FROM Lieux
								#LEFT JOIN Sunrise_set ON 1
								Left join Device on Device.Lieux_ID= Lieux.ID
								Left join cmd_device on cmd_device.Device_ID = Device.ID
								LEFT JOIN Type_Device on Type_Device.ID = Device.Type_Id
								LEFT JOIN planning ON planning.ETAT_IO_ID = cmd_device.id AND planning.ACTIVATE =1 
								WHERE Lieux.visible=1 AND Device.id IS NOT NULL AND Device.visible = 1 AND cmd_device.visible = 1
								GROUP BY Device.id, cmd_device.deviceid, Device.CarteID, cmd_device.Request,  Device.Nom, Type_Device.Type ,Value, cmd_device.Etat, Lieux.Nom, Lieux.Position, widget, Lieux.img, Lieux.Backgd#, Sunrise_set.Sunrise, Sunrise_set.Sunset 
							) as T 
							GROUP BY ID ORDER BY Position, Lieux, Nom");

		$bcl = 0;
		$bcl1 = 0;
		$Lieux = "";
		$data = "";
		$Nom = "";
		$mod = 0;
		$ext_first = 0;
		$act= "";

		While ($donnees = $req->fetch_array())

		{

			if (($donnees["Type"] != "Temperature piece") or ($donnees["widget"] != "Temperature"))

			{

				if ($Lieux != $donnees["Lieux"])

				{

					$bcl1 = 0;

					$Lieux = $donnees["Lieux"];

					$LieuxWithoutSpace = /*utf8_encode*/(str_replace(" ","_",$donnees["Lieux"]));

					$Backgd = $donnees["Backgd"];

					$Backgd= "background:url('.$Backgd') no-repeat scroll 0 0 / 100% 100% transparent";

					//$Backgd ="background:rgba(33, 61, 88, 0.19)";

					$Backgd ="background:rgba(0, 0, 0, 0.46)";

					if ($Backgd == "")

					{

						$Backgd = "pic/Background_div.jpg";

						$Backgd= "background:url('.$Backgd') no-repeat scroll 0 0 / 100% 100% transparent";

					}



					if (($bcl % 3)== 0)

					{

						$mod = $mod + 1;

						if($bcl !=0)

						{

							$data .= '</ul></li></div>';

						}


						$data .= '<div class="content-secondary" draggable="true" position="'.$donnees["Position"].'" name="'.$Lieux.'" bcl="'.$bcl.'" mod="'.$mod.'">';

						$data .= '<ul data-role="listview" data-inset="true" data-theme="a" data-dividertheme="a">';

						$data .= '<li data-role="list-divider" class="part_title">';

						$data .= '<img src="'.$donnees["img"].'">';

						$data .= $Lieux."&nbsp";

						$data .= '<span id="DateTemp_'.$LieuxWithoutSpace.'" class="ui-btn-b ui-btn-corner-all DateTemp" style="color:rgb(160,255,0);text-align:center;margin-left:auto;margin-right:auto;display:none">';

						$data .= '</span>&nbsp;';

						$data .= '<span id="Temp_'.$LieuxWithoutSpace.'" class="ui-btn-b ui-btn-corner-all Temp" style="color:rgb(160,255,0);text-align:center;margin-left:auto;margin-right:auto;display:none">';

						$data .= '</span>';

						$data .= '<span id="StatusTemp_'.$LieuxWithoutSpace.'" class="ui-btn-b ui-btn-corner-all StatusTemp" style="color:rgb(160,255,0);text-align:center;margin-left:auto;margin-right:auto;display:none">';	

						$data .= '<img src="" />';

						$data .= '</span>';

						$data .= '</li>';

						$data .= '<li style="'.$Backgd.'">';

					}

					else

					{

						$data .= '</ul></li></div><div class="content-primary" draggable="true" position="'.$donnees["Position"].'" name="'.$Lieux.'" bcl="'.$bcl.'" mod="'.$mod.'">';

						$data .= '<ul data-role="listview" data-inset="true" data-theme="a" data-dividertheme="a">';

						$data .= '<li data-role="list-divider" class="part_title">';

						$data .= '<img src="'.$donnees["img"].'">';

						$data .= $Lieux."&nbsp";

						$data .= '<span id="DateTemp_'.$LieuxWithoutSpace.'" class="ui-btn-b ui-btn-corner-all DateTemp" style="color:rgb(160,255,0);text-align:center;margin-left:auto;margin-right:auto;display:none">';

						$data .= '</span>&nbsp;';

						$data .= '<span id="Temp_'.$LieuxWithoutSpace.'" class="ui-btn-b ui-btn-corner-all Temp" style="color:rgb(160,255,0);text-align:center;margin-left:auto;margin-right:auto;display:none">';

						$data .= '</span>';

						$data .= '<span id="StatusTemp_'.$LieuxWithoutSpace.'" class="ui-btn-b ui-btn-corner-all StatusTemp" style="color:rgb(160,255,0);text-align:center;margin-left:auto;margin-right:auto;display:none">';

						$data .= '<img src="" />';

						$data .= '</span>';

						$data .= '</li>';

						$data .= '<li style="'.$Backgd.'">';

					}

					$bcl = $bcl +1;

				}



				//if (($bcl1 % 3) == 0)
				if ($bcl1 == 0)

				{	

					if($bcl1 != 0)

					{

						$data .= '</div >';

					}

					$data .= '<div class="ui-grid-a">';
					$data .= '<div class="ui-block-a ui-corner-all conteneur_device" style="border-width: 2px; border-style: solid; border-color: rgb(57, 107, 158); ">';

				}			

				else

				{

					$data .= '<div class="ui-block-b ui-corner-all conteneur_device" style="border-width: 2px; border-style: solid; border-color: rgb(57, 107, 158); ">';

				}



				$Nom = $donnees["Nom"];			

				$NomWithoutSpace = /*utf8_encode*/(str_replace(" ","_",$Nom));



				if ($Nom != null)

				{			

					$data .= '<center><table><tr>';

				//$data .= '<label for="'.$donnees["Nom"].'">';

					if ($donnees["Etat"] == "1" && $donnees["widget"] == "plus_moins"  )

					{

						$data .= '<td><img id="circle-status-'.$NomWithoutSpace.'-'.$LieuxWithoutSpace.'" src="pic/circle-green.png" style="width:17px"></td>';

					}

					elseif ($donnees["Etat"] == "0" && $donnees["widget"] == "plus_moins")

					{

						$data .= '<td><img id="circle-status-'.$NomWithoutSpace.'-'.$LieuxWithoutSpace.'" src="pic/circle-red.png" style="width:17px"></td>';

					}

					$data .= '<td class="device_title" id="title_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$donnees["id"].'" >'./*utf8_encode*/($Nom).':</td>';

					if ($donnees["widget"] == "plus_moins")

					{

						$data .= '<td><div class="circle" value="'.$donnees["Value"].'" id="info_slider_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" >'.$donnees["Value"].'</div></td>';

					}

				//$data .= '</label>';

					$data .= '</tr></table>';

					$data .= '<table class="conteneur_widget"><tr>';



					if ($donnees["widget"] == "slider")

					{

						$data .= '<tr><td class="widget">';

						$data .= '<select name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" device_id ="'.$donnees["id"].'" pinId="'.$donnees["pinID"].'" class="flipswitch"  carte_id="'.$donnees["CarteID"].'" class="slider" data-role="slider">';

						$data .= '<option value="off" >Off</option>';

						$data .= '<option value="on" >On</option>';

						$data .= '</select>';

						$data .= '</td></tr>';

					//$data .= '<tr><td><div style="height: 9px;"></div></td></tr>';



					}

					elseif($donnees["widget"] == "RGB")

					{

						$data .= '<tr><td class="widget">';

						$data .= '<select name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" device_id ="'.$donnees["id"].'" pinId="'.$donnees["pinID"].'" class="flipswitch"  carte_id="'.$donnees["CarteID"].'" class="slider" data-role="slider">';

						$data .= '<option value="off" >Off</option>';

						$data .= '<option value="on" >On</option>';

						$data .= '</select></td><td>';						

						$data .= '<form style="width:20%;height: 20%;">';
						$data .= '<input type="button" id="color_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" name="color" class="type_rgb" style="background-color: '.$donnees["Value"].';" onclick="OpenColorPicker(\'color_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'\','.$donnees["id"].','.$donnees["pinID"].','.$donnees["CarteID"].')">';
						$data .= '</form>' ;

						$data .= '</td></tr>';

					}

					elseif($donnees["widget"] == "BAL")

					{

						$data .= '<tr><td class="widget">';					

						$data .= '<img id="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" src="pic/Mailbox_empty.png"  onclick="reinit('.$donnees["id"].')">';

						$data .= '</td>';

						$data .= '</td></tr>';

					}

					elseif ($donnees["widget"] == "plus_moins")

					{

						if ($donnees["Type"] == "Chauffage")

						{

							$action = "chauf";

						}



					/*$action_moins = "'".$donnees["Nom"]."','-'";

					$action_plus = "'".$donnees["Nom"]."','+'";

					$data .= '<div class="ui-grid-b">';				

					$data .= '<div class="ui-block-a" style="padding:1px"><img id="'.$donnees["Nom"].'-" device_id ="'.$donnees["id"].'" src="/pic/fleche_bas.png" style="color:black;text-decoration:none;" class="img_chauff" onclick="'.$action.'('.$action_moins.')"></div>';

					$data .= '<div class="ui-block-b" style=""><input type="text" name="name" style="text-align:center;"  id="'.$donnees["Nom"].'" value="'.$donnees["Value"].'"  /></div>';

					$data .= '<div class="ui-block-c" style="padding:1px"><img id="'.$donnees["Nom"].'+" device_id ="'.$donnees["id"].'"  src="/pic/fleche_haut.png" style="color:black;text-decoration:none;" class="img_chauff"  onclick="'.$action.'('.$action_plus.')"></div>';

					$data .= '</div>';*/

					//$data .= '<div id="'.$donnees["Nom"].'slider" class="slider-numeric"></div>';

					$data .= '<tr><td class="widget">';

					$data .= '<span class="tooltip"></span> <input class="slider_numeric" id="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" min="10" max="35"  device_id ="'.$donnees["id"].'"  pinId="'.$donnees["pinID"].'" carte_id="'.$donnees["CarteID"].'" value="'.$donnees["Value"].'" step="0.5" type="hidden">';

					$data .= '</td></tr>';

				}

				elseif ($donnees["widget"] == "Alerte")

				{				

					$data .= '<tr><td class="widget">';

					$data .= '<img src="pic/alert_down.png" id="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" onclick="reinit('.$donnees["id"].')" device_id ="'.$donnees["id"].'"  pinId="'.$donnees["pinID"].'" carte_id="'.$donnees["CarteID"].'" value="'.$donnees["Value"].'"/>';

					$data .= '</td></tr>';

				}

				elseif ($donnees["widget"] == "Temperature_objet")

				{

					$data .= '<tr><td class="widget">';

					$data.=  '<img src="pic/thermometer-hot.png"  id="img_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" style="height: 61px;"> <span id="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" class="temperature_objet">';

					$data .= '</td></tr>';

					$data .='<tr style="align-content: center;text-align: center;"><td><span id="DateTemp_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" class="DateTemp_Objet"></span></td></tr>';					

				}

				elseif ($donnees["Type"] == "Plugins")
				{ 
					$plugins =  $donnees["Request"];
					$plugins_url = json_decode($plugins)->{'url'};
					ob_start();
					include $plugins_url;
					$data .=  ob_get_clean();
				}

				$data .= '<tr><td>';

				if ($donnees["widget"] != "Alerte" && $donnees["widget"] != "Temperature_objet" && $donnees["widget"] != "BAL" && $donnees["widget"] != "Plugins")

				{
						$CountPlanning = "";
						if ($donnees["CountPlanning"]+0 > 0)
						{
							$CountPlanning = "(".$donnees["CountPlanning"].")";
						}
					$data .='<div class="btnsmall" onclick="ShowPlanning(\''.$donnees["id"].'\',\''.$NomWithoutSpace.'\',\''.$LieuxWithoutSpace.'\',\''.$donnees["widget"].'\');" data-i18n="Edit"  data-theme="a">Planning '.$CountPlanning.'</div>';

				}

				//$data .= '<span class="LastEvent" id="LastEvent_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'"></span>';

				$data .= '</td></tr>';

				$data .='</table>';

				$data .= '</center>';

			}
			else

			{

				$data .= '<center style="color:white">No device in '.$Lieux.'</center>';

			}			



			$data .= '</div>';





			/*if ($Lieux == "Exterieur" && $ext_first == 0)

			{						

				$data1 .= '<div class="ui-block-b ui-corner-all conteneur_device" style="border-width: 2px; border-style: solid; border-color: rgb(57, 107, 158); ">

							<center>

								<table>

									<tbody>

										<tr>

											<td class="device_title">Ensoleillement:</td>

										</tr>

									</tbody>

								</table>

								<br>

								<table class="conteneur_widget">

									<tbody>

									<tr>

									</tr>

									<tr>

										<td class="widget" style="color:white"><img src="pic/Sunrise.png" style="width: 28px;"><span id="Sunrise"> '.$donnees["Sunrise"].'</span></td>

									</tr>

									<tr></tr>

									<tr>

										<td class="widget" style="color:white"><img src="pic/Sunset.png" style="width: 28px;margin-top: 4.7%;"><span id="Sunset"> '.$donnees["Sunset"].'</span></td>

									</tr>

									</tbody>

								</table>

							</center>

						</div>';				

				$ext_first=1;

			}*/





			$bcl1 = $bcl1 +1;

			

		}

	}

	$data .= '</ul></li></div>';
	echo $data;

	?>

</div>

