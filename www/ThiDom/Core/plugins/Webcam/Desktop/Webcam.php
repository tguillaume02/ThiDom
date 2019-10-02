<?php
require_once dirname(__FILE__)  .'/../../../Security.php'; 
require_once dirname(__FILE__) .'/../../../ListRequire.php';
?>

<div class="embed-responsive embed-responsive-4by3">

	<img id="Status<?php echo $Cmd_device_Id ?>" alt="video" src="<?php echo Device::byId($Device_id)->get_Configuration("url", "")?>" class="embed-responsive-item video Enlarge Corner-bottom ">
	<!--<img id="Status<?php echo $Cmd_device_Id ?>" alt="video" class="embed-responsive-item video Enlarge Corner-bottom ">-->

</div>

<script>
	function getSnap($device_id)
	{
		var request = $.ajax({
			type: "POST",
			url: 'Core/plugins/Webcam/Desktop/Webcam_ajax.php',            
			data: {
				act: "getSnap",
				Device_id: $device_id
			}/*,
			cache: false,
			async: true*/
		});

		request.done(function (data) {
			$("#Status<?php  echo $Device_id?>").attr('src',data);
		});

		request.fail(function (jqXHR, textStatus, errorThrown) {
			console.log("Webcam error : " + textStatus);
		});
	} 
	 <?php 
	 	$res = CmdDevice::getCmdId("Refresh Snapshot", $Device_id);
		 if ($res != false)
		 {
			 if ($res->get_RAZ() != null)
			 {
				$refresh = $res->get_RAZ() * 1000;
				echo "setInterval(function(){
				   getSnap($Device_id		   },$refresh);";
			 }
		}
	 ?>
</script>
