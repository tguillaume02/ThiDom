<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>		
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#20B2AA">
	<?php	
	include_once dirname(__FILE__) .'/Core/ListRequire.php';
	include_once dirname(__FILE__) .'/css/include_css.php';
	?>
	<link rel="stylesheet" href="css/login.css"/>

	<title>ThiDom</title>
	<link rel="manifest" href="manifest.json">
</head>
<body> 		
	<div style="display: none;width : 100%" id="error-success-information">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<span id="error-success-detail"></span>
	</div>
	<div class="container">
		<div class="content">
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<div class="form-signin"> 
						<div class="login-logo">
							<img src="Core/pic/home.png" alt="Company Logo" class="center-block" id="CompagnyPic">
						</div>
						<div class="form-group input-group ">						
  							<span class="input-group-addon"><i class="fas fa-user fa-fw"></i></span>
							<input id="user" name="user" type="text" class="form-control" placeholder="Login">
						</div>
						<div class="form-group input-group ">
							<span class="input-group-addon"><i class="fas fa-key fa-fw"></i></span>
							<input id="pass_user" name="pass_user" type="password" class="form-control" placeholder="Password">
						</div>
						<div class="form-group text-center">
							<label class="checkbox">
								<input type="checkbox" id="remember" name="remember"> Se souvenir de moi
							</label>
						</div>
						<div class="form-group">
							<button id="btn_login" name="submit" class="btn btn-lg btn-primary btn-block"><i class="fas fa-sign-in-alt"> </i>
								Connexion
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="js/jquery/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/fontawesome/fontawesome.min.js"></script>
	<script type="text/javascript" src="js/fontawesome/fa-solid.min.js"></script>
	<script type="text/javascript" src="js/fontawesome/fa-regular.min.js"></script>
	<script>
		$("#user, #pass_user").keypress(function (e) {
			var key = (e.keyCode ? e.keyCode : e.which);
		 	if(key == 13)  // the enter key code
		 	{
		 		checkpwd();
		 		return false;  
		 	}
		 });   

		$('#btn_login').click(function(){
			checkpwd();
		});

		function ShowLoading()
		{
			if ($('#Loading').length == 0)
			{
				$('body').prepend('<div id="Loading"><div class="waitspin"><i class="fas fa-cog fa-spin"></i></div></div>');
			}
			$('#Loading').show();
		}

		function HideLoading()
		{
			$('#Loading').hide('slow/400/fast', function() {
				
			});
		}

		function ErrorLoading(textStatus)
		{		
			$("#error-success-information").removeClass();
			$("#error-success-information").addClass( "alert alert-danger" );
			$("#error-success-information").show();
			$("#error-success-detail").html(textStatus);
			setTimeout(function(){ $("#error-success-information").hide()}, 5000);
		}

		function checkpwd()
		{
			ShowLoading();
			var request = $.ajax({
				type: "POST",
				dataType: "json",
				url: "Core/ajax/login_ajax.php",
				data: {
					user : $("#user").val(),
					pass_user : $("#pass_user").val(),
					remember : $("#remember").is(":checked")
				},
				cache: false,
				async: true
			});
			request.done(function (data)
			{
				if (data.status == "error") 
				{
					HideLoading();
					ErrorLoading(data.message);
				}
				else
				{
					HideLoading();
					var redir = new String(data.result);
					window.location.href =redir;
				}
			});
			request.fail(function (jqXHR, textStatus, errorThrown) {
				ErrorLoading(" Erreur Login");
			});
		}


	</script>

</body>
</html>