<script>
var old_notification = "";
if (typeof (EventSource) !== "undefined")
{
	lastdate = ""
	sse = "sse.php"
	var source = new EventSource(sse);
	source.onmessage = function (event)
	{
		type = JSON.parse(event.data);
		/*if (type == "UpdateTempDetected") {
			Recup_Temp();
		}
		else */
			Recup_Etat();

			if (type.lastTypeupdate == "UpdateDeviceDetected" && type.Notification == 1) {



				notif = "Notification "+type.deviceNom+" "+type.LieuxNom+","+ type.deviceValue+" - "+(parseInt(type.deviceEtat)?"on":"off");

				if (notif != old_notification )
				{
					var options =
					{
						body: type.deviceValue+" - "+(parseInt(type.deviceEtat)?"on":"off"),
						icon: 'Core/pic/home.png'
				  	}
					new Notification(type.deviceNom+" "+type.LieuxNom, options);
					//console.log("Notification "+type.deviceNom+" "+type.LieuxNom+","+ type.deviceValue+" - "+(parseInt(type.deviceEtat)?"on":"off"));
					old_notification = "Notification "+type.deviceNom+" "+type.LieuxNom+","+ type.deviceValue+" - "+(parseInt(type.deviceEtat)?"on":"off");
				}
			}
	};
}
else
{
	console.log("Sorry, your browser does not support server-sent events...");
}
</script>