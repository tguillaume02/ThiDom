<?php
/*
if ($_POST['log'] == "842*7")
{
	setcookie("WAY", "Agent",time()+86400);
	//echo 'http://my-rpi.dyndns-home.com/domo3.php';
	echo 'domo3.php';
}
else
{
	setcookie("WAY", "CIA",time()+86400);
}; 
*/
	include_once('connect.php');	
	$user = $_POST['user'];
	$pass = md5($_POST['pass_user']);
	$res = execute_sql("SELECT USERNAME FROM User WHERE USERNAME ='$user' and USERPASS ='$pass'"); 
	$row = $res->fetch_array(MYSQLI_ASSOC);
	if($row['USERNAME'] != '' and $row['USERNAME'] == $user)
	{
		setcookie("WAY", "Agent",time()+3600*24*31);
		echo 'domo4.php';
	}
	else 
	{
		setcookie("WAY", "CIA",time()+3600*24*31);
		echo 'login3.php';
	}; 
?>