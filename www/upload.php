<?php
$output_dir = "pic/Background";
if(isset($_FILES["user_background"]))
{
	$ret = array();
	$fileName = $_FILES["user_background"]["name"];
	move_uploaded_file($_FILES["user_background"]["tmp_name"],$output_dir.$fileName);
	$ret[]= $fileName;

	echo json_encode($ret);
}