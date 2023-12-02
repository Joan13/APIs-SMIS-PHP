<?php

require_once("../config/dbconnect.functions.php");
include("../config/functions.php");

header("Access-Control-Allow-Origin: *");
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$annee = $_POST['annee'];
$response = array();
$timetable = array();
$timetable1 = array();

$query_classes = "SELECT * FROM classes_completed WHERE school_year='$annee' ORDER BY classes_alignment";
$request_classes = $database_connect->query($query_classes);
while ($response_classes = $request_classes->fetchObject()) {
	$timetable_class = array();
	$pretimetable_class = array();

	$class_number = find_class_number($response_classes->class_id);
	if ($class_number == 1) {
		$class_number = "1 ère";
	} else {
		$class_number = "$class_number ème";
	}

	$cycle_name = find_cycle_name($response_classes->cycle_id);
	$order_name = find_order_name($response_classes->order_id);
	$section_name = find_section_name($response_classes->section_id);
	$option_name = find_option_name($response_classes->option_id);

	$timetable_class['class_name'] = $class_number . " " . $section_name . " " . $option_name . " " . $order_name; // . " ".$cycle_name;

	$query_timetable = "SELECT * FROM timetable WHERE class_id='$response_classes->id_classes' ORDER BY day_course, hour_course";
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
		$timet['worker_id'] = $response_timetable->worker_id;
		$timet['class_id'] = $response_timetable->class_id;
		$timet['course_id'] = $response_timetable->course_id;
		$timet['day_course'] = $response_timetable->day_course;
		$timet['hour_course'] = $response_timetable->hour_course;
		$timet['course_name'] = $response_course->course_name;
		$timet['worker_name'] = $response_worker_timetable->first_name . " " . $response_worker_timetable->second_name . " " . $response_worker_timetable->last_name;

		array_push($pretimetable_class, $timet);
	}

	$timetable_class['timetable'] = $pretimetable_class;
	array_push($timetable, $timetable_class);
}

$query_worker_timetable = "SELECT * FROM workers_info WHERE worker_year='$annee' AND poste=5";
$request_worker_timetable = $database_connect->query($query_worker_timetable);
while ($response_worker_timetable = $request_worker_timetable->fetchObject()) {
	$timetable_class = array();
	$pretimetable_class = array();

	$timetable_class['worker_id'] = $response_worker_timetable->worker_id;
	$timetable_class['worker_name'] = $response_worker_timetable->first_name . " " . $response_worker_timetable->second_name . " " . $response_worker_timetable->last_name;

	$query_timetable = "SELECT * FROM timetable WHERE worker_id='$response_worker_timetable->worker_id' ORDER BY day_course, hour_course";
	$request_timetable = $database_connect->query($query_timetable);
	while ($response_timetable = $request_timetable->fetchObject()) {

		$timet = array();

		$query_classes = "SELECT * FROM classes_completed WHERE id_classes='$response_timetable->class_id' ORDER BY classes_alignment";
		$request_classes = $database_connect->query($query_classes);
		$response_classes = $request_classes->fetchObject();

		$class_number = find_class_number($response_classes->class_id);
		if ($class_number == 1) {
			$class_number = "1 ère";
		} else {
			$class_number = "$class_number ème";
		}

		$cycle_name = find_cycle_name($response_classes->cycle_id);
		$order_name = find_order_name($response_classes->order_id);
		$section_name = find_section_name($response_classes->section_id);
		$option_name = find_option_name($response_classes->option_id);

		$query_course = "SELECT * FROM courses WHERE course_id='$response_timetable->course_id'";
		$request_course = $database_connect->query($query_course);
		$response_course = $request_course->fetchObject();

		$timet['timetable_id'] = $response_timetable->timetable_id;
		$timet['worker_id'] = $response_timetable->worker_id;
		$timet['class_id'] = $response_timetable->class_id;
		$timet['course_id'] = $response_timetable->course_id;
		$timet['day_course'] = $response_timetable->day_course;
		$timet['hour_course'] = $response_timetable->hour_course;
		$timet['course_name'] = $response_course->course_name;
		$timet['class_name'] = $class_number . " " . $section_name . " " . $option_name . " " . $order_name; // . " ".$cycle_name;

		array_push($pretimetable_class, $timet);
	}

	$timetable_class['timetable'] = $pretimetable_class;
	array_push($timetable1, $timetable_class);
}

$response['timetable'] = $timetable;
$response['timetable1'] = $timetable1;
echo json_encode($response);

?>
