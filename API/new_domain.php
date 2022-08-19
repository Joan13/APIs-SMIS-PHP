<?php

	include '../config/dbconnect.functions.php';
	include '../config/functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$response = array();
	$domains = array();
	$sub_domains = array();

	$cycle_id = htmlspecialchars(strip_tags(trim($_POST['cycle_id'])));
	$class_id = htmlspecialchars(strip_tags(trim($_POST['class_id'])));
	$section_id = htmlspecialchars(strip_tags(trim($_POST['section_id'])));
	$option_id = htmlspecialchars(strip_tags(trim($_POST['option_id'])));
	$school_year = htmlspecialchars(strip_tags(trim($_POST['school_year'])));
	$domain_name = htmlspecialchars(strip_tags(trim($_POST['domain_name'])));

	$insert_domain = "INSERT INTO courses_domains(cycle_id, class_id, section_id, school_year, option_id, domain_name) 
    VALUES(?, ?, ?, ?, ?, ?)";
    $rinsert_domain = $database_connect->prepare($insert_domain);
    $rinsert_domain->execute(array($cycle_id, $class_id, $section_id, $school_year, $option_id, $domain_name));

	// $query_count_domains = "SELECT school_year, cycle_id, class_id, section_id, option_id, COUNT(*) AS count_domains_exist FROM courses_domains WHERE school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=?";
    // $request_count_domains = $database_connect->prepare($query_count_domains);
    // $request_count_domains->execute(array($school_year, $cycle_id, $class_id, $section_id, $option_id));
    // $response_count_domains = $request_count_domains->fetchObject();

    // if($response_count_domains->count_domains_exist != 0) {
    //     $query_domains = "SELECT * FROM courses_domains WHERE school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=? ORDER BY domain_id ASC";
    //     $request_domains = $database_connect->prepare($query_domains);
    //     $request_domains->execute(array($school_year, $cycle_id, $class_id, $section_id, $option_id));
    //     while($response_domains = $request_domains->fetchObject()) {
    //         array_push($domains, $response_domains);
            
    //         $query_count_sub_domains = "SELECT domain_id, COUNT(*) AS count_sub_domains_exist FROM courses_sub_domains WHERE domain_id=?";
    //         $request_count_sub_domains = $database_connect->prepare($query_count_sub_domains);
    //         $request_count_sub_domains->execute(array($response_domains->domain_id));
    //         $response_count_sub_domains = $request_count_sub_domains->fetchObject();

    //         if($response_count_sub_domains->count_sub_domains_exist != 0) {
    //             $query_sub_domains = "SELECT * FROM courses_sub_domains WHERE domain_id=? ORDER BY sub_domain_id ASC";
    //             $request_sub_domains = $database_connect->prepare($query_sub_domains);
    //             $request_sub_domains->execute(array($response_domains->domain_id));
    //             while($response_sub_domains = $request_sub_domains->fetchObject()) {
    //                 array_push($sub_domains, $response_sub_domains);
    //             }
    //         }
    //     }
    // }

	// $domains['sub_domains'] = $sub_domains;
	// $response['domains'] = $domains;
	echo json_encode('1');
	
?>