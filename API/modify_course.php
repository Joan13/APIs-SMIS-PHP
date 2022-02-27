<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$course_id = htmlspecialchars(strip_tags(trim($_POST['course_id'])));
	$course_name = htmlspecialchars(strip_tags(trim($_POST["course_name"])));
	$total_marks = htmlspecialchars(strip_tags(trim($_POST['total_marks'])));

	

	if($course_id == '' || $course_name == "" || $total_marks == "")
	{

	}
	else
	{
		$total_marks_exam = $total_marks*2;
		$modify_query = "UPDATE courses SET course_name='$course_name', total_marks='$total_marks' WHERE course_id='$course_id'";
		$modify_request = $database_connect->query($modify_query);

		$modify_query2 = "UPDATE marks_info SET total_marks='$total_marks' WHERE course='$course_id' AND (school_period=1 OR school_period=2 OR school_period=3 OR school_period=4)";
		$modify_request2 = $database_connect->query($modify_query2);

		$modify_query3 = "UPDATE marks_info SET total_marks='$total_marks_exam' WHERE course='$course_id' AND (school_period=10 OR school_period=11)";
		$modify_request3 = $database_connect->query($modify_query3);
	}

	echo json_encode("1");

?>