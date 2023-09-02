<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$worker = strip_tags(trim($_POST['worker']));
	$course = strip_tags(trim($_POST["course"]));
	$school_year = strip_tags(trim($_POST['annee']));
	$tricks_course = strip_tags(trim($_POST['tricks_course']));
	$tricks_course_successive = strip_tags(trim($_POST['tricks_course_successive']));
	$classe = strip_tags(trim($_POST['classe']));
	$hours_per_week = strip_tags(trim($_POST['hours_per_week']));

	// if($pupil_id == '' || $main_conseil == "" || $school_year == "")
	// {

	// }
	// else
	// {
		$sel_query = "SELECT worker_id, course_id, class_id, COUNT(*) AS count_assignment FROM trics_timetable WHERE worker_id='$worker' AND course_id='$course' AND class_id='$classe'";
		$sel_req = $database_connect->query($sel_query);
		$sel_res = $sel_req->fetchObject();
		if ($sel_res->count_assignment == 0) {
			$insert_query = "INSERT INTO trics_timetable(worker_id, course_id, class_id, hours_per_week, tricks_course, tricks_course_successive, school_year) 
					VALUES(?, ?, ?, ?, ?, ?)";
			$insert_request = $database_connect->prepare($insert_query);
			$insert_request->execute(array($worker, $course, $classe, $tricks_course, $tricks_course_successive,$school_year));
		} else {
			if($hours_per_week == 0) {
				$delete_query = "DELETE FROM trics_timetable WHERE worker_id='$worker' AND class_id='$classe' AND course_id='$course'";
				$delete_request = $database_connect->query($delete_query);
			} else {
				$modify_query = "UPDATE trics_timetable SET tricks_course='$tricks_course', tricks_course_successive='$tricks_course_successive', hours_per_week='$hours_per_week' WHERE (worker_id='$worker' AND class_id='$classe' AND course_id='$course')";
				$modify_request = $database_connect->query($modify_query);
			}
		}

	echo json_encode(1);

?>
