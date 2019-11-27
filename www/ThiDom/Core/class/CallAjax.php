<?php
require_once dirname(__FILE__)  .'/../Security.php'; 
require_once dirname(__FILE__) .'/../ListRequire.php';
?>
<script>

function CheckStatusProcessus()
{
	if ($.fn.DataTable.isDataTable("#table-content-sante"))
	{
		$("#tbody-content-sante").html("");
		$("#table-content-sante").dataTable().fnDestroy();
	}
	var santeTable = $("#table-content-sante").dataTable(
	{
		"order": [[ 1, "asc" ]],
		"columnDefs": [
		{"className": "dt-center", "targets": "_all"},
		],
		"searching": false,
		"pagingType": "numbers",
		"iDisplayLength": 13,
		"lengthMenu": [[15, 100, -1], [15, 50, "All"]],
		"bLengthChange": false,		
		"columns": [
		{data: "Process"},
		{data: "Status"},
		/*{data: null, sortable: false, render: function(data, type, full) 
			{
				return '<button class="btn btn-primary btn-md"> Edit <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>   <button class="btn btn-danger btn-md"> Delete <i class="fas fa-trash" aria-hidden="true"></i></button>';
			}
		}*/
		]
	});

	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetStatusProcessus',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data) {		
		santeTable.fnClearTable();
		if (data.length != 0)
		{
			santeTable.fnAddData(data);
			santeTable.fnDraw();
		}
	})

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('LoadSanteProcessus');
	});
}

function checkUpdate()
{
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'CheckUpdate',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});
	
	request.done( function (data) 
	{
		if (data.status == "true")
		{
			$("#tools .count").show();
			$("#update-link .count").show();
		}
		else
		{
			$("#tools .count").hide();
			$("#update-link .count").hide();
		}
	})
}

function callPlugins(plugins, device_id,role,type,value)
{
	value = value+"";
	$.ajax({
		type: 'POST',
		url: 'Core/ajax/'+plugins+'.php',
		data: 'Device_Id=' + device_id +'&Role='+role+'&Type='+type+'&Value='+value,
		cache: false
	})
	.done( function (msg) 
	{
	})
}

function action_domo(device_id,cmd_device_id,role,type,value, mode)
{
	value = value+"";
	$.ajax({
		type: 'POST',
		url: 'Core/ajax/action_domo.php',
		data: 'Device_Id=' + device_id +'&CmdDevice_Id='+cmd_device_id+'&Role='+role+'&Type='+type+'&mode='+mode+'&Value='+value,
		cache: false
	})
	.done( function (msg) 
	{
	})
}

function Recup_Etat()
{
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'Etat',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data)
	{
		$.each(data, function (index, item)
		{
			Widget_icons = undefined;
			Device_Nom = $.trim(item.Cmd_nom).replace(/\ /g, "_");
			item_Nom = $.trim(item.Nom).replace(/\ /g, "_");
			item_Lieux = $.trim(item.Lieux).replace(/\ /g,"_");
			device_id = item.Id;
			Cmd_device_Id = item.Cmd_device_Id;
			isVisible = item.cmd_visible;
			dDate = item.Date;
			Etat = item.Etat;
			Value = item.Value;
			Unite = item.Unite;
			Vcc = item.Vcc;
			StringEtat = Boolean(parseInt(Etat)) ? "on" : "off";
			StringColorEtat = Boolean(parseInt(Etat)) ? "#276941" : "#FF0000";
			Widget_Id = item.WidgetId;
			Widget_Name = item.WidgetName;
			Widget_Type = item.WidgetType;
			//Type_Device_Action = item.Type_Device_Action;
			Configuration  = item.Configuration;
			Request  = item.Request;
			TypeTemplate  = item.TypeTemplate;
			device_format =  item_Nom + "_" + item_Lieux+"_"+device_id;
			cmd_device_format = /* Device_Nom + "_" +*/ item_Lieux+"_"+Cmd_device_Id;


			if (isVisible == 0)
			{					
				$("#Contentcmd_"+Cmd_device_Id).hide();
				//$("#InfoDevice_"+cmd_device_format).hide();
				if ($("#Contentcmd_"+Cmd_device_Id).parent().find(".ContentCmd:visible").length == 0)
				{
					$("#Contentcmd_"+Cmd_device_Id).parent().hide();
				}
			}
			else
		 	{
				$("#Contentcmd_"+Cmd_device_Id).show();
				//$("#InfoDevice_"+cmd_device_format).show();
				$("#Contentcmd_"+Cmd_device_Id).parent().show();
		 	}


			$("#Date_"+cmd_device_format).html(dDate);
			if (Request != "" && Request != null)
			{
				Obj_Configuration = $.parseJSON(Request);
				if (!$.isEmptyObject(Obj_Configuration.icons))
				{
					Widget_icons =  Obj_Configuration.icons;
				}
			}

			/*if (Widget_icons == undefined && TypeTemplate != null)
			{
				Obj_Configuration = $.parseJSON(TypeTemplate);
				Widget_icons =  Obj_Configuration.icons;
			}*/

			if (Unite != null)
			{
				Value = Value + " " + Unite;
			}

			if (Vcc != null)
			{
				$("#Battery_"+cmd_device_format+" tspan").html(Vcc);
			}

			if (Widget_Type == "Text") // Numeric
			{
				$("#InfoDevice_"+cmd_device_format).html(Value);
				$("#InfoDevice_"+cmd_device_format).val(Value);
			}
			else if (Widget_Type == "Slider") // Thermostat
			{				
				$("#InfoDevice_"+cmd_device_format).html(Value);
				$("#Range_"+cmd_device_format).val(this.Value);
				$("#InfoDevice_"+cmd_device_format).attr('value',this.value);
				$("#InfoDevice_"+cmd_device_format).removeClass('circle_on circle_off');
				$("#InfoDevice_"+cmd_device_format).addClass('circle_'+StringEtat);
			}
			else if (Widget_Type == "Color") // RGB
			{
				$("#InfoDevice_"+cmd_device_format).css("background",Value);
				$("#InfoDevice_"+cmd_device_format).val(colourNameToHex(Value));
				$("#InfoDevice_"+cmd_device_format).html("&nbsp;");
			}
			else
			{
				if (Widget_icons != undefined)
				{
					$("#"+cmd_device_format+" img").attr("src","Core/pic/Widget/"+Widget_icons+"_"+StringEtat);
					Widget_icons = undefined	
				}
				else
				{
					$("#"+cmd_device_format+" img").attr("src","Core/pic/Widget/"+Widget_Name+"_"+StringEtat);						
				}


				$("#InfoDevice_"+cmd_device_format).html(Value);
				$("#InfoDevice_"+cmd_device_format).val(Value);
			}
		});
		resizeMaison();
	});

	request.fail(function (jqXHR, textStatus, errorThrown) 
	{
		ErrorLoading('recup_etat');
	});
}

