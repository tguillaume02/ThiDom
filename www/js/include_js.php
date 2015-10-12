		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.1/jquery.qtip.min.js"></script>
		<script type="text/javascript" src="js/jquery_mobile/jquery.mobile-events.min.js"></script>
		<script src="js/jquery.base64.js" type="text/javascript"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://code.highcharts.com/stock/highstock.js"></script>
		<script type="text/javascript" src="js/Highstock/js/themes/gray.js"></script>
		
		<script type="text/javascript" src="js/Blockly/blockly_compressed.js"></script>
		<script type="text/javascript" src="js/Blockly/blocks_compressed.js"></script>
		<script type="text/javascript" src="js/Blockly/fr.js"></script>
		<script type="text/javascript" src="js/Blockly/BlocklyPerso.js"></script>
		
		<script type="text/javascript"  src="js/JusteGage/justgage.js"></script>
		<script type="text/javascript"  src="js/JusteGage/raphael.2.1.0.min.js"></script>
		
		<script type="text/javascript" src="palette-couleur/scr/farbtastic.js"></script>

		<script>
		$( document ).ready(function() {
			$("body").on("click",function(t){
				ClassElementClicked = (t.target.className); 
				if (ClassElementClicked !="type_rgb" && ClassElementClicked != "farbtastic" && ClassElementClicked != "wheel"){
					$("#colorpicker").hide( "slow", function() {
						parent_picker = $("#colorpicker").attr("parent");
						bg_color_picker = $("#"+parent_picker).css("background-color");
						bg_color_picker_hex = rgb2hex( bg_color_picker );
						color_picker_deviceid = $("#colorpicker").attr("deviceid");
						color_picker_pinid = $("#colorpicker").attr("pinid");
						color_picker_carteid = $("#colorpicker").attr("carteid");

						var request = $.ajax({
							type: 'post',  
							url: 'action_ajax.php',
							data: {
								mode: 'change_value',
								id : color_picker_deviceid,
								value : bg_color_picker_hex
							},
							cache: false,
							async: false
						}); 
					});
				};		
			})
		});

		function addEvent(obj, event, fct) {
				if (obj.attachEvent) //Est-ce IE ?
					obj.attachEvent("on" + event, fct); //Ne pas oublier le "on"
				else
					obj.addEventListener(event, fct, true);
			}
			
			function ErrorLoading()
			{			
				// $.mobile.showPageLoadingMsg( $.mobile.pageLoadErrorMessageTheme, "Impossible d'effectuer cette action, vérifiez votre connexion internet" , true );
				$.mobile.loading( "show", {
					text:  "Impossible d'effectuer cette action, vérifiez votre connexion internet",
					textVisible: true,
					textonly : true,
					theme: "e",
					html: ""
				});
				// setTimeout( $.mobile.hidePageLoadingMsg, 2000 );
				setTimeout(  $.mobile.loading( "hide" ), 2000 );
			}
			
			function info(msg)
			{			
				//$.mobile.showPageLoadingMsg( $.mobile.pageLoadErrorMessageTheme, msg , true );
				$.mobile.loading( "show", {
					text: msg,
					textVisible: true,
					textonly : true,
					theme: "b",
					html: ""
				});
				// setTimeout( $.mobile.hidePageLoadingMsg, 2000 );
				 setTimeout(function () {$.mobile.loading('hide');},2000);
			}
			
			var ajax_action;


			function SetToolTip()
			{	
				$(".device_title[id]").each(function()
				{
					$(this).qtip(
					{
						content: {
							text: 'Loading...',  
							ajax: 
							{
							 	url: 'action_ajax.php',					 
							 	type: 'post', 
								data: {
									mode: 'last_log',
									id : $(this).attr('id').split("_").reverse()[0]
								},
								success: function(data) 
								{								
									$.each(data, function (index, item) {
										content = item.content;
									}); 

									this.set('content.text', content);
							 	}
							}
						},				
					 	show: {
							event: 'mouseover',
							solo: true // Only show one tooltip at a time
						 },							
						style: { 
							classes: 'qtip-light qtip-rounded ui-tooltip-light ui-tooltip-shadow ui-tooltip-rounded',

							tip: {
								corner: true
							},
							 width: { max: '100%' } ,
						},

						position: {
							effect: true,
							my: 'bottom right',
							at: 'top left',
							viewport:$(window)
						}		
					});
				});
			}

			function get_time()
			{
				date = new Date;
				h = date.getHours();
				if(h<10)
				{
					h = "0"+h;
				}
				m = date.getMinutes();
				if(m<10)
				{
					m = "0"+m;
				}
				s = date.getSeconds();
				if(s<10)
				{
					s = "0"+s;
				}
				$("#time").html(h+':'+m+':'+s);
				setTimeout('get_time();', 1000);
			};
			
			function LoadGraph() {
				$.ajax({
					type: "POST",
					url: 'LoadGraph_new.php',
					cache: false,
					async: true,
					success: function (data) {
						$("#content_graph").html("");
						$("#content_graph").html(data);
						$(window).resize();
					}
				});
			}

			function LoadMaison() {
				var request = $.ajax({
					type: "POST",
					url: 'LoadMaison.php',
					cache: false,
					async: true
				});
				
				request.done(function (data) {
					$("#content_home").html("");
					Recup_Temp();
					$("#content_home").html(data);
					$("#div_home").page();
					$("#div_home").page("destroy").page();
					$(".ui-content .ui-last-child").css('min-height', '117');
					$(".conteneur_device").css('min-height', '117');
					// permet de definir la hauteur des conteneurs
					$(".ui-content .ui-last-child").height("auto");
					$(".conteneur_device").height("auto");
					init_component();
					resizeMaison();
					SetToolTip();
				});
				
				request.fail(function (jqXHR, textStatus, errorThrown) {
					ErrorLoading();
				});
			}

			function LoadEquipement()
			{				
				$("#tbody_AdminEquipement").html("");
				$("#AdminPlugins tbody").html("");
				var request = $.ajax({
					dataType: "json",
					type: "POST",
					url: 'action_ajax.php',							
					data: {
						mode: 'Equipement',
						property: '',
						lieux:'',
						id:'',
						act:''
					},
					cache: false,
					async: true
				});
				
				request.done(function (data) {		
					if (data.length > 0)
					{					
						var oTable = $("#AdminEquipement").DataTable();
						oTable.destroy();	
						$("#tbody_AdminEquipement").empty()
						$("#AdminPlugins").append("<tr id='TrAdminPlugins'></tr>")
						w = -1;
						for(var i=0;i<data.length;i++) {	
							$event = data[i];	
							ID = $event.ID;	
							cmd_device_ID = $event.cmd_device_ID;
							DeviceID = $event.DeviceID;
							Carte_ID = $event.Carte_ID;
							TypeID = $event.TypeID;
							LieuxID = $event.LieuxID;
							Type = $event.Type ;
							Piece = $event.Piece;
							Nom = $event.Nom;
							RAZ = $event.RAZ;
							Visible = $event.Visible;
							event_delete = "delete_app";
							if (Type == "Plugins")
							{
								$("#TrAdminPlugins").append("<td><table class='Equipement_Plugins'><tr><td><img src=plugins/"+Nom+"/pic/"+Nom+".png></td></tr><tr><td style='text-align:center;color:white'>"+Nom+"</td></tr></table></td>");
							}
							else
							{
								w+=1;
								$("#tbody_AdminEquipement").append("<tr><td style='text-align:center;'>"+ID+"</td><td style='text-align:center;'>"+cmd_device_ID+"</td><td style='text-align:center;'>"+DeviceID+"</td><td style='text-align:center;'>"+Carte_ID+"</td><td style='text-align:center;'>"+TypeID+"</td><td style='text-align:center;'>"+LieuxID+"</td><td style='text-align:center;'>"+RAZ+"</td><td style='text-align:center;'>"+Visible+"</td><td style='text-align:center;'>"+Piece+"</td><td style='text-align:center;'>"+Nom+"</td><td style='text-align:center;'>"+Type+"</td><td style='text-align:center;' id='edit_"+w+"' ><img src='pic/pencil.png' alt='pencil' id='pencil_"+w+"' onclick='EditEquipement("+ID+","+cmd_device_ID+","+w+")'></td><td><img src='pic/delete.png' ald='pencil'  onclick='delete_confirm(event_delete,"+cmd_device_ID+","+w+")'></td></tr>");
								//$("#tbody_AdminEquipement").append("<tr><td style='text-align:center;'>"+ID+"</td><td style='text-align:center;'>"+Piece+"</td><td style='text-align:center;'>"+Nom+"</td><td style='text-align:center;'>"+Type+"</td><td style='text-align:center;' id='edit_"+i+"' ><img src='pic/pencil.png' alt='pencil' id='pencil_"+i+"' onclick='EditEquipement("+ID+","+i+")'>&nbsp;&nbsp;<img src='pic/delete.png' ald='pencil'></td></tr>");
							}
						}			
						oTable = $("#AdminEquipement").DataTable({
							"bJQueryUI": true,
							"bLengthChange": false,
							"bFilter": true,
							"bInfo": false,
							"bSort": false,
							"sPaginationType": "full_numbers",

							"columnDefs": [
							{ "visible": false, "targets": [0,1,2,3,4,5,6,7] }
							],
							"oLanguage": {
								"SProcessing" :     "Traitement en cours ..." ,
								"Ssearch" :         "Rechercher:" ,
								"sLengthMenu" :     "éléments Display _MENU_" ,
								"SInfo" :           "Affichage de l'élement _START_ à _END_ sur _TOTAL_ éléments" ,
								"SInfoEmpty" :      "Affichage de l'élement 0 à 0 sur 0 éléments" ,
								"SInfoFiltered" :   "(Filtre de _MAX_ 'éléments au total)" ,
								"SInfoPostFix" :    "" ,
								"sLoadingRecords" : "Chargement en cours ..." ,
								"sZeroRecords" :    "Aucun élément à afficher" ,
								"SEmptyTable" :     "Aucune Donnée Disponible Dans Le tableau" ,
								"OPaginate" : {
									"SFirst" :      "Premier" ,
									"SPrevious" :   "Précédent" ,
									"SNext" :       "Suivant" ,
									"Slast" :       "Dernier"
								},
								"OAria" : {
									"SSortAscending" :  ": activer verser juge la colonne par ordre croissant" ,
									"SSortDescending" : ": activer verser juge la colonne par ordre decroissant"
								},
							}
						});

						/*if (window.innerWidth < 484)
						{
							oTable = $("#AdminEquipement").dataTable();
							oTable.fnSetColumnVis( 3, false);
							oTable.fnSetColumnVis( 4, false);
						};*/
						$(".dataTable").width('100%');
					}
					
				});

		request.fail(function (jqXHR, textStatus, errorThrown) 
		{
			ErrorLoading();
		});

	}

	function delete_confirm(event,cmd_device_id,num_row)
	{
		if (event ==  "delete_piece")
		{
			question ="Voulez vous vraiment supprimer cette pièce et tout les equipements qui y sont attaché? ( Si vous desirez garder vos equipements attaché à cette pièce, attachez les à une autre pièces avant de supprimer)";
		}
		else
		{
			question ="Voulez vous vraiment supprimer cet equipement?";
		}
		var r = confirm(question);
		if (r == true) {
			valid(event,cmd_device_id,num_row);
		}
	}

	function EditEquipement(id,cmd_device_id,num_row)
	{ 	
		Load_Lieux();
		//num_row = num_row +1;
		/*piece_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(2)').html();
		app_name_old= $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(3)').html();
		id_app_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(4)').html();
		id_carte_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(5)').html();
		type_app_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(6)').html();
		*/
		/*$('#AdminEquipement tr:nth-child('+num_row+')  td:nth-child(2)').html('<input type="text" id="new-title-'+id+'" value="'+piece_old+'" />');
		$('#AdminEquipement tr:nth-child('+num_row+')  td:nth-child(3)').html('<input type="text" id="new-title-'+id+'" value="'+app_name_old+'" />');
		$('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(4)').html('<input type="text" id="new-title-'+id+'" value="'+id_app_old+'" />');
		$('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(5)').html('<input type="text" id="new-title-'+id+'" value="'+id_carte_old+'" />');
		$('#AdminEquipement tr:nth-child('+num_row+')  td:nth-child(6)').html('<input type="text" id="new-title-'+id+'" value="'+type_app_old+'" />');*/
		
		//$('#edit_'+num_row-1).html("<img id=accept_"+num_row-1+" src='pic/accept.png' alt='accept'>&nbsp; <img id=cross_"+num_row-1+" src='pic/cross.png' alt='cross'>");
		if (id == undefined)
		{
			$("#ID_EtatIO").val("");
			$("#app_name").val("");
			$("#app_id").val("");
			$("#carte_id").val("");	
			$("#add_app_name_piece option[value='0']").attr('selected', true);
			$("#add_app_type_app option[value='0']").attr('selected', true);
			$("#add_app_name_piece").selectmenu('refresh', true);
			$("#add_app_type_app").selectmenu('refresh', true);					
		}
		else
		{
			oTable= $('#AdminEquipement').dataTable();	
			ID = oTable.fnGetData(num_row)[0];
			cmd_device_ID = oTable.fnGetData(num_row)[1];
			deviceID = oTable.fnGetData(num_row)[2];
			carteID = oTable.fnGetData(num_row)[3];
			typeID = oTable.fnGetData(num_row)[4];
			lieux = oTable.fnGetData(num_row)[5];
			NomApp = oTable.fnGetData(num_row)[9];
			RAZ = oTable.fnGetData(num_row)[6];
			visible_app = oTable.fnGetData(num_row)[7]-0;
			if (RAZ != "" && RAZ >0)
			{
				var hours = Math.floor( RAZ / 60);
				if (hours < 10)          
				{
					hours= "0"+hours;
				}
				var minutes = RAZ % 60;
				if (minutes < 10)
				{
					minutes = "0"+minutes;
				}
			}
			$("#add_app_name_piece option[value='"+lieux+"']").attr('selected', true);
			$("#add_app_type_app option[value='"+typeID+"']").attr('selected', true);
			$("#add_app_name_piece").selectmenu('refresh', true);
			$("#add_app_type_app").selectmenu('refresh', true);
			$("#ID_EtatIO").val(ID);
			$("#cmd_device_ID").val(cmd_device_ID);
			$("#app_name").val(NomApp);
			$("#app_id").val(deviceID);
			$("#carte_id").val(carteID);
			$('#Visible_app').prop('checked', visible_app).checkboxradio('refresh');
			$("#RAZ_value").val(hours+":"+minutes);
		}
		$("#PopUpEditEquipement").popup( "open",{transition: 'pop'} );
		$("#ID_EtatIO").parents("div")[0].style.display = "none";
	}
			
			function LoadPlugins()
			{		
				var request = $.ajax({
					dataType: "json",
					type: "POST",
					url: 'action_ajax.php',							
					data: {
						mode: 'LoadPlugins',
						property: '',
						lieux:'',
						id:'',
						act:''
					},
					cache: false,
					async: false
				});
				
				request.done(function (data) {
					$("#add_app_name_piece").empty();
					$("#add_plugins_name_piece").append(new Option("Nom de la pièce:",""));
					$('#Liste_plugins').empty();
					$("#Liste_plugins").append(new Option("Selectionner plugins:",""));
					for(var i=0;i<data.length;i++) {					
						$plugins = data[i];
						$("#Liste_plugins").append(new Option($plugins.Nom,$plugins.url));
					}
				});

				request.fail(function (jqXHR, textStatus, errorThrown) 
				{
					ErrorLoading();
				});
				$("#PopUpEditPlugins").popup( "open",{transition: 'pop'} );
			}

			function Load_Lieux()
			{				
				var request = $.ajax({
					dataType: "json",
					type: "POST",
					url: 'action_ajax.php',							
					data: {
						mode: 'lieux',
						property: '',
						lieux:'',
						id:'',
						act:''
					},
					cache: false,
					async: false
				});
				
				request.done(function (data) {
					$("#add_app_name_piece").empty();
					$("#add_app_name_piece").append(new Option("Nom de la pièce:",""));
					for(var i=0;i<data.length;i++) {					
						$lieux = data[i];
						$("#add_app_name_piece").append(new Option($lieux.Nom,$lieux.ID));
						$("#add_plugins_name_piece").append(new Option($lieux.Nom,$lieux.ID));
						$("#delete_app_name_piece").append(new Option($lieux.Nom,$lieux.ID));
						$("#delete_piece_name_piece").append(new Option($lieux.Nom,$lieux.ID));
					}
					$("#delete_app_name_piece").on( "change", function(event, ui) {
						LoadDevice($("#delete_app_name_piece").val());
					});
					
					if (data.length > 0)
					{
						$("#planning_no_data").hide();	
						var oTable = $("#AdminPiece").DataTable();
						oTable.destroy();				
						$("#tbody_AdminPiece").empty();
						for(var i=0;i<data.length;i++) {					
							$event = data[i];								
							event_delete = "delete_piece";						
							$("#tbody_AdminPiece").append("<tr><td style='text-align:center;'>"+$event.ID+"</td><td style='text-align:center;'>"+$event.Nom+"</td><td style='text-align:center;'><img src='pic/pencil.png' ald='pencil' onclick='EditPiece("+$event.ID+","+i+","+$event.Visible+")'></td><td style='text-align:center;'><img src='pic/delete.png' ald='pencil' onclick='delete_confirm(event_delete,"+i+")'></td></tr>");
						}				
						oTable = $("#AdminPiece").DataTable({
							"bJQueryUI": true,
							"bLengthChange": false,
							"bFilter": true,
							"bInfo": false,
							"bSort": false,
							"sPaginationType": "full_numbers",

							"columnDefs": [
							{ "visible": false, "targets": [0] }
							],
							"oLanguage": {
								"SProcessing" :     "Traitement en cours ..." ,
								"Ssearch" :         "Rechercher:" ,
								"sLengthMenu" :     "éléments Display _MENU_" ,
								"SInfo" :           "Affichage de l'élement _START_ à _END_ sur _TOTAL_ éléments" ,
								"SInfoEmpty" :      "Affichage de l'élement 0 à 0 sur 0 éléments" ,
								"SInfoFiltered" :   "(Filtre de _MAX_ 'éléments au total)" ,
								"SInfoPostFix" :    "" ,
								"sLoadingRecords" : "Chargement en cours ..." ,
								"sZeroRecords" :    "Aucun élément à afficher" ,
								"SEmptyTable" :     "Aucune Donnée Disponible Dans Le tableau" ,
								"OPaginate" : {
									"SFirst" :      "Premier" ,
									"SPrevious" :   "Précédent" ,
									"SNext" :       "Suivant" ,
									"Slast" :       "Dernier"
								},
								"OAria" : {
									"SSortAscending" :  ": activer verser juge la colonne par ordre croissant" ,
									"SSortDescending" : ": activer verser juge la colonne par ordre decroissant"
								},
							}
						});
	};					
});

		request.fail(function (jqXHR, textStatus, errorThrown) 
		{
			ErrorLoading();
		});
	}


	function EditPiece(id,num_row,visible)
	{ 	
		//num_row = num_row +1;
		/*piece_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(2)').html();
		app_name_old= $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(3)').html();
		id_app_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(4)').html();
		id_carte_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(5)').html();
		type_app_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(6)').html();
		*/
		/*$('#AdminEquipement tr:nth-child('+num_row+')  td:nth-child(2)').html('<input type="text" id="new-title-'+id+'" value="'+piece_old+'" />');
		$('#AdminEquipement tr:nth-child('+num_row+')  td:nth-child(3)').html('<input type="text" id="new-title-'+id+'" value="'+app_name_old+'" />');
		$('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(4)').html('<input type="text" id="new-title-'+id+'" value="'+id_app_old+'" />');
		$('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(5)').html('<input type="text" id="new-title-'+id+'" value="'+id_carte_old+'" />');
		$('#AdminEquipement tr:nth-child('+num_row+')  td:nth-child(6)').html('<input type="text" id="new-title-'+id+'" value="'+type_app_old+'" />');*/
		
		//$('#edit_'+num_row-1).html("<img id=accept_"+num_row-1+" src='pic/accept.png' alt='accept'>&nbsp; <img id=cross_"+num_row-1+" src='pic/cross.png' alt='cross'>");
		if (id == undefined)
		{
			$("#ID_Piece").val("");
			$("#add_piece_name_piece").val("");
		}
		else
		{
			oTable= $('#AdminPiece').dataTable();	
			ID = oTable.fnGetData(num_row)[0];
			PieceName = oTable.fnGetData(num_row)[1];
			$("#ID_Piece").val(ID);
			$("#add_piece_name_piece").val(PieceName);
		}
		$('#Visible_piece').prop('checked', visible).checkboxradio('refresh');
		$("#PopUpEditPiece").popup( "open",{transition: 'pop'} );
		$("#ID_Piece").parents("div")[0].style.display = "none";
	}

	function DeletePiece(id,num_row)
	{
		if (id != undefined)
		{
			alert(id);
			alert(num_row);
		}
	}

	function Load_User()
	{				
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'action_ajax.php',							
			data: {
				mode: 'user',
				property: '',
				lieux:'',
				id:'',
				act:''
			},
			cache: false,
			async: false
		});
		
		request.done(function (data) {
			$("#add_app_name_piece  option[value!='']").remove();
			$("#delete_app_name_piece  option[value!='']").remove();
			$("#delete_piece_name_piece  option[value!='']").remove();
			for(var i=0;i<data.length;i++) {					
				$lieux = data[i];
				$("#add_app_name_piece").append(new Option($lieux.Nom,$lieux.ID));
				$("#delete_app_name_piece").append(new Option($lieux.Nom,$lieux.ID));
				$("#delete_piece_name_piece").append(new Option($lieux.Nom,$lieux.ID));
			}
			$("#delete_app_name_piece").on( "change", function(event, ui) {
				LoadDevice($("#delete_app_name_piece").val());
			});
			
			if (data.length > 0)
			{
				$("#planning_no_data").hide();

				for(var i=0;i<data.length;i++) {					
					$event = data[i];							
					$("#tbody_AdminUser").append("<tr><td style='text-align:center;'>"+$event.ID+"</td><td style='text-align:center;'>"+$event.Pass+"</td><td style='text-align:center;'>"+$event.Nom+"</td><td style='text-align:center;'>"+$event.Background+"</td><td style='text-align:center;'><img src='pic/pencil.png' ald='pencil'  onclick='EditUser("+$event.ID+","+i+")'></td><td style='text-align:center;'><img src='pic/delete.png' ald='pencil'></td></tr>");
				}
				var oTable = $("#AdminUser").DataTable();
				oTable.destroy();				
				oTable = $("#AdminUser").DataTable({
					"bJQueryUI": true,
					"bLengthChange": false,
					"bFilter": true,
					"bInfo": false,
					"bSort": false,
					"sPaginationType": "full_numbers",

					"columnDefs": [
					{ "visible": false, "targets": [0,1] }
					],
					"oLanguage": {
						"SProcessing" :     "Traitement en cours ..." ,
						"Ssearch" :         "Rechercher:" ,
						"sLengthMenu" :     "éléments Display _MENU_" ,
						"SInfo" :           "Affichage de l'élement _START_ à _END_ sur _TOTAL_ éléments" ,
						"SInfoEmpty" :      "Affichage de l'élement 0 à 0 sur 0 éléments" ,
						"SInfoFiltered" :   "(Filtre de _MAX_ 'éléments au total)" ,
						"SInfoPostFix" :    "" ,
						"sLoadingRecords" : "Chargement en cours ..." ,
						"sZeroRecords" :    "Aucun élément à afficher" ,
						"SEmptyTable" :     "Aucune Donnée Disponible Dans Le tableau" ,
						"OPaginate" : {
							"SFirst" :      "Premier" ,
							"SPrevious" :   "Précédent" ,
							"SNext" :       "Suivant" ,
							"Slast" :       "Dernier"
						},
						"OAria" : {
							"SSortAscending" :  ": activer verser juge la colonne par ordre croissant" ,
							"SSortDescending" : ": activer verser juge la colonne par ordre decroissant"
						},
					}
				});
	};					
});

		request.fail(function (jqXHR, textStatus, errorThrown) 
		{
			ErrorLoading();
		});
	}



	function EditUser(id,num_row)
	{ 	
				//num_row = num_row +1;
				/*piece_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(2)').html();
				app_name_old= $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(3)').html();
				id_app_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(4)').html();
				id_carte_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(5)').html();
				type_app_old = $('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(6)').html();
				*/
				/*$('#AdminEquipement tr:nth-child('+num_row+')  td:nth-child(2)').html('<input type="text" id="new-title-'+id+'" value="'+piece_old+'" />');
				$('#AdminEquipement tr:nth-child('+num_row+')  td:nth-child(3)').html('<input type="text" id="new-title-'+id+'" value="'+app_name_old+'" />');
				$('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(4)').html('<input type="text" id="new-title-'+id+'" value="'+id_app_old+'" />');
				$('#AdminEquipement tr:nth-child('+num_row+') td:nth-child(5)').html('<input type="text" id="new-title-'+id+'" value="'+id_carte_old+'" />');
				$('#AdminEquipement tr:nth-child('+num_row+')  td:nth-child(6)').html('<input type="text" id="new-title-'+id+'" value="'+type_app_old+'" />');*/
				
				//$('#edit_'+num_row-1).html("<img id=accept_"+num_row-1+" src='pic/accept.png' alt='accept'>&nbsp; <img id=cross_"+num_row-1+" src='pic/cross.png' alt='cross'>");
				oTable= $('#AdminUser').dataTable();	
				ID = oTable.fnGetData(num_row)[0];
				Pass = oTable.fnGetData(num_row)[1];
				UserName = oTable.fnGetData(num_row)[2];
				$("#ID_User").val(ID);
				$("#add_User_name_User").val(UserName);
				$("#add_User_pass").val(Pass);
				$("#add_User_pass2").val(Pass);
				$("#PopUpEditUser").popup( "open",{transition: 'pop'} );
				$("#ID_User").parents("div")[0].style.display = "none";
				$("#add_User_background").parents("div")[0].style.display = "none";
			}
			
			function Load_Type_App()
			{				
				var request = $.ajax({
					dataType: "json",
					type: "POST",
					url: 'action_ajax.php',											
					data: {
						mode: 'type_app',
						property: '',
						lieux:'',
						id:'',
						act:''
					},
					cache: false,
					async: true
				});
				
				request.done(function (data) {
					$('#add_app_type_app').empty();
					$("#add_app_type_app").append(new Option("Type d'appareil:",""));
					for(var i=0;i<data.length;i++) {					
						$lieux = data[i]
						$("#add_app_type_app").append(new Option($lieux.type,$lieux.id));
					}
				});
				
				request.fail(function (jqXHR, textStatus, errorThrown) 
				{
					ErrorLoading();
				});
			}
			
			function LoadDevice(lieux)
			{							
				var request = $.ajax({
					dataType: "json",
					type: "POST",
					url: 'action_ajax.php',											
					data: {
						mode: 'LoadDevice',
						lieux : lieux,
						property: '',
						id:'',
						act:''
					},
					cache: false,
					async: false
				});
				
				request.done(function (data) {
					$('#delete_app_name_app').empty();
					$("#delete_app_name_app").append(new Option("Nom de l'appareil:",""));
					for(var i=0;i<data.length;i++) {					
						$nom = data[i]
						$("#delete_app_name_app").append(new Option($nom.Nom,$nom.Nom));
					}
				});
				
				request.fail(function (jqXHR, textStatus, errorThrown) 
				{
					ErrorLoading();
				});
			}
			
			
			function LoadPlanning(DeviceId)
			{				
				$("#tbody_planning").html("");
				var request = $.ajax({
					dataType: "json",
					type: "POST",
					url: 'action_ajax.php',											
					data: {
						mode: 'load_planning',
						id : DeviceId,
						property: '',
						lieux:'',
						act:''
					},
					cache: false,
					async: false
				});
				
				request.done(function (data) {
					if (data.length > 0)
					{
						$("#planning_no_data").hide();
					}
					for(var i=0;i<data.length;i++) {					
						$event = data[i]
						$("#tbody_planning").append("<tr style='color:"+$event.color+"'><td style='text-align:center;'>"+$event.ID+"</td><td style='text-align:center;'>"+$event.ACTIVATE+"</td><td style='text-align:center;'>"+$event.STATUS+"</td><td style='text-align:center;'>"+$event.HOURS+"</td><td style='text-align:center;'>"+$event.DAYS+"</td></tr>");
					}
					
				});

				request.fail(function (jqXHR, textStatus, errorThrown) 
				{
					ErrorLoading();
				});
			}

			function ShowPlanning(DeviceId,Name,Lieux,widget)
			{			
				
				/*var request = $.ajax({
					type: "POST",
					url: 'Planning.php',										
					data: {
						widget : widget
					},
					cache: false,
					async: false
				});*/

		/*request.done(function (data) {*/
			var oTable = $("#table_planning").DataTable();
			oTable.destroy();				
					//$.mobile.navigate( "#div_planning", { transition : "slide"} );
					$.mobile.navigate( "#div_planning", { transition : ""} );
					//$("#content").html("");
					//$("#content").html(data);
					LoadPlanning(DeviceId);
					$("#div_planning[data-role=page]").page();
					$("#div_planning[data-role=page]").page("destroy").page();
					$(".ui-table-columntoggle-btn").hide();
					$("#id_device").val(DeviceId);
					$("#Device_name").html(Name);
					$("#Lieux_name").html(Lieux);	


					$("#Check_active").prop('checked',false).checkboxradio('refresh');
					$("#Action_On").prop('checked',false).checkboxradio('refresh');
					$("#Action_Off").prop('checked',false).checkboxradio('refresh');
					$("#jour_Lundi").prop('checked',false).checkboxradio('refresh');
					$("#jour_Mardi").prop('checked',false).checkboxradio('refresh');
					$("#jour_Mercredi").prop('checked',false).checkboxradio('refresh');
					$("#jour_Jeudi").prop('checked',false).checkboxradio('refresh');
					$("#jour_Vendredi").prop('checked',false).checkboxradio('refresh');
					$("#jour_Samedi").prop('checked',false).checkboxradio('refresh');
					
					$("#id_planning").val("");
					oTable = $("#table_planning").DataTable({
						"bJQueryUI": true,
						"bLengthChange": false,
						"bFilter": true,
						"bInfo": false,
						"bSort": false,
						"sPaginationType": "full_numbers",

						"columnDefs": [
						{ "visible": false, "targets":0 }
						],
						"language": {
							"SProcessing" :     "Traitement en cours ..." ,
							"Ssearch" :         "Rechercher:" ,
							"sLengthMenu" :     "éléments Display _MENU_" ,
							"SInfo" :           "Affichage de l'élement _START_ à _END_ sur _TOTAL_ éléments" ,
							"SInfoEmpty" :      "Affichage de l'élement 0 à 0 sur 0 éléments" ,
							"SInfoFiltered" :   "(Filtre de _MAX_ 'éléments au total)" ,
							"SInfoPostFix" :    "" ,
							"sLoadingRecords" : "Chargement en cours ..." ,
							"sZeroRecords" :    "Aucun élément à afficher" ,
							"SEmptyTable" :     "Aucune Donnée Disponible Dans Le tableau" ,
							"OPaginate" : {
								"SFirst" :      "Premier" ,
								"SPrevious" :   "Précédent" ,
								"SNext" :       "Suivant" ,
								"Slast" :       "Dernier"
							},
							"aria" : {
								"SSortAscending" :  ": activer verser juge la colonne par ordre croissant" ,
								"SSortDescending" : ": activer verser juge la colonne par ordre decroissant"
							},
						}
					});

		$("#div_planning input[type=checkbox]").hide();
		$("#div_planning input[type=radio]").hide();


		var Slider_planning = $(".slider_numeric");
		var tooltip_planning = $('.tooltip');
		tooltip_planning.hide();
		Slider_planning.slider({
			create: function (event, ui) {
				bullet_id = "info_slider_planning";
				val = 10;
				color_bullet(bullet_id, val);
			}
		});

		Slider_planning.on(
			"change", function (event, ui) {
				id = ($(this).attr("id"));
				bullet_id = "info_slider_planning";
				val = $(this).val();
				color_bullet(bullet_id, val);
			});



		$('#DeletePlanning').click( function() {
			oTable = $("#table_planning").dataTable();
			var oSettings = oTable.fnSettings();
			var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) ;
			oTable.fnSetColumnVis( 0, true);
			var anSelected = fnGetSelected( oTable );
			if ( anSelected.length !== 0 ) {
				PlanningId = $(anSelected[0]).find('td').first().html();
				oTable.fnSetColumnVis( 0, false);
			}
			var request = $.ajax({
				type: 'post',  
				dataType: 'json',
				url: 'action_ajax.php',
				data: {
					mode: 'delete_planning',
					id : PlanningId,
					property: '',
					lieux:'',
					act:''
				},
				cache: false,
				async: false
			}); 

			request.done(function (data) {
				var anSelected = fnGetSelected( oTable );
				oTable.fnDeleteRow( anSelected[0] );						
			});

			request.fail(function (jqXHR, textStatus, errorThrown) {
				ErrorLoading();
			});
			oTable.fnPageChange(page);
		});

		$('#ModifyPlanning').click( function() {
			oTable = $("#table_planning").dataTable();
			var anSelected = fnGetSelected( oTable );
			if ( anSelected.length !== 0 ) {
				var oSettings = oTable.fnSettings();
				var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) ;
				$("input:checkbox").prop('checked',false).checkboxradio();
				$("input:checkbox").prop('checked',false).checkboxradio('refresh');
				$("#Action_On").prop('checked',false).checkboxradio('refresh');
				$("#Action_Off").prop('checked',false).checkboxradio('refresh');
				$("#slider_planning").val(0).slider("refresh");
				$("#Hours_planning").val("--:--");
				oTable.fnSetColumnVis( 0, true);

				PlanningId = $(anSelected[0]).find('td').first().html();	
							//alert(PlanningId);							
							oTable.fnSetColumnVis( 0, false);
							var activate = $($(anSelected[0]).find('td')[0]).html();
							var commande = $($(anSelected[0]).find('td')[1]).html();
							var heure = $($(anSelected[0]).find('td')[2]).html();
							var jour = $($(anSelected[0]).find('td')[3]).html();
							
							$("#id_planning").val(PlanningId);
							
							if (activate == "Oui" || activate == "Yes")
							{
								$("#Check_active").prop('checked',true).checkboxradio('refresh');
							}
							else
							{
								$("#Check_active").prop('checked',false).checkboxradio('refresh');
							}
							
							if (commande == "On" )
							{
								$("#Action_On").prop('checked',true).checkboxradio('refresh');
							}
							else if (commande == "Off" )
							{
								$("#Action_Off").prop('checked',true).checkboxradio('refresh');
							}
							else
							{
								$("#slider_planning").val(commande).slider("refresh");
							}
							
							if (jour.indexOf(",") != -1)
							{
								jour = jour.replace(/ /g,"").trim();
								tab_jour = jour.split(",");
								for (var i=0; i<tab_jour.length; i++) {
									jour =  tab_jour[i];
									$("#jour_"+jour).prop('checked',true).checkboxradio('refresh');
								}						
							}
							else
							{
								$("#jour_"+jour).prop('checked',true).checkboxradio('refresh');
							}
							
							$("#Hours_planning").val(heure);
							oTable.fnPageChange( page);
						}
					});



		$("#SavePlanning").click(function()
		{	
			$("#mode").val("Save_planning");
			var request = $.ajax({
				type: 'post',  
				dataType: 'json',
				url: 'action_ajax.php',
				data: $('#form_planning').serialize(),
				cache: false,
				async: false
			}); 

			request.done(function (data) {
				if (data[0].msg != "empty")
				{
					ShowPlanning(DeviceId,Name,Lieux,widget);
					var oSettings = oTable.fnSettings();
					var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) ;
					oTable.fnPageChange( page);
					$("#Hours_planning").val("")
					oTable.fnSetColumnVis( 0, true);
					$('#form_planning')[0].reset();
					$('#table_planning').dataTable().fnClearTable();
					LoadPlanning(DeviceId);
					$(".dataTables_empty").hide();
					$("#id_device").val(DeviceId);
					oTable = $('#table_planning').dataTable( );
					oTable.fnSetColumnVis( 0, false);
				}
				else
				{
					info(msg);
				}
			});		

			request.fail(function (jqXHR, textStatus, errorThrown) {
				$("#id_device").val(DeviceId);
				ErrorLoading();
			});		
			return false;				
		});	



		$("#table_planning_filter").width('100%');
		$("#fieldset_on_off .ui-controlgroup-controls table td:eq(0)").css("width", "1%");		
		$("#fieldset_slider .ui-controlgroup-controls table td:eq(0)").css("width", "1%");				


		if (widget == "plus_moins")
		{
			$("#fieldset_on_off").hide();
			$("#fieldset_slider").show();
		}
		else
		{
			$("#fieldset_slider").hide();
			$("#fieldset_on_off").show();
		}


					//location.hash = "#header";
				/*}
				);
				
				request.fail(function (jqXHR, textStatus, errorThrown) {
					ErrorLoading();
				});*/
	}

	function fnGetSelected( oTableLocal )
	{
		return oTableLocal.$('tr.row_selected');
	}

	function afterSetExtremes(e) {
				//loadData(e.min,e.max);
			}

			function action_domo(device, lieux, val, device_id, pinID,carte_id) {
				/*if(ajax_action){ 
									ajax_action.abort();
								}*/
								ajax_action = $.ajax({
									type: 'POST',
									url: 'action_domo.php',
									data: 'device=' + device + '&lieux=' + lieux + '&val=' + val + '&device_id=' + device_id + '&pinID=' + pinID +'&carte_id=' + carte_id,
									cache: false,
									/*async: false,*/
									success: function (msg) {
						//$("#"+id).slider('enable');	
					}
				});
							}

							function Recup_Temp() {
								var request = $.ajax({
									type: 'POST',
									dataType: "json",
									url: 'action_ajax.php',
									data: {
										act: 'Temp',
										property: '',
										lieux:'',
										id:'',
										mode:''
									}
								});

								request.done(function (data) {
									$.each(data, function (index, item) {
										if (item.lieux == "Exterieur" || item.lieux == "Box")
										{
											/*$(".Temp_" + item.lieux).html(item.temp + "°C").show();
											$(".DateTemp_" + item.lieux).html(item.date).show();
											$(".StatusTemp_" + item.lieux+" img").attr("src",item.changement);
											$(".StatusTemp_" + item.lieux).show();*/
											$("#Temp_" + item.lieux).html(item.temp + "°C").show();
											$("#DateTemp_" + item.lieux).html(item.date).show();
											$("#StatusTemp_" + item.lieux+" img").attr("src",item.changement);
											$("#StatusTemp_" + item.lieux).show();
										}
										else
										{
											widget = item.widget;
											if (widget == "Temperature")
											{
												$("#Temp_" + item.lieux).html(item.temp + "°C").show();
												$("#DateTemp_" + item.lieux).html(item.date).show();
												$("#StatusTemp_" + item.lieux+" img").attr("src",item.changement);
												$("#StatusTemp_" + item.lieux).show();
											}
											else if (widget == "Temperature_objet")
											{
												$("#"+item.DeviceName+"_" + item.lieux).html(item.temp + "°C").show();
												$("#DateTemp_" + item.DeviceName+"_" + item.lieux).html(item.date).show();
								//$("#StatusTemp_" + item.lieux+" img").attr("src",item.changement);
								//$("#StatusTemp_" + item.lieux).show();
							}
						}
					});
	});


		request.fail(function (jqXHR, textStatus, errorThrown) 
		{
			ErrorLoading();
		});
	}


	function Recup_Etat() {
		var request = $.ajax({
			type: 'POST',
			dataType: "json",
			url: 'action_ajax.php',
			data: {
				act: 'Etat',
				property: '',
				lieux:'',
				id:'',
				mode:''
			}
		});

		request.done(function (data) {
			$.each(data, function (index, item) {
				item_Nom = (item.Nom).replace(/\ /g, "_");
				item_Lieux = (item.Lieux).replace(/\ /g,"_");
				if (item.Widget == "plus_moins") {
					$("#" +  item_Nom + "_" + item_Lieux).val(item.Value).slider("refresh");
					$("#info_slider_" +  item_Nom + "_" + item_Lieux).val(item.Value);
					color_bullet("info_slider_" +  item_Nom + "_" + item_Lieux,item.Value);
					if (item.Etat == 1) {
						$("#circle-status-" + item_Nom + "-" + item_Lieux).attr('src', 'pic/circle-green.png');
					} else if (item.Etat == 0) {
						$("#circle-status-" + item_Nom + "-" + item_Lieux).attr('src', 'pic/circle-red.png');
					}
				} 
				else if (item.Widget == "slider") {
					if (item.Etat == 1) {
						$("#" + item_Nom + "_" + item_Lieux).val("on").slider("refresh");
					} else if (item.Etat == 0) {
						$("#" + item_Nom + "_" + item_Lieux).val("off").slider("refresh");
					}
				}
				else if (item.Widget == "Alerte") {
					if (item.Etat == 1) {
						$("#" +item_Nom + "_" + item_Lieux).attr("src","pic/blink_alert.gif");
						$('#son_sonette')[0].play();
					} else if (item.Etat == 0) {
						$("#" +  item_Nom + "_" + item_Lieux).attr("src","pic/alert_down.png");
					}							
				}				
				else if (item.Widget == "BAL")
				{
					if (item.Etat == 1)
					{
						$("#" +item_Nom + "_" + item_Lieux).attr("src","pic/Mailbox_full.png");
					}
					else if (item.Etat == 0)
					{
						$("#" +item_Nom + "_" + item_Lieux).attr("src","pic/Mailbox_empty.png");
					}
				}			
				else if (item.Widget == "Plugins")
				{
					$("#" +item_Nom + "_" + item_Lieux).html(" "+item.Value+" ");
					if (item_Nom =='Conditions')
					{
						$("#div_princ, .conteneur").css("backgroundImage", "url('pic/Background/"+item.Value+".jpg')");
					}
				}

				//$("#LastEvent_" +item_Nom + "_" + item_Lieux).html(item.Date);
			});
	});


		request.fail(function (jqXHR, textStatus, errorThrown) 
		{
			ErrorLoading();
		});
	}

	function Recup_Conditions()
	{		
		var request = $.ajax({
			type: 'POST',
			dataType: "json",
			url: 'action_ajax.php',
			data: {
				mode: 'LoadConditions'
			}
		});

		request.done(function (data) {
			$.each(data, function (index, item) {
				$("#div_princ, .conteneur").css("backgroundImage", "url('pic/Background/"+item.Conditions+".jpg')");
				/*$("#Sunrise").html(item.Sunrise);
				$("#Sunset").html(item.Sunset);*/
			});
		});
	}

	function update_etat() {}

			// <![CDATA[
			$(function () {
				$("#mainbar").hide();
				$("li#showmenu").click(function () {
					$("#mainbar").toggle(500);
				});
				//$("#nav1 a").tooltip_bottom();
			});
			// ]]>



			function show_part(id) {
				$("[state]").hide();
				$("[state='" + id + "']").show();
			}


			// EMPECHER DE SURLIGNER
			function disableselect(e) {
				return false
			}

			function reEnable() {
				return true
			}
			//internet explorer version 4 et plus
			document.onselectstart = new Function("return false")
			//internet explorer version 6
			if (window.sidebar) {
				document.onmousedown = disableselect
				document.onclick = reEnable
			}

			/*$(window).scroll(function () {
							width_init = $("#header").width();
							if ($(window).scrollTop() > 0 ){
								initPos =  $('#header').position().top;
								$("#header").css("position", "absolute");
								$("#header").css("width",width_init);
								$("#header").css("z-index", 2);
								$('#header').css('margin-top',$(window).scrollTop()-initPos + 'px');
							}
							else
							{
								$("#header").css("position", "relative");
								$('#header').css('margin-top','0px');
							}
						});*/

		function goTo(ancre) {
			jQuery('html,body').animate({
				scrollTop: jQuery(ancre).offset().top /*-($("#header").height()*2)*/
			}, speed, 'swing', function () {
				window.location.hash = ancre;
			});
		}

		var speed = 500;
		jQuery('a[href^="#"]').bind('click', function () {
			var id = jQuery(this).attr('href');
			if (id == '#')
				goTo("#jqm")
			else
				goTo(id)
			return (false);
		});

			//LoadMaison();
			Recup_Temp();
			get_time();
			////setInterval(loadData, 1000*60*5);
			//setInterval(Recup_Temp, 1000*60*5);


			// LOAD MAISON

			$('#luminosite_salon').change(function () {
				var slider_value = $(this).val();
				alert(slider_value);
				// do whatever you want with that value...
			});

			function chauf(id, temp,device_ID,pinID,carte_id) {
				lieux = id.split('_');
				lieux = lieux[1];
				action_domo("Chauffage", lieux, temp, device_ID,pinID,carte_id);
			}

			/*function chauf(id,type){
							lieux = id.split('_');
							lieux = lieux[1];
							temp = parseInt($("#"+id).val());
							var device_id = $(this).attr('device_id');
							
							switch (type)
							{ 
								case '+':
									$("#"+id).val(temp+1)
									action_domo("Chauffage",lieux,temp+1,device_id);
									break;
								case '-':
									$("#"+id).val(temp-1)
									action_domo("Chauffage",lieux,temp-1,device_id);
									break;
							}
						}*/


			//	$("div[data-role=page]").page("destroy").page();

			function color_bullet(bullet_id, val) {
				$("#" + bullet_id).html(val);
				/*if (val <= 10) {
					$("#" + bullet_id).css("background-color", "#4ea6cf");
				} else if (val > 10 && val <= 13) {
					$("#" + bullet_id).css("background-color", "#5ac5cf");
				} else if (val > 13 && val <= 16) {
					$("#" + bullet_id).css("background-color", "#7dd7bf");
				} else if (val > 16 && val <= 19) {
					$("#" + bullet_id).css("background-color", "#E5BF7C");
				} else if (val > 19 && val <= 22) {
					$("#" + bullet_id).css("background-color", "#F5C369");
				} else if (val > 22 && val <= 25) {
					$("#" + bullet_id).css("background-color", "#d79168");
				} else if (val > 25 && val <= 28) {
					$("#" + bullet_id).css("background-color", "#cd7159");
				} else if (val > 28) {
					$("#" + bullet_id + ".circle").css("background-color", "#c4463a");
				};*/
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

			//$(function () {
				function init_component() {
					var Slider = $(".slider_numeric");
					var tooltip = $('.tooltip');
					tooltip.hide();
					Slider.slider({
						create: function (event, ui) {
							id = ($(this).attr("id"));
							bullet_id = "info_slider_" + id;
							val = $(this).val();
							color_bullet(bullet_id, val);
						}
					});

					Slider.on(
						"change", function (event, ui) {
							id = ($(this).attr("id"));
							bullet_id = "info_slider_" + id;
							val = $(this).val();
							color_bullet(bullet_id, val);
						});
					Slider.on(
						"slidestop", function (event, ui) {
							id = $(this).attr("id");
						//alert(id);
						temp = $(this).val();
						var device_id = $(this).attr('device_id');
						var pinID = $(this).attr('pinID');
						var carte_id = $(this).attr('carte_id');
						chauf(id, temp,device_id,pinID,carte_id);
					});

					$(".ui-slider-switch").bind("change", function (event, ui) {
						var chosenoption = this.options[this.selectedIndex]
						var slider_id = $(this).attr('id');
						var tb_slider_id = slider_id.split('_');
						var device = tb_slider_id[0];
						var lieux = tb_slider_id[1];
						var device_id = $(this).attr('device_id');
						var carte_id = $(this).attr('carte_id');
						var pinId = $(this).attr('pinId');
						if (chosenoption.value == "off") {
						//$("#Interrupt_salon").slider( 'disable');
						action_domo(device, lieux, "0", device_id, pinId,carte_id);
					} else if (chosenoption.value == "on") {
						//$("#Interrupt_salon").slider( 'disable');
						action_domo(device, lieux, "1", device_id, pinId,carte_id);
					}
				});

					var cols = document.querySelectorAll('#data-maison .content-primary, #data-maison .content-secondary');
					[].forEach.call(cols, function(col) {
						col.addEventListener('dragstart', handleDragStart, false);
						col.addEventListener('dragenter', handleDragEnter, false)
						col.addEventListener('dragover', handleDragOver, false);
						col.addEventListener('dragleave', handleDragLeave, false);
						col.addEventListener('drop', handleDrop, false);
						col.addEventListener('dragend', handleDragEnd, false);
					});
					Recup_Etat();
				};
			//});

		function reinit(id)
		{					
			var request = $.ajax({
				type: 'POST',
				url: 'action_ajax.php',
				data: {
					mode: 'reinit',
					id : id
				}
			});

			request.done(function (data) {
				
			});
		}
		function init_admin()
		{
			$(".admin").css("text-align", "center");
				//$(".admin").css("margin: 0 auto, 50%");
				$('.admin').hide();
				$('#App').show();
				LoadEquipement();
				Load_Lieux();
				Load_Type_App();
			};
			

			function annule()
			{
				//$("select option[value='']").attr('selected', true);			
				$(".popup").popup( "close",{transition: 'pop'} );
				$("input").val("");				
				$("select option").remove();				
				$("#add_app_name_piece").append(new Option("Nom de la pièce:","")).selectmenu().selectmenu("refresh");
				$("#delete_app_name_piece").append(new Option("Nom de la pièce:","")).selectmenu().selectmenu("refresh");
				$("#delete_piece_name_piece").append(new Option("Nom de la pièce:","")).selectmenu().selectmenu("refresh"); 	
				$("#add_plugins_name_piece").append(new Option("Nom de la pièce:","")).selectmenu().selectmenu("refresh"); 	
				$("#delete_app_name_app").append(new Option("Nom de l'appareil:","")).selectmenu().selectmenu("refresh"); 
				$("#Liste_plugins").append(new Option("Selectionner plugins:","")).selectmenu().selectmenu("refresh"); 
				$("#add_app_type_app").append(new Option("Type d'appareil:","")).selectmenu().selectmenu("refresh");
				Load_Lieux();
				Load_Type_App();
			};
			
			function valid(event,cmd_device_id, num_row)
			{
				id = "";
				cmd_device_id = "";
				piece_id="";
				piece_name = "";
				app_name = "";
				app_id = "";
				carte_id = "";
				app_type = "";
				app_type_id = "";
				app_widget = "";
				user_name= "";
				user_pass="";
				user_pass2="";
				user_background="";
				RAZ_value = "";
				visible_app="";
				piece_visible="";
				Request = "";

				if (event == "add_app")
				{				
					id = $("#ID_EtatIO").val();
					cmd_device_ID = $("#cmd_device_ID").val();
					piece_id=$("#add_app_name_piece :selected").val();
					piece_name = $("#add_app_name_piece :selected").text();
					app_name = $("#app_name").val();
					app_id = $("#app_id").val();
					carte_id = $("#carte_id").val();
					app_type = $("#add_app_type_app :selected").text();
					app_type_id = $("#add_app_type_app :selected").val();
					app_widget = $("#add_app_type_app").val();
					RAZ_value = $("#RAZ_value").val();
					visible_app = $('#Visible_app').is(':checked');
					if (RAZ_value == "00:00" || RAZ_value == "")
					{
						RAZ_value = "NULL";
					}
				}
				
				if(event == "delete_app")
				{			
					oTable = $('#AdminEquipement').dataTable();
					id = oTable.fnGetData(num_row)[0];
					cmd_device_ID = oTable.fnGetData(num_row)[1];
					piece_id =  oTable.fnGetData(num_row)[5];
					app_name =  oTable.fnGetData(num_row)[9];
				}
				
				if (event == "add_piece")
				{		
					id = $("#ID_Piece").val();
					piece_name = $("#add_piece_name_piece").val();
					piece_visible = $('#Visible_piece').is(':checked')
				}
				
				if (event == "delete_piece")
				{
					piece_id = $("#delete_piece_name_piece").val();
					piece_name = $("#delete_piece_name_piece option:selected").html();
				}
				
				if (event == "add_user")
				{
					user_name= $("#add_User_name_User").val();
					user_pass=$("#add_User_pass").val();
					user_pass2=$("#add_User_pass2").val();										
					//user_background =pic_64;
					id = $("#ID_User").val();
				}

				if (event == "add_plugins")
				{				
					piece_name = $("#add_plugins_name_piece option:selected").html();
					piece_id = $("#add_plugins_name_piece option:selected").val();
					app_name =$("#Liste_plugins option:selected").html();
					Request = $("#Liste_plugins option:selected").val();
					cmd_device_ID = '';

				}
				var request = $.ajax({
					dataType: "json",
					type: "POST",
					url: 'action_ajax.php',		
					data: {
						mode: 'valide',
						property: event,
						piece_name : piece_name,
						piece_id : piece_id,
						app_name : app_name,
						app_id : app_id,
						Request : Request,
						carte_id: carte_id,
						app_type : app_type,
						app_type_id : app_type_id,
						app_widget : app_widget,
						user_name: user_name,
						user_pass: user_pass,
						user_pass2:user_pass2,
						RAZ_value:RAZ_value,
						visible_app:visible_app,
						piece_visible:piece_visible,
						//	user_background: user_background,
						id : id,
						cmd_device_ID:cmd_device_ID
					}
				});

				request.done(function (data) {	
					for(var i=0;i<data.length;i++) {
						$tb = data[i];
						info($tb.msg);
						if (event == "delete_app" || event == "delete_piece")
						{							
							var anSelected = fnGetSelected( oTable );
							oTable.fnDeleteRow( anSelected[0] );
						}
						if ($tb.clear == "on")
						{
							annule();					
						}
						LoadEquipement();
					}
				});
				
				request.fail(function (jqXHR, textStatus, errorThrown) 
				{
					ErrorLoading();
				});
			};

			function select_background()
			{
				$("#add_User_background").click();
			}
			
			function file_selected()
			{
				$("#file_background").val($('#add_User_background')[0].files[0].name);
				pic_64 ="";
				b64($("#add_User_background"));
			}
			
			function b64(input){
				var FR= new FileReader();
				FR.onload = function(e) {
					pic_64 = e.target.result ;
				};       
				FR.readAsDataURL( input.prop("files")[0] );
				
				return pic_64;
			}			

			function LoadLog()
			{

				var request = $.ajax({
					dataType: "json",
					type: "POST",
					url: 'action_ajax.php',							
					data: {
						mode: 'Log',
						property: '',
						lieux:'',
						id:'',
						act:''
					},
					cache: false,
					async: false
				});
						
				var oTable = $("#AdminLog").DataTable();
				oTable.destroy();	
				$("#tbody_AdminLog").empty()

				request.done(function (data) {					
					if (data.length > 0)
					{									
						for(var i=0;i<data.length;i++) {					
							$event = data[i];							
							$("#tbody_AdminLog").append("<tr><td style='text-align:center;'>"+$event.ID+"</td><td style='text-align:center;'>"+$event.Date+"</td><td style='text-align:center;'>"+$event.Action+"</td><td style='text-align:center;'>"+$event.Message+"</td></tr>");
						}
					};

				});

				
				var oTable = $("#AdminLog").DataTable();
				oTable.destroy();				
				oTable = $("#AdminLog").DataTable({
					"bJQueryUI": true,
					"bLengthChange": false,
					"bFilter": true,
					"bInfo": false,
					"bSort": false,
					"sPaginationType": "full_numbers",

					"columnDefs": [
					{ "visible": false, "targets": [0] }
					],
					"oLanguage": {
						"SProcessing" :     "Traitement en cours ..." ,
						"Ssearch" :         "Rechercher:" ,
						"sLengthMenu" :     "éléments Display _MENU_" ,
						"SInfo" :           "Affichage de l'élement _START_ à _END_ sur _TOTAL_ éléments" ,
						"SInfoEmpty" :      "Affichage de l'élement 0 à 0 sur 0 éléments" ,
						"SInfoFiltered" :   "(Filtre de _MAX_ 'éléments au total)" ,
						"SInfoPostFix" :    "" ,
						"sLoadingRecords" : "Chargement en cours ..." ,
						"sZeroRecords" :    "Aucun élément à afficher" ,
						"SEmptyTable" :     "Aucune Donnée Disponible Dans Le tableau" ,
						"OPaginate" : {
							"SFirst" :      "Premier" ,
							"SPrevious" :   "Précédent" ,
							"SNext" :       "Suivant" ,
							"Slast" :       "Dernier"
						},
						"OAria" : {
							"SSortAscending" :  ": activer verser juge la colonne par ordre croissant" ,
							"SSortDescending" : ": activer verser juge la colonne par ordre decroissant"
						},
					}
});

		request.fail(function (jqXHR, textStatus, errorThrown)
		{
			ErrorLoading();
		});
	}	

	function OpenColorPicker(textid,deviceid,pinid,carteid)
	{
		p = $("#"+textid).offset();		
		$("#colorpicker").farbtastic("#"+textid);
		$( "#colorpicker" ).show( "slow", function() {
		$("#colorpicker").offset({ top: p.top-230, left: p.left-180 });
		$("#colorpicker").attr("parent",textid);
		$("#colorpicker").attr("deviceid",deviceid);
		$("#colorpicker").attr("pinid",pinid);
		$("#colorpicker").attr("carteid",carteid);
		/*$("#colorpicker").farbtastic(function(e){ 
  			$('#'+textid).css("background",e);
		});*/
		});
	}

	// ##############   SCRIPT POUR SCENARIO ############### //

	function LoadDeviceName()
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'action_ajax.php',             
			data: {
				mode: "LoadDeviceName"
			},
			cache: false,
			async: true
		});


		request.done(function (data) {
			switchesAF = [];
			switchesGL = [];
			switchesMR = [];
			switchesSZ = [];
		  	//if (typeof data.result != 'undefined') {
		  		$.each(data, function (index, item) {   
		  			if ("abcdef".indexOf(item.Nom.charAt(0).toLowerCase()) > -1)
		  			{
	  					switchesAF.push([item.Nom+ item.cmd_Nom +'( '+item.Lieux+')',item.cmd_device_id]);
		  			}
		  			else if ("ghijkl".indexOf(item.Nom.charAt(0).toLowerCase()) > -1) {
		  				switchesGL.push([item.Nom+ item.cmd_Nom +'( '+item.Lieux+ ')',item.cmd_device_id]);
		  			}
		  			else if ("mnopqr".indexOf(item.Nom.charAt(0).toLowerCase()) > -1) {
		  				switchesMR.push([item.Nom+ item.cmd_Nom +'( '+item.Lieux+')',item.cmd_device_id]);
		  			}
		  			else if ("stuvwxyz".indexOf(item.Nom.charAt(0).toLowerCase()) > -1) {
		  				switchesSZ.push([item.Nom+ item.cmd_Nom +'( '+item.Lieux+')',item.cmd_device_id]);
		  			}
		        // numbers etc with the a list
		        else {
		        	
		        }

		    });

		  		if (switchesAF.length === 0) {switchesAF.push(["No devices found",0]);}
		  		if (switchesGL.length === 0) {switchesGL.push(["No devices found",0]);}
		  		if (switchesMR.length === 0) {switchesMR.push(["No devices found",0]);}
		  		if (switchesSZ.length === 0) {switchesSZ.push(["No devices found",0]);}

		  		switchesAF.sort();
		  		switchesGL.sort();
		  		switchesMR.sort();
		  		switchesSZ.sort();
		 // };
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
			ErrorLoading();
		});
	}


	function LoadTemperatureName()
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'action_ajax.php',             
			data: {
				mode: "LoadTemperatureName"
			},
			cache: false,
			async: true
		});


		request.done(function (data) {
		  	//if (typeof data.result != 'undefined') {
  				temperatures=[];
				temperatures.push(["No devices found",0]);
				temperatures.sort();
		  		$.each(data, function (index, item) {   
		  			temperatures.push(['Temperature ( '+item.Lieux+ ')',item.ID]);
		  		});

		  		if (temperatures.length === 0) {temperatures.push(["No devices found",0]);}
		  		temperatures.sort();
		 // };
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
			ErrorLoading();
		});
	}

	function LoadScenario()
	{
		$("#scenario svg").remove();
		$(".blocklyToolboxDiv").remove();
		$(".blocklyWidgetDiv").remove();
		$("#BlocklyScenario").height("98%");
		$("#BlocklyScenario").width("98%");
		$("#ScenarioName").val('');
		$("#ScenarioActive").prop('checked',false).checkboxradio('refresh');
		Blockly.inject(document.getElementById('BlocklyScenario'),
		{
			path: 'https://blockly-demo.appspot.com/static/', toolbox: document.getElementById('toolbox')
		}
		);
		LoadDeviceName();
		LoadTemperatureName();
		ListScenario();		
		$("#savedScenarios-button").removeClass();
	}

	function LoadScenarioXML(Scenario_id)
	{
		//LoadScenario();
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'action_ajax.php',								
			data: {
				mode: "ListScenario",
				idScenario: Scenario_id
			},
			cache: false,
			async: true
		});
		request.done(function (data) {	
			var xmltemp = data[0].XML;
			var xml = Blockly.Xml.textToDom(xmltemp.replace(/\~amp~/g, '&'));
			$("#savedScenarios-button> span").remove();
			Blockly.mainWorkspace.clear();		
			//var xml = Blockly.Xml.textToDom(data.XML);
			Blockly.Xml.domToWorkspace( Blockly.mainWorkspace, xml );
			OriginalName =  data[0].Name;
			$("#ScenarioName").val( data[0].Name);
			$("#blockId").html(data[0].ID);

			if (data[0].Status == 1)
			{
				$("#ScenarioActive").prop('checked', true);
			}
		});
	}

	function ListScenario()
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'action_ajax.php',								
			data: {
				mode: "ListScenario"
			},
			cache: false,
			async: true
		});
		request.done(function (data) {		
			$('#savedScenarios').empty();
			$.each(data, function (index, item) {	
				var option = document.createElement("option");
				option.text = item.Name;
				option.value = item.ID;
				option.style.color = 'red';
				if (item.Status == '1') {
					option.style.color = 'black';
				}
				else if (item.Status == '2') {
					option.style.color = 'purple';
				}
				$("#savedScenarios").append(option);
			});
			$("#savedScenarios-button> span").remove();
			OriginalName = "";
			$("#blockId").val("");
		});
	}


	function NewScenario () {
		OriginalName ="";
		$("#ScenarioName").val("");
		$("#blockId").html("");
		Blockly.mainWorkspace.clear();
		$("#savedScenarios").val("");
		LoadScenario();
	}

	function opSymbol(operand) {
		switch(operand)
		{
			case 'EQ':
			operand = ' == ';
			break;
			case 'NEQ':
			operand = ' ~= ';
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

	function parseXml(xml) {

		if ($(xml).children().length > 1) {
			return "err:Please make sure there is only a single block structure";
		}
		var firstBlockType = $(xml).find("block").first().attr("type");
		if (firstBlockType.indexOf("controls_if") == -1) {
			return "err:Please start with a control block";
		}
		var elseIfCount = 0;
		if (firstBlockType == "controls_ifelseif")
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



	function parseXmlBlocks(xml,pairId) {

		var boolString = "";

		function parseLogicCompare(thisBlock){
			var locOperand = opSymbol($($(thisBlock).children("field:first")).text());
			var valueA = $(thisBlock).children("value[name='A']")[0];
			var variableType = $(valueA).children("block:first").attr("type");
			
			if (variableType.indexOf("switchvariables") >= 0) {
				var titleA = $(valueA).find("field")[0];
				var valueB = $(thisBlock).children("value[name='B']")[0];
				var titleB = $(valueB).find("field")[0];
				var compareString = "device["+$(titleA).text()+"]";
				if ($(titleB).attr("name") == "State" || $(titleB).attr("name") == "NUM") {
					compareString += locOperand;
					compareString += '"'+$(titleB).text()+'"'; 
				}
				return compareString;
			}
			else if (variableType == "temperaturevariables") {
				var titleA = $(valueA).find("field")[0];
				var valueB = $(thisBlock).children("value[name='B']")[0];
				var titleB = $(valueB).find("field")[0];
				var compareString = "temperaturedevice["+$(titleA).text()+"]";
				if ($(titleB).attr("name") == "NUM") {
					compareString += locOperand;
					compareString += $(titleB).text(); 
				}
				return compareString;						
			}
			else if (variableType == "humidityvariables") {
				var titleA = $(valueA).find("field")[0];
				var valueB = $(thisBlock).children("value[name='B']")[0];
				var titleB = $(valueB).find("field")[0];
				var compareString = "humiditydevice["+$(titleA).text()+"]";
				if ($(titleB).attr("name") == "NUM") {
					compareString += locOperand;
					compareString += +$(titleB).text(); 
				}
				return compareString;						
			}
			else if (variableType == "barometervariables") {
				var titleA = $(valueA).find("field")[0];
				var valueB = $(thisBlock).children("value[name='B']")[0];
				var titleB = $(valueB).find("field")[0];
				var compareString = "barometerdevice["+$(titleA).text()+"]";
				if ($(titleB).attr("name") == "NUM") {
					compareString += locOperand;
					compareString += $(titleB).text(); 
				}
				return compareString;						
			}
			else if (variableType == "utilityvariables") {
				var titleA = $(valueA).find("field")[0];
				var valueB = $(thisBlock).children("value[name='B']")[0];
				var titleB = $(valueB).find("field")[0];
				var compareString = "utilitydevice["+$(titleA).text()+"]";
				if ($(titleB).attr("name") == "NUM") {
					compareString += locOperand;
					compareString += $(titleB).text(); 
				}
				return compareString;						
			}
			else {
				return "unknown comparevariable "+variableType;
			}
		}
		
		function parseLogicTimeOfDay(thisBlock) {
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
			return compareString;	
		}
		
		function parseLogicWeekday(thisBlock) {
			var locOperand = opSymbol($($(thisBlock).children("field:first")).text());
			var valueA = $(thisBlock).children("field[name='Weekday']")[0];
			var compareString = 'weekday '+locOperand+' '+$(valueA).text(); 
			return compareString;
		}		

		function parseSecurityStatus(thisBlock) {
			var locOperand = opSymbol($($(thisBlock).children("field:first")).text());
			var valueA = $(thisBlock).children("field[name='Status']")[0];
			var compareString = 'securitystatus '+locOperand+' '+$(valueA).text(); 
			return compareString;
		}
		
		function parseValueBlock(thisBlock,locOperand,Sequence) {
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
		}
		
		function parseLogicOperation(thisBlock){
			var locOperand = ' '+$($(thisBlock).children("field:first")).text().toLowerCase()+' ';
			var valueA = $(thisBlock).children("value[name='A']")[0];
			var valueB = $(thisBlock).children("value[name='B']")[0];
			var conditionA = parseValueBlock(valueA,locOperand,"A");
			var conditionB = parseValueBlock(valueB,locOperand,"B");
			var conditionstring = "("+conditionA+" "+locOperand+" "+conditionB+")";
			return conditionstring; 
		}




		var ifBlock = $($(xml).find("value[name='IF"+pairId+"']")[0]).children('block:first');

		if (ifBlock.attr("type")=="logic_compare") {
      		// just the one compare, easy
      		var compareString = parseLogicCompare(ifBlock);
      		boolString += compareString;
      	}

      	else if (ifBlock.attr("type")=="logic_operation") {
      		// nested logic operation, drill down
      		var compareString = parseLogicOperation(ifBlock);
      		boolString += compareString;

      	}
      	else if (ifBlock.attr("type")=="logic_timeofday") {
      		// nested logic operation, drill down
      		var compareString = parseLogicTimeOfDay(ifBlock);
      		boolString += compareString;
      	}
      	else if (ifBlock.attr("type")=="logic_weekday") {
      		// nested logic operation, drill down
      		var compareString = parseLogicWeekday(ifBlock);
      		boolString += compareString;
      	}
      	else if (ifBlock.attr("type")=="security_status") {
      		// nested logic operation, drill down
      		var compareString = parseSecurityStatus(ifBlock);
      		boolString += compareString;
      	}      	
      	
      	var setArray = [];
      	var doBlock = $($(xml).find("statement[name='DO"+pairId+"']")[0]);
      	$(doBlock).find('block').each (function(){ 
      		if ($(this).attr('type') == 'logic_set') {
      			var valueA = $(this).find("value[name='A']")[0];
      			var titleA = $(valueA).find("field")[0];
      			var blockA = $(valueA).children("block:first");
      			var setString = "commandArray["+$(titleA).text()+"]";
      			var valueB = $(this).find("value[name='B']")[0];
      			var titleB = $(valueB).find("field")[0];
      			var blockB = $(valueB).children("block:first");
      			if ((blockB.attr("type")=="logic_states") && ($(titleB).attr("name") == "State")) {
      				setString += '="'+$(titleB).text()+'"'; 
      				setArray.push(setString);
      			}
      			else if ((blockB.attr("type")=="logic_setlevel") && ($(titleB).attr("name") == "NUM")) {
      				setString += '="Set Level '+$(titleB).text()+'"'; 
      				setArray.push(setString);
      			}
      			else
      			{      				
      				setString += '="'+$(titleB).text()+'"'; 
      				setArray.push(setString);
      			}
      		}
      		else if ($(this).attr('type') == 'logic_setdelayed') {
      			var valueA = $(this).find("value[name='A']")[0];
      			var titleA = $(valueA).find("field")[0];
      			var valueC = $(this).find("value[name='C']")[0];
      			var titleC = $(valueC).find("field")[0];
      			var blockA = $(valueA).children("block:first");
	        	/*if ((blockA.attr("type")=="scenevariables")) {
	        		var setString = "commandArray[Scene:"+$(titleA).text()+"]";
	        	}
	        	else { */
	        		var setString = "commandArray["+$(titleA).text()+"]";
	        	//}
	        	var valueB = $(this).find("value[name='B']")[0];
	        	var titleB = $(valueB).find("field")[0];
	        	var blockB = $(valueB).children("block:first");
	        	if ((blockB.attr("type")=="logic_states") && ($(titleB).attr("name") == "State")) {
	        		setString += '="'+$(titleB).text()+' FOR '+ $(titleC).text()+'"'; 
	        		setArray.push(setString);
	        	}        	
	        	else if ((blockB.attr("type")=="logic_setlevel") && ($(titleB).attr("name") == "NUM")) {
	        		setString += '="Set Level '+$(titleB).text()+' FOR '+ $(titleC).text()+'"'; 
	        		setArray.push(setString);
	        	}	        	

	        }
	        else if ($(this).attr('type') == 'logic_setrandom') {
	        	var valueA = $(this).find("value[name='A']")[0];
	        	var titleA = $(valueA).find("field")[0];
	        	var valueB = $(this).find("value[name='B']")[0];
	        	var titleB = $(valueB).find("field")[0];
	        	var valueC = $(this).find("value[name='C']")[0];
	        	var titleC = $(valueC).find("field")[0];
	        	var blockA = $(valueA).children("block:first");
	        	/* if ((blockA.attr("type")=="scenevariables")) {
	        		var setString = "commandArray[Scene:"+$(titleA).text()+"]";
	        	}
	        	else { */
	        		var setString = "commandArray["+$(titleA).text()+"]";
	        	//}
	        	var blockB = $(valueB).children("block:first");
	        	if ((blockB.attr("type")=="logic_states") && ($(titleB).attr("name") == "State")) {	        	
	        		setString += '="'+$(titleB).text()+' RANDOM '+ $(titleC).text()+'"'; 
	        		setArray.push(setString);
	        	}
	        	else if ((blockB.attr("type")=="logic_setlevel") && ($(titleB).attr("name") == "NUM")) {
	        		setString += '="Set Level '+$(titleB).text()+' RANDOM '+ $(titleC).text()+'"'; 
	        		setArray.push(setString);
	        	}        	
	        }
	        else if ($(this).attr('type') == 'send_notification') {
	        	var subjectBlock = $(this).find("value[name='notificationTextSubject']")[0];
	        	var bodyBlock = $(this).find("value[name='notificationTextBody']")[0];
	        	var notificationBlock = $(this).children("field[name='notificationPriority']")[0];
	        	var soundBlock = $(this).children("field[name='notificationSound']")[0];
	        	var sTitleText = $(subjectBlock).find("field[name='TEXT']")[0];
	        	var bTitleText = $(bodyBlock).find("field[name='TEXT']")[0];
	        	var sTT = $(sTitleText).text().replace(/\,/g, ' ');
	        	var bTT = $(bTitleText).text().replace(/\,/g, ' ');
	        	var pTT=$(notificationBlock).text();
	        	var aTT=$(soundBlock).text();
		    	// message separator here cannot be # like in scripts, changed to $..
		    	// also removed commas as we need to separate commandArray later.
		    	var setString = 'commandArray["SendNotification"]="'+sTT+'$'+bTT+'$'+pTT+'$'+aTT+'"';
		    	setArray.push(setString);		      	
		    }
		    else if ($(this).attr('type') == 'send_email') {
		    	var subjectBlock = $(this).children("field[name='TextSubject']")[0];
		    	var bodyBlock = $(this).children("field[name='TextBody']")[0];
		    	var toBlock = $(this).children("field[name='TextTo']")[0];
		    	var sSubject = $(subjectBlock).text().replace(/\,/g, ' ');
		    	var sBody = $(bodyBlock).text().replace(/\,/g, ' ');
		    	var sTo = $(toBlock).text();
		    	// message separator here cannot be # like in scripts, changed to $..
		    	// also removed commas as we need to separate commandArray later.
		    	var setString = 'commandArray["SendEmail"]="'+sSubject+'$'+sBody+'$'+sTo+'"';
		    	setArray.push(setString);		      	
		    }
		    else if ($(this).attr('type') == 'open_url') {
		    	var urlBlock = $(this).find("value[name='urlToOpen']")[0];
		    	var urlText = $(urlBlock).find("fieldfieldfieldfieldfield[name='TEXT']")[0];
		    	var urlNoAmpersands =  $(urlText).text().replace(/\&/g, '~amp~');
		    	urlNoAmpersands =  urlNoAmpersands.replace(/\,/g, '~comma~');
		    	var setString = 'commandArray["OpenURL"]="'+urlNoAmpersands+'"';
		    	setArray.push(setString);		      	
		    }
		    else if ($(this).attr('type') == 'groupvariables') {
		    	var titleA = $(this).find("fieldfieldfieldfield[name='Group']")[0];
		    	var titleB = $(this).find("fieldfieldfield[name='Status']")[0];
		    	var setString = "commandArray[Group:"+$(titleA).text()+"]";
		    	setString += '="'+$(titleB).text()+'"'; 
		    	setArray.push(setString);
		    }
		    else if ($(this).attr('type') == 'scenevariables') {
		    	var titleA = $(this).find("fieldfield[name='Scene']")[0];
		    	var titleB = $(this).find("field[name='Status']")[0];
		    	var setString = "commandArray[Scene:"+$(titleA).text()+"]";
		    	setString += '="'+$(titleB).text()+'"'; 
		    	setArray.push(setString);
		    }		
		    else if ($(this).attr('type') == 'logic_Execute') {
      			var valueA = $(this).find("value[name='A']")[0];
      			var titleA = $(valueA).find("field")[0];
      			var blockA = $(valueA).children("block:first");
      			var setString = "commandArray["+$(titleA).text()+"]";
		    	setArray.push(setString);
		    }		    
		});
		var conditionArray = [];
		conditionArray.push(boolString);
		return [conditionArray,setArray];     
	}

	function SaveScenario() {
		var xml = Blockly.Xml.workspaceToDom( Blockly.mainWorkspace );
		var ScenarioName = $("#ScenarioName").val();
		var id =  $("#blockId").html();
		var UpdateScenario = false;
		if (ScenarioName) { 
			var exists = false; 
			var doSave = false;
			$('#savedEvents  option').each(function(){
				if (this.text == ScenarioName) {
					exists = true;
				}
			});
			if (exists) {
				var answer = confirm("Overwrite "+ScenarioName+"?")
				if (answer){
					doSave = true;
					id = $("#blockId").html();
				}
				else{
					doSave = false;
				}
			}
			else {doSave = true;}
			if (doSave) {
				var blockXml  = Blockly.Xml.domToText( xml );
				var logicArray = parseXml(xml);
				blockXml = blockXml.replace(/\&/g, '~amp~');
				if (typeof(logicArray)=='string') {
					var answerparts = logicArray.split(':');
					if (answerparts[0]=="err") {
						alert(answerparts[1]);
					}
				}
				else if (typeof(logicArray)=='object') {
					var isActive = 0;
					if ($('#ScenarioActive').is(':checked')) {isActive = 1};
					if ((OriginalName == ScenarioName) && (id!="")) {UpdateScenario = true};
					var request = $.ajax({
						dataType: "json",
						type: "POST",
						url: 'action_ajax.php',
						data: {
							mode: "Create_Scenario",
							UpdateScenario: UpdateScenario,
							id: id,
							Scenario_Name: ScenarioName,
							Xml_Scenario: blockXml,
							Xml_Status:isActive,
							logicArray : JSON.stringify(logicArray)
						},
						cache: false,
						async: true
					});
					request.done(function (data) {
						if (typeof data != 'undefined') {
							if (data.status=="OK") {
								generate_noty('information', '<b>Event saved:<br>'+ScenarioName, 2000);
								LoadScenario();
							}
							if (data.status=="ERR") {
								generate_noty('warning', '<b>Error while saving:<br>'+ScenarioName, 2000);
								LoadScenario();
							}
						}
					});
				}
			}
			else {
				alert("Save aborted!");
			}
		}
		else {
			alert('no event name entered!');
		}
	}

	function delete_scenario()
	{
		var id =  $('#savedScenarios').find(":selected").val();
		if ((id!=null)&&(id!="")) {
			$.ajax({
				type: "POST",
				url: 'action_ajax.php',
				async: false, 
				data: {
					mode: "DeleteScenario",
					idScenario : id
				}
				/*success: function(data) {
					if (typeof data != 'undefined') {
						if (data.status=="OK") {
							generate_noty('alert', '<b>Event Deleted<br>'+$("#blockName").val(), 2000);
							Blockly.mainWorkspace.clear();
							$("#blockName").val("");
							LoadScenario();
						}
					}
				}*/
			});
		}
		else {
			alert("Nothing selected!")
		}
	}

	$(function () {
		init_component();
				//LoadEquipement();
				$('#table_planning tbody').on( 'click', 'tr', function () {
					if ( $(this).hasClass('row_selected') ) {
						$(this).removeClass('row_selected');
					}
					else {
						$('#table_planning').DataTable().$('tr.row_selected').removeClass('row_selected');
						$(this).addClass('row_selected');
					}
				});		
				$("#AdminEquipement").on( 'click', 'tr', function () {
					if ( $(this).hasClass('row_selected') ) {
						$(this).removeClass('row_selected');
					}
					else {
						$('#AdminEquipement').DataTable().$('tr.row_selected').removeClass('row_selected');
						$(this).addClass('row_selected');
					}
				});						
			});



	$( window ).on( "orientationchange", function( event ) {
		if($("#planning"))
		{
			oTable = $('#table_planning').dataTable( );
			oTable.fnDraw();
		}
		if($("#AdminEquipement"))
		{
			oTable = $('#AdminEquipement').dataTable( );
			oTable.fnDraw();					
		}
	});

	$( window ).resize(function() {
		/*if (window.innerWidth < 484)
			{
				oTable = $("#AdminEquipement").dataTable();
				oTable.fnSetColumnVis( 3, false);
				oTable.fnSetColumnVis( 4, false);
			}
			else
			{
				oTable = $("#AdminEquipement").dataTable();
				oTable.fnSetColumnVis( 3, true);
				oTable.fnSetColumnVis( 4, true);
			};*/
			$(".dataTable").width('100%');
			resizeMaison();
			ResizeBlockyWindow();
			
		});


	function resizeMaison()
	{
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

	function ResizeBlockyWindow()
	{
		$("#BlocklyScenario").css("width", $(window).width()-250);
		$("#BlocklyScenario").css("height", $(window).height()-20);
	}

	function back()
	{
	    window.history.back();
		setTimeout(
		  function() 
		  {
			LoadMaison();
		  }, 50);
	}

	/* ######################### FUNCTION DRAG AND DROP ################ */



	function handleDragStart(e) {
				  this.style.opacity = '0.4';  // this / e.target is the source node.
				}

				function handleDragOver(e) {
					if (e.preventDefault) {
					e.preventDefault(); // Necessary. Allows us to drop.
				}

				  e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

				  return false;
				}

				function handleDragEnter(e) {

				  // this / e.target is the current hover target.
				  this.classList.add('over');
				}

				function handleDragLeave(e) {
				  this.classList.remove('over');  // this / e.target is previous target element.
				}




				function handleDrop(e) {
				  // this / e.target is current target element.

				  if (e.stopPropagation) {
					e.stopPropagation(); // stops the browser from redirecting.
				}

				  // See the section on the DataTransfer object.

				  return false;
				}

				function handleDragEnd(e) {
					// this/e.target is the source node.

					[].forEach.call(cols, function (col) {
						col.classList.remove('over');
					});
					dragSrcEl.style.opacity = '1';
					this.style.opacity = '1';
				}
				
				var dragSrcEl = null;

				function handleDragStart(e) {
					// Target (this) element is the source node.
					this.style.opacity = '0.4';

					dragSrcEl = this;

					e.dataTransfer.effectAllowed = 'move';
					e.dataTransfer.setData('text/html', this.innerHTML);
				}


				function handleDrop(e) {
					// this/e.target is current target element.
					if (e.stopPropagation) {
						e.stopPropagation(); // Stops some browsers from redirecting.
					}

					// Don't do anything if dropping the same column we're dragging.
					if (dragSrcEl != this) {
						/*pos = $(this).attr("position")+ "-" +$(dragSrcEl).attr("name");
						  alert(pos);
						  pos1 = $(dragSrcEl).attr("position")+ "-" +$(this).attr("name");;
						  alert(pos1);*/						

						old_name = $(this).attr("name");
						new_name = $(dragSrcEl).attr("name");

						$(this).attr("name",new_name);
						$(dragSrcEl).attr("name", old_name);

				  		var request = $.ajax({
							dataType: "json",
							type: "POST",
							url: 'action_ajax.php',							
							data: {
								mode: "update_position",
								pos: $(dragSrcEl).attr("position"),
								pos_lieux: $(dragSrcEl).attr("name"),
								pos1:$(this).attr("position"),
								pos_lieux1:$(this).attr("name")
							},
							cache: false,
							async: true
						});


				  		resizeMaison();
						// Set the source column's HTML to the HTML of the column we dropped on.
						dragSrcEl.innerHTML = this.innerHTML;
						this.innerHTML = e.dataTransfer.getData('text/html');
						dragSrcEl.style.opacity = '1';
						this.style.opacity = '1';
					}

					return false;
				}

			
			/* #################################################*/
			


			function rgb2hex(rgb){
			 rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
			 return (rgb && rgb.length === 4) ? "#" +
			  ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
			  ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
			  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
			}

			</script>