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
    $courses_count = 0;
    $conduites = array();
    $push_maxima = 0;
    $class = array();
    $conseils = array();
    $pupils_count = 0;
    
    // $school_year = 1;//htmlspecialchars(trim(strip_tags($_POST["school_year"])));
    // $cycle_id = 1;//htmlspecialchars(trim(strip_tags($_POST["cycle_id"])));
    // $class_id = 1;//htmlspecialchars(trim(strip_tags($_POST["class_id"])));
    // $order_id = 1;//htmlspecialchars(trim(strip_tags($_POST["order_id"])));
    // $section_id = 0;//htmlspecialchars(trim(strip_tags($_POST["section_id"])));
    // $option_id = 0;//htmlspecialchars(trim(strip_tags($_POST["option_id"])));

    $school_year = htmlspecialchars(trim(strip_tags($_POST["school_year"])));
    $cycle_id = htmlspecialchars(trim(strip_tags($_POST["cycle_id"])));
    $class_id = htmlspecialchars(trim(strip_tags($_POST["class_id"])));
    $order_id = htmlspecialchars(trim(strip_tags($_POST["order_id"])));
    $section_id = htmlspecialchars(trim(strip_tags($_POST["section_id"])));
    $option_id = htmlspecialchars(trim(strip_tags($_POST["option_id"])));

    $array_places_1 = array();
	$array_places_2 = array();
	$array_places_10 = array();
	$array_places_tot1 = array();
    $array_places_3 = array();
    $array_places_4 = array();
    $array_places_11 = array();
    $array_places_tot2 = array();
    $array_places_tott = array();

    $query_count_courses = "SELECT school_year, cycle_id, class_id, section_id, option_id, COUNT(*) AS count_courses_exist FROM courses WHERE school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=?";
    $request_count_courses = $database_connect->prepare($query_count_courses);
    $request_count_courses->execute(array($school_year, $cycle_id, $class_id, $section_id, $option_id));
    $response_count_courses = $request_count_courses->fetchObject();

    if($response_count_courses->count_courses_exist != 0) {

        $maxima_exists = 0;

        $query_courses = "SELECT * FROM courses WHERE school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=? ORDER BY total_marks ASC";
        $request_courses = $database_connect->prepare($query_courses);
        $request_courses->execute(array($school_year, $cycle_id, $class_id, $section_id, $option_id));
        while($response_courses = $request_courses->fetchObject()) {
            array_push($courses, $response_courses);
            $first_course = $response_courses->course_id;
            $courses_count = $courses_count + 1;

            if ($maxima_exists == $response_courses->total_marks) {}
            else { 
                $maxima_exists = $response_courses->total_marks;
                $push_maxima = $push_maxima + 1; 
            }
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
            $first_pupil = $response->pupil_id;
            $pupil = array();
            $pupil_marks = array();
            $pupil_conduite = array();
            $pupil_paiements = array();

            $pupils_count = $pupils_count + 1;

            // if (in_array(find_pupil_sum_main_marks($response->pupil_id, 1, $response->school_year), $array_places_1)) {
            //     array_push($array_places_1, find_pupil_sum_main_marks($response->pupil_id, 1, $response->school_year) + 1);
            // } else {
                $arrayy1 = array();
                $arrayy1['marks_found'] = find_pupil_sum_main_marks($response->pupil_id, 1, $response->school_year);
                $arrayy1['pupil_id'] = $response->pupil_id;
                array_push($array_places_1, $arrayy1);

                $arrayy2 = array();
                $arrayy2['marks_found'] = find_pupil_sum_main_marks($response->pupil_id, 2, $response->school_year);
                $arrayy2['pupil_id'] = $response->pupil_id;
                array_push($array_places_2, $arrayy2);

                $arrayy10 = array();
                $arrayy10['marks_found'] = find_pupil_sum_main_marks($response->pupil_id, 10, $response->school_year);
                $arrayy10['pupil_id'] = $response->pupil_id;
                array_push($array_places_10, $arrayy10);

                $arrayyTot1 = array();
                $arrayyTot1['marks_found'] = find_pupil_sum_main_marks_sem_trim($response->pupil_id, 1, 2, 10, $response->school_year);
                $arrayyTot1['pupil_id'] = $response->pupil_id;
                array_push($array_places_tot1, $arrayyTot1);

                $arrayy3 = array();
                $arrayy3['marks_found'] = find_pupil_sum_main_marks($response->pupil_id, 3, $response->school_year);
                $arrayy3['pupil_id'] = $response->pupil_id;
                array_push($array_places_3, $arrayy3);

                $arrayy4 = array();
                $arrayy4['marks_found'] = find_pupil_sum_main_marks($response->pupil_id, 4, $response->school_year);
                $arrayy4['pupil_id'] = $response->pupil_id;
                array_push($array_places_4, $arrayy4);

                $arrayy11 = array();
                $arrayy11['marks_found'] = find_pupil_sum_main_marks($response->pupil_id, 11, $response->school_year);
                $arrayy11['pupil_id'] = $response->pupil_id;
                array_push($array_places_11, $arrayy11);

                $arrayyTot2 = array();
                $arrayyTot2['marks_found'] = find_pupil_sum_main_marks_sem_trim($response->pupil_id, 3, 4, 11, $response->school_year);
                $arrayyTot2['pupil_id'] = $response->pupil_id;
                array_push($array_places_tot2, $arrayyTot2);

                $arrayyTott = array();
                $arrayyTott['marks_found'] = find_pupil_sum_main_marks_sem_trim($response->pupil_id, 3, 4, 11, $response->school_year) + find_pupil_sum_main_marks_sem_trim($response->pupil_id, 1, 2, 10, $response->school_year);
                //find_pupil_total_marks($response->pupil_id, $response->school_year);

                $arrayyTott['pupil_id'] = $response->pupil_id;
                array_push($array_places_tott, $arrayyTott);
            // }
            
            // array_push($array_places_2, find_pupil_sum_main_marks($response->pupil_id, 2, $response->school_year));
            // array_push($array_places_10, find_pupil_sum_main_marks($response->pupil_id, 10, $response->school_year));
            // array_push($array_places_tot1, find_pupil_sum_main_marks_sem_trim($response->pupil_id, 1, 2, 10, $response->school_year));

            $query_count_marks = "SELECT pupil, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=?";
            $request_count_marks = $database_connect->prepare($query_count_marks);
            $request_count_marks->execute(array($response->pupil_id));
            $response_count_marks = $request_count_marks->fetchObject();

            if ($response_count_marks->count_marks_exist != 0) {
                $query_marks = "SELECT * FROM marks_info WHERE pupil=?";
                $request_marks = $database_connect->prepare($query_marks);
                $request_marks->execute(array($response->pupil_id));
                while($response_marks = $request_marks->fetchObject()) {
                    // array_push($pupil_marks, $response_marks);
                    array_push($marks, $response_marks);
                }
            }

            // $query_count_conseil = "SELECT pupil_id, COUNT(*) AS count_conseil_exist FROM conseil_deliberation WHERE pupil_id=?";
            // $request_count_conseil = $database_connect->prepare($query_count_conseil);
            // $request_count_conseil->execute(array($response->pupil_id));
            // $response_count_conseil = $request_count_conseil->fetchObject();

            // if ($response_count_conseil->count_conseil_exist != 0) {
            //     $query_conseil = "SELECT * FROM conseil_deliberation WHERE pupil_id=?";
            //     $request_conseil = $database_connect->prepare($query_conseil);
            //     $request_conseil->execute(array($response->pupil_id));
            //     while($response_conseil = $request_conseil->fetchObject()) {
            //         array_push($conseils, $response_conseil);
            //     }
            // }


            $query_count_conduite = "SELECT pupil_id, COUNT(*) AS count_conduite_exists FROM conduite WHERE pupil_id=?";
            $request_count_conduite = $database_connect->prepare($query_count_conduite);
            $request_count_conduite->execute(array($response->pupil_id));
            $response_count_conduite = $request_count_conduite->fetchObject();

            if ($response_count_conduite->count_conduite_exists != 0) {
                $query_conduite = "SELECT * FROM conduite WHERE pupil_id=?";
                $request_conduite = $database_connect->prepare($query_conduite);
                $request_conduite->execute(array($response->pupil_id));
                while($response_conduite = $request_conduite->fetchObject()) {
                    // array_push($pupil_conduite, $response_conduite);
                    array_push($conduites, $response_conduite);
                }
            }

            $pupil['pupil'] = $response;
            $pupil['marks'] = $pupil_marks;
            $pupil['conduites'] = $pupil_conduite;
            $pupil['paiements'] = $pupil_paiements;
            array_push($pupils, $pupil);
        }
    }

    rsort($array_places_1);
    rsort($array_places_2);
    rsort($array_places_10);
    rsort($array_places_tot1);
    rsort($array_places_3);
    rsort($array_places_4);
    rsort($array_places_11);
    rsort($array_places_tot2);
    rsort($array_places_tott);
    
    $response['valeur_colonne'] = $push_maxima + $courses_count;
    $response['array_places_1'] = $array_places_1;
    $response['array_places_2'] = $array_places_2;
    $response['array_places_10'] = $array_places_10;
    $response['array_places_tot1'] = $array_places_tot1;
    $response['array_places_3'] = $array_places_3;
    $response['array_places_4'] = $array_places_4;
    $response['array_places_11'] = $array_places_11;
    $response['array_places_tot2'] = $array_places_tot2;
    $response['array_places_tott'] = $array_places_tott;
    $response['courses'] = $courses;
    $response['pupils_count'] = $pupils_count;
    $response['pupils_marks'] = $marks;
    $response['conduites'] = $conduites;
    $response['courses_count'] = $courses_count;
    $response['pupils'] = $pupils;
    $response['first_pupil'] = $first_pupil;
    $response['first_course'] = $first_course;
    $response['pupils_marks'] = $marks;
    $response['conseil_deliberation'] = $conseils;
    
    echo json_encode($response);

?>