function SetToolTipLog(device_id,balise_id)
{
	$(".popover").popover('destroy').popover();

    if(requestGetLastLog && requestGetLastLog.readyState != 4)
    {
        requestGetLastLog.abort();
    }

	var requestGetLastLog = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetLastLog',
			Property: '',
			Lieux:'',
			Id:device_id,
			Mode:''
		}
	});

	requestGetLastLog.done(function (data) {
		$content = "";
		$.each(data, function (index, item) {
			//$content += '<li style="font-weight:bold;color:grey;border-bottom:1px solid grey;">'+item.Date+'</li><li style="color:black;padding-top: 2%;padding-bottom: 2%;padding-left: 8%;">'+item.Message+'</li>';
			$content += '<li style="font-weight:bold;color:grey;border-bottom:1px solid grey;">'+item.Date+' - '+item.Message+'</li>';
		});
		if ($content)
		{
			//$("#"+balise_id).popover('destroy').popover();
			$("#"+balise_id).popover({placement:'bottom',content:'<ul>'+$content+'</ul>', html:true});
			$("#"+balise_id).popover("show");
			$(".popover").width("90%");
			$(".popover").height("auto");
		}
		else
		{
			$("#"+balise_id).popover('destroy').popover();
		}
	});


	requestGetLastLog.fail(function (jqXHR, textStatus, errorThrown) 
	{
		//ErrorLoading();

			$content = '<li style="font-weight:bold;color:grey;border-bottom:1px solid grey;"><li style="color:black;padding-top: 2%;padding-bottom: 2%;padding-left: 8%;">No Data</li>';
			$("#"+balise_id).popover({placement:'bottom',content:'<ul>'+$content+'</ul>', html:true});
			$("#"+balise_id).popover("show");
			$(".popover").width("90%");
			$(".popover").height("auto");
	});
}

function GetLog()
{
	if ($.fn.DataTable.isDataTable("#table-content-log"))
	{
		$("#tbody-content-log").html("");
		$("#table-content-log").dataTable().fnDestroy();
	}

	var logTable = $("#table-content-log").dataTable(
	{
		"order": [[ 0, "desc" ]],
		"columnDefs": [
			{"className": "dt-center", "targets": "_all"}
		],
		"searching": true,
		"pagingType": "numbers",
		"iDisplayLength": 15,
		"lengthMenu": [[15, 50, -1], [15, 50, "All"]],
		"bLengthChange": false,
		"columns": [
			{data: "Date"},
			{data: "Action"},
			{data: "Message"}
		]
	});

	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'AllLog',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''

		}
	});

	request.done(function(data, textStatus,jqXHR )
	{
		logTable.fnClearTable();

		if (data.length != 0)
		{			
			logTable.fnAddData(data);
			logTable.fnDraw();
		};
	})

	request.fail(function (jqXHR, textStatus, errorThrown)
	{
		ErrorLoading('GetAllLog');
	});
}

function RemoveLog()
{	
	bootbox.confirm({
    	message: "Êtes vous sûr de vouloir supprimer tout les logs?",
	    buttons: {
	        confirm: {
	            label: 'Yes',
	            className: 'btn-success'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },

		callback: function (result){
    		if (result)
    		{
				var request = $.ajax({
					type: 'POST',
					dataType: "json",
					url: 'Core/class/GetAjaxResult.php',							
					data: {
						Act: 'RemoveLog',
						Property: '',
						Lieux:'',
						Id:'',
						Mode:''
					}
				});

				request.done(function (data) {
					$("#table-content-log").DataTable().clear().draw();
					info(data.msg);
				});

				request.fail(function (jqXHR, textStatus, errorThrown) {
					ErrorLoading('DeleteLog');
				});
    		}
        }
	});
}
////////// LOAD PAGE ////////////////


function LoadMaison(mode="")
{
	var request = $.ajax({
		type: "POST",
		url: 'Desktop/Home_device.php?mode='+mode,
		cache: false
	});

	request.done(function (data) {
		$("#content-home").html("");
		$("#content-home").html(data);
		$("[id^='Contentcmd_']").hide();
		$(".ui-content .ui-last-child").css('min-height', '117');
		$(".conteneur_device").css('min-height', '117');
		// permet de definir la hauteur des conteneurs
		$(".ui-content .ui-last-child").height("auto");
		$(".conteneur_device").height("auto");
		//DeviceEvent();
		Recup_Etat();
		resizeMaison();
		LoadEvent();
	});

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('LoadMaison');
	});
}

function listPlugins()
{

	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetModuleType',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});
	
	request.done(function (data) {
		PluginsIntall = [];
		$.map( data, function( val, i )
		{
			PluginsIntall.push(val.ModuleName)	
		});
	})

	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetListOfNewPlugins',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data) {
		$("#list-new-plugins option").siblings("[value!='']").remove();	
		$.each(data, function (index, item)
		{
			item = item.replace('/', '');
			if ($.inArray(item, PluginsIntall) === -1)
			{
				$("#list-new-plugins").append(new Option(item, index));
			}
		})
	})

}

