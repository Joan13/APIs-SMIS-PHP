<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$course_id = htmlspecialchars(strip_tags(trim($_POST['course_id'])));

	if($course_id == '')
	{

	}
	else
	{
		$delete_query = "DELETE FROM courses WHERE course_id='$course_id'";
		$delete_request = $database_connect->query($delete_query);

		$delete_query_marks_course = "DELETE FROM marks_info WHERE course='$course_id'";
		$delete_request_marks_course = $database_connect->query($delete_query_marks_course);
	}

	echo json_encode("1");

?>