<html manifest="cache.appcache"> 
<head>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" />
	<link rel="stylesheet" href="css/login.css"/>
	<meta  charset="utf-8" HTTP-EQUIV="Pragma" name="viewport" content="width=device-width, initial-scale=1,no-cache">
	<title>My home</title>
</head>
<body>
<div class="section" id="game_code" style="display: block;">
		<table>
			<tbody><tr>
				<td>
					<img alt="" src="pic/login.jpg" width="100" height="100" id="img_key">
				</td>
			</tr>
			<tr>
				<td class="numpad_result">
					<div id="numpad_result">
						
						<img alt="" src="pic/code_button_delete.png" id="button_delete_numpad_result">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="section" id="game_code_numpad" style="display: block;">
						<table>
							<tbody><tr>
								<td><div class="numpad_key">1</div></td>
								<td><div class="numpad_key">2</div></td>
								<td><div class="numpad_key">3</div></td>
							</tr>
							<tr>
								<td><div class="numpad_key">4</div></td>
								<td><div class="numpad_key">5</div></td>
								<td><div class="numpad_key">6</div></td>
							</tr>
							<tr>
								<td><div class="numpad_key">7</div></td>
								<td><div class="numpad_key">8</div></td>
								<td><div class="numpad_key">9</div></td>
							</tr>
							<tr>
								<td><div class="numpad_key">*</div></td>
								<td><div class="numpad_key">0</div></td>
								<td><div class="numpad_key">#</div></td>
							</tr>
						</tbody></table>
					</div>
				<!--	<div class="section" id="game_code_synchro" style="display: none;">
						<img alt="" src="assets/images/mobile/code_button_sync.png" id="button_synchro">
					</div> -->
				</td>
			</tr>
		</tbody></table>
	</div>
	</body>
</html>



<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
	<script src="http://code.createjs.com/preloadjs-0.3.0.min.js"></script>
	<script src="js/jquery_mobile/jquery.mobile-events.min.js"></script>
	<script>
		$(document).ready(function() {
		
						
			setAndCheckNumpadResultLength(''); 
				
			$('.numpad_key').tapend(function() {
				var r = $('#numpad_result').text();
				if( r.length < 5 ) r = r + $(this).text();
				//$('#numpad_result').text(r);
				setAndCheckNumpadResultLength(r);
			}); 
			
			$('#img_key').tapend(function() {
				checkpwd();
			});
		});
	
		var button_delete;
		function setAndCheckNumpadResultLength(r)
		{
			if( !button_delete ) 
				button_delete = $('#button_delete_numpad_result').detach();
				$('#numpad_result').text(r);
			if( r.length > 0 )
			{
				button_delete.appendTo('#numpad_result');
				//button_delete = null;
				initDeleteButton();
			}
		}
			
				
		function initDeleteButton(){
			$('#button_delete_numpad_result').tapend(function(e) {
				// navigator.notification.alert("message", null, "title", "button title");
				$('#numpad_result').text('');
				setAndCheckNumpadResultLength('');
			}).click(function(e) {
				// navigator.notification.alert("message", null, "title", "button title");
				$('#numpad_result').text('');
				setAndCheckNumpadResultLength('');
			});
		} 


		function addLog(txt)
		{
			$("#text-login").val($("#text-login").val()+txt);
		}
		
		function checkpwd()
		{
			$.ajax({
				type: "POST",
				url: 'login_ajax.php',
				data :'log='+$('#numpad_result').text(),
				cache: false, async: true,
				success: function(data){
				   if (data) {
						// data.redirect contains the string URL to redirect to
						var redir = new String(data);
						window.location.href =redir;
					}
					else {
						// data.form contains the HTML for the replacement form
						//$('#popupLogin').popup('open');$('#popupLogin').css('width:300px;margin:0% 42% 0 42%;');
					}
				} 
			});
		}
	
	</script> 
	