function LoadPlugins()
{
	if ($.fn.DataTable.isDataTable("#table-content-plugins"))
	{
		$("#tbody-content-plugins").html("");
		$("#table-content-plugins").dataTable().fnDestroy();
	}
	var pluginsTable = $("#table-content-plugins").dataTable(
	{
		"order": [[ 1, "asc" ]],
		"columnDefs": [
		{"className": "dt-center", "targets": "_all"},
		{ "visible": false, "targets": [0] },		
		{ "type": "alt-string", "targets": [1] }
		],
		"searching": false,
		"pagingType": "numbers",
		"iDisplayLength": 13,
		"lengthMenu": [[15, 50, -1], [15, 50, "All"]],
		"bLengthChange": false,
		"columns": [
		{data: "Id"},
		{data: "ModuleName"},
		{
			data: null, sortable: false, render: function(data, type, full) 
			{
				return '<button class="btn btn-primary btn-md"> Edit <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>   <button class="btn btn-danger btn-md"> Delete <i class="fas fa-trash" aria-hidden="true"></i></button>';
			}
		}
		]
	});

	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetModuleType',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data) {
		$("#list-plugins option").siblings("[value!='']").remove();		
		pluginsTable.fnClearTable();
		if (data.length != 0)
		{
			pluginsTable.fnAddData(data);
			pluginsTable.fnDraw();
		}
		$.each(data, function (index, item) {
			$("#list-plugins").append(new Option(item.ModuleName, item.Id));
			if(item.bFileExist == false)
			{
				$($("#table-content-plugins tr .btn-primary")[index]).prop( "disabled", true );
				$($("#table-content-plugins tr .btn-primary")[index]).css({"background":"transparent", "color":"transparent", "border-color":"transparent"})
			}
		})
	})

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('LoadPlugins');
	});


	$('#table-content-plugins tbody').unbind('click').on( 'click', '.btn-primary', function ()
	{
		PluginsData = $('#table-content-plugins').DataTable().row( $(this).parent().parent() ).data();		
		EditPlugins(PluginsData);
	}).on( 'click', '.btn-danger', function ()
	{
		PluginsData = $('#table-content-plugins').DataTable().row( $(this).parent().parent() ).data();		
		DeletePlugins(PluginsData, $(this).parent().parent());
	});
}

function SavePlugins(id, name, type, configuration)
{	
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',		
		data: {
			Act: "SavePlugins",
			Id: id,
			Name: name,
			Type: type,
			Configuration: configuration
		}
	});

	request.done(function (data) {		
		info(data.msg);
		LoadPlugins();
		init_component();
	});

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('SavePlugins');
	});
}

function DeletePlugins(data, RowSelected)
{
	bootbox.confirm({
    	message: `Êtes vous sûr de vouloir supprimer: ${data.ModuleName}?`,
	    buttons: {
	        confirm: {
	            label: 'Yes',
	            className: 'btn-success'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },

		callback: function (result){
    		if (result)
    		{
    			data.Act = "DeletePlugins";

				var request = $.ajax({
					type: 'POST',
					dataType: "json",
					url: 'Core/class/GetAjaxResult.php',		
					data: data
				});

				request.done(function (data) {
					$("#table-content-plugins").DataTable().row(RowSelected).remove().draw();
					info(data.msg);
					LoadLieux();
					LoadMaison();
				});

				request.fail(function (jqXHR, textStatus, errorThrown) {
					ErrorLoading('DeletePlugins');
				});
    		}
        }
	});
}

function LoadLieux()
{
	if ($.fn.DataTable.isDataTable("#table-content-room"))
	{
		$("#tbody-content-room").html("");
		$("#table-content-room").dataTable().fnDestroy();
	}
	var roomTable = $("#table-content-room").dataTable(
	{
		"order": [[ 2, "asc" ]],
		"columnDefs": [
		{"className": "dt-center", "targets": "_all"},
		{ "visible": false, "targets": [0,1,] },		
		{ "type": "alt-string", "targets": [3] }
		],
		"searching": false,
		"pagingType": "numbers",
		"iDisplayLength": 13,
		"lengthMenu": [[15, 50, -1], [15, 50, "All"]],
		"bLengthChange": false,
		"columns": [
		{data: "Id"},
		{data: "Img"},
		{data: "Nom"},
		//{data: "Position"},
		{
			data: "Visible",
			render : function(data)
			{
    			return data != '1' ? '<img class="RoomVisible" src="Core/pic/notview.png" alt="noview">' : '<img class="RoomVisible" src="Core/pic/view.png" alt="view">';
			}
		},
		{
			data: null, sortable: false, render: function(data, type, full) 
			{
				return '<button class="btn btn-primary btn-md"> Edit <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>   <button class="btn btn-danger btn-md"> Delete <i class="fas fa-trash" aria-hidden="true"></i></button>';
			}
		}
		]
	});

	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetLieux',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data) {
		$("#list-room option").siblings("[value!='']").remove();		
		roomTable.fnClearTable();
		if (data.length != 0)
		{
			roomTable.fnAddData(data);
			roomTable.fnDraw();
		}
		$.each(data, function (index, item) {
			$("#list-room").append(new Option(item.Nom, item.Id));
		})
	})

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('LoadLieux');
	});


	$('#table-content-room tbody').unbind('click').on( 'click', '.RoomVisible', function ()
	{
		RoomData = $('#table-content-room').DataTable().row( $(this).parent().parent() ).data();
		RoomData.Visible = parseInt(RoomData.Visible)?0:1;	
		$(this).attr("src",RoomData.Visible?"Core/pic/view.png":"Core/pic/notview.png");
		$(this).attr("alt",RoomData.Visible?"view":"notview");
		RoomData = $.param({Id: RoomData.Id, Img: RoomData.Img, Name: RoomData.Nom, Backgd: RoomData.Backgd, Position: RoomData.Position, Visible: RoomData.Visible});
		SaveLieux(RoomData);
	})
	.on( 'click', '.btn-primary', function ()
	{
		RoomData = $('#table-content-room').DataTable().row( $(this).parent().parent() ).data();		
		EditLieux(RoomData);
	})
	.on( 'click', '.btn-danger', function ()
	{
		RoomData = $('#table-content-room').DataTable().row( $(this).parent().parent() ).data();
		DeleteLieux(RoomData, $(this).parent().parent());
	});
}

function SaveUser(data)
{
	data += "&Act=SaveUser";
	
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',		
		data: data
	});

	request.done(function (data) {		
		info(data.msg);
		LoadUser();
	});

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('SaveUser');
	});
}

