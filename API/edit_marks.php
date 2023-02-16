<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$response = array();
	$date_work = date('Y') . "-" . date('m') . "-" . date("d");
	$all_marks = array();
	$success = '';

	$marks = $_POST['marks'];

	foreach($marks as $key => $value){

		$periode_marks = $value['period'];
		$year_school = $value['school_year'];
		$main_marks = $value['marks'];
		$total_marks = $value['total_marks'];
		$course_id = $value['course_id'];
		$pupil_id = $value['pupil_id'];

		if($main_marks != '') {
			if($periode_marks == 7 || $periode_marks == 8 || $periode_marks == 9 || $periode_marks == 10 || $periode_marks == 11) {
				$total_marks = $total_marks*2;
			}
	
			$query = "SELECT pupil, course, school_period, school_year, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=? AND course=? AND school_period=? AND school_year=?";
			$request = $database_connect->prepare($query);
			$request->execute(array($pupil_id, $course_id, $periode_marks, $year_school));
			$response_array = $request->fetchObject();
	
			if ($response_array->count_marks_exist == 0) {
				$insert00 = "INSERT INTO marks_info(pupil, course, main_marks, total_marks, school_period, school_year, date_work) 
				VALUES(?, ?, ?, ?, ?, ?, ?)";
				$insert = $database_connect->prepare($insert00);
				$insert->execute(array($pupil_id, $course_id, $main_marks, $total_marks, $periode_marks, $year_school, $date_work));
	
				$success = '1';
			} else {
				$edit00 = "UPDATE marks_info SET  main_marks=? WHERE pupil=? AND course=? AND school_period=? AND school_year=?";
				$edit = $database_connect->prepare($edit00);
				$edit->execute(array($main_marks, $pupil_id, $course_id, $periode_marks, $year_school));
				$success = '2';
			}
		} else {
			$success = '3';
		}
	}

	$response['success'] = $success;
	echo json_encode($response);

?>