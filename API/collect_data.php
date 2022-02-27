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
    $attributions = array();
    $annee_scolaire = array();
    $autres = array();
    $pupils = array();
    $pupils_count = 0;
    $reussites = 0;
    $doubles = 0;
    $echecs = 0;
    $abandon = 0;
    $paiements = array();
    $frais_divers = array();

    $annee = $_POST['annee'];

    if($annee == "") {
        $query = "SELECT * FROM school_years ORDER BY year_id DESC";
        $request = $database_connect->query($query);
        $response_array_years = $request->fetchObject();

        $annee = $response_array_years->year_id;
        $annee_scolaire = $response_array_years;
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

        $query_paiements = "SELECT * FROM paiements WHERE school_year='$annee'";
        $request_paiements = $database_connect->query($query_paiements);
        while($response_array_paiments = $request_paiements->fetchObject()) {
            $verify_query = "SELECT paiement_id, COUNT(*) AS count_paiement_exist FROM paiements WHERE paiement_id=?";
            $verify_request = $database_connect_export->prepare($verify_query);
            $verify_request->execute(array($response_array_paiments->paiement_id));
            $verify_response = $verify_request->fetchObject();
        
            if ($verify_response->count_paiement_exist == 0) {
                $insert_query = "INSERT INTO paiements(paiement_id, pupil_id, montant_paye, montant_text, libelle, total_montant, school_year, paiement_validated, date_creation)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $insert_request = $database_connect_export->prepare($insert_query);
                $insert_request->execute(array($response_array_paiments->paiement_id, $response_array_paiments->pupil_id, $response_array_paiments->montant_paye, $response_array_paiments->montant_text, $response_array_paiments->libelle, $response_array_paiments->total_montant, $response_array_paiments->school_year, $response_array_paiments->paiement_validated, $response_array_paiments->date_creation));

                array_push($paiements, $response_array_paiments);
            }
    }


    $query_frais_divers = "SELECT * FROM frais_divers WHERE school_year='$annee'";
    $request_frais_divers = $database_connect->query($query_frais_divers);
    while($response_array_frais_divers = $request_frais_divers->fetchObject()) {
        $verify_query = "SELECT frais_divers_id, COUNT(*) AS count_paiement_exist FROM frais_divers WHERE frais_divers_id=?";
        $verify_request = $database_connect_export->prepare($verify_query);
        $verify_request->execute(array($response_array_frais_divers->frais_divers_id));
        $verify_response = $verify_request->fetchObject();

        if ($verify_response->count_paiement_exist == 0) {
            $insert_query = "INSERT INTO frais_divers(frais_divers_id, pupil_id, libelle, montant, school_year, date_entry, deleted, visible_print)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_request = $database_connect_export->prepare($insert_query);
            $insert_request->execute(array($response_array_frais_divers->frais_divers_id, $response_array_frais_divers->pupil_id, $response_array_frais_divers->libelle, $response_array_frais_divers->montant, $response_array_frais_divers->school_year, $response_array_frais_divers->date_entry, $response_array_frais_divers->deleted, $response_array_frais_divers->visible_print));

            array_push($frais_divers, $response_array_frais_divers);
        }
    }

    
    $querypupils = "SELECT * FROM pupils_info WHERE school_year='$annee'";
    $requestpupils = $database_connect->query($querypupils);
    while($response_array_pupils = $requestpupils->fetchObject()) {
        $verify_query = "SELECT pupil_id, COUNT(*) AS count_pupil_exist FROM pupils_info WHERE pupil_id=?";
        $verify_request = $database_connect_export->prepare($verify_query);
        $verify_request->execute(array($response_array_pupils->pupil_id));
        $verify_response = $verify_request->fetchObject();

        if ($verify_response->count_pupil_exist == 0) {
            $classes_alignment = $response_array_pupils->cycle_school ." ". $response_array_pupils->class_school ." ". $response_array_pupils->class_order ." ". $response_array_pupils->class_section ." ". $response_array_pupils->class_option ." ". $response_array_pupils->school_year;

            insert_pupil_export($response_array_pupils->pupil_id,$response_array_pupils->first_name, $response_array_pupils->second_name, $response_array_pupils->last_name, $response_array_pupils->gender, $response_array_pupils->birth_date, $response_array_pupils->birth_place, $response_array_pupils->father_names, $response_array_pupils->mother_names, $response_array_pupils->parents_alive, $response_array_pupils->parents_state, $response_array_pupils->father_principal_work, $response_array_pupils->mother_principal_work, $response_array_pupils->lives_with, $response_array_pupils->cycle_school, $response_array_pupils->class_school, $response_array_pupils->class_order, $response_array_pupils->class_section, $response_array_pupils->class_option, $response_array_pupils->school_year, $response_array_pupils->email_address, $response_array_pupils->physical_address, $response_array_pupils->contact_phone_1, $response_array_pupils->contact_phone_2, $response_array_pupils->contact_phone_3, $response_array_pupils->contact_phone_4, randomUserId(10), $response_array_pupils->identification_number, $response_array_pupils->permanent_number, $response_array_pupils->nationality, $response_array_pupils->statut_scolaire);
            insert_class_completed_export($response_array_pupils->cycle_school, $response_array_pupils->class_school, $response_array_pupils->class_order, $response_array_pupils->class_section, $response_array_pupils->class_option, $response_array_pupils->school_year, $classes_alignment);
            
            array_push($pupils, $response_array_pupils);
        }
    }

    // $queryorders = "SELECT * FROM class_order";
    // $requestorders = $database_connect->query($queryorders);
    // while($response_array_orders = $requestorders->fetchObject()) {
    //     array_push($orders, $response_array_orders);
    // }

    // $querysections = "SELECT * FROM sections";
    // $requestsections = $database_connect->query($querysections);
    // while($response_array_sections = $requestsections->fetchObject()) {
    //     array_push($sections, $response_array_sections);
    // }

    // $queryoptions = "SELECT * FROM options";
    // $requestoptions = $database_connect->query($queryoptions);
    // while($response_array_options = $requestoptions->fetchObject()) {
    //     array_push($options, $response_array_options);
    // }

    // $query_fetch00 = "SELECT id_classes, cycle_id, class_id, order_id, section_id, option_id, school_year, classes_alignment FROM classes_completed WHERE school_year=? ORDER BY classes_alignment";
    // $query_fetch11 = $database_connect->prepare($query_fetch00);
    // $query_fetch11->execute(array($annee));
    // while($query_fetch = $query_fetch11->fetchObject())
    // {
    //     $cycle_name = find_cycle_name($query_fetch->cycle_id);
    //     $class_number = find_class_number($query_fetch->class_id);
    //     $order_name = find_order_name($query_fetch->order_id);
    //     $section_name = find_section_name($query_fetch->section_id);
    //     $option_name = find_option_name($query_fetch->option_id);

    //     if($class_number == 1)
    //     {
    //         $class_number = "1 ère";
    //     }
    //     else
    //     {
    //         $class_number = "$class_number ème";
    //     }

    //     $class = array();
    //     $class['id_classes'] = $query_fetch->id_classes;
    //     $class['cycle_id'] = $cycle_name;
    //     $class['class_id'] = $class_number;
    //     $class['order_id'] = $order_name;
    //     $class['section_id'] = $section_name;
    //     $class['option_id'] = $option_name;
    //     $class['pupils_count'] = nbr_pupils_class($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->order_id, $query_fetch->section_id, $query_fetch->option_id, $query_fetch->school_year);
    //     $class['pupils_count_male'] = nbr_pupils_class_male($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->order_id, $query_fetch->section_id, $query_fetch->option_id, $query_fetch->school_year);
    //     $class['pupils_count_female'] = nbr_pupils_class_female($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->order_id, $query_fetch->section_id, $query_fetch->option_id, $query_fetch->school_year);
    //     $class['cycle'] = $query_fetch->cycle_id;
    //     $class['class'] = $query_fetch->class_id;
    //     $class['order'] = $query_fetch->order_id;
    //     $class['section'] = $query_fetch->section_id;
    //     $class['option'] = $query_fetch->option_id;
    //     $class['school_year'] = $query_fetch->school_year;

    //     array_push($classes, $class);
    // }

    $response['paiements'] = $paiements;
    $response['frais_divers'] = $frais_divers;
    $response['pupils'] = $pupils;
    // $response['classes'] = $classes;
    // $response['annees'] = $annees;
    // $response['cycles'] = $cycles;
    // $response['class_numbers'] = $class_numbers;
    // $response['orders'] = $orders;
    // $response['annee'] = $annee;
    // $response['pupils'] = $pupils;
    // $response['pupils_count'] = $pupils_count;
    // $response['sections'] = $sections;
    // $response['options'] = $options;
    // $response['annee_scolaire'] = $annee_scolaire;
    // $response['school_name'] = $school_name;
    // $response['school_name_abb'] = $school_name_abb;
    // $response['attributions'] = $attributions;
    // $response['autress'] = $autres;
    // $response['reussites'] = $reussites;
    // $response['doubles'] = $doubles;
    // $response['echecs'] = $echecs;
    // $response['abandon'] = $abandon;


    echo json_encode($response);


?>