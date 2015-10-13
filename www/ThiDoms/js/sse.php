<script>
	if (typeof (EventSource) !== "undefined") {
		lastdate = ""
		sse = "sse.php"
		var source = new EventSource(sse);
		source.onmessage = function (event) {
			type = event.data;
			if (type == "Temp") {
				Recup_Temp();
			}
			else if (type == "Etat") {
				Recup_Etat();
			}
			/*else if (type == "Conditions") {
				Recup_Conditions();
			}*/
		};
	} else {
		document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
</script>