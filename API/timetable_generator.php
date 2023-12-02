<?php

require_once("../config/dbconnect.functions.php");
include("../config/functions.php");

header("Access-Control-Allow-Origin: *");
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$annee = 4; //$_POST['annee'];
$index = 0; //$_POST['index'];
$response_count = 0;
$response = array();

// Final timetable array
$final_timetable = array();

// Add in this array every time a new course hour row is inserted
$hours_done = array();

// Add in this array class_id every time a class has a new hour inserted
$classes_done = array();

// while(count($response) < 800) {

$query_classess = "SELECT * FROM classes_completed WHERE school_year='$annee'";
$request_classess = $database_connect->query($query_classess);
while ($response_classess = $request_classess->fetchObject()) {

	// if($response_classess->class_id == 1 || $response_classess->class_id == 2) {

	$query_timetable_config = "SELECT * FROM trics_timetable WHERE school_year='$annee'";
	$request_timetable_config = $database_connect->query($query_timetable_config);
	while ($response_timetable_config = $request_timetable_config->fetchObject()) {

		// if($response_timetable_config->worker_id == 29) {

		$query_worker = "SELECT * FROM workers_info WHERE worker_id='$response_timetable_config->worker_id'";
		$request_worker = $database_connect->query($query_worker);
		$response_worker = $request_worker->fetchObject();

		$nbr_hours_used = count(array_keys($hours_done, $response_timetable_config->tricks_id));
		$nbr_classes_used = count(array_keys($classes_done, $response_timetable_config->class_id));
		$timetable_single = array();

		// if($nbr_classes_used < 36 || $response_timetable_config->hours_per_week != $nbr_hours_used) {

		$day = rand(1, 6);
		$hour = rand(1, 6);

		// if ($response_worker->free_day_1 != $day || $response_worker->free_day_2 != $day) {
		// 	$day = rand(1, 6);
		// 	$hour = rand(1, 6);
		// }

		// $query_count_timetable_conge = "SELECT COUNT(*) AS count_timetable FROM timetable WHERE class_id='$response_timetable_config->class_id'";
		// $request_count_timetable_conge = $database_connect->query($query_count_timetable_conge);
		// $response_count_timetable_conge = $request_count_timetable_conge->fetchObject();

		// if($response_count_timetable->count_timetable != 0) {
		// 	$day = rand(1, 6);
		// 	$hour = rand(1, 6);
		// }

		$array_days = array();

	// 	$query_timetable_config_conge = "SELECT * FROM trics_timetable WHERE class_id='$response_timetable_config->class_id'";
	// $request_timetable_config_conge = $database_connect->query($query_timetable_config_conge);
	// while($response_timetable_config_conge = $request_timetable_config_conge->fetchObject()){

	// 	$query_worker_conge = "SELECT * FROM workers_info WHERE worker_id='$response_timetable_config_conge->worker_id'";
	// 	$request_worker_conge = $database_connect->query($query_worker_conge);
	// 	$response_worker_conge = $request_worker_conge->fetchObject();

	// 	if($response_worker_conge->free_day_1 != "") {
	// 		array_push($array_days, $response_worker_conge->free_day_1);
	// 	}
	// }

	// if(!empty($array_days)) {
	// 	$k = array_rand($array_days, 1);
	// 	$day = $array_days[$k];
	// }

		if ($response_worker->free_day_1 != $day) {

			$timetable_single['class_id'] = $response_timetable_config->class_id;
			$timetable_single['course_id'] = $response_timetable_config->course_id;
			$timetable_single['worker_id'] = $response_worker->worker_id;
			$timetable_single['day_course'] = $day;
			$timetable_single['hour_course'] = $hour;
			$timetable_single['school_year'] = $response_timetable_config->school_year;

			$query_count_course_timetable = "SELECT COUNT(*) AS count_timetablee FROM timetable WHERE course_id='$response_timetable_config->course_id' AND class_id='$response_timetable_config->class_id'";
			$request_count_course_timetable = $database_connect->query($query_count_course_timetable);
			$response_count_course_timetable = $request_count_course_timetable->fetchObject();

			if ($response_count_course_timetable->count_timetablee < $response_timetable_config->hours_per_week) {

				$query_count_timetable = "SELECT COUNT(*) AS count_timetable FROM timetable WHERE class_id='$response_timetable_config->class_id' AND day_course='$day' AND hour_course='$hour'";
				$request_count_timetable = $database_connect->query($query_count_timetable);
				$response_count_timetable = $request_count_timetable->fetchObject();

				if ($response_count_timetable->count_timetable == 0) {

					$query_count_worker_timetable = "SELECT COUNT(*) AS count_timetablea FROM timetable WHERE worker_id='$response_timetable_config->worker_id' AND day_course='$day' AND hour_course='$hour'";
					$request_count_worker_timetable = $database_connect->query($query_count_worker_timetable);
					$response_count_worker_timetable = $request_count_worker_timetable->fetchObject();

					if ($response_count_worker_timetable->count_timetablea == 0) {
						$query_insert_timetable = "INSERT INTO timetable(worker_id, class_id, course_id, day_course, hour_course, school_year)
														VALUES (?, ?, ?, ?, ?, ?)";
						$request_insert_timetable = $database_connect->prepare($query_insert_timetable);
						$request_insert_timetable->execute(array($response_worker->worker_id, $response_timetable_config->class_id, $response_timetable_config->course_id, $day, $hour, $response_timetable_config->school_year));

						// array_push($hours_done, $response_timetable_config->tricks_id);
						// array_push($classes_done, $response_timetable_config->class_id);
						// array_push($response_count, $timetable_single);
					}
				}
			} else {
				// $query_count_timetable = "SELECT COUNT(*) AS count_timetable FROM timetable WHERE class_id='$response_timetable_config->class_id'";
				// $request_count_timetable = $database_connect->query($query_count_timetable);
				// $response_count_timetable = $request_count_timetable->fetchObject();

				// 	if ($response_count_timetable->count_timetable < 32) {
				// 		$delete_query = "DELETE FROM timetable WHERE class_id='$response_timetable_config->class_id' AND worker_id!=29";
				// 		$delete_request = $database_connect->query($delete_query);
				// 	}
			}
		}
		// }
	}
}



