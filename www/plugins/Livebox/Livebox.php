<tr>
	<td class="widget">
		<img id="Livebox_pic" src="plugins/Livebox/pic/Livebox.png">
	</td>
</tr>
<tr>
	<td class="widget" style="color:white">
		<img src="pic/temp_up.png"/>
		<span id="Up_<?php echo $LieuxWithoutSpace ?>"></span> MB
	</td>
</tr>
<tr>
	<td class="widget" style="color:white">
		<img src="pic/temp_down.png"/>
		<span id="Down_<?php echo $LieuxWithoutSpace ?>"></span> MB
	</td>
</tr>
<tr>
	<td class="widget" style="color:white">
		<img src="pic/Synchronize.png"/>
		<span id="Last_Change_<?php echo $LieuxWithoutSpace ?>"></span>
	</td>
</tr>
<tr>
</tr>
<tr>
	<td>
		<div class="btnsmall" data-i18n="Edit" data-theme="a" id="rebootLivebox">Reboot </div>
		<div class="btnsmall" data-i18n="Edit" data-theme="a" id="reload">Rafraichir </div>
	</td>
</tr>

<script>

	function loadData()
	{
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'plugins/Livebox/Livebox_ajax.php',            
			data: {
				act: "loadData"
			},
			cache: false,
			async: true
		});

		request.done(function (data) {
			/*$("#UpStream").html(data[0].UpStream+" Mb");
			$("#DownStream").html(data[0].DownStream+" Mb");
			$("#LastChange").html(data[0].LastChange);*/
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
		});
	}

	$("#rebootLivebox").click(function(){
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'plugins/Livebox/Livebox_ajax.php',             
			data: {
				act: "rebootLivebox"
			},
			cache: false,
			async: true
		});


		request.done(function (data) {
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
		});
	})

	$("#reload").click(function(){
		loadData();
	})

	loadData();

	var auto_refresh = setInterval(function () {loadData()},3600000);

</script>
