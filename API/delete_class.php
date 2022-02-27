<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$class_id = htmlspecialchars(strip_tags(trim($_POST['class_id'])));

	if($class_id == '')
	{

	}
	else
	{
		$delete_query = "DELETE FROM classes_completed WHERE id_classes='$class_id'";
		$delete_request = $database_connect->query($delete_query);

		// $delete_query_marks_course = "DELETE FROM marks_info WHERE course='$course_id'";
		// $delete_request_marks_course = $database_connect->query($delete_query_marks_course);
	}

	echo json_encode("1");

?>