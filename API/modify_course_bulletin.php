<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$course_id = htmlspecialchars(strip_tags(trim($_POST['course_id'])));
	$considered = htmlspecialchars(strip_tags(trim($_POST["tag"])));

	

	if($course_id == '')
	{

	}
	else
	{
		$modify_query = "UPDATE courses SET considered='$considered' WHERE course_id='$course_id'";
		$modify_request = $database_connect->query($modify_query);
	}

	echo json_encode("1");

?>