function LoadUser()
{
	if ($.fn.DataTable.isDataTable("#table-content-user"))
	{
		$("#tbody-content-user").html("");
		$("#table-content-user").dataTable().fnDestroy();
	}
	var userTable = $("#table-content-user").dataTable(
	{
		"order": [[ 1, "asc" ]],
		"columnDefs": [
		{"className": "dt-center", "targets": "_all"},
		{ "visible": false, "targets": [0] }
		],
		"searching": false,
		"pagingType": "numbers",
		"iDisplayLength": 13,
		"lengthMenu": [[15, 100, -1], [15, 50, "All"]],
		"bLengthChange": false,		
		"columns": [
		{data: "Id"},
		{data: "UserName"},
		{data: "LastLog"},
		{data: null, sortable: false, render: function(data, type, full) 
			{
				return '<button class="btn btn-primary btn-md"> Edit <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>   <button class="btn btn-danger btn-md"> Delete <i class="fas fa-trash" aria-hidden="true"></i></button>';
			}
		}
		]
	});

	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetUser',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data) {		
		userTable.fnClearTable();
		if (data.length != 0)
		{
			userTable.fnAddData(data);
			userTable.fnDraw();
		}
	})

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('LoadUser');
	});

	$('#table-content-user tbody').unbind('click').on( 'click', '.btn-primary', function ()
	{
		UserData = $('#table-content-user').DataTable().row( $(this).parent().parent() ).data();
		EditUser(UserData);
	})
	.on( 'click', '.btn-danger', function ()
	{
		UserData = $('#table-content-user').DataTable().row( $(this).parent().parent() ).data();
		DeleteUser(UserData, $(this).parent().parent());
	});
}

function getNewHash()
{
	var request = $.ajax({
		type:'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetNewHash',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function(data)
    	{
		$("#user-hash").val(data.hash);
    	});
}

function LoadModuleType()
{
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetModuleType',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data) {
		$("#list-module-type option").siblings("[value!='']").remove();
		$.each(data, function (index, item) {			
			$('<option/>', {
				text: item.ModuleName,
				value: item.Id,
				data: {					
					moduleType: item.ModuleType
				}
			}).appendTo($("#list-module-type"));
		})
	})

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('LoadModuleType');
	});	
}

/*function LoadTypeWidget()
{
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetTypeWidget',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data)
	{
		$("#list-type option").siblings("[value!='']").remove();
		$("#modal-manage-device #list-type").prop('disabled', true);
		$.each(data, function (index, item) {
			$('<option/>', {
				text: item.Name,
				value: item.Id,
				data: {					
					module_Id: item.ModuleType_Id
				}
			}).appendTo($("#list-type*"));


			$("#modal-manage-device #list-type").prop('disabled', false);
		})


	})

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('LoadTypeWidget');
		$("#modal-manage-device #list-type").prop('disabled', true);
	});	
}*/

function LoadGraph()
{
	$.ajax({
		type: "POST",
		url: 'Desktop/Graph.php',
		cache: true
	})
	.done( function (data)
	{
		$("#graph").html("");
		$("#graph").html(data);
		resizeMaison();
	});
}

function Recup_Planning()
{
	scheduler.clearAll();
	var requestGetPlanning = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetAllPlanning',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	requestGetPlanning.done(function (data) {
		var events = [];
		$.each(data, function (index, item) {	
			var planningId = item.Id;
			var planningCmdDeviceId = item.CmdDevice_Id;
			var planningDeviceId = item.DeviceId;
			var planningDate = item.Date;
			var planningDays = item.Days;
			var planningHours = item.Hours;
			var planningDeviceWidget = item.Widget_Id;
			var planningDeviceWidgetType = item.WidgetType;
			var planningActivate = item.Activate;
			var planningDeviceStatus = item.Status;
			var arrayPlanningDays = planningDays.split(",");
			var arrayPlanningHours = planningHours.split(":");
			var planningRepeat = planningDays.length >= 1 ? true: false;
			var planningName =  item.DeviceName+" - "+item.LieuxName+": "+item.Status;
			var colorText =  planningActivate == 1 ? "#0E64A0": "red";
			for (arrayIndex=0; arrayIndex <arrayPlanningDays.length;arrayIndex++)
			{
				var d = new moment();
				var items= {};
				var month = moment(scheduler.getState().date).month();
				var year = moment(scheduler.getState().date).year();
				if (planningRepeat == true)
				{
					d.set({'year': year, 'month': month});
				}
				else
				{
					tb_days = planningDate.split('-');						
					d.set({'year': tb_days[0], 'month': tb_days[1]-1, 'date': tb_days[2]});
				}

				d.hour(arrayPlanningHours[0]);
				d.minute(arrayPlanningHours[1]);

					//// RECUPERE LE PREMIER JOUR DU MOIS CORRESPONDANT AU JOUR CHOISI DANS LE PLANNING
					///  EX: LE PREMIER MARDI DU MOIS	
					if (planningRepeat)
					{
						d.set('date',1);
						while(d.day().toString() !== (parseInt(arrayPlanningDays[arrayIndex])+1==7?"0":(parseInt(arrayPlanningDays[arrayIndex])+1).toString()))
						{
							d.set('date',d.date()+1);
						} 
					}

					while (d.month() === month) {
						items= {
							"id":  item.Id+d.date()+d.month()+d.hour()+d.minute()+item.Status,
							"planningId":planningId,
							"planningCmdDeviceId": planningCmdDeviceId,
							"planningDeviceId": planningDeviceId,
							"planningRepeat": planningRepeat,
							"planningDate": planningDate,
							"planningDays": planningDays,
							"planningHours": planningHours,
							"planningDeviceWidget": planningDeviceWidget,
							"planningDeviceWidgetType": planningDeviceWidgetType,
							"planningActivate": planningActivate,
							"planningDeviceStatus": planningDeviceStatus,
							"planningName" : item.DeviceName+" - "+item.LieuxName,
							"text":  planningName,
							"start_date": d.format('MM/DD/YYYY HH:mm'),
							"end_date": d.format('MM/DD/YYYY HH:mm'),
							"textColor": colorText
						};					
						events.push(items);
						if (planningRepeat)
						{
							d.set('date',d.date()+7);
						}
						else
						{
							month = month +1;
						}
					};
				}	
			})			
				//console.log(events);
				scheduler.parse(events, "json");
			})
}

