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

			if (type.lastTypeupdate == "UpdateDeviceDetected")
			{
				$(type['device']).each(function()
				{
					Recup_Etat(this.cmd_deviceId);
					if (this.Notification == 1)
					{
						notif = "Notification "+this.deviceNom+" "+this.LieuxNom+","+ this.deviceValue+" - "+(parseInt(this.deviceEtat)?"on":"off");

						if (notif != old_notification )
						{
							var options =
							{
								body: this.deviceValue+" - "+(parseInt(this.deviceEtat)?"on":"off"),
								icon: 'Core/pic/home.png'
							}
							new Notification(this.deviceNom+" "+this.LieuxNom, options);
							//console.log("Notification "+type.deviceNom+" "+type.LieuxNom+","+ type.deviceValue+" - "+(parseInt(type.deviceEtat)?"on":"off"));
							old_notification = "Notification "+this.deviceNom+" "+this.LieuxNom+","+ this.deviceValue+" - "+(parseInt(this.deviceEtat)?"on":"off");
						}
					}
				})
			}
	};
}
else
{
	console.log("Sorry, your browser does not support server-sent events...");
}
</script>