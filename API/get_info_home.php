<?php

    require_once("../config/dbconnect.functions.php");
    require_once("../config/functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $classes = array();
    $annees = array();
    $cycles = array();
    $class_numbers = array();
    $orders = array();
    $sections = array();
    $options = array();
    $libelles = array();
    $attributions = array();
    $payes = 0;
    $annee_scolaire = array();
    $autres = array();
    $pupils = array();
    $pupils_count = 0;
    $pupils_count_caisse = 0;
    $paied_caisse = 0;
    $reussites = 0;
    $doubles = 0;
    $echecs = 0;
    $abandon = 0;
    $pupils_count_male = 0;
    $pupils_count_female = 0;
    $pupils_marks = array();
    $main_total_montant = 0;
    $paiement_categories = array();

    $annee = $_POST['annee'];

    if($annee == "") {
        $query = "SELECT * FROM school_years ORDER BY year_id DESC";
        $request = $database_connect->query($query);
        $response_array_years = $request->fetchObject();

        $annee = $response_array_years->year_id;
        $annee_scolaire = $response_array_years;

        $query = "SELECT * FROM school_years ORDER BY year_id DESC LIMIT 1";
        $request = $database_connect->query($query);
        $response_array_years = $request->fetchObject();

        $autres['annee'] = $response_array_years->year_name;
    } else {
        $count_years = "SELECT year_id, COUNT(*) AS count_years FROM school_years WHERE year_id='$annee'";
        $count_years_request = $database_connect->query($count_years);
        $count_years_response = $count_years_request->fetchObject();

        if ($count_years_response->count_years != 0) {
            $query = "SELECT * FROM school_years WHERE year_id='$annee'";
            $request = $database_connect->query($query);
            $response_array_years = $request->fetchObject();

            $annee_scolaire = $response_array_years;
            $autres['annee'] = $response_array_years->year_name;
        }
    }

    // $query_conseil = "SELECT * FROM conseil_deliberation";
    // $request_conseil = $database_connect->query($query_conseil);
    // while($response_conseil = $request_conseil->fetchObject()) {

    //     if ($response_conseil->main_conseil == "0" || $response_conseil->main_conseil == "3") {
    //         $reussites = $reussites + 1;
    //     }

    //     if ($response_conseil->main_conseil == "1" || $response_conseil->main_conseil == "4") {
    //         $doubles = $doubles + 1;
    //     }

    //     if ($response_conseil->main_conseil == "2" || $response_conseil->main_conseil == "5") {
    //         $echecs = $echecs + 1;
    //     }

    //     if ($response_conseil->main_conseil == "6") {
    //         $abandon = $abandon + 1;
    //     }
    // }

    $categories_query = "SELECT * FROM paiement_categories WHERE school_year='$annee'";
    $categories_request = $database_connect->query($categories_query);
    $categories_response = $categories_request->fetchObject();

    $query_countc = "SELECT school_year, COUNT(*) AS count_paiement_categories FROM paiement_categories WHERE school_year=?";
	$request_countc = $database_connect->prepare($query_countc);
	$request_countc->execute(array($annee));
	$response_countc = $request_countc->fetchObject();

	if ($response_countc->count_paiement_categories != 0) {
		$querypc = "SELECT * FROM paiement_categories WHERE school_year=?";
        $requestpc = $database_connect->prepare($querypc);
        $requestpc->execute(array($annee));
        while($response_categories = $requestpc->fetchObject()) {
            array_push($paiement_categories, $response_categories);
        }
	}

    $query_countl = "SELECT school_year, COUNT(*) AS count_libelles FROM libelles WHERE school_year=?";
	$request_countl = $database_connect->prepare($query_countl);
	$request_countl->execute(array($annee));
	$response_countl = $request_countl->fetchObject();

	if ($response_countl->count_libelles != 0) {
		$queryl = "SELECT * FROM libelles WHERE school_year=?";
        $requestl = $database_connect->prepare($queryl);
        $requestl->execute(array($annee));
        while($response_libelles = $requestl->fetchObject()) {
            array_push($libelles, $response_libelles);
        }
	}

    $sieq = "SELECT school_year, COUNT(*) AS base_school_infos_exists FROM base_school_info WHERE school_year='$annee'";
    $siereq = $database_connect->query($sieq);
    $sieres = $siereq->fetchObject();
    if ($sieres->base_school_infos_exists != 0) {
        $sel_info_school = "SELECT * FROM base_school_info WHERE school_year='$annee'";
        $req_school_info = $database_connect->query($sel_info_school);
        $res_school_info = $req_school_info->fetchObject();

        $autres['code_school'] = $res_school_info->code_school;
        $autres['name_promoter'] = $res_school_info->name_promoter;
        $autres['date_end'] = $res_school_info->date_end;

    } else {

        $autres['code_school'] = "Code ecole: vide";
        $autres['name_promoter'] = "Nom Chef d'établissement";
        $autres['date_end'] = "Date fin d'année";
    }

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

    $query_paiements = "SELECT * FROM paiements WHERE school_year='$annee'";
    $request_paiements = $database_connect->query($query_paiements);
    while($response_paiements = $request_paiements->fetchObject()) {
        $payes = $payes + $response_paiements->montant_paye;
    }

    $query = "SELECT * FROM school_years ORDER BY year_id DESC";
    $request = $database_connect->query($query);
    while($response_array_years = $request->fetchObject()) {
        array_push($annees, $response_array_years);
    }

    $querycycles = "SELECT * FROM cycle";
    $requestcycles = $database_connect->query($querycycles);
    while($response_array_cycles = $requestcycles->fetchObject()) {
        array_push($cycles, $response_array_cycles);
    }

    // $querymarks = "SELECT * FROM marks_info WHERE school_year='$annee'";
    // $requestmarks = $database_connect->query($querymarks);
    // while($response_array_marks = $requestmarks->fetchObject()) {
    //     array_push($pupils_marks, $response_array_marks);
    // }

    $queryattributions = "SELECT * FROM attribution_teachers";
    $requestattributions = $database_connect->query($queryattributions);
    while($response_array_attributions = $requestattributions->fetchObject()) {
        array_push($attributions, $response_array_attributions);
    }

    $queryclasses = "SELECT * FROM classes";
    $requestclasses = $database_connect->query($queryclasses);
    while($response_array_classes = $requestclasses->fetchObject()) {
        array_push($class_numbers, $response_array_classes);
    }

    // $querypupils = "SELECT * FROM pupils_info WHERE school_year='$annee'";
    // $requestpupils = $database_connect->query($querypupils);
    // while($response_array_pupils = $requestpupils->fetchObject()) {
    //     array_push($pupils, $response_array_pupils);
    // }

    $queryorders = "SELECT * FROM class_order";
    $requestorders = $database_connect->query($queryorders);
    while($response_array_orders = $requestorders->fetchObject()) {
        array_push($orders, $response_array_orders);
    }

    $querysections = "SELECT * FROM sections";
    $requestsections = $database_connect->query($querysections);
    while($response_array_sections = $requestsections->fetchObject()) {
        array_push($sections, $response_array_sections);
    }

    $queryoptions = "SELECT * FROM options";
    $requestoptions = $database_connect->query($queryoptions);
    while($response_array_options = $requestoptions->fetchObject()) {
        array_push($options, $response_array_options);
    }

    $query_fetch00 = "SELECT id_classes, cycle_id, class_id, order_id, section_id, option_id, school_year, classes_alignment FROM classes_completed WHERE school_year=? ORDER BY classes_alignment";
    $query_fetch11 = $database_connect->prepare($query_fetch00);
    $query_fetch11->execute(array($annee));
    while($query_fetch = $query_fetch11->fetchObject())
    {
        $cycle_name = find_cycle_name($query_fetch->cycle_id);
        $class_number = find_class_number($query_fetch->class_id);
        $order_name = find_order_name($query_fetch->order_id);
        $section_name = find_section_name($query_fetch->section_id);
        $option_name = find_option_name($query_fetch->option_id);
        $payess = 5;
        $courses = array();
        $total_payable = 0;

        if($class_number == 1) {
            $class_number = "1 ère";
        } else {
            $class_number = "$class_number ème";
        }

        $class = array();
        $pupils_class = array();
        $courses = array();
        // $paiements = array();
        $pupils_count_class = 0;
        $pupils_count_men = 0;
        $pupils_count_women = 0;
        $courses_count = 0;

        $query_courses_class = "SELECT * FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY total_marks ASC";
        $request_courses_class = $database_connect->prepare($query_courses_class);
        $request_courses_class->execute(array($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->section_id, $query_fetch->option_id, $query_fetch->school_year));
        while($response_courses_class = $request_courses_class->fetchObject()) {
            array_push($courses, $response_courses_class);
            $courses_count = $courses_count + 1;
        }

        $class['id_classes'] = $query_fetch->id_classes;
        $class['cycle_id'] = $cycle_name;
        $class['class_id'] = $class_number;
        $class['order_id'] = $order_name;
        $class['section_id'] = $section_name;
        $class['option_id'] = $option_name;
        $class['courses_count'] = $courses_count;
        $class['courses'] = $courses;
        $class['cycle'] = $query_fetch->cycle_id;
        $class['class'] = $query_fetch->class_id;
        $class['order'] = $query_fetch->order_id;
        $class['section'] = $query_fetch->section_id;
        $class['option'] = $query_fetch->option_id;
        $class['school_year'] = $query_fetch->school_year;
        $class['data'] = array();

        $query_pupils_class = "SELECT * FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? AND is_inactive=? ORDER BY first_name ASC, second_name ASC, last_name ASC";
        $request_pupils_class = $database_connect->prepare($query_pupils_class);
        $request_pupils_class->execute(array($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->order_id, $query_fetch->section_id, $query_fetch->option_id, $query_fetch->school_year,0));
        while($response_pupils_class = $request_pupils_class->fetchObject()) {

            $pupil = array();
            $marks = array();
            $paiements = array();
            $frais_divers = array();
            $soldes_paiements = array();
            $montants_payes = 0;
            $montant_total = 0;
            $message_soldes_t1 = 0;
            $message_soldes_t2 = 0;
            $message_soldes_t3 = 0;

            $pupil_id = $response_pupils_class->pupil_id;
            $pupils_count = $pupils_count + 1;
            $pupils_count_caisse = $pupils_count_caisse + 1;
            $pupils_count_class = $pupils_count_class + 1;

            if ($response_pupils_class->gender == 1) {
                $pupils_count_men = $pupils_count_men + 1;
                $pupils_count_male = $pupils_count_male + 1;
            } else {
                $pupils_count_women = $pupils_count_women + 1;
                $pupils_count_female = $pupils_count_female + 1;
            }

            if($response_pupils_class->paiement_category == '') {
                $modify_query = "UPDATE pupils_info SET paiement_category='$categories_response->category_id' WHERE pupil_id='$response_pupils_class->pupil_id'";
                $modify_request = $database_connect->query($modify_query);
            }

            // $querymarks = "SELECT * FROM marks_info WHERE pupil='$pupil_id'";
            // $requestmarks = $database_connect->query($querymarks);
            // while($response_array_marks = $requestmarks->fetchObject()) {
            //     array_push($marks, $response_array_marks);
            // }

            // $querypaiements = "SELECT * FROM paiements WHERE pupil_id='$pupil_id' ORDER BY paiement_id DESC";
            // $requestpaiements = $database_connect->query($querypaiements);
            // while($response_array_paiements = $requestpaiements->fetchObject()) {

            //     if ($response_array_paiements->paiement_validated == 1) {
            //         $montants_payes = $montants_payes + $response_array_paiements->montant_paye;
            //         $payess = $payess + $response_array_paiements->montant_paye;
            //         if ($response_array_paiements->total_montant != 0) {
            //             $montant_total = $response_array_paiements->total_montant;
            //             $main_total_montant = $response_array_paiements->total_montant;
            //         } else {
            //             $montant_total = $main_total_montant;
            //         }
            //     }
                
            //     array_push($paiements, $response_array_paiements);
            // }

            // $queryfrais = "SELECT * FROM frais_divers WHERE pupil_id='$pupil_id' ORDER BY frais_divers_id DESC";
            // $requestfrais = $database_connect->query($queryfrais);
            // while($response_array_frais = $requestfrais->fetchObject()) {
                
            //     array_push($frais_divers, $response_array_frais);
            // }

			// 	if($montant_total !== 0) {
            //         $s1 = $montant_total/3;
            //     } else {
            //         $s1 = $main_total_montant/3;
            //     }

			// 	$s2 = $s1 + $s1;
			// 	$s3 = $s2 + $s1;

            //     $montant = $montants_payes;
            //     if($montant != 0)
            //     {
            //         if($montant <= $s1)
            //         {
            //             if($montant == $s1)
            //             {
            //                 $message_soldes_t1 = "OK";
            //                 $message_soldes_t2 = $s1;
            //                 $message_soldes_t3 = $s1;
            //             }
            //             else
            //             {
            //                 $tr1 = $s1-$montant;
            //                 $message_soldes_t1 = "$tr1";
            //                 $message_soldes_t2 = $s1;
            //                 $message_soldes_t3 = $s1;
            //             }
            //         }

            //         if($montant > $s1 && $montant <= $s2)
            //         {
            //             if($montant == $s2)
            //             {
            //                 $message_soldes_t1 = "OK";
            //                 $message_soldes_t2 = "OK";
            //                 $message_soldes_t3 = $s1;
            //             }
            //             else
            //             {
            //                 $tr2 = $s2-$montant;
            //                 $message_soldes_t1 = "OK";
            //                 $message_soldes_t2 = "$tr2";
            //                 $message_soldes_t3 = $s1;
            //             }
            //         }

            //         if($montant > $s2)
            //         {
            //             if($montant == $s3)
            //             {
            //                 $message_soldes_t1 = "OK";
            //                 $message_soldes_t2 = "OK";
            //                 $message_soldes_t3 = "OK";
            //             }
            //             else
            //             {
            //                 $tr3 = $s3-$montant;
            //                 $message_soldes_t1 = "OK";
            //                 $message_soldes_t2 = "OK";
            //                 $message_soldes_t3 = "$tr3";
            //             }
            //         }
            //     }
            //     else
            //     {
            //         $message_soldes_t1 = $s1;
            //         $message_soldes_t2 = $s1;
            //         $message_soldes_t3 = $s1;
            //     }

            $soldes_paiements['solde'] = $montant_total-$montants_payes;
            $soldes_paiements['solde1'] = $message_soldes_t1;
            $soldes_paiements['solde2'] = $message_soldes_t2;
            $soldes_paiements['solde3'] = $message_soldes_t3;
            $soldes_paiements['montant_paye'] = $montants_payes;
                
            $pupil['pupil'] = $response_pupils_class;
            $pupil['pupil_id'] = $response_pupils_class->pupil_id;
            $pupil['paiements'] = $paiements;
            $pupil['frais_divers'] = $frais_divers;
            $pupil['soldes'] = $soldes_paiements;
            $pupil['marks'] = $marks;
            array_push($pupils_class, $pupil);
            array_push($pupils, $pupil);
        }

        $class['pupils_count'] = $pupils_count_class;
        $class['pupils_count_male'] = $pupils_count_men;
        $class['pupils_count_female'] = $pupils_count_women;
        $class['pupils'] = $pupils_class;
        $class['paye'] = $payess;
        array_push($classes, $class);
    }

    $response['classes'] = $classes;
    $response['annees'] = $annees;
    $response['cycles'] = $cycles;
    $response['class_numbers'] = $class_numbers;
    $response['orders'] = $orders;
    $response['annee'] = $annee;
    $response['pupils'] = $pupils;
    $response['marks'] = $pupils_marks;
    $response['pupils_count'] = $pupils_count;
    $response['pupils_count_paiements'] = $pupils_count_caisse;
    $response['pupils_count_male'] = $pupils_count_male;
    $response['pupils_count_female'] = $pupils_count_female;
    $response['sections'] = $sections;
    $response['options'] = $options;
    $response['montant_paye'] = $payes;
    $response['annee_scolaire'] = $annee_scolaire;
    $response['school_name'] = $school_name;
    $response['school_name_abb'] = $school_name_abb;
    $response['attributions'] = $attributions;
    $response['autres'] = $autres;
    $response['reussites'] = $reussites;
    $response['doubles'] = $doubles;
    $response['echecs'] = $echecs;
    $response['abandon'] = $abandon;
    $response['libelles'] = $libelles;
    $response['paiement_categories'] = $paiement_categories;
    echo json_encode($response);

?>