function LoadCalendar()
{
	var request = $.ajax({
		type: "POST",
		url: 'Desktop/Calendar.php',
		cache: false
	});	

	request.done(function (data) {
		$("#content-calendar").html("");
		$("#content-calendar").html(data);		
		Recup_Planning();
	});

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('LoadCalendar');
	});


	scheduler.attachEvent("onBeforeViewChange", function (old_mode, old_date, mode, date) {
		if (old_mode != mode || $.datepicker.formatDate('dd-mm-y',date) != $.datepicker.formatDate('dd-mm-y', old_date))
		{
			Recup_Planning();

		}
		return true;
	});
}

function SaveCalendar()
{
	var Data = $("#form-planning").find('input[name!=days][name!=commande], input[name=commande]:not(:hidden), select:not(:hidden)').serialize()+"&Act=AddPlanning";
	var ratings = '';
	$("#form-planning input.SchedulerDays:checked").map(function(i, n) {
		ratings += (i ? ',' : '') + n.value;
	});

	Data += '&days='+ratings;

	var request = $.ajax({
		type:'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: Data
	});

	request.done(function(data){
		if (data.status == "error")
		{
			ErrorLoading(data.msg);
		}
		else
		{
			//$("#modal-planning-data").modal('toggle');
			info(data.msg);
		}
		Recup_Planning();
	})

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('SaveCalendar');
	});
}

function DeleteCalendar()
{
	var Data =  $("#form-planning").find("#planning-planningId").serialize()+"&Act=DeletePlanning";

	var request = $.ajax({
		type:'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: Data
	});

	request.done(function(data){
		if (data.status == "error")
		{
			ErrorLoading(data.msg);
		}
		else
		{
			info(data.msg);
		}
		Recup_Planning();
	})

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('DeleteCalendar');
	});
}

//########SCENARIO ############ //


function LoadDeviceName()
{
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'AllDeviceAndCmd',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data) {
		$.each(data, function (index, item) {
			switchesAF = [];
			switchesGL = [];
			switchesMR = [];
			switchesSZ = [];
			temperatures=[];
			$.each(data, function (index, item) {   
				if (item.Nom == "Temperature")
				{
					temperatures.push([item.Cmd_nom+"_"+item.Lieux,item.Cmd_device_Id]);
				}
				else
				{
					device =  item.Nom==item.Cmd_nom?item.Nom:item.Nom+"_"+item.Cmd_nom

					if ("abcdef".indexOf(item.Nom.charAt(0).toLowerCase()) > -1)
					{
						switchesAF.push([device +'( '+item.Lieux+')',item.Cmd_device_Id]);
					}
					else if ("ghijkl".indexOf(item.Nom.charAt(0).toLowerCase()) > -1) {
						switchesGL.push([device +'( '+item.Lieux+ ')',item.Cmd_device_Id]);
					}
					else if ("mnopqr".indexOf(item.Nom.charAt(0).toLowerCase()) > -1) {
						switchesMR.push([device +'( '+item.Lieux+')',item.Cmd_device_Id]);
					}
					else if ("stuvwxyz".indexOf(item.Nom.charAt(0).toLowerCase()) > -1) {
						switchesSZ.push([device +'( '+item.Lieux+')',item.Cmd_device_Id]);
					}
					else {
					}
				}
			});

			if (switchesAF.length === 0) {switchesAF.push(["No devices found",0]);}
			if (switchesGL.length === 0) {switchesGL.push(["No devices found",0]);}
			if (switchesMR.length === 0) {switchesMR.push(["No devices found",0]);}
			if (switchesSZ.length === 0) {switchesSZ.push(["No devices found",0]);}
			if (temperatures.length === 0) { temperatures.push(["No devices found",0]);}

			switchesAF.sort();
			switchesGL.sort();
			switchesMR.sort();
			switchesSZ.sort();
			temperatures.sort();
		})
		request.fail(function (jqXHR, textStatus, errorThrown) {
			ErrorLoading('LoadDeviceName');
		});
	})
}

function ListScenario()
{
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetListScenario',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data) {
		$.each(data, function (index, item) {	
			$('#scenario-list').empty();
			$.each(data, function (index, item) {	
				var option = document.createElement("option");
				option.text=item.Name;
				option.setAttribute("value", item.Id);
				option.setAttribute("name", item.Name);
				option.setAttribute("XML", item.XML);
				option.setAttribute("status", item.Status);
				option.style.color = 'red';
				if (item.Status == '1') {
					option.style.color = 'black';
				}
				else if (item.Status == '2') {
					option.style.color = 'purple';
				}
				$("#scenario-list").append(option);
			});
		})
	});

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('ListScenario');
	});
}

function SaveScenarioXMLAndScenarioExec(ScenarioId, UpdateScenario, ScenarioName, blockXml, isActive, logicArray)
{
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'SaveScenario',
			Id: ScenarioId,
			UpdateScenario: UpdateScenario,
			Scenario_Name: ScenarioName,
			Xml_Scenario: blockXml,
			Xml_Status:isActive,
			LogicArray : JSON.stringify(logicArray)
		}
	});

	request.done(function (data) {
		info(data.msg);
		ListScenario();
	});

	request.fail(function (jqXHR, textStatus, errorThrown) 
	{
		ErrorLoading("SaveScenarioXMLAndScenarioExec");
	});
}

function DeleteScenario(id)
{	
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'DeleteScenario',
			Property: '',
			Lieux:'',
			Id:id,
			Mode:''
		}
	});

	request.done(function (data) {		
		info(data.msg);
		CleanScenario();
		ListScenario();
	});

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('DeleteScenario');
	});
}

/*function AddPlugins(Device, DeviceConfiguration)
{	
	Device += "&DeviceConfiguration="+DeviceConfiguration
	Device += "&Act=AddPlugins";

	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',		
		data: Device
	});

	request.done(function (data) {
		$.each(data, function (index, item)
		{
			linkCommande = "Core/plugins/Commande.php";
			$("#modal-manage-device #CommandeDevice").load(linkCommande, {device_id: item.Id}, function()
			{
				$("#ModalEquipementGeneral #device-deviceid").val(item.Id);
			})
		});
	});

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('SaveDevice');
	});
}*/


