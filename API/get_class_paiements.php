<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/class.marksheet.insert.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $pupils = array();
    $ttotal = 0;
    $ppayed = 0;
    
    $school_year = htmlspecialchars(trim(strip_tags($_POST["school_year"])));
    $cycle_id = htmlspecialchars(trim(strip_tags($_POST["cycle_id"])));
    $class_id = htmlspecialchars(trim(strip_tags($_POST["class_id"])));
    $order_id = htmlspecialchars(trim(strip_tags($_POST["order_id"])));
    $section_id = htmlspecialchars(trim(strip_tags($_POST["section_id"])));
    $option_id = htmlspecialchars(trim(strip_tags($_POST["option_id"])));

    // $autres['annee'] = find_school_year($school_year);
    // $autres['school_name'] = $school_name;
    // $autres['school_name_abb'] = $school_name_abb;
    // $autres['devise_school'] = $devise_school;
    // $autres['school_bp'] = $school_bp;
    // $autres['email_school'] = $email_school;
    // $autres['school_city'] = $school_city;
    // $autres['school_province'] = $school_province;
    // $autres['school_commune'] = $school_commune;
    // $autres['phone_1'] = $phone_1;
    // $autres['phone_2'] = $phone_2;

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
        while($response_pupil = $request->fetchObject()) {
            $pp = array();
            $pp['pupil'] = $response_pupil;
            $montant_total = 0;
            $montants_payes = 0;
            $main_total_montant = 0;
            $soldes_paiements = array();

            if($response_pupil->paiement_category != '0') {
                $category_query = "SELECT * FROM paiement_categories WHERE category_id='$response_pupil->paiement_category' AND school_year='$school_year'";
                $category_request = $database_connect->query($category_query);
                $category_response = $category_request->fetchObject();
    
                $montant_total = $category_response->category_amount;
                $ttotal = $ttotal + $category_response->category_amount;
                $main_total_montant = $category_response->category_amount;
                // $ttrims = $ttrims + $category_response->category_amount / 3;
            }

            $querypaiements = "SELECT * FROM paiements WHERE pupil_id='$response_pupil->pupil_id' AND paiement_validated='1' ORDER BY paiement_id DESC";
            $requestpaiements = $database_connect->query($querypaiements);
            while($response_array_paiements = $requestpaiements->fetchObject()) {

                    $montants_payes = $montants_payes + $response_array_paiements->montant_paye;
                    $ppayed = $ppayed + $response_array_paiements->montant_paye;
            }

            if($montant_total !== 0) {
                $s1 = $montant_total/3;
            } else {
                $s1 = $main_total_montant/3;
            }

            $s2 = $s1 + $s1;
            $s3 = $s2 + $s1;

            $montant = $montants_payes;
            if($montant != 0)
            {
                if($montant <= $s1)
                {
                    if($montant == $s1)
                    {
                        $message_soldes_t1 = "0";
                        $message_soldes_t2 = $s1;
                        $message_soldes_t3 = $s1;
                    }
                    else
                    {
                        $tr1 = $s1-$montant;
                        $message_soldes_t1 = "$tr1";
                        $message_soldes_t2 = $s1;
                        $message_soldes_t3 = $s1;
                    }
                }

                if($montant > $s1 && $montant <= $s2)
                {
                    if($montant == $s2)
                    {
                        $message_soldes_t1 = "0";
                        $message_soldes_t2 = "0";
                        $message_soldes_t3 = $s1;
                    }
                    else
                    {
                        $tr2 = $s2-$montant;
                        $message_soldes_t1 = "0";
                        $message_soldes_t2 = "$tr2";
                        $message_soldes_t3 = $s1;
                    }
                }

                if($montant > $s2)
                {
                    if($montant == $s3)
                    {
                        $message_soldes_t1 = "0";
                        $message_soldes_t2 = "0";
                        $message_soldes_t3 = "0";
                    }
                    else
                    {
                        $tr3 = $s3-$montant;
                        $message_soldes_t1 = "0";
                        $message_soldes_t2 = "0";
                        $message_soldes_t3 = "$tr3";
                    }
                }
            }
            else
            {
                $message_soldes_t1 = $s1;
                $message_soldes_t2 = $s1;
                $message_soldes_t3 = $s1;
            }

        $soldes_paiements['solde'] = $montant_total-$montants_payes;
        $soldes_paiements['solde1'] = $message_soldes_t1;
        $soldes_paiements['solde2'] = $message_soldes_t2;
        $soldes_paiements['solde3'] = $message_soldes_t3;
        $pp['soldes_paiements'] = $soldes_paiements;

            array_push($pupils, $pp);
        }
    }
    
    // $response['courses'] = $courses;
    // $response['courses_count'] = $courses_count;
    $response['pupils'] = $pupils;
    $response['total_montant'] = $ttotal;
    $response['paye'] = $ppayed;
    
    echo json_encode($response);


?>