<script type="text/javascript" src="js/jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/qtip2/jquery.qtip.min.js"></script>
<script type="text/javascript" src="js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/Popper/popper.min.js"></script>
<script type="text/javascript" src="js/Bootstrap/V3/bootstrap.min.js"></script>
<script type="text/javascript" src="js/Bootstrap/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="js/Bootstrap/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="js/Bootstrap/bootbox.min.js"></script>

<script type="text/javascript" src="js/jquery.base64.js"></script>
<script type="text/javascript" src="js/DataTable/V1.10.16/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/DataTable/V1.10.16/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="js/DataTable/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="js/DataTable/responsive.bootstrap.min.js"></script>
<script type="text/javascript" src="js/Highstock/js/highstock.js"></script>
<script type="text/javascript" src="js/Highstock/js/themes/gray.js"></script>


<script type="text/javascript" src="js/Blockly/blockly_compressed.js"></script>
<script type="text/javascript" src="js/Blockly/blocks_compressed.js"></script>
<script type="text/javascript" src="js/Blockly/BlocklyPerso.js"></script>
<script type="text/javascript" src="js/Blockly/fr.js"></script>

<script type="text/javascript"  src="js/JusteGage/justgage.js"></script>
<script type="text/javascript"  src="js/JusteGage/raphael.2.1.0.min.js"></script>

<script type="text/javascript" src="palette-couleur/scr/farbtastic.js"></script>

<script type="text/javascript"  src="js/moment/moment.js"></script>

<script src="js/scheduler/dhtmlxscheduler.js" type="text/javascript"></script>


