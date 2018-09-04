<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';
?>

<div class="embed-responsive embed-responsive-4by3">

	<!--<img id="Status<?php echo $Cmd_device_Id ?>" alt="video" src="<?php echo Device::byId($Device_id)->get_Configuration("url", "")?>" class="embed-responsive-item video Enlarge Corner-bottom ">-->
	<img id="Status<?php echo $Cmd_device_Id ?>" alt="video" class="embed-responsive-item video Enlarge Corner-bottom ">

</div>

<script>
		var request = $.ajax({
			dataType: "json",
			type: "POST",
			url: 'Core/plugins/Webcam/Desktop/Webcam_ajax.php',            
			data: {
				act: "getSnap",
				Device_id: <?php  echo $Device_id?>
			},
			cache: false,
			async: true
		});

		request.done(function (data) {
			console.log(data);
			$("#Status<?php  echo $Device_id?>").attr('src',data);
			console.log("ok");
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
			console.log("Webcam error : " + textStatus);
		});
</script>
