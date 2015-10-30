<?php
	if ($_SERVER['HTTP_HOST'] == "localhost" or $_SERVER['HTTP_HOST'] == "192.168.1.25")
	{
		setcookie("WAY", "Agent",time()+3600*24*31);
		include_once('connect.php');	
		include_once('functions.php');			
	}
	else
	{
		$nom_value = "Agent";
		$bonbon = trim($_COOKIE['WAY']);
		if ( ($bonbon == $nom_value /*and  $_SESSION['user']  != "" and $_SESSION['ConnectThiDom']==1*/) and ! isset($mysqli))
		{
			include_once('connect.php');	
			include_once('functions.php');	
		}
		/*elseif ($bonbon == $nom_value or $_SESSION['user']  != "")
		{
			if (mysqli_ping($mysqli)) 
			{
				//printf ("La connexion est Ok !\n");
			} 
			else 
			{
				mysqli_close($mysqli);
				include_once('connect.php');
				include_once('functions.php');		
				//printf ("Erreur : %s\n", mysqli_error($mysqli));
			}	
		}*/
		else
		{
			mysqli_close($mysqli);
			//header("Location:login2.php")  			
			header('Location: login3.php');    			
			exit();
		}
	}

?>
