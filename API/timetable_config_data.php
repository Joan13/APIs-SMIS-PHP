<?php

	require_once("../config/dbconnect.functions.php");

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$annee = $_POST['annee'];
	$users = array();

	$query_workers = "SELECT * FROM workers_info WHERE worker_year='$annee'";
	$request_workers = $database_connect->query($query_workers);
	while($response_workers = $request_workers->fetchObject()) {

		$query_count_timetable_config = "SELECT COUNT(*) AS count_timetable_config FROM trics_timetable WHERE worker_id='$response_workers->worker_id'";
		$request_count_timetable_config = $database_connect->query($query_count_timetable_config);
		$response_count_timetable_config = $request_count_timetable_config->fetchObject();

		if($response_count_timetable_config->count_timetable_config != 0) {
			$worker = array();
			$tricks_timetable = array();
			$number_hours = 0;
			$worker['worker'] = $response_workers;

			$query_timetable_config = "SELECT * FROM trics_timetable WHERE worker_id='$response_workers->worker_id'";
			$request_timetable_config = $database_connect->query($query_timetable_config);
			while($response_timetable_config = $request_timetable_config->fetchObject()) {

				$query_count_courses = "SELECT COUNT(*) AS count_courses FROM courses WHERE course_id='$response_timetable_config->course_id'";
				$request_count_courses = $database_connect->query($query_count_courses);
				$response_count_courses = $request_count_courses->fetchObject();

				if($response_count_courses->count_courses != 0) {

					$tricks = array();

					$query_course = "SELECT * FROM courses WHERE course_id='$response_timetable_config->course_id'";
					$request_course = $database_connect->query($query_course);
					$response_course = $request_course->fetchObject();

					$tricks['course'] = $response_course;
					$tricks['trick'] = $response_timetable_config;
					$number_hours = $number_hours + $response_timetable_config->hours_per_week;

					array_push($tricks_timetable, $tricks);
				}
			}

			$worker['tricks'] = $tricks_timetable;
			$worker['number_hours'] = $number_hours;
			array_push($users, $worker);
		}
	}

	$response['workers'] = $users;
	echo json_encode($response);

?>
