<?php

	require_once("../config/dbconnect.functions.php");

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$query_courses = "SELECT * FROM courses WHERE school_year=3";
	$request_courses = $database_connect->query($query_courses);
	while($response_courses = $request_courses->fetchObject()) {
		$exist00 = "SELECT course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year, COUNT(*) AS count_course_exist FROM courses WHERE course_name=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND total_marks=? AND school_year=? ";
		$exist11 = $database_connect->prepare($exist00);
		$exist11->execute(array($response_courses->course_name, $response_courses->cycle_id, $response_courses->class_id, $response_courses->section_id, $response_courses->option_id, $response_courses->total_marks, 4));
		$exist = $exist11->fetchObject();
		$res = $exist->count_course_exist;

		if($res == 0) {
			$query = "INSERT INTO courses(course_name, cycle_id, class_id, section_id, option_id, total_marks, considered, hours_week, school_year)
						VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?) ";
			$request = $database_connect->prepare($query);
			$request->execute(array($response_courses->course_name, $response_courses->cycle_id, $response_courses->class_id, $response_courses->section_id, $response_courses->option_id, $response_courses->total_marks, $response_courses->considered, "", 4));
		}
	}

	echo json_encode("1");

?>