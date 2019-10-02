<?php 

if(!isset($_SESSION['userId']))
{
	$_SESSION['userId'] = -1;
}

spl_autoload_register(function($class) {
	$class=preg_replace("/[^a-z0-9_ ]/i", "", $class);
	if (file_exists(dirname(__FILE__) .'/class/' . $class . '.class.php'))
	{
		require_once dirname(__FILE__) .'/class/' . $class . '.class.php';		
	}
	else if (file_exists(dirname(__FILE__) .'/plugins/'.$class.'/Core/' . $class . '.class.php'))
	{
		require_once dirname(__FILE__) .'/plugins/'.$class.'/Core/' . $class . '.class.php';
	}
});
require_once dirname(__FILE__) .'/Function/utils.php'; 

?>