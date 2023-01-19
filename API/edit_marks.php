<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$response = array();
	$periode_marks = htmlspecialchars(strip_tags(trim($_POST['periode'])));
	$date_work = date('Y') . "-" . date('m') . "-" . date("d"); 
	$year_school = htmlspecialchars(strip_tags(trim($_POST['school_year'])));
	$main_marks = htmlspecialchars(strip_tags(trim($_POST['main_marks'])));
	$total_marks = htmlspecialchars(strip_tags(trim($_POST['total_marks'])));
	$course_id = htmlspecialchars(strip_tags(trim($_POST['course_id'])));
	$pupil_id = htmlspecialchars(strip_tags(trim($_POST['pupil_id'])));
	$success = '';

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

	if ($success == '1' || $success == '2') {
		$pupil_marks = array();
		$marks_p1 = 0;
		$marks_p2 = 0;
		$marks_p3 = 0;
		$marks_p4 = 0;
		$marks_p5 = 0;
		$marks_p6 = 0;
		$marks_ex1 = 0;
		$marks_ex2 = 0;
		$marks_ex3 = 0;
		$marks_sem1 = 0;
		$marks_sem2 = 0;
		$marks_sem3 = 0;
		$marks_total = 0;
		$all_marks = array();
		$marks = array();

		$query_count_marks = "SELECT pupil, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=?";
		$request_count_marks = $database_connect->prepare($query_count_marks);
		$request_count_marks->execute(array($pupil_id));
		$response_count_marks = $request_count_marks->fetchObject();

		if ($response_count_marks->count_marks_exist != 0) {
			$query_marks = "SELECT * FROM marks_info WHERE pupil=?";
			$request_marks = $database_connect->prepare($query_marks);
			$request_marks->execute(array($pupil_id));
			while($response_marks = $request_marks->fetchObject()) {

				if ($response_marks->school_period == '1') {
					$marks_p1 += $response_marks->main_marks;
				}

				if ($response_marks->school_period == '2') {
					$marks_p2 += $response_marks->main_marks;
				}

				if ($response_marks->school_period == '3') {
					$marks_p3 += $response_marks->main_marks;
				}

				if ($response_marks->school_period == '4') {
					$marks_p4 += $response_marks->main_marks;
				}

				if ($response_marks->school_period == '5') {
					$marks_p5 += $response_marks->main_marks;
				}

				if ($response_marks->school_period == '6') {
					$marks_p6 += $response_marks->main_marks;
				}

				if ($response_marks->school_period == '10') {
					$marks_ex1 += $response_marks->main_marks;
				}

				if ($response_marks->school_period == '11') {
					$marks_ex2 += $response_marks->main_marks;
				}

				if ($response_marks->school_period == '12') {
					$marks_ex3 += $response_marks->main_marks;
				}
				
				array_push($marks, $response_marks);
				array_push($all_marks, $response_marks);
			}
		}

		$pupil_marks['p1'] = $marks_p1;
		$pupil_marks['p2'] = $marks_p2;
		$pupil_marks['p3'] = $marks_p3;
		$pupil_marks['p4'] = $marks_p4;
		$pupil_marks['p5'] = $marks_p5;
		$pupil_marks['p6'] = $marks_p6;
		$pupil_marks['ex1'] = $marks_ex1;
		$pupil_marks['ex2'] = $marks_ex2;
		$pupil_marks['ex3'] = $marks_ex3;
		$pupil_marks['sem1'] = $marks_p1 + $marks_p2 + $marks_ex1;
		$pupil_marks['sem2'] = $marks_p3 + $marks_p4 + $marks_ex2;
		$pupil_marks['sem3'] = $marks_p5 + $marks_p6 + $marks_ex3;
		$pupil_marks['total'] = $marks_sem1 + $marks_sem2 + $marks_sem3;

		$pupil['tmarks'] = $pupil_marks;
		$pupil['marks'] = $all_marks;
	}

	$response['success'] = $success;
	$response['tmarks'] = $pupil_marks;
	$response['marks'] = $all_marks;
	echo json_encode($response);

?>