<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/class.marksheet.insert.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $pupils = array();
    $courses = array();
    $marks = array();
    $first_pupil = 1;
    $first_course = 1;
    $total_marks = array();
    $autres = array();
    $courses_count = 0;
    $conduites = array();
    $paiements = array();
    
    $school_year = htmlspecialchars(trim(strip_tags($_POST["school_year"])));
    $cycle_id = htmlspecialchars(trim(strip_tags($_POST["cycle_id"])));
    $class_id = htmlspecialchars(trim(strip_tags($_POST["class_id"])));
    $order_id = htmlspecialchars(trim(strip_tags($_POST["order_id"])));
    $section_id = htmlspecialchars(trim(strip_tags($_POST["section_id"])));
    $option_id = htmlspecialchars(trim(strip_tags($_POST["option_id"])));

    $autres['annee'] = find_school_year($school_year);
    $autres['school_name'] = $school_name;
    $autres['school_name_abb'] = $school_name_abb;
    $autres['devise_school'] = $devise_school;
    $autres['school_bp'] = $school_bp;
    $autres['email_school'] = $email_school;
    $autres['school_city'] = $school_city;
    $autres['school_province'] = $school_province;
    $autres['school_commune'] = $school_commune;
    $autres['phone_1'] = $phone_1;
    $autres['phone_2'] = $phone_2;

    $query_count_courses = "SELECT school_year, cycle_id, class_id, section_id, option_id, COUNT(*) AS count_courses_exist FROM courses WHERE school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=?";
    $request_count_courses = $database_connect->prepare($query_count_courses);
    $request_count_courses->execute(array($school_year, $cycle_id, $class_id, $section_id, $option_id));
    $response_count_courses = $request_count_courses->fetchObject();

    if($response_count_courses->count_courses_exist != 0) {
        $query_courses = "SELECT * FROM courses WHERE school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=? ORDER BY course_id";
        $request_courses = $database_connect->prepare($query_courses);
        $request_courses->execute(array($school_year, $cycle_id, $class_id, $section_id, $option_id));
        while($response_courses = $request_courses->fetchObject()) {
            array_push($courses, $response_courses);
            $first_course = $response_courses->course_id;
            $courses_count = $courses_count + 1;
        }
    }

    $query_count = "SELECT school_year, cycle_school, class_school, class_order, class_section, class_option, COUNT(*) AS count_pupils_exist FROM pupils_info WHERE school_year=? AND cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=?";
    $request_count = $database_connect->prepare($query_count);
    $request_count->execute(array($school_year, $cycle_id, $class_id, $order_id, $section_id, $option_id));
    $response_count = $request_count->fetchObject();

    if($response_count->count_pupils_exist != 0) {
        $query = "SELECT * FROM pupils_info WHERE school_year=? AND cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? ORDER BY first_name ASC, second_name ASC, last_name ASC";
        $request = $database_connect->prepare($query);
        $request->execute(array($school_year, $cycle_id, $class_id, $order_id, $section_id, $option_id));
        while($response = $request->fetchObject()) {
            array_push($pupils, $response);
            $first_pupil = $response->pupil_id;

            // $countp = "SELECT pupil_id, COUNT(*) AS count_paiements FROM paiements WHERE pupil_id='$response->pupil_id'";
            // $countreq = $database_connect->query($countp);
            // $countres = $countreq->fetchObject();


            // $query_count_conduite = "SELECT pupil_id, COUNT(*) AS count_conduite_exists FROM conduite WHERE pupil_id=?";
            // $request_count_conduite = $database_connect->prepare($query_count_conduite);
            // $request_count_conduite->execute(array($response->pupil_id));
            // $response_count_conduite = $request_count_conduite->fetchObject();

            // if ($response_count_conduite->count_conduite_exists != 0) {
            //     $query_conduite = "SELECT * FROM conduite WHERE pupil_id=?";
            //     $request_conduite = $database_connect->prepare($query_conduite);
            //     $request_conduite->execute(array($response->pupil_id));
            //     while($response_conduite = $request_conduite->fetchObject()) {
            //         array_push($conduites, $response_conduite);
            //     }
            // }
        }
    }
    
    $response['courses'] = $courses;
    $response['courses_count'] = $courses_count;
    $response['pupils'] = $pupils;
    $response['autres'] = $autres;
    
    echo json_encode($response);


?>