function SaveDevice(Device, DeviceConfiguration = "", CmdDevice = "", CmdDeviceConfiguration ="")
{
	//Data.Act = "SaveDevice";
	//Data = JSON.stringify(Data);
	Device += "&CmdDevice="+CmdDevice;
	Device += "&DeviceConfiguration="+DeviceConfiguration
	Device += "&CmdDeviceConfiguration="+CmdDeviceConfiguration
	Device += "&Act=SaveDevice";

	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',		
		data: Device
	});

	request.done(function (data) {
		if (data.status == "error")
		{
			ErrorLoading(data.msg);
			return false;
		}
		else
		{
			DataDevice = JSON.parse('{"' + decodeURI(Device).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}')

			info(data.msg);
			if (DataDevice.DeviceId == "" && data.deviceId != "")
			{
				console.log("j'ai enregistré et je peux essayer de recharger les commandes pour device "+ data.deviceId+ " et pour les commandes "+data.cmddeviceId);
				datas= { "newDevice": "true", "LieuxId":$("#list-room option:selected").val(), "Type":$("#list-device option:selected").text(), "WidgetId":$("#list-type option:selected").val(), "ModuleName":$("#list-module-type option:selected").text(), "ModuleId":$("#list-module-type option:selected").val(), "DeviceId": data.deviceId, "CmdDeviceId": data.cmddeviceId };
				DeviceData = datas;
				data.refresh = false;
				EditDevice(datas);
				LoadEquipement();
			}

			if(data.refresh == true)
			{		
				//$("#modal-manage-device").modal('hide');
				LoadMaison();
				LoadEquipement();
   				$('#modal-manage-device').modal('hide');
			}
			else
			{
				return false;
			}
		}
	});

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('SaveDevice');
		return false;
	});
}

function DeleteDevice(data, RowSelected)
{
	bootbox.confirm({
    	message: `Êtes vous sûr de vouloir supprimer: ${data.DeviceNom}?`,
	    buttons: {
	        confirm: {
	            label: 'Yes',
	            className: 'btn-success'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },

		callback: function (result){
    		if (result)
    		{
    			data.Act = "DeleteDevice";

				var request = $.ajax({
					type: 'POST',
					dataType: "json",
					url: 'Core/class/GetAjaxResult.php',		
					data: data,
					cache: false
				});

				request.done(function (data) {
					info(data.msg);
					$("#table-content-equipement").DataTable().row(RowSelected).remove();//.draw();
					RowSelected.hide();
					LoadMaison();
				});

				request.fail(function (jqXHR, textStatus, errorThrown) {
					ErrorLoading('DeleteDevice');
				});
    		}
        }
	});
}

function ReorderDevice(data, lieux)
{	
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'ReorderDevice',
			deviceList: data,
			lieux: lieux
		}
	});
}

function SaveLieux(data)
{
	data += "&Act=SaveLieux";
	
	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',		
		data: data
	});

	request.done(function (data) {		
		info(data.msg);
		LoadLieux();
		LoadMaison();
	});

	request.fail(function (jqXHR, textStatus, errorThrown) {
		ErrorLoading('SaveLieux');
	});
}

function DeleteLieux(data, RowSelected)
{
	bootbox.confirm({
    	message: `Êtes vous sûr de vouloir supprimer: ${data.Nom}?`,
	    buttons: {
	        confirm: {
	            label: 'Yes',
	            className: 'btn-success'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },

		callback: function (result){
    		if (result)
    		{
    			data.Act = "DeleteLieux";

				var request = $.ajax({
					type: 'POST',
					dataType: "json",
					url: 'Core/class/GetAjaxResult.php',		
					data: data
				});

				request.done(function (data) {
					$("#table-content-equipement").DataTable().row(RowSelected).remove().draw();
					LoadLieux();
					LoadMaison();
					info(data.msg);
				});

				request.fail(function (jqXHR, textStatus, errorThrown) {
					ErrorLoading('DeleteDevice');
				});
    		}
        }
	});
}

function LoadEquipement()
{
	LoadLieux();

	if (!$.fn.DataTable.isDataTable("#table-content-equipement"))
	{
		var equipementTable = $("#table-content-equipement").dataTable({
			"order": [[ 1, "asc" ]],
			"responsive": true,
			"columnDefs": [
			{"className": "dt-center", "targets": "_all"},
			{"className": "dt-body-right", "targets": [4]},
			{ "visible": false, "targets": [4,5,6,7,8,9,10,11] }
			//{ "width": "5%", "targets": 8 }
			],
			"searching": true,
			"pagingType": "numbers",
			"iDisplayLength": 13,
			"lengthMenu": [[15, 50, -1], [15, 50, "All"]],
			"bLengthChange": false,
			"columns": [
			{data: "DeviceNom"},
			{data: "NamePiece"},
			{
				data: "DeviceVisible",
				render : function(data)
				{				
					return data != '1' ? '<img class="EquipementVisible" src="Core/pic/notview.png" alt="noview">' : '<img class="EquipementVisible" src="Core/pic/view.png" alt="view">';
				}
			},
			{data: null, sortable: false, render: function(data) 
				{
					return '<button class="btn btn-primary btn-md"> Edit <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>   <button class="btn btn-danger btn-md"> Delete <i class="fas fa-trash" aria-hidden="true"></i></button>';
				}
			},
			{data: "DeviceId"},
			{data: "LieuxId"},
			{data: "ModuleId"},
			{data: "ModuleName"},
			{data: "WidgetId"},
			{data: "CarteId"},
			{data: "RAZ"},
			{data: "Configuration"}
			]
		});
	}
	else
	{
		//$("#tbody-content-equipement").html("");
		//$("#table-content-equipement").dataTable().fnDestroy();
		
		equipementTable = $("#table-content-equipement").dataTable();
	}

	var request = $.ajax({
		type: 'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'GetAllEquipement',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:''
		}
	});

	request.done(function (data)
	{ 
		equipementTable.fnClearTable();
		if (data.length != 0)
		{
			equipementTable.fnAddData(data);
			equipementTable.fnDraw();
		} 
	})

	request.fail(function (jqXHR, textStatus, errorThrown)
	{
		ErrorLoading("equipementTable");
	});  

	$('#table-content-equipement tbody').unbind('click').on( 'click', '.EquipementVisible', function ()
	{
		DeviceData = $('#table-content-equipement').DataTable().row( $(this).parent().parent() ).data();
		DeviceData.DeviceVisible = parseInt(DeviceData.DeviceVisible)?0:1;	
		$(this).attr("src",DeviceData.DeviceVisible?"Core/pic/view.png":"Core/pic/notview.png");
		$(this).attr("alt",DeviceData.DeviceVisible?"view":"notview");
		Device = $.param({DeviceId: DeviceData.DeviceId, LieuxId: DeviceData.LieuxId, ModuleId: DeviceData.ModuleId, ModelTypeId: DeviceData.TypeId, DeviceName: DeviceData.DeviceNom, CarteDeviceId: DeviceData.Cmd_Device_DeviceId, CarteId: DeviceData.CarteId, RAZ: DeviceData.RAZ, DeviceVisible: DeviceData.DeviceVisible, CmdDeviceid: DeviceData.Cmd_device_Id })
		SaveDevice(Device, DeviceData.Configuration);
	})
	.on( 'click', '.btn-primary', function ()
	{
		console.log($('#table-content-equipement').DataTable().row( $(this).parent().parent() ).data());
		DeviceData = $('#table-content-equipement').DataTable().row( $(this).parent().parent() ).data();		
		EditDevice(DeviceData);
	})
	.on( 'click', '.btn-danger', function ()
	{
		console.log($('#table-content-equipement').DataTable().row( $(this).parent().parent() ).data());
		DeviceData = $('#table-content-equipement').DataTable().row( $(this).parent().parent() ).data();
		DeleteDevice(DeviceData, $(this).parent().parent());
	});
}

