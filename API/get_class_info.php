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
    $domains = array();
    $sub_domains = array();
    $ttotal = 0;
    $ppayed = 0;
    
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

    $query_count_domains = "SELECT school_year, cycle_id, class_id, section_id, option_id, COUNT(*) AS count_domains_exist FROM courses_domains WHERE school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=?";
    $request_count_domains = $database_connect->prepare($query_count_domains);
    $request_count_domains->execute(array($school_year, $cycle_id, $class_id, $section_id, $option_id));
    $response_count_domains = $request_count_domains->fetchObject();

    if($response_count_domains->count_domains_exist != 0) {
        $query_domains = "SELECT * FROM courses_domains WHERE school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=? ORDER BY domain_id ASC";
        $request_domains = $database_connect->prepare($query_domains);
        $request_domains->execute(array($school_year, $cycle_id, $class_id, $section_id, $option_id));
        while($response_domains = $request_domains->fetchObject()) {
            array_push($domains, $response_domains);
            
            $query_count_sub_domains = "SELECT domain_id, COUNT(*) AS count_sub_domains_exist FROM courses_sub_domains WHERE domain_id=?";
            $request_count_sub_domains = $database_connect->prepare($query_count_sub_domains);
            $request_count_sub_domains->execute(array($response_domains->domain_id));
            $response_count_sub_domains = $request_count_sub_domains->fetchObject();

            if($response_count_sub_domains->count_sub_domains_exist != 0) {
                $query_sub_domains = "SELECT * FROM courses_sub_domains WHERE domain_id=? ORDER BY sub_domain_id ASC";
                $request_sub_domains = $database_connect->prepare($query_sub_domains);
                $request_sub_domains->execute(array($response_domains->domain_id));
                while($response_sub_domains = $request_sub_domains->fetchObject()) {
                    array_push($sub_domains, $response_sub_domains);
                }
            }
        }
    }

    $query_count = "SELECT school_year, cycle_school, class_school, class_order, class_section, class_option, COUNT(*) AS count_pupils_exist FROM pupils_info WHERE school_year=? AND cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND is_inactive=?";
    $request_count = $database_connect->prepare($query_count);
    $request_count->execute(array($school_year, $cycle_id, $class_id, $order_id, $section_id, $option_id, 0));
    $response_count = $request_count->fetchObject();

    if($response_count->count_pupils_exist != 0) {
        $query = "SELECT * FROM pupils_info WHERE school_year=? AND cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND is_inactive=? ORDER BY first_name ASC, second_name ASC, last_name ASC";
        $request = $database_connect->prepare($query);
        $request->execute(array($school_year, $cycle_id, $class_id, $order_id, $section_id, $option_id, 0));
        while($response_pupil = $request->fetchObject()) {
            $first_pupil = $response_pupil->pupil_id;
            $pupil = array();
            $pupil_marks = array();
            $pupil_conduite = array();
            $pupil_paiements = array();
            $montant_total = 0;
            $montants_payes = 0;
            $main_total_montant = 0;
            $soldes_paiements = array();

            $pupils_count = $pupils_count + 1;

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

            // if (in_array(find_pupil_sum_main_marks($response_pupil->pupil_id, 1, $response_pupil->school_year), $array_places_1)) {
            //     array_push($array_places_1, find_pupil_sum_main_marks($response_pupil->pupil_id, 1, $response_pupil->school_year) + 1);
            // } else {
            $arrayy1 = array();
            $arrayy1['marks_found'] = find_pupil_sum_main_marks($response_pupil->pupil_id, 1, $response_pupil->school_year);
            $arrayy1['pupil_id'] = $response_pupil->pupil_id;
            array_push($array_places_1, $arrayy1);

            $arrayy2 = array();
            $arrayy2['marks_found'] = find_pupil_sum_main_marks($response_pupil->pupil_id, 2, $response_pupil->school_year);
            $arrayy2['pupil_id'] = $response_pupil->pupil_id;
            array_push($array_places_2, $arrayy2);

            $arrayy10 = array();
            $arrayy10['marks_found'] = find_pupil_sum_main_marks($response_pupil->pupil_id, 10, $response_pupil->school_year);
            $arrayy10['pupil_id'] = $response_pupil->pupil_id;
            array_push($array_places_10, $arrayy10);

            $arrayyTot1 = array();
            $arrayyTot1['marks_found'] = find_pupil_sum_main_marks_sem_trim($response_pupil->pupil_id, 1, 2, 10, $response_pupil->school_year);
            $arrayyTot1['pupil_id'] = $response_pupil->pupil_id;
            array_push($array_places_tot1, $arrayyTot1);

            $arrayy3 = array();
            $arrayy3['marks_found'] = find_pupil_sum_main_marks($response_pupil->pupil_id, 3, $response_pupil->school_year);
            $arrayy3['pupil_id'] = $response_pupil->pupil_id;
            array_push($array_places_3, $arrayy3);

            $arrayy4 = array();
            $arrayy4['marks_found'] = find_pupil_sum_main_marks($response_pupil->pupil_id, 4, $response_pupil->school_year);
            $arrayy4['pupil_id'] = $response_pupil->pupil_id;
            array_push($array_places_4, $arrayy4);

            $arrayy11 = array();
            $arrayy11['marks_found'] = find_pupil_sum_main_marks($response_pupil->pupil_id, 11, $response_pupil->school_year);
            $arrayy11['pupil_id'] = $response_pupil->pupil_id;
            array_push($array_places_11, $arrayy11);

            $arrayyTot2 = array();
            $arrayyTot2['marks_found'] = find_pupil_sum_main_marks_sem_trim($response_pupil->pupil_id, 3, 4, 11, $response_pupil->school_year);
            $arrayyTot2['pupil_id'] = $response_pupil->pupil_id;
            array_push($array_places_tot2, $arrayyTot2);

            $arrayyTott = array();
            $arrayyTott['marks_found'] = find_pupil_sum_main_marks_sem_trim($response_pupil->pupil_id, 3, 4, 11, $response_pupil->school_year) + find_pupil_sum_main_marks_sem_trim($response_pupil->pupil_id, 1, 2, 10, $response_pupil->school_year);
            //find_pupil_total_marks($response_pupil->pupil_id, $response_pupil->school_year);

            $arrayyTott['pupil_id'] = $response_pupil->pupil_id;
            array_push($array_places_tott, $arrayyTott);
            // }
            
            // array_push($array_places_2, find_pupil_sum_main_marks($response_pupil->pupil_id, 2, $response_pupil->school_year));
            // array_push($array_places_10, find_pupil_sum_main_marks($response_pupil->pupil_id, 10, $response_pupil->school_year));
            // array_push($array_places_tot1, find_pupil_sum_main_marks_sem_trim($response_pupil->pupil_id, 1, 2, 10, $response_pupil->school_year));

            $query_count_marks = "SELECT pupil, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=?";
            $request_count_marks = $database_connect->prepare($query_count_marks);
            $request_count_marks->execute(array($response_pupil->pupil_id));
            $response_count_marks = $request_count_marks->fetchObject();

            if ($response_count_marks->count_marks_exist != 0) {
                $query_marks = "SELECT * FROM marks_info WHERE pupil=?";
                $request_marks = $database_connect->prepare($query_marks);
                $request_marks->execute(array($response_pupil->pupil_id));
                while($response_marks = $request_marks->fetchObject()) {
                    // array_push($pupil_marks, $response_marks);
                    array_push($marks, $response_marks);
                }
            }

            $query_count_conseil = "SELECT pupil_id, COUNT(*) AS count_conseil_exist FROM conseil_deliberation WHERE pupil_id=?";
            $request_count_conseil = $database_connect->prepare($query_count_conseil);
            $request_count_conseil->execute(array($response_pupil->pupil_id));
            $response_count_conseil = $request_count_conseil->fetchObject();

            if ($response_count_conseil->count_conseil_exist != 0) {
                $query_conseil = "SELECT * FROM conseil_deliberation WHERE pupil_id=?";
                $request_conseil = $database_connect->prepare($query_conseil);
                $request_conseil->execute(array($response_pupil->pupil_id));
                while($response_conseil = $request_conseil->fetchObject()) {
                    array_push($conseils, $response_conseil);
                }
            }


            $query_count_conduite = "SELECT pupil_id, COUNT(*) AS count_conduite_exists FROM conduite WHERE pupil_id=?";
            $request_count_conduite = $database_connect->prepare($query_count_conduite);
            $request_count_conduite->execute(array($response_pupil->pupil_id));
            $response_count_conduite = $request_count_conduite->fetchObject();

            if ($response_count_conduite->count_conduite_exists != 0) {
                $query_conduite = "SELECT * FROM conduite WHERE pupil_id=?";
                $request_conduite = $database_connect->prepare($query_conduite);
                $request_conduite->execute(array($response_pupil->pupil_id));
                while($response_conduite = $request_conduite->fetchObject()) {
                    // array_push($pupil_conduite, $response_conduite);
                    array_push($conduites, $response_conduite);
                }
            }

            $pupil['pupil'] = $response_pupil;
            $pupil['pupil_id'] = $response_pupil->pupil_id;
            $pupil['marks'] = $pupil_marks;
            $pupil['soldes_paiements'] = $soldes_paiements;
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
    $response['domains'] = $domains;
    $response['sub_domains'] = $sub_domains;
    $response['conduites'] = $conduites;
    $response['courses_count'] = $courses_count;
    $response['pupils'] = $pupils;
    $response['first_pupil'] = $first_pupil;
    $response['first_course'] = $first_course;
    $response['pupils_marks'] = $marks;
    $response['conseil_deliberation'] = $conseils;
    $response['paye_paiements'] = $ppayed;
    $response['total_paiements'] = $ttotal;
    
    echo json_encode($response);

?>