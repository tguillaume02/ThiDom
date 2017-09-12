<?php 
spl_autoload_register(function($class) {
	$class=preg_replace("/[^a-z0-9_ ]/i", "", $class);
	require_once dirname(__FILE__) .'/class/' . $class . '.class.php';
});
require_once dirname(__FILE__) .'/Function/utils.php'; 

?>