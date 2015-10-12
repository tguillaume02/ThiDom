<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
?>


<html> 
	<head>
		<meta  charset="utf-8" HTTP-EQUIV="Pragma" name="viewport" content="width=device-width, initial-scale=1,no-cache">
		<title>Domo'Box</title>
		<?php
			include_once('css/include_css.php');
			include_once('js/include_js.php');
		?>
		<link rel="stylesheet" href="css/login.css"/>
	</head>
	<body>
		<div id="header_logo_login"style="text-align:center">	
			<img src="pic/neon-home.png" alt="logo" style="text-align:center" ><!--Domo'Box-->
		</div>
		<div id="login">
			<form id="login_form" name="login_form"  action="#">
				<table style="text-align:center;margin-right:auto;margin-left:auto;min-width: 21%;">
					<tr>
						<td>
							<input type="text" id="user" name="user" style="background-color:default;color:black"  data-mini="true" placeholder="Identifiant">						
						</td>
					</tr>
					<tr>
					</tr>					
					<tr>
					</tr>
					<tr>
						<td>
							<div style="margin-top:20px">
								<input type="password" id="pass_user" name="pass_user" style="background-color:default;color:black;"  data-mini="true" placeholder="mot de passe">						
							</div>
						</td>
					</tr>
					<tr>
						<td></td>
					</tr>
					<tr>
						<td>
							<div class="btnsmall" id="btn_login" href="javascript:;" data-i18n="Edit" data-theme="a" style="width: 80%;margin-top:10px;font-size:15pt">Connexion </div>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
	<script src="http://code.createjs.com/preloadjs-0.3.0.min.js"></script>
	<script src="js/jquery_mobile/jquery.mobile-events.min.js"></script>

	<script>		
	$(document).ready( function () { 
		$('#btn_login').click(function(){
			checkpwd();
		});
	})
	
		function checkpwd()
		{			
			 var request = $.ajax({
                type: "POST",
                url: "login_ajax.php",
				data: {
					user : $("#user").val(),
					pass_user : $("#pass_user").val()
				},
                cache: false,
                async: true
            });

            request.done(function (data) {
                    var redir = new String(data);
					window.location.href =redir;
            });
		};
	</script>
