<?php
session_start();
include_once ('../ListRequire.php');

try{
	if (empty($_POST['user']) || empty($_POST['pass_user']))
	{
		throw new Exception('Mot de passe ou nom d\'utilisateur incorrect',400);
	}
	else
	{		
		$userObject = new User();
		$userObject::FormCheckUser('', $_POST['user'], $_POST['pass_user'], $_POST['remember'] );
	}
} 
catch (Exception $e) 
{
	$return = array(
		'status' => 'error',
		'code' => $e->getCode(),
		'message' => $e->getMessage()
		);
	echo json_encode($return,JSON_UNESCAPED_UNICODE);
}
?>