function InstallCommand()
{
	var request = $.ajax({
		type:'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'AddCommande',
			ModuleId: DeviceData.ModuleId,
			DeviceId: DeviceData.DeviceId
		}
	});
	EditDevice(DeviceData);
}

function EditCommandeName(elem)
{	
	var value = $.trim($(elem).text());
	var id = $(elem).attr("id");
	$("#Edit_"+id).remove();
	var new_html = ('<input id="Edit_'+id+'" value="' + value + '" onkeyup="$('+id+').text($(this).val())" onfocusout="UpdateCommandeName(this,'+id+');" size="'+$(elem).html().trim().length+'">');
	$(elem).after(new_html);
	$(elem).hide();
	$("#Edit_"+id).focus();
}

function UpdateCommandeName(elemInput, elemLabel)
{	
	$(elemInput).remove();
	$(elemLabel).show();

	var request = $.ajax({
		type:'POST',
		dataType: "json",
		url: 'Core/class/GetAjaxResult.php',
		data: {
			Act: 'NewCommandeName',
			Property: '',
			Lieux:'',
			Id:'',
			Mode:'',
			cmddeviceId: $(elemLabel).attr("cmdId"),
			NewName: $(elemLabel).html()
		}
	});
}

function DeleteCommandeDevice(Name, cmdId)
{
	bootbox.confirm({
    	message: `Êtes vous sûr de vouloir supprimer: ${Name}?`,
	    buttons: {
	        confirm: {
	            label: 'Yes',
	            className: 'btn-success'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },

		callback: function (result){
    		if (result)
    		{
				var request = $.ajax({
					type: 'POST',
					dataType: "json",
					url: 'Core/class/GetAjaxResult.php',		
					data: {
						Act: "DeleteCmdDevice",
						cmddeviceId: cmdId
					},
					cache: false
				});

				request.done(function (data) {
					info(data.msg);
					$("#CommandeName"+cmdId).parent().parent().remove();
					LoadMaison();
				});

				request.fail(function (jqXHR, textStatus, errorThrown) {
					ErrorLoading('DeleteCommandDevice');
				});
    		}
        }
	});
}

// OLD	
/*	function Recup_Conditions()
	{		
		var request = $.ajax({
			type: 'POST',
			dataType: "json",
			url: 'Core/ajax/action_ajax.php',
			data: {
				mode: 'LoadConditions'
			}
		});

		request.done(function (data) {
			$.each(data, function (index, item) {
				$("#div_princ, .conteneur").css("backgroundImage", "url('pic/Background/"+item.Conditions+".jpg')");
			});
		});
	}
	*/

/*	function reinit(id)
	{					
		var request = $.ajax({
			type: 'POST',
			url: 'Core/ajax/action_ajax.php',
			data: {
				mode: 'reinit',
				id : id
			}
		});

		request.done(function (data) {

		});
	}
	*/

	// ##############   SCRIPT POUR SCENARIO ############### //

/*	function LoadScenarioXML(Scenario_id)
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/ajax/action_ajax.php',								
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
	*/

/*	function ListScenario()
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/ajax/action_ajax.php',								
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
	*/

/*	function delete_scenario()
	{
		var id =  $('#savedScenarios').find(":selected").val();
		if ((id!=null)&&(id!="")) {
			$.ajax({
				type: "POST",
				url: 'Core/ajax/action_ajax.php',
				async: false, 
				data: {
					mode: "DeleteScenario",
					idScenario : id
				}
				//success: function(data) {
				//	if (typeof data != 'undefined') {
				//		if (data.status=="OK") {
				//			generate_noty('alert', '<b>Event Deleted<br>'+$("#blockName").val(), 2000);
				//			Blockly.mainWorkspace.clear();
				//			$("#blockName").val("");
				//			LoadScenario();
				///		}
				//	}
				//}
			});
		}
		else {
			alert("Nothing selected!")
		}
	}
	*/

/*	function LoadEquipement()
	{				
		$("#tbody_AdminEquipement").html("");
		$("#AdminPlugins tbody").html("");
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/class/GetAjaxResult.php',						
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
					Cmd_device_Id = $event.Cmd_device_Id;
					DeviceID = $event.DeviceID;
					Carte_ID = $event.Carte_ID;
					TypeID = $event.TypeID;
					LieuxID = $event.LieuxID;
					Type = $event.Type ;
					Piece = $event.Piece;
					Nom = $event.Nom;
					RAZ = $event.RAZ;
					Visible = $event.Visible;
					if (Type == "Plugins")
					{
						event_delete = "delete_plugins_app";
						event_manage = "manage_plugins_app";
						$("#TrAdminPlugins").append("<td><table class='Equipement_Plugins'><tr><td style='text-align: right'><img src='pic/pencil.png' alt='manageplugins' onclick=\"EditPlugins('"+event_manage+"',"+Cmd_device_Id+",'',"+ID+",'"+Nom+"')\" style='cursor:pointer;margin-right: 74%;margin-top: -24%;'><img src='pic/delete.png' alt='delete' onclick=\"delete_confirm('"+event_delete+"',"+Cmd_device_Id+",'',"+ID+")\"  style='cursor:pointer;margin-top: -23%;margin-right: -10%;'></td></tr><tr><td><img class='icons_plugins' src=plugins/"+Nom+"/pic/"+Nom+"></td></tr><tr><td style='text-align:center;color:white'>"+Nom+"</td></tr></table></td>");
					}
					else
					{
						event_delete = "delete_app";
						w+=1;
						$("#tbody_AdminEquipement").append("<tr><td style='text-align:center;'>"+ID+"</td><td style='text-align:center;'>"+Cmd_device_Id+"</td><td style='text-align:center;'>"+DeviceID+"</td><td style='text-align:center;'>"+Carte_ID+"</td><td style='text-align:center;'>"+TypeID+"</td><td style='text-align:center;'>"+LieuxID+"</td><td style='text-align:center;'>"+RAZ+"</td><td style='text-align:center;'>"+Visible+"</td><td style='text-align:center;'>"+Piece+"</td><td style='text-align:center;'>"+Nom+"</td><td style='text-align:center;'>"+Type+"</td><td style='text-align:center;' id='edit_"+w+"' ><img src='pic/pencil.png' alt='pencil' id='pencil_"+w+"' onclick='EditEquipement("+ID+","+Cmd_device_Id+","+w+")'></td><td><img src='pic/delete.png' alt='pencil'  onclick=\"delete_confirm('"+event_delete+"',"+Cmd_device_Id+","+w+")\"></td></tr>");
					}
				}			
				oTable = $("#AdminEquipement").DataTable({
					"bJQueryUI": true,
					"bLengthChange": false,
					"bFilter": true,
					"bInfo": false,
					"bSort": false,
					"pagingType": "full_numbers",

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

				$(".dataTable").width('100%');
			}

		});

		request.fail(function (jqXHR, textStatus, errorThrown) 
		{
			ErrorLoading('LoadEquipement');
		});

	}
	*/

/*	function EditPlugins(event,Cmd_device_Id,num_row,id,nom)
	{					
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/ajax/action_ajax.php',							
			data: {
				mode: 'LoadConfigPlugins',
				id:id
			},
			cache: false,
			async: false
		});	

		request.done(function (data) {
			$("#ConfigPluginsData").load( "plugins/"+nom+"/Config/"+nom+".php" );

			console.log("plugins/"+nom+"/Config/"+nom+".php");
			console.log(data);


			console.log("Liste d'objet %o", data);
			console.log("length "+data.length)
			if(data.length>0){
				for(var ii = 0; ii <data.length; ii++ ){
					var item = data[ii];
					console.log("OBJET JSON %o", item);
				}
			}

		})

		request.fail(function (jqXHR, textStatus, errorThrown) 
		{
		})


		$("#PopUpConfigPlugins").popup( "open",{transition: 'pop'} );
	}
	*/

/*	function LoadPlugins()
	{		
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/ajax/action_ajax.php',							
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
			ErrorLoading('LoadPlugins');
		});
		$("#PopUpEditPlugins").popup( "open",{transition: 'pop'} );
	}
	*/

/*	function Load_Lieux()
	{				
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/ajax/action_ajax.php',							
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
					$("#tbody_AdminPiece").append("<tr><td style='text-align:center;'>"+$event.ID+"</td><td style='text-align:center;'>"+$event.Nom+"</td><td style='text-align:center;'><img src='pic/pencil.png' alt='pencil' onclick='EditPiece("+$event.ID+","+i+","+$event.Visible+")'></td><td style='text-align:center;'><img src='pic/delete.png' alt='pencil' onclick=\"delete_confirm('"+event_delete+"',"+i+")\"></td></tr>");
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
			$(".dataTable").width('100%');
		});

		request.fail(function (jqXHR, textStatus, errorThrown) 
		{
			ErrorLoading('LoadLieux');
		});
	}
	*/

/*	function Load_User()
	{				
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/ajax/action_ajax.php',							
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
			if (data.length > 0)
			{
				$("#planning_no_data").hide();

				var oTable = $("#AdminUser").DataTable();
				oTable.destroy();			
				$("#tbody_AdminUser").empty();
				for(var i=0;i<data.length;i++) {					
					$event = data[i];							
					$("#tbody_AdminUser").append("<tr><td style='text-align:center;'>"+$event.ID+"</td><td style='text-align:center;'>"+$event.Pass+"</td><td style='text-align:center;'>"+$event.Nom+"</td><td style='text-align:center;'>"+$event.Background+"</td><td style='text-align:center;'><img src='pic/pencil.png' alt='pencil'  onclick='EditUser("+$event.ID+","+i+")'></td><td style='text-align:center;'><img src='pic/delete.png' alt='pencil'></td></tr>");
				}		
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
			$(".dataTable").width('100%');				
		});

		request.fail(function (jqXHR, textStatus, errorThrown) 
		{
			ErrorLoading('LoadUser');
		});
	}

	*/

/*	function Load_Type_App()
	{				
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/ajax/action_ajax.php',											
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
				$("#add_app_type_app").append(new Option($lieux.type,$lieux.Id));
			}
		});

		request.fail(function (jqXHR, textStatus, errorThrown) 
		{
			ErrorLoading('Load_Type_App');
		});
	}
	*/

/*	function LoadDevice(lieux)
	{							
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/ajax/action_ajax.php',											
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
			ErrorLoading('LoadDevice');
		});
	}
	*/

</script>
