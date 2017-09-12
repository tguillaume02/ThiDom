<?php
require_once '../Core/Security.php';
//require_once '../Core/ListRequire.php';
?>

<div id="data-maison" class="container-fluid">
	<div class="row visible-lg visible-md" id="Content-desktop" >
		<div class=" visible-xs visible-sm">
			<button type="button" class="btn btn-default" aria-label="Arrow Back" onclick="ReturnMobileView()">
				<i class="fa fa-arrow-left"></i>
			</button>
		</div>
		<?php
		$data='';
		$LieuxWithDevice = Lieux::byVisibleWithDevice();
		//$ListDeviceByLieux =  Device::getAllDeviceAndCmdVisible();

		foreach($LieuxWithDevice as $Lieux)
		{
			$IconsWidget="";
			$LieuxID = $Lieux->get_Id();
			$LieuxNom =  $Lieux->get_Name();
			$data .="<div class='DeviceContainer Corner col-xs-12 col-lg-6 col-md-6 col-sm-6' Lieux='".$LieuxID."' >";
			$data .="<div class='SubContainer Corner'>";
			$data .="<legend class='Corner'>";
			$data .=  $LieuxNom;
			$data .="</legend>";

			$data .= "<div class='ContentLieux col-xs-12 col-lg-12 col-md-12 col-sm-12' id='DivLieux_".$LieuxID."'>";/*class='col-xs-12 col-lg-12 col-md-12 col-sm-12 col-sm-12'*/

			$ListDeviceByLieux = Device::getAllDeviceAndCmdVisibleByLieux($LieuxID);

			foreach($ListDeviceByLieux as $donneesDevice)
			{
				$IconsWidget = "";
				$Pictures_device = "";
				$Device_id = $donneesDevice["Id"];
				$Cmd_device_Id = $donneesDevice["Cmd_device_Id"];
				$Cmd_type = $donneesDevice["Cmd_type"];
				$PinId = $donneesDevice["PinId"];
				$CarteId = $donneesDevice["CarteId"];
				$Request = $donneesDevice["Request"];
				$Nom = $donneesDevice["Nom"];	
				$TypeId = $donneesDevice["TypeId"];
				$CmdDeviceValue = $donneesDevice["Value"];
				$cmdDeviceUnite = $donneesDevice["Unite"];
				$CmdDeviceEtat = $donneesDevice["Etat"];
				$DevicePosition = $donneesDevice["Position"];
				$DeviceWidget = $donneesDevice["Widget"];
				$WidgetType = $donneesDevice["WidgetType"];
				$History = $donneesDevice["History"];
				$Configuration =  $donneesDevice["Configuration"];
				$TypeTemplate =  $donneesDevice["TypeTemplate"];

				if (isset(json_decode($Configuration)->icons))
				{
					$IconsWidget = getJsonAttr($Configuration,"icons","");//json_decode($Configuration)->{'icons'};
				}
				else if (isset(json_decode($TypeTemplate)->icons))
				{
					$IconsWidget =  getJsonAttr($TypeTemplate,"icons","");//json_decode($TypeTemplate)->{'icons'};
				}
				else if ($IconsWidget == "")
				{
					$IconsWidget =  $DeviceWidget; 
				}
				
				$Lieux = Lieux::byId($LieuxID);

				$LieuxName = $Lieux->get_Name();//$donneesDevice["Lieux"];
				$LieuxImg = $Lieux->get_Img();//$donneesDevice["Img"];
				$LieuxBackgd = $Lieux->get_Backgd();//$donneesDevice["Backgd"];
				$CountPlanning = $donneesDevice["CountPlanning"];	
				$LieuxWithoutSpace = SpaceToScore($LieuxName);
				$NomWithoutSpace = SpaceToScore($Nom);
				$date = $donneesDevice["date"];

				$LastUpdateDate = DateDifferenceToString($date);

				$AddDate = '<span  id="Date'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" class="WidgetDate"> depuis '.$LastUpdateDate.'</span>';

				$data .= "<div id='ContentDevice".$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id."' class='DeviceContent Corner col-xs-12 col-lg-4 col-md-6 col-sm-6'  TypeId=".$TypeId." >";
				$data .= "<div class='widget DeviceDetail Corner'>";
				$data .= "<div class='Device_title Corner text-center'>".$Nom."</div>";

				$data .= "<div class='PannelSettings'>";

				if ($History == 1 )
				{
					$data .= "<i id='LastLog".$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id."' Device_id='".$Device_id."' title='Last log' class='fa fa-history pull-right visible-sm-*'></i>";	
				}	

				if($Cmd_type == "Action")
				{
					$data .= "<i id='Calendar".$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id."' data-name='".$Nom."' data-cmddeviceId='".$Cmd_device_Id."' data-widget='".$DeviceWidget."' data-widgettype='".$WidgetType."' title='Calendar' class='fa fa-calendar pull-right visible-sm-* addSchedulerData' data-toggle='modal' data-target='#modal-planning-data'></i>";
				}

				$data .= "</div>";

				if ($DeviceWidget == "Plugins") //PLUGINS
				{
					$plugins =  $Request;
					$plugins_url = "../Core/plugins/".$Nom."/Desktop/".$Nom.".php";//.json_decode($plugins)->{'url'};
					ob_start();
					include $plugins_url;
					$data .=  ob_get_clean();
				}
				else
				{
					$data .= '<div class="">';
					$data .= '<table class="table table-borderless text-center WidgetContent"><tr>';

					if ($DeviceWidget == "Numeric") //Numeric
					{			
						$strEtat = $CmdDeviceValue . $cmdDeviceUnite;
						$Pictures_device = '<img  id="Icons'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" class="img-circle img_btn_device" alt="icons'.$IconsWidget.'" src="Core/pic/Widget/'.$IconsWidget.'" >';
						$data .= '<td><div class="div_btn_device Corner" name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" Cmd_device_Id="'.$Cmd_device_Id.'" Device_id ="'.$Device_id.'" data-type="'.$Cmd_type.'" data-role="'.$DeviceWidget.'">'.$Pictures_device.'</div></td>';
						$data .= '<td class="WidgetStatus-left">';
						$data .= '<table><tr><td class="WidgetStatus-left"><span id="InfoDevice'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'">&nbsp; '.$strEtat.'</span></td></tr><tr><td class="WidgetStatus-left"> '.$AddDate.'</td></tr></table>';
						$data .= '</td>';

					}
					elseif  ($DeviceWidget == "Dimmer" || $DeviceWidget == "Thermostat") //Dimmer || Thermostat
					{
						$Pictures_device = '<div id="InfoDevice'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" class="img-circle img_btn_device circle" value="'.$CmdDeviceValue.'" >'.$CmdDeviceValue.'</div>';
						$data .= '<td><div class="div_btn_device Corner" name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" Cmd_device_Id="'.$Cmd_device_Id.'" Device_id ="'.$Device_id.'" data-type="'.$Cmd_type.'" data-role="'.$DeviceWidget.'">'.$Pictures_device.'</div></td>';
						$data .= '<td class="WidgetStatus-left">';
						$min = 0;
						$max = 100;
						if ($DeviceWidget == "Thermostat") //Thermostat
						{
							$min = 15;
							$max = 30;
						}
						$data .= '<table style="width:100%"><tr><td class="WidgetStatus-left"><input class="bar" value="0" type="range" step="0.5" min="'.$min.'" max="'.$max.'" Cmd_device_Id="'.$Cmd_device_Id.'" Device_id ="'.$Device_id.'" name="Range_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'"  id="Range_'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" data-type="'.$Cmd_type.'" data-role="'.$DeviceWidget.'" oninput="$(InfoDevice'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.').html(this.value);$(InfoDevice'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.').attr(\'value\',this.value)"/></td></tr></table>';
						$data .= '</td>';		
					}
					elseif ($DeviceWidget == "Video") //Video
					{							
						$data .= '<td><img id="Status'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" alt="video" src="'.getJsonAttr($Configuration,"url","").'" class="video Enlarge" style="width:100%"></td>';
					}
					elseif ($DeviceWidget == "RGB") //RGB
					{							
						$Pictures_device = '<input  id="InfoDevice'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" class="img_btn_device Corner" type="color" >';
						$data .= '<td><div class="div_btn_device Corner" name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" Cmd_device_Id="'.$Cmd_device_Id.'" Device_id ="'.$Device_id.'" data-type="'.$Cmd_type.'" data-role="'.$DeviceWidget.'">'.$Pictures_device.'</div></td>';
						$data .= '<td class="WidgetStatus-left">';
						$data .= '<table><tr><td class="WidgetStatus-left">';
						$data .= $AddDate;
						$data .= '</td></tr></table>';
						$data .= '</td>';
					}
					else
					{
						$strEtat = BooleanToString($CmdDeviceEtat);
						$Pictures_device = '<img id="Icons'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" class="img-circle img_btn_device" alt="icons'.$IconsWidget.'" src="Core/pic/Widget/'.$IconsWidget.'_'.$strEtat.'">';
						$data .= '<td><div class="div_btn_device Corner" name="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'" id="'.$NomWithoutSpace.'_'.$LieuxWithoutSpace.'_'.$Cmd_device_Id.'" Cmd_device_Id="'.$Cmd_device_Id.'" Device_id ="'.$Device_id.'" data-type="'.$Cmd_type.'" data-role="'.$DeviceWidget.'">'.$Pictures_device.'</div></td>';
						
						$data .= '<td class="WidgetStatus-left">';
						$data .= '<table><tr><td class="WidgetStatus-left">'.$AddDate.'</td></tr></table>';
						$data .= '</td>';
					}

					$data .= '</tr></table>';
					$data .= '</div>';
				}
				$data .= "</div> ";
				$data .= "</div> ";
			}
			$data .= '</div>';
			$data .= '</div>';
			$data .= '</div>';
		}
		echo $data;
		?>

	</div>
	<div class="row visible-xs visible-sm" id="Content-mobile" >
		<?php	
		$data = "";	
		$data .="<div class='row'>";
		$Lieux = Lieux::byVisibleWithDevice();

		foreach($Lieux as $donneesLieux)
		{
			$LieuxId = $donneesLieux->get_Id();
			$LieuxName = $donneesLieux->get_Name();
			$LieuxIcon = $donneesLieux->get_Img();

			$data .="<div class='DeviceContainer col-xs-4 col-sm-4' onclick='ShowLieux(".$LieuxId.")'>";
			$data .="<div class='SubContainer ShortcutMobile' Type='Lieux' id='".$LieuxId."'>";
			$data .="<div class='ContentLieux col-xs-12 col-sm-12' style='height: auto;''>";
			$data .="<center>";
			$data .="<div class='div_btn_device Corner' id='Lieux_".$LieuxId."' Lieuxid='".$LieuxId."'>";
			if ($LieuxIcon != "")
			{
				$data .="<img class='img-circle img_btn_device' alt='icon".$LieuxName."' src='Core/".$LieuxIcon."'>";
			}
			else
			{				
				$data .="<img class='img-circle img_btn_device' alt='icon".$LieuxName."' src='//:0'>";
			}
			$data .="</div>";
			$data .="</center>";
			$data .= "</div>";
			$data .="<div class='Device_title  text-center'>".$LieuxName."</div>";
			$data .="</div>";
			$data .="</div>";
		}

		### SHORTCUT FOR ACCES TO ALL ROOM ####

		$data .="<div class='DeviceContainer col-xs-4 col-sm-4' onclick='ShowLieux(\"ALL\")'>";
		$data .="<div class='SubContainer ShortcutMobile' Type='Lieux'>";
		$data .="<div class='ContentLieux col-xs-12 col-sm-12' style='height: auto;''>";
		$data .="<center>";
		$data .="<div class='div_btn_device Corner' id='Lieux_All' Lieuxid='ALL'>";
		$data .="<img class='img-circle img_btn_device' alt='iconHouse' src='Core/pic/House1.png'>";
		$data .="</div>";
		$data .="</center>";
		$data .= "</div>";
		$data .="<div class='Device_title text-center'>ALL</div>";
		$data .="</div>";
		$data .="</div>";

		$data .="</div>";
		$data .="<hr>";
		$data .="<div class='row'>";

		$Widget = Device::GetDeviceWidgetVisible();
		foreach($Widget as $donneesWidget)
		{
			$IconsWidget = "";
			//$Widget = $donneesWidget["Widget"];
			$TypeDevice = $donneesWidget["Type"];
			$TypeDeviceId = $donneesWidget["TypeId"];
			$Configuration =  $donneesWidget["Configuration"];
			$TypeTemplate =  $donneesWidget["TypeTemplate"];

			if (isset(json_decode($TypeTemplate)->icons))
			{
				$IconsWidget = json_decode($TypeTemplate)->{'icons'};
			}
			/*else if (isset(json_decode($Configuration)->icons))
			{
				$IconsWidget = json_decode($Configuration)->{'icons'};
			}*/
			else if ($IconsWidget == "")
			{
				$IconsWidget =  $TypeDevice; 
			}


			$data .="<div class='DeviceContainer col-xs-4 col-sm-4' onclick='ShowDevice(\"".$TypeDeviceId."\")'>";
			$data .="<div class='SubContainer ShortcutMobile' type='Widget'  id='".$TypeDevice."'>";
			$data .="<div class='ContentLieux col-xs-12 col-sm-12' style='height: auto;''>";
			$data .="<center>";
			$data .="<div class='div_btn_device Corner' id='widget_".$TypeDevice."' Widgetid='".$TypeDevice."'>";
			$data .="<img class='img-circle img_btn_device' alt='iconWidget".$IconsWidget."' src='Core/pic/Widget/".$IconsWidget."'>";
			$data .="</div>";
			$data .="</center>";
			$data .= "</div>";
			$data .="<div class='Device_title text-center' id='ShortcutMobile' >".$TypeDevice."</div>";
			$data .="</div>";
			$data .="</div>";
		} 
		$data .="</div>";
		echo $data;
		?>
	</div>		
</div>

</div>

<script>
	//$(".slider").bootstrapToggle();
	//resizeMaison();
</Script>