$query_classes = "SELECT * FROM classes_completed WHERE school_year='$annee'";
$request_classes = $database_connect->query($query_classes);
while ($response_classes = $request_classes->fetchObject()) {
	$timetable_class = array();

	$class_number = find_class_number($response_classes->class_id);
	if ($response_classes->class_id == 1) {
		$class_number = "1 ère";
	} else {
		$class_number = "$response_classes->class_id ème";
	}

	$cycle_name = find_cycle_name($response_classes->cycle_id);
	$order_name = find_order_name($response_classes->order_id);
	$section_name = find_section_name($response_classes->section_id);
	$option_name = find_option_name($response_classes->option_id);

	$timet['class_name'] = $class_number . " " . $section_name . " " . $option_name . " " . $order_name; // . " ".$cycle_name;

	$query_timetable = "SELECT * FROM timetable WHERE class_id='$response_classes->id_classes'";
	$request_timetable = $database_connect->query($query_timetable);
	while ($response_timetable = $request_timetable->fetchObject()) {

		$timet = array();

		$query_worker_timetable = "SELECT * FROM workers_info WHERE worker_id='$response_timetable->worker_id'";
		$request_worker_timetable = $database_connect->query($query_worker_timetable);
		$response_worker_timetable = $request_worker_timetable->fetchObject();

		$query_course = "SELECT * FROM courses WHERE course_id='$response_timetable->course_id'";
		$request_course = $database_connect->query($query_course);
		$response_course = $request_course->fetchObject();

		$timet['timetable_id'] = $response_timetable->timetable_id;
		$timet['class_id'] = $response_timetable->worker_id;
		$timet['class_id'] = $response_timetable->class_id;
		$timet['course_id'] = $response_timetable->course_id;
		$timet['day_course'] = $response_timetable->day_course;
		$timet['hour_course'] = $response_timetable->hour_course;
		$timet['course_name'] = $response_course->course_name;
		$timet['worker_name'] = $response_worker_timetable->first_name . " " . $response_worker_timetable->second_name . " " . $response_worker_timetable->last_name;

		array_push($timetable_class, $timet);
	}

	// $query_timetable_configg = "SELECT * FROM trics_timetable WHERE class_id='$response_classes->id_classes'";
	// $request_timetable_configg = $database_connect->query($query_timetable_configg);
	// while($response_timetable_configg = $request_timetable_config->fetchObject()) {

	// 	$tricks = array();

	// 	$query_courset = "SELECT * FROM courses WHERE course_id='$response_timetable_configg->course_id'";
	// 	$request_courset = $database_connect->query($query_courset);
	// 	$response_courset = $request_courset->fetchObject();

	// 	$tricks['course'] = $response_course;
	// 	$tricks['trick'] = $response_timetable_config;
	// 	$number_hours = $number_hours + $response_timetable_config->hours_per_week;

	// 	array_push($tricks_timetable, $tricks);
	// }

	array_push($final_timetable, $timetable_class);
}

$response['count'] = $response_count;
$response['timetable'] = $final_timetable;
echo json_encode($response);
?>