<script>
	$.extend( $.fn.dataTable.defaults, {
		responsive: true
	} );

	$(document).ready( function ()
	{
		showTabFromHash();
		//resizeWhenChangeTab();
		$(window).on('hashchange', showTabFromHash);
		//DragAndDrop();
		LoadMaison();
		init_component();
		getAutorisationNotification();
		resizeMaison();

	});

	setInterval(function () {
		var date = new Date();
		date.setTime(date.getTime());
		var hour = date.getHours();
		var minute = date.getMinutes();
		var seconde = date.getSeconds();
		var heure = (hour < 10) ? '0' + hour : hour;
		heure += ':';
		heure += (minute < 10) ? '0' + minute : minute;
		heure += ':';
		heure += (seconde < 10) ? '0' + seconde : seconde;
		$('#hour').text(heure);
	}, 1000);

	$( window ).resize(function() {
		resizeMaison();
	});

	$.fn.serializeObject = function()
	{
	    var o = {};
	    var a = this.serializeArray();
	    $.each(a, function() {
	        if (o[this.name] !== undefined) {
	            if (!o[this.name].push) {
	                o[this.name] = [o[this.name]];
	            }
	            o[this.name].push(this.value || '');
	        } else {
	            o[this.name] = this.value || '';
	        }
	    });
	    return o;
	};


	function DeviceEvent()
	{		
		$("#Content-desktop .div_btn_device").filter("[data-Type='Action']").bind("click", function (event, ui)
		{
			var device_id = $(this).attr('device_id');
			var device_role = $(this).attr('data-role');
			var device_type = $(this).attr('data-type');
			if ($(this).filter("[data-role='Color']"))
			{
				var value = $(this).children().val()
			}
			else
			{
				var value = $(this).val();
			}
			action_domo(device_id,device_role,device_type,value);
		});

		$("#Content-desktop .bar").filter("[data-Type='Action']").bind('change', function(){
			var device_id = $(this).attr('device_id');
			var device_role = $(this).attr('data-role');
			var device_type = $(this).attr('data-type');
			var value = $(this).val();
			action_domo(device_id,device_role,device_type,value);
		});
	}

	function ShowLoading()
	{
		if ($('#Loading').length == 0)
		{
			$('body').prepend('<div id="Loading"><div class="overlay"></div><i class="fa fa-cog fa-spin"></i></div>');
		}
        $('#Loading').show();
	}

	function HideLoading()
	{
    	$('#Loading').show();
	}

	function EditDevice(data = "")
	{
		$("#ModalEquipementConfiguration #ConfigurationDevice").html('')
		$("#ModalEquipementCommande #CommandeDevice").html('')		
		$("#ModalEquipementConfiguration").hide();
		$("#ModalEquipementCommande").hide();		
		//$("#modal-manage-device #list-device").prop('disabled', true);
		//$("#modal-manage-device #list-type").prop('disabled', true);

		if (data.ModuleName == "Plugins")
		{
			linkConfig = "Core/plugins/"+data.Type+"/Core/"+data.Type+"Config.php";
			if (!data.newDevice)
			{
				linkCommande = "Core/plugins/Commande.php";
			}
			else
			{				
				linkCommande = "Core/plugins/"+data.Type+"/Core/"+data.Type+"Commande.php";
			}
		}
		else
		{
			linkConfig = "Core/plugins/"+data.ModuleName+"/Core/"+data.ModuleName+"Config.php";
			linkCommande = "Core/plugins/"+data.ModuleName+"/Core/"+data.ModuleName+"Commande.php";
		}

		$("#modal-manage-device #ConfigurationDevice").load(linkConfig , {device_id: data.DeviceId},
 			function() {
 				$("#modal-manage-device #CommandeDevice").load(linkCommande, {device_id: data.DeviceId, cmd_device_id: data.Cmd_device_Id}, function()
 				{
					if (!data.newDevice)
					{						
						$("#modal-manage-device #device-deviceid").val(data.DeviceId);
						$("#modal-manage-device #list-module-type").val(data.ModuleId).trigger('change')
						$("#modal-manage-device #list-type").val(data.WidgetId).trigger('change');
						$("#modal-manage-device #list-device").val(data.TypeId);
						$("#modal-manage-device #device-name").val(data.DeviceNom);
						$("#modal-manage-device #list-room").val(data.LieuxId);

						$("#modal-manage-device #carte-id").val(data.CarteId);
						$("#modal-manage-device #device-id").val(data.Cmd_Device_DeviceId);

						$("#modal-manage-device #device-visible").prop('checked',data.DeviceVisible);
						$("#modal-manage-device #cmddevice-visible").prop('checked',data.DeviceVisible);
						$("#modal-manage-device #cmddevice-historiser").prop('checked',data.History);

						if (data.Configuration != null)
						{
							var obj = $.parseJSON(data.Configuration);
							$.each(obj,function(i,el)
							{
								$("#modal-manage-device #"+i).val(el);
							});
							$("#modal-manage-device #cmddevice-notification").prop('checked',parseInt(JSON.parse(data.Configuration).Notification));
						}
					}
					else
					{
					/*	if (data.ModuleName == "Plugins")
						{
							$("#ModalEquipementConfiguration #add-plugins").show();
						}
						*/
					}

					$('input:checkbox[name=Type]').bootstrapToggle();
					$("#ModalEquipementCommande").show();
					$("#ModalEquipementConfiguration").show();
				});
			})


		/*if (data.WidgetId == "87")
		{
			$("#modal-manage-device #ConfigurationDevice").load( "Core/plugins/"+data.Type+"/Config/"+data.Type+"Config.php", {device_id: data.DeviceId},
		 		function() {
					if (!data.newDevice)
					{
						$("#modal-manage-device #list-device").prop('disabled', false);
						$("#modal-manage-device #CommandeDevice").load( "Core/plugins/Commande.php", {device_id: data.DeviceId}, function()
						{
							$("#modal-manage-device #carte-id").val(data.CarteId);
							$("#ModalEquipementCommande").show();
						});
						$("#modal-manage-device #device-visible").prop('checked',data.DeviceVisible);
						$("#modal-manage-device #device-historiser").prop('checked',data.History);
					
						if(data.Configuration != null)
						{
							var obj = $.parseJSON(data.Configuration);
							$.each(obj,function(i,el)
							{
								$("#modal-manage-device #"+i).val(el);
							});
							$("#modal-manage-device #device-notification").prop('checked',parseInt(JSON.parse(data.Configuration).Notification));
						}
						else
						{

						}
					}
					//$("#ModalEquipementGeneral").attr('class','col-xs-12 col-sm-6 col-md-6 col-lg-6');
					//("#ModalEquipementConfiguration").attr('class','col-xs-12 col-sm-6 col-md-6 col-lg-6');
					$("#ModalEquipementConfiguration").show();
				});
		}
		else
		{
			cmd_device_id = (data.Cmd_device_Id != undefined) ? data.Cmd_device_Id : "null";
			$("#modal-manage-device #ConfigurationDevice").load("Core/plugins/Default/Config/DefaultConfig.php", {cmd_device_id: cmd_device_id},
				function(){
					$("#modal-manage-device #CommandeDevice").load("Core/plugins/Default/Config/DefaultCommande.php", {cmd_device_id: cmd_device_id},
						function() {
							$("#ModalEquipementCommande").show();
							if (!data.newDevice)
							{
							 	//$("#modal-manage-device #CmdDeviceid").val(cmd_device_id);
								if (data.RAZ)
								{
									$("#modal-manage-device #raz-value").val(moment().startOf('day').seconds(data.RAZ).format('HH:mm:ss'));
								}
								$("#modal-manage-device #device-visible").prop('checked',data.DeviceVisible);
								$("#modal-manage-device #device-historiser").prop('checked',data.History);
								$("#modal-manage-device #device-notification").prop('checked',JSON.parse(data.Configuration) ? parseInt(JSON.parse(data.Configuration).Notification) : 0);
								$('input:checkbox[name=Type]').bootstrapToggle((data.Cmd_type == "Action")? "on" : "off");
								$("#modal-manage-device #list-device").prop('disabled', false);
							}
							
							$('input:checkbox[name=Type]').bootstrapToggle();
							$("#ModalEquipementConfiguration").show();
							//$("#ModalEquipementGeneral").attr('class','col-xs-12 col-sm-12 col-md-12 col-lg-12');
							//$("#ModalEquipementConfiguration").attr('class','col-xs-12 col-sm-12 col-md-12 col-lg-12');
							//$('input:radio[name=Type]').filter('[value='+data.Cmd_type+']').prop('checked', true);
						}
					);
			});
		}*/
		if ($("#modal-manage-device").data('bs.modal'))
		{
			if (!$("#modal-manage-device").data('bs.modal').isShown)
			{
				$("#modal-manage-device").modal('toggle');
			}
		}
		else
		{
			$("#modal-manage-device").modal('toggle');
		}
	}

	function EditLieux(data="")
	{
		$("#modal-manage-room #room-id").val(data.Id);
		$("#modal-manage-room #room-name").val(data.Nom);
		$("#modal-manage-room #room-position").val(data.Position);
		$("#modal-manage-room #room-visible").prop('checked',data.Visible);
		$("#modal-manage-room").modal('toggle');
	}

	function EditUser(data="")
	{
		$("#modal-manage-user #user-id").val(data.Id);
		$("#modal-manage-user #user-name").val(data.UserName);
		$("#modal-manage-user #user-hash").val(data.UserHash);
		$("#modal-manage-user #user-password").val("***********");
		$("#modal-manage-user").modal('toggle');
	}

	function strToRGB(str){
		var hash = 0;
		for (var i = 0; i < str.length; i++) {
			hash = str.charCodeAt(i) + ((hash << 5) - hash);
		}
		var c = (hash & 0x00FFFFFF)
		.toString(16)
		.toUpperCase();

		return "#"+"00000".substring(0, 6 - c.length) + c;
	}

	function getAutorisationNotification()
	{
		if('Notification' in window){ 
			Notification.requestPermission(function (status) { 
			} )
		}
	};

	var resizeWhenChangeTab = function()
	{
		$(".nav li").click(function(event) {
			setTimeout(function(){ resizeMaison(); }, 200);
		});
	}

	var showTabFromHash = function() {
		var hash = window.location.hash;
		if (hash) {
			hash && $('ul.nav a[href="' + hash + '"]').tab('show');
		};
		resizeMaison();
	}

	function DragAndDrop()
	{
		$( ".ContentLieux" ).sortable({
			//connectWith: $(this),
			connectWith: $(".ContentLieux"),
			handle: ".Device_title",
			update: function( event, ui )
			{
           		$($($(this).parent()).find(".DeviceContent")).each( function(e) {
      				console.log({device_Id: $(this).attr('device_id'), Lieux_Id: $(this).parent().parent().parent().attr("Lieux"),  Position: e});
              	})
       			resizeMaison();
           	}
		});
	}

	function init_component()
	{
		/*var Slider = $(".DeviceSlider");
		Slider.slider();*/	
		LoadModuleType();
		LoadTypeWidget();
		//LoadEquipement();
		//LoadLieux();
		//LoadCalendar();
		//LoadScenario();
		//LoadGraph();
		//LoadUser();
		LoadEvent();
		//GetLog();
	};

	function LoadEvent()
	{
		var tooltip = $('.tooltip');
		tooltip.hide();

		$("#Content-desktop .div_btn_device ").off();
		$("#Content-desktop .bar").off();
		$(".fa-history").off();
		$('#scenario-list').off();
		$('#calendar-link').off();
		$('#scenario-link').off();
		$('#manage-equipement-link').off();
		$('#manage-room-link').off();
		$('#user-link').off();
		$(".Enlarge").off();
		$("#scenario-save").off();
		$('#scenario-new').off();
		$('#scenario-delete').off();
		$("#log-link").off();

		$(".fa-history").mouseover(function(event) {
			var device_id = $(this).attr('device_id');
			var id = $(this).attr('id');
			SetToolTipLog(device_id,id)
		});

		$( ".fa-history" ).mouseout(function() {
			$(".popover").popover('destroy');
		});

		$('#scenario-list').on('change', function(){
			var id =  $('option:selected', this).attr('value');
			var name =$('option:selected', this).attr('name');
			var xml = $('option:selected', this).attr('XML');
			var status = $('option:selected', this).attr('status');
			xml = Blockly.Xml.textToDom(xml.replace(/\~amp~/g, '&'));
			Blockly.mainWorkspace.clear();		
			Blockly.Xml.domToWorkspace( Blockly.mainWorkspace, xml );
			$("#scenario-name").val(name);
			$("#scenario-id").val(id);

			if (status == 1)
			{
				$("#scenario-active").prop('checked', true);
			}
			else
			{
				$("#scenario-active").prop('checked', false);	
			}
		});
		
		$('#calendar-link').on('shown.bs.tab', function (e)
		{
			LoadCalendar();
		})

		$('#graphic-link').on('shown.bs.tab', function (e)
		{
			resizeMaison();
		})

		$('#scenario-link').on('shown.bs.tab', function (e)
		{
			LoadScenario();
		});

		$('#manage-equipement-link').on('shown.bs.tab', function (e)
		{
			LoadEquipement();
		});

		$('#manage-room-link').on('shown.bs.tab', function (e)
		{
			LoadLieux();
		});

		$('#user-link').on('shown.bs.tab', function (e) 
		{
			LoadUser();
		});

		$("#add-user").on("click", function()
		{
			getNewHash();
		});

		$(".Enlarge").on("click", function() {
		   $('#imagepreview').attr('src', $(this).attr('src')); // here asign the image to the modal when the user click the enlarge link
		   $("#modalEnlargeLabel").html($(this).parents(".DeviceContent").text());
		   $('#modalEnlarge').modal('show'); // modalEnlarge is the id attribute assigned to the bootstrap modal, then i use the show function
		});

		$("#scenario-save").click(function() {
			SaveScenario();
		});

		$('#scenario-new').click(function() {
			CleanScenario();
		});

		$('#scenario-delete').click(function() {
			var ScenarioId = $("#scenario-id").val();
			DeleteScenario(ScenarioId);
		});

		$("#log-link").click(function(){
			GetLog();
		});

		DeviceEvent();
	}

	function ShowLieux(LieuxId)
	{
		$("#Content-desktop .DeviceContainer").hide();
		if (LieuxId != "ALL")
		{
			$("#Content-desktop .DeviceContainer").filter('[lieux="'+LieuxId+'"]').show();
		}
		else
		{
			$(".DeviceContainer").show();
		}
		$(".DeviceContent").show();
		$("#Content-desktop").attr('style', 'display: block !important;');
		$("#Content-mobile").attr('style', 'display: none !important;');
		resizeMaison();
	}


	function ShowDevice(WidgetId)
	{
		$("#Content-desktop").attr('style', 'display: block !important;');
		$("#Content-mobile").attr('style', 'display: none !important;');
		$("#Content-desktop .DeviceContainer").hide();
		$("#Content-desktop .DeviceContent").hide();
		$("#Content-desktop .DeviceContent").filter('[WidgetId="'+WidgetId+'"]').parentsUntil( "#Content-desktop" ).show();
		$("#Content-desktop .DeviceContent").filter('[WidgetId="'+WidgetId+'"]').show();
		resizeMaison();
	}

	function ReturnMobileView()
	{
		$("#Content-desktop").attr('style', 'display: none !important;');
		$("#Content-mobile").attr('style', 'display: block !important;');
		resizeMaison();
	}

	function ErrorLoading(textStatus)
	{
		/*$("#error-success-information").removeClass();
		$("#error-success-information").addClass( "alert alert-danger" );
		$("#error-success-information").show();*/
		/***$("#error-sucess-detail").html("Impossible d'effectuer cette action, vérifiez votre connexion internet "+textStatus);***/
		/*$("#error-success-detail").html(textStatus);
		setTimeout(function(){ $("#error-success-information").hide()}, 5000);*/
		dhtmlx.message({
                text:"Impossible d'effectuer cette action, vérifiez votre connexion internet "+textStatus,
                expire: 5000,
                type:"error"
            });
	}

	function info(msg)
	{
		/*$("#error-success-information").removeClass();
		$("#error-success-information").addClass( "alert alert-success" );
		$("#error-success-information").show();
		$("#error-success-detail").html(msg);
		setTimeout(function(){ $("#error-success-information").hide()}, 5000);*/

		dhtmlx.message({
                text: msg,
                expire: 5000,
                type:"messageSuccess"
            });

	}

	function color_bullet(bullet_id, val) {
		$("#" + bullet_id).html(val);
		if (val <= 10) {
			$("#" + bullet_id).css("background-color", "#4ea6cf");
		} else if (val > 10 && val <= 12.5) {
			$("#" + bullet_id).css("background-color", "#5ac5cf");
		} else if (val >= 13 && val < 14) {
			$("#" + bullet_id).css("background-color", "#7dd7bf");

		} else if (val >= 14 && val < 15) {
			$("#" + bullet_id).css("background-color", "#98D3AF");
		} else if (val >= 15 && val < 16) {
			$("#" + bullet_id).css("background-color", "#C7CC94");
		} 
		else if (val >= 16 && val <= 17) {
			$("#" + bullet_id).css("background-color", "#E5BF7C");
		} 
		else if (val > 19 && val <= 22) {
			$("#" + bullet_id).css("background-color", "#E1B076");
		} else if (val > 22 && val <= 25) {
			$("#" + bullet_id).css("background-color", "#d79168");
		} else if (val > 25 && val <= 28) {
			$("#" + bullet_id).css("background-color", "#cd7159");
		} else if (val > 28  && val <= 29) {
			$("#" + bullet_id + ".circle").css("background-color", "#C75C50");
		}else if (val > 30) {
			$("#" + bullet_id + ".circle").css("background-color", "#c4463a");
		};
	};

	function colourNameToHex(colour)
	{
		var colours = {
			"aliceblue":"#f0f8ff",
			"antiquewhite":"#faebd7",
			"aqua":"#00ffff",
			"aquamarine":"#7fffd4",
			"azure":"#f0ffff",
			"beige":"#f5f5dc",
			"bisque":"#ffe4c4",
			"black":"#000000",
			"blanchedalmond":"#ffebcd",
			"blue":"#0000ff",
			"blueviolet":"#8a2be2",
			"brown":"#a52a2a",
			"burlywood":"#deb887",
			"cadetblue":"#5f9ea0",
			"chartreuse":"#7fff00",
			"chocolate":"#d2691e",
			"coral":"#ff7f50",
			"cornflowerblue":"#6495ed",
			"cornsilk":"#fff8dc",
			"crimson":"#dc143c",
			"cyan":"#00ffff",
			"darkblue":"#00008b",
			"darkcyan":"#008b8b",
			"darkgoldenrod":"#b8860b",
			"darkgray":"#a9a9a9",
			"darkgreen":"#006400",
			"darkkhaki":"#bdb76b",
			"darkmagenta":"#8b008b",
			"darkolivegreen":"#556b2f",
			"darkorange":"#ff8c00",
			"darkorchid":"#9932cc",
			"darkred":"#8b0000",
			"darksalmon":"#e9967a",
			"darkseagreen":"#8fbc8f",
			"darkslateblue":"#483d8b",
			"darkslategray":"#2f4f4f",
			"darkturquoise":"#00ced1",
			"darkviolet":"#9400d3",
			"deeppink":"#ff1493",
			"deepskyblue":"#00bfff",
			"dimgray":"#696969",
			"dodgerblue":"#1e90ff",
			"firebrick":"#b22222",
			"floralwhite":"#fffaf0",
			"forestgreen":"#228b22",
			"fuchsia":"#ff00ff",
			"gainsboro":"#dcdcdc",
			"ghostwhite":"#f8f8ff",
			"gold":"#ffd700",
			"goldenrod":"#daa520",
			"gray":"#808080",
			"green":"#008000",
			"greenyellow":"#adff2f",
			"honeydew":"#f0fff0",
			"hotpink":"#ff69b4",
			"indianred ":"#cd5c5c",
			"indigo":"#4b0082",
			"ivory":"#fffff0",
			"khaki":"#f0e68c",
			"lavender":"#e6e6fa",
			"lavenderblush":"#fff0f5",
			"lawngreen":"#7cfc00",
			"lemonchiffon":"#fffacd",
			"lightblue":"#add8e6",
			"lightcoral":"#f08080",
			"lightcyan":"#e0ffff",
			"lightgoldenrodyellow":"#fafad2",
			"lightgrey":"#d3d3d3",
			"lightgreen":"#90ee90",
			"lightpink":"#ffb6c1",
			"lightsalmon":"#ffa07a",
			"lightseagreen":"#20b2aa",
			"lightskyblue":"#87cefa",
			"lightslategray":"#778899",
			"lightsteelblue":"#b0c4de",
			"lightyellow":"#ffffe0",
			"lime":"#00ff00",
			"limegreen":"#32cd32",
			"linen":"#faf0e6",
			"magenta":"#ff00ff",
			"maroon":"#800000",
			"mediumaquamarine":"#66cdaa",
			"mediumblue":"#0000cd",
			"mediumorchid":"#ba55d3",
			"mediumpurple":"#9370d8",
			"mediumseagreen":"#3cb371",
			"mediumslateblue":"#7b68ee",
			"mediumspringgreen":"#00fa9a",
			"mediumturquoise":"#48d1cc",
			"mediumvioletred":"#c71585",
			"midnightblue":"#191970",
			"mintcream":"#f5fffa",
			"mistyrose":"#ffe4e1",
			"moccasin":"#ffe4b5",
			"navajowhite":"#ffdead",
			"navy":"#000080",
			"oldlace":"#fdf5e6",
			"olive":"#808000",
			"olivedrab":"#6b8e23",
			"orange":"#ffa500",
			"orangered":"#ff4500",
			"orchid":"#da70d6",
			"palegoldenrod":"#eee8aa",
			"palegreen":"#98fb98",
			"paleturquoise":"#afeeee",
			"palevioletred":"#d87093",
			"papayawhip":"#ffefd5",
			"peachpuff":"#ffdab9",
			"peru":"#cd853f",
			"pink":"#ffc0cb",
			"plum":"#dda0dd",
			"powderblue":"#b0e0e6",
			"purple":"#800080",
			"rebeccapurple":"#663399",
			"red":"#ff0000",
			"rosybrown":"#bc8f8f",
			"royalblue":"#4169e1",
			"saddlebrown":"#8b4513",
			"salmon":"#fa8072",
			"sandybrown":"#f4a460",
			"seagreen":"#2e8b57",
			"seashell":"#fff5ee",
			"sienna":"#a0522d",
			"silver":"#c0c0c0",
			"skyblue":"#87ceeb",
			"slateblue":"#6a5acd",
			"slategray":"#708090",
			"snow":"#fffafa",
			"springgreen":"#00ff7f",
			"steelblue":"#4682b4",
			"tan":"#d2b48c",
			"teal":"#008080",
			"thistle":"#d8bfd8",
			"tomato":"#ff6347",
			"turquoise":"#40e0d0",
			"violet":"#ee82ee",
			"wheat":"#f5deb3",
			"white":"#ffffff",
			"whitesmoke":"#f5f5f5",
			"yellow":"#ffff00",
			"yellowgreen":"#9acd32"
		};

		if (typeof colours[colour.toLowerCase()] != 'undefined')
		{
			return colours[colour.toLowerCase()];
		}
		else
		{
			return colour;
		}
	}

	function resizeMaison()
	{
		Nb_DeviceContainer = $(".DeviceContainer").length;
		$(".SubContainer, .ContentLieux, .widget").height("auto");
		posX = 0;
		for(i=0;i<=Nb_DeviceContainer ;i++)
		{
			var tb= [];
			function GetPosition()
			{
				tb.length=0;
				tb_device = $($($(".DeviceContainer")[i]).find( ".DeviceContent"))
				x = 0;
				$.each( tb_device, function( key,item) {
					tb.push( {
						Id: key,
						Pos:$(tb_device[x]).position().top
					})
					x++;
				});
			}

			posOld = 0;
			WHeightMax = 0;
			ZFirst = 0;
			GetPosition();	
			for (z=0;z<=tb.length; z++)
			{
				if (z<tb.length)
				{
					posX = tb[z].Pos;
				}
				if (((posX != posOld) || (z == tb.length))  && posOld != 0)
				{	
					for (Y = ZFirst; Y<z; Y++)
					{
						$($($($(".DeviceContainer")[i]).find( ".widget"))[Y]).height(WHeightMax);
					}
					GetPosition();
					if (z != tb.length)
					{
						posX = tb[z].Pos;
					}
					ZFirst = z;
					WHeightMax = 0;
					posOld = posX;
				}

				if (z<tb.length)
				{
					WHeight = $($($($(".DeviceContainer")[i]).find( ".widget"))[z]).height();
					if (WHeight > WHeightMax)
					{
						WHeightMax = WHeight;
					}
					posOld = posX;
				}
			}

			if (i%2 == 0)
			{
				Imax=Math.max($($(".DeviceContainer")[i]).height(), $($(".DeviceContainer")[i+1]).height()/*, $($(".DeviceContainer")[i+2]).height()*/);
				$($(".DeviceContainer .SubContainer")[i]).height(Imax);
				$($(".DeviceContainer .SubContainer")[i+1]).height(Imax);
				Imax=Math.max($($(".ContentLieux")[i]).height(), $($(".ContentLieux")[i+1]).height()/*, $($(".ContentLieux")[i+2]).height()*/);
				$($(".ContentLieux ")[i]).height(Imax);
				$($(".ContentLieux ")[i+1]).height(Imax);
			}

			//i = i+1;
		}

		if (window.innerWidth > 1200){
			$("#data-maison div.content-secondary, #data-maison div.content-primary").each(function() {
				if ($(this).attr('mod') == $(this.nextSibling).attr('mod')){
					if ($(this.nextSibling).height() <= $(this).height())
					{
						$(this.nextSibling).height($(this).height());
						$(this.nextSibling).children("ul").height($(this).children("ul").height());
						$($(this.nextSibling).children("ul").children("li")[0]).height($($(this).children("ul").children("li")[0]).height());
						$($(this.nextSibling).children("ul").children("li")[1]).height($($(this).children("ul").children("li")[1]).height());
					}
					else if  ($(this.nextSibling).height() > $(this).height())
					{
						$(this).height($(this.nextSibling).height());
						$(this).children("ul").height($(this.nextSibling).children("ul").height());
						$($(this).children("ul").children("li")[0]).height($($(this.nextSibling).children("ul").children("li")[0]).height());
						$($(this).children("ul").children("li")[1]).height($($(this.nextSibling).children("ul").children("li")[1]).height());

					}
				}
			});
		}
		else
		{
			$("#data-maison div.content-secondary, #data-maison div.content-primary").each(function() {
				$(this).height("auto");
			})
		}
	}

	//#region Scenario

	function LoadScenario()
	{ 
		if(Blockly.getMainWorkspace()){
			$("#blockly-scenario .injectionDiv").remove();
			Blockly.getMainWorkspace().dispose();
		}
		var blocklyDiv = document.getElementById("blockly-scenario");
		var BlocklyContent = Blockly.inject(blocklyDiv,
			{toolbox: document.getElementById('toolbox'), scrollbars: false});
		var onresize = function(e) {
			blocklyDiv.style.height = $("#scenario").height()+ 'px';
			Blockly.svgResize(BlocklyContent);
		};
		window.addEventListener('resize', onresize, false);
		LoadDeviceName();
		ListScenario();
		onresize();
	}

	function parseXml(xml) {
		if ($(xml).children().length > 2) {
			return "err:Please make sure there is only a single block structure";
		}
		var firstBlockType = $(xml).find("block").first().attr("type");
		if (firstBlockType.indexOf("controls_if") == -1) {
			return "err:Please start with a control block";
		}
		var elseIfCount = 0;
		if ($(xml).find("mutation:first").attr("elseif")>0)
		{
			var elseIfString = $(xml).find("mutation:first").attr("elseif");
			elseIfCount = parseInt(elseIfString);
		}
		elseIfCount++;

		var json = {};
		json.eventlogic = []

		for (var i=0;i<elseIfCount;i++)
		{ 
			conditionActionPair = parseXmlBlocks(xml,i);
			var oneevent = {};
			oneevent.conditions = conditionActionPair[0].toString();
			oneevent.actions = conditionActionPair[1].toString();
			json.eventlogic.push(oneevent);
		}

		return json;
	}

	function opSymbol(operand) {
		switch(operand)
		{
			case 'EQ':
			operand = ' = ';
			break;
			case 'NEQ':
			operand = ' <> ';
			break;
			case 'LT':
			operand = ' < ';
			break;
			case 'GT':
			operand = ' > ';
			break;
			case 'LTE':
			operand = ' <= ';
			break;
			case 'GTE':
			operand = ' >= ';
			break;
			default:
		} 
		return operand;		     
	}

	function parseStateAttr(value,Operand)
	{
		NewStr = "";
		var fieldB = $(value).find("field")[0];

		switch ($(fieldB).attr("name")) {
			case "State":      					
			NewStr = ' && DeviceEtat'+Operand+'"'+$(fieldB).text()+'"';
			break;
			case "TEXT":
			NewStr = ' && DeviceValue'+Operand+'"'+$(fieldB).text()+'"';
			break;
			case "NUM":
			NewStr = ' && DeviceValue'+Operand+$(fieldB).text();
			break;
			default:
			NewStr = ' && DeviceEtat'+Operand+'"'+$(fieldB).text()+'"';
		}

		return NewStr;
	}

	function parseXmlBlocks(xml,pairId)
	{
		var boolString = "";

		function GetValueText(value, variableType,locOperand)
		{
			if ((variableType.indexOf("switchvariables")  >= 0 ) ||  ((variableType == "temperaturevariables") == true))
			{
				var fieldA = $(value).find("field")[0];
				return "DeviceId="+$(fieldA).text();
			}
			else
			{
				return parseStateAttr (value,locOperand);
			}
		}

		function parseLogicCompare(thisBlock)
		{
			var locOperand = opSymbol($($(thisBlock).children("field:first")).text());
			var valueA = $(thisBlock).children("value[name='A']")[0];
			var variableTypeA = $(valueA).children("block:first").attr("type");
			var valueB = $(thisBlock).children("value[name='B']")[0];
			var variableTypeB = $(valueB).children("block:first").attr("type");
			var varTextA=GetValueText(valueA,variableTypeA);
			var varTextB=GetValueText(valueB,variableTypeB,locOperand);
			var compareString = varTextA;
			compareString += varTextB; 
			return compareString;
		}

		function parseLogicTimeOfDay(thisBlock)
		{
			var compareString = "";
			var locOperand = opSymbol($($(thisBlock).children("field:first")).text());
			var valueTime = $(thisBlock).children("value[name='Time']")[0];
			var timeBlock = $(valueTime).children("block:first");
			if (timeBlock.attr("type")=="logic_timevalue") {	
				var valueA = $(timeBlock).children("field[name='TEXT']")[0];			
				var hours=parseInt($(valueA).text().substr(0,2));
				var minutes=parseInt($(valueA).text().substr(3,2));
				var totalminutes=(hours*60)+minutes;
				compareString = 'timeofday '+locOperand+' '+totalminutes;
			}
			else if (timeBlock.attr("type")=="logic_sunrisesunset") {
				var valueA = $(timeBlock).children("field[name='SunriseSunset']")[0];
				compareString = 'timeofsun '+locOperand+' @'+$(valueA).text(); 
			}
			else if (timeBlock.attr("type").indexOf("switchvariables") != -1)
			{
				compareString = "timeofday" + locOperand + ";getdata["+$($(timeBlock).find("field")[0]).text()+"]";
			}

			return compareString;	
		}

		function parseLogicLastExecute(thisBlock)
		{
			var compareString = "";
			var locOperand = opSymbol($($(thisBlock).children("field:first")).text());
			var valueTime = $(thisBlock).children("value[name='Last Execute']")[0];
			var timeBlock = $(valueTime).children("block:last");
			if (timeBlock.attr("type")=="logic_timevalue") {	
				var valueA = $(timeBlock).children("field[name='TEXT']")[0];			
				var hours=parseInt($(valueA).text().substr(0,2));
				var minutes=parseInt($(valueA).text().substr(3,2));
				var totalminutes=(hours*60)+minutes;
				compareString = ' (INTERVAL '+totalminutes+' minute)'+locOperand+' LastExecute';
			}
			return compareString;				
		}

		function parseLogicLastUpdate(thisBlock)
		{
			var compareString = "";
			var locOperand = opSymbol($($(thisBlock).children("field:first")).text());
			var valueTimeOfObject = $(thisBlock).children("value[name='A']")[0];
			var variableTypeOfObject = $(valueTimeOfObject).children("block:first").attr("type");
			var fieldOfObject = $(valueTimeOfObject).find("field")[0];

			var valueTime = $(thisBlock).children("value[name='B']")[0];
			var timeBlock = $(valueTime).children("block:last");
			if (timeBlock.attr("type")=="logic_timevalue") {	
				var valueA = $(timeBlock).children("field[name='TEXT']")[0];			
				var hours=parseInt($(valueA).text().substr(0,2));
				var minutes=parseInt($(valueA).text().substr(3,2));
				var totalminutes=(hours*60)+minutes;
				compareString = 'DeviceId = ' + $(fieldOfObject).text() + ' && (INTERVAL '+totalminutes+' minute)' + locOperand + 'LastDeviceUpdate' ;
			}
			return compareString;				
		}

		function parseLogicWeekday(thisBlock)
		{
			var locOperand = opSymbol($($(thisBlock).children("field:first")).text());
			var valueA = $(thisBlock).children("field[name='Weekday']")[0];
			var compareString = 'weekday '+locOperand+' '+$(valueA).text(); 
			return compareString;
		}		

		function parseSecurityStatus(thisBlock)
		{
			var locOperand = opSymbol($($(thisBlock).children("field:first")).text());
			var valueA = $(thisBlock).children("field[name='Status']")[0];
			var compareString = 'securitystatus '+locOperand+' '+$(valueA).text(); 
			return compareString;
		}

		function parseValueBlock(thisBlock,locOperand,Sequence)
		{
			var firstBlock = $(thisBlock).children("block:first");
			if (firstBlock.attr("type")=="logic_compare") {
				var conditionstring = parseLogicCompare(firstBlock);
				return conditionstring;
			}
			else if (firstBlock.attr("type")=="logic_weekday") {
				var conditionstring = parseLogicWeekday(firstBlock);
				return conditionstring;
			}
			else if (firstBlock.attr("type")=="logic_timeofday") {
				var conditionstring = parseLogicTimeOfDay(firstBlock);
				return conditionstring;
			}
			else if (firstBlock.attr("type")=="logic_operation") {
				var conditionstring = parseLogicOperation(firstBlock);
				return conditionstring;
			}
			else if (firstBlock.attr("type")=="security_status") {
				var conditionstring = parseSecurityStatus(firstBlock);
				return conditionstring;
			} 
			else if (firstBlock.attr("type")=="logic_LastExecute")
			{
				var conditionstring = parseLogicLastExecute(firstBlock);
				return conditionstring;
			}
			else if (firstBlock.attr("type")=="logic_LastUpdate")
			{
				var conditionstring = parseLogicLastUpdate(firstBlock);
				return conditionstring;
			}
		}

		function parseLogicOperation(thisBlock)
		{
			var locOperand = $($(thisBlock).children("field:first")).text().toLowerCase();
			var valueA = $(thisBlock).children("value[name='A']")[0];
			var valueB = $(thisBlock).children("value[name='B']")[0];
			var conditionA = parseValueBlock(valueA,locOperand,"A");
			var conditionB = parseValueBlock(valueB,locOperand,"B");
			var conditionstring = "("+conditionA+" ) "+locOperand+" ( "+conditionB+")";
			return conditionstring; 
		}

		var ifBlock = $($(xml).find("value[name='IF"+pairId+"']")[0]).children('block:first');

		if (ifBlock.attr("type")=="logic_compare")
		{
      		// just the one compare, easy
      		var compareString = parseLogicCompare(ifBlock);
      		boolString += compareString;
      	}
      	else if (ifBlock.attr("type")=="logic_operation")
      	{
      		// nested logic operation, drill down
      		var compareString = parseLogicOperation(ifBlock);
      		boolString += compareString;

      	}
      	else if (ifBlock.attr("type")=="logic_timeofday")
      	{
      		// nested logic operation, drill down
      		var compareString = parseLogicTimeOfDay(ifBlock);
      		boolString += compareString;
      	}
      	else if (ifBlock.attr("type")=="logic_weekday")
      	{
      		// nested logic operation, drill down
      		var compareString = parseLogicWeekday(ifBlock);
      		boolString += compareString;
      	}    	
      	else if (ifBlock.attr("type")=="logic_LastUpdate")
      	{
      		// nested logic operation, drill down
      		var compareString = parseLogicLastUpdate(ifBlock);
      		boolString += compareString;
      	}   
      	else if (ifBlock.attr("type")=="security_status")
      	{
      		// nested logic operation, drill down
      		var compareString = parseSecurityStatus(ifBlock);
      		boolString += compareString;
      	}     	
      	
      	var setArray = [];
      	var doBlock = $($(xml).find("statement[name='DO"+pairId+"']")[0]);
      	$(doBlock).find('block').each (function()
      	{ 
      		if ($(this).attr('type') == 'logic_set')
      		{
      			var locOperand = opSymbol($($(this).children("field:first")).text());
      			var valueA = $(this).find("value[name='A']")[0];
      			var titleA = $(valueA).find("field")[0];
      			var blockA = $(valueA).children("block:first");
      			var valueB = $(this).find("value[name='B']")[0];
      			var titleB = $(valueB).find("field")[0];
      			var blockB = $(valueB).children("block:first");

      			var setString = " DeviceId= "+$(titleA).text();
      			setString += parseStateAttr (valueB,"=");
      			setArray.push(setString);
      		}
      		else if ($(this).attr('type') == 'logic_setdelayed')
      		{
      			var valueA = $(this).find("value[name='A']")[0];
      			var titleA = $(valueA).find("field")[0];
      			var valueC = $(this).find("value[name='C']")[0];
      			var titleC = $(valueC).find("field")[0];
      			var blockA = $(valueA).children("block:first");      			
      			var valueB = $(this).find("value[name='B']")[0];
      			var titleB = $(valueB).find("field")[0];
      			var blockB = $(valueB).children("block:first");

      			var setString = " DeviceId= "+$(titleA).text();
      			if ((blockB.attr("type")=="logic_states") && ($(titleB).attr("name") == "State")) {
      				setString += '="'+$(titleB).text()+' FOR '+ $(titleC).text()+'"'; 
      				setArray.push(setString);
      			}        	
      			else if ((blockB.attr("type")=="logic_setlevel") && ($(titleB).attr("name") == "NUM")) {
      				setString += '="Set Level '+$(titleB).text()+' FOR '+ $(titleC).text()+'"'; 
      				setArray.push(setString);
      			}	        	

      		}
      		else if ($(this).attr('type') == 'logic_setrandom')
      		{
      			var valueA = $(this).find("value[name='A']")[0];
      			var titleA = $(valueA).find("field")[0];
      			var valueB = $(this).find("value[name='B']")[0];
      			var titleB = $(valueB).find("field")[0];
      			var valueC = $(this).find("value[name='C']")[0];
      			var titleC = $(valueC).find("field")[0];
      			var blockA = $(valueA).children("block:first");

      			var setString = " DeviceId= "+$(titleA).text();
      			var blockB = $(valueB).children("block:first");
      			if ((blockB.attr("type")=="logic_states") && ($(titleB).attr("name") == "State"))
      			{	        	
      				setString += '="'+$(titleB).text()+' RANDOM '+ $(titleC).text()+'"'; 
      				setArray.push(setString);
      			}
      			else if ((blockB.attr("type")=="logic_setlevel") && ($(titleB).attr("name") == "NUM"))
      			{
      				setString += '="Set Level '+$(titleB).text()+' RANDOM '+ $(titleC).text()+'"'; 
      				setArray.push(setString);
      			}        	
      		}
      		else if ($(this).attr('type') == 'send_notification') 
      		{
      			var subjectBlock = $(this).find("value[name='notificationTextSubject']")[0];
      			var bodyBlock = $(this).find("value[name='notificationTextBody']")[0];
      			var notificationBlock = $(this).children("field[name='notificationPriority']")[0];
      			var soundBlock = $(this).children("field[name='notificationSound']")[0];
      			var sTitleText = $(subjectBlock).find("field[name='TEXT']")[0];
      			var bTT ="" ;

      			if ($(bodyBlock).find("mutation").attr("items") > 0)
      			{    		
      				var LengthTextNorification = $(bodyBlock).find("mutation").attr("items");
      				for (var LTN=0; LTN<LengthTextNorification; LTN++) 
      				{
      					var LTN_Value = $(this).find("value[name='ADD"+LTN+"']").find("field[name]")[0];
      					if ($(LTN_Value).attr("name") == "TEXT")
      					{
      						var bTitleText = $(LTN_Value);   
      						bTT += $(bTitleText).text().replace(/\,/g, ' ');
      					}
      					else
      					{			
      						var LTN_parent = $(LTN_Value).parent().find("field")[0];
      						if (LTN_parent != undefined)
      						{
      							bTT += ";getdata["+$(LTN_parent).text()+"]";		
      						}
      					}
      				}		
      			}
      			else
      			{
      				var bTitleText = $(bodyBlock).find("field[name='TEXT']")[0];	
      				var bTT = $(bTitleText).text().replace(/\,/g, ' ');        		
      			}

      			var sTT = $(sTitleText).text().replace(/\,/g, ' ');
      			var pTT=$(notificationBlock).text();
      			var aTT=$(soundBlock).text();
		    	// message separator here cannot be # like in scripts, changed to $..
		    	// also removed commas as we need to separate commandArray later.

		    	var setString = ' DeviceId="SendNotification"&&DeviceValue="'+sTT+'$'+bTT+'$'+pTT+'$'+aTT+'"';
		    	setArray.push(setString);		      	
		    }
		    else if ($(this).attr('type') == 'send_email')
		    {
		    	var subjectBlock = $(this).children("field[name='TextSubject']")[0];
		    	var bodyBlock = $(this).children("field[name='TextBody']")[0];
		    	var toBlock = $(this).children("field[name='TextTo']")[0];
		    	var sSubject = $(subjectBlock).text().replace(/\,/g, ' ');
		    	var sBody = $(bodyBlock).text().replace(/\,/g, ' ');
		    	var sTo = $(toBlock).text();
		    	// message separator here cannot be # like in scripts, changed to $..
		    	// also removed commas as we need to separate commandArray later.
		    	var setString = ' DeviceId="SendEmail"&&DeviceValue="'+sSubject+'$'+sBody+'$'+sTo+'"';
		    	setArray.push(setString);		      	
		    }
		    else if ($(this).attr('type') == 'open_url')
		    {
		    	var urlBlock = $(this).find("value[name='urlToOpen']")[0];
		    	var urlText = $(urlBlock).find("fieldfieldfieldfieldfield[name='TEXT']")[0];
		    	var urlNoAmpersands =  $(urlText).text().replace(/\&/g, '~amp~');
		    	urlNoAmpersands =  urlNoAmpersands.replace(/\,/g, '~comma~');
		    	var setString = ' DeviceId="OpenURL"&&DeviceValue="'+urlNoAmpersands+'"';
		    	setArray.push(setString);		      	
		    }
		    else if ($(this).attr('type') == 'groupvariables')
		    {
		    	var titleA = $(this).find("fieldfieldfieldfield[name='Group']")[0];
		    	var titleB = $(this).find("fieldfieldfield[name='Status']")[0];
		    	var setString = ' DeviceId="Group:'+$(titleA).text()+'"';
		    	setString += '&& DeviceValue="'+$(titleB).text()+'"'; 
		    	setArray.push(setString);
		    }
		    else if ($(this).attr('type') == 'scenevariables')
		    {
		    	var titleA = $(this).find("fieldfield[name='Scene']")[0];
		    	var titleB = $(this).find("field[name='Status']")[0];
		    	var setString = ' DeviceId="Scene:'+$(titleA).text()+'"';
		    	setString += ' && DeviceValue="'+$(titleB).text()+'"'; 
		    	setArray.push(setString);
		    }		
		    else if ($(this).attr('type') == 'logic_Execute')
		    {
		    	var valueA = $(this).find("value[name='A']")[0];
		    	var titleA = $(valueA).find("field")[0];
		    	var blockA = $(valueA).children("block:first");
		    	var setString = " Execute="+$(titleA).text();
		    	setArray.push(setString);
		    }		    
		});
	var conditionArray = [];
	conditionArray.push(boolString);
	return [conditionArray,setArray];
}

