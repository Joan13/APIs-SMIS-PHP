<?php

require_once("../config/dbconnect.functions.php");
header("Access-Control-Allow-Origin: *");
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$response = array();
$pupils = array();
$marks = array();
$courses = array();

$school_year = htmlspecialchars(trim(strip_tags($_POST["school_year"])));
$cycle_id = htmlspecialchars(trim(strip_tags($_POST["cycle_id"])));
$class_id = htmlspecialchars(trim(strip_tags($_POST["class_id"])));
$order_id = htmlspecialchars(trim(strip_tags($_POST["order_id"])));
$section_id = htmlspecialchars(trim(strip_tags($_POST["section_id"])));
$option_id = htmlspecialchars(trim(strip_tags($_POST["option_id"])));

$query_count_courses = "SELECT school_year, cycle_id, class_id, section_id, option_id, COUNT(*) AS count_courses_exist FROM courses WHERE school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=?";
$request_count_courses = $database_connect->prepare($query_count_courses);
$request_count_courses->execute(array($school_year, $cycle_id, $class_id, $section_id, $option_id));
$response_count_courses = $request_count_courses->fetchObject();

if ($response_count_courses->count_courses_exist != 0) {
    $query_courses = "SELECT * FROM courses WHERE school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=? ORDER BY course_id";
    $request_courses = $database_connect->prepare($query_courses);
    $request_courses->execute(array($school_year, $cycle_id, $class_id, $section_id, $option_id));
    while ($response_courses = $request_courses->fetchObject()) {
        array_push($courses, $response_courses);
    }
}

$query_count = "SELECT school_year, cycle_school, class_school, class_order, class_section, class_option, COUNT(*) AS count_pupils_exist FROM pupils_info WHERE school_year=? AND cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=?";
$request_count = $database_connect->prepare($query_count);
$request_count->execute(array($school_year, $cycle_id, $class_id, $order_id, $section_id, $option_id));
$response_count = $request_count->fetchObject();

if ($response_count->count_pupils_exist != 0) {
    $query = "SELECT * FROM pupils_info WHERE school_year=? AND cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? ORDER BY first_name ASC, second_name ASC, last_name ASC";
    $request = $database_connect->prepare($query);
    $request->execute(array($school_year, $cycle_id, $class_id, $order_id, $section_id, $option_id));
    while ($response = $request->fetchObject()) {
        array_push($pupils, $response);

        $query_count_marks = "SELECT pupil, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=?";
        $request_count_marks = $database_connect->prepare($query_count_marks);
        $request_count_marks->execute(array($response->pupil_id));
        $response_count_marks = $request_count_marks->fetchObject();

        if ($response_count_marks->count_marks_exist != 0) {
            $query_marks = "SELECT * FROM marks_info WHERE pupil=?";
            $request_marks = $database_connect->prepare($query_marks);
            $request_marks->execute(array($response->pupil_id));
            while ($response_marks = $request_marks->fetchObject()) {
                array_push($marks, $response_marks);
            }
        }
    }
}

$response['pupils_marks'] = $marks;
$response['courses'] = $courses;
$response['pupils'] = $pupils;
echo json_encode($response);

?>