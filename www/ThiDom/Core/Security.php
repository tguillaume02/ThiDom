<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__FILE__) .'/ListRequire.php';
$login_session="";
$hash = "";
$userObject = new User();
$host = $_SERVER['HTTP_HOST'];

if (isset($_REQUEST["hash"]))
{
	$hash = $_REQUEST["hash"];
}

if ($host != "localhost" and $host != "127.0.0.1" and $host != "192.168.1.25")
{
	$userObject->isLogged($hash);
	/*$userObject::logout();
	die();*/
}
else
{	
	$userObject->isLogged("local");
}
?>