function SaveScenario() {
	var xml = Blockly.Xml.workspaceToDom( Blockly.mainWorkspace );
	var ScenarioName = $("#scenario-name").val();
	var ScenarioId =  $("#scenario-id").val();
	var UpdateScenario = false;
	if (ScenarioName) { 
		var exists = false; 
		var doSave = false;
		$('#scenario-list  option').each(function(){
			if (this.value == ScenarioId) {
				exists = true;
			}
		});
		if (exists) {
			var answer = confirm("Overwrite "+ScenarioName+"?")
			if (answer){
				doSave = true;
				ScenarioId = $("#scenario-id").val();
			}
			else{
				doSave = false;
			}
		}
		else {doSave = true;}
		if (doSave)
		{
			var blockXml  = Blockly.Xml.domToText( xml );
			var logicArray = parseXml(xml);
			blockXml = blockXml.replace(/\&/g, '~amp~');
			if (typeof(logicArray) == 'string')
			{
				var answerparts = logicArray.split(':');
				if (answerparts[0] == "err")
				{
					alert(answerparts[1]);
				}
			}
			else if (typeof(logicArray) == 'object')
			{
				var isActive = 0;
				if ($('#scenario-active').is(':checked')) 
				{
					isActive = 1;
				}

				if (ScenarioId != "") 
				{
					UpdateScenario = true;
				}

				SaveScenarioXMLAndScenarioExec(ScenarioId, UpdateScenario, ScenarioName, blockXml, isActive, logicArray);
			}
		}
		else
		{
			alert("Save aborted!");
		}
	}
	else
	{
		alert('no event name entered!');
	}
}

