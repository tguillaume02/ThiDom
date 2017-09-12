<?php
	require_once dirname(__FILE__) .'/Core/Security.php';

$UserObject = new User();
$UserObject::logout();
?>