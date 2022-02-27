<?php

    require_once("../config/dbconnect.functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $marks = "";
    
    $school_year = 1;//htmlspecialchars(trim(strip_tags($_POST["school_year"])));
    $pupil_id = 675;//htmlspecialchars(trim(strip_tags($_POST["pupil_id"])));
    $course_id = 9;//htmlspecialchars(trim(strip_tags($_POST["course_id"])));
    $periode = 1;//htmlspecialchars(trim(strip_tags($_POST["periode"])));

    $query_count_marks = "SELECT school_year, pupil, course, school_period, COUNT(*) AS count_marks_exist FROM marks_info WHERE school_year=? AND pupil=? AND course=? AND school_period=?";
    $request_count_marks = $database_connect->prepare($query_count_marks);
    $request_count_marks->execute(array($school_year, $pupil_id, $course_id, $periode));
    $response_count_marks = $request_count_marks->fetchObject();

    if($response_count_marks->count_marks_exist != 0) {
        $query_marks = "SELECT * FROM marks_info WHERE school_year=? AND pupil=? AND course=? AND school_period=?";
        $request_marks = $database_connect->prepare($query_marks);
        $request_marks->execute(array($school_year, $pupil_id, $course_id, $periode));
        $response_marks = $request_marks->fetchObject();
        $mark = $response_marks->main_marks;
    } 
    else {
        $mark = "";
    }

    $response['mark'] = $mark;
    echo json_encode($response);


?>