function CleanScenario()
{	
	Blockly.mainWorkspace.clear();	
	$("#scenario-name").val('');
	$("#scenario-id").val('');
	$('#scenario-list option').removeAttr("selected")
}

// #endregion 

function GenerateGraph(Lieux, data)
{
	$(function() {
		Highcharts.setOptions({
			global : {
				useUTC:false
			}
		});  
		$('#History_'+Lieux).highcharts('StockChart', {		

			chart: {
				renderTo: 'History_'+Lieux,
				zoomType: false

			},
			rangeSelector: {
				buttons : [{
					type : 'hour',
					count : 3,
					text : '3h'
				},{
					type : 'hour',
					count : 12,
					text : '12h'

				},{
					type : 'hour',
					count : 24,
					text : '24'

				},{
					type: 'day',
					count: 3,
					text: '3d'
				},{
					type : 'week',
					count : 1,
					text : '1s'
				},{
					type : 'week',
					count : 2,
					text : '2s'
				},{
					type : 'week',
					count : 3,
					text : '3s'
				},{
					type : 'month',
					count : 1,
					text : '1m'
				},{
					type : 'month',
					count : 2,
					text : '2m'
				},{
					type : 'all',
					count : 1,
					text : 'All'
				}],
				selected : 3,
				inputEnabled : false
			},	
			legend: {
				enabled: true
			},
			title: {
				text: Lieux
			},
			xAxis: {
				min: new Date().getTime()- 48 * 3600 * 1000,
				max: new Date().getTime() +1  * 3600 *1000,
				ordinal: false,
				plotLines: [{
					value: 0,
					width: 2,
					color: 'silver'
				}]
			},
			yAxis: {
				title: {
					text: 'Temperature (°C )'
				}
			},
			/*plotOptions: {
				series: {
					compare: 'percent'
				}
			},*/


			series: data,
			pointInterval: 60*10,
			tooltip: {
				pointFormat: '<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y}</b><br/>',
				crosshairs: true,
				shared: true,
				valueDecimals: 1,
				valueSuffix: ' °C'
			}
		});
	})
}
</script>
