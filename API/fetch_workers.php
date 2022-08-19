<?php

    include '../config/dbconnect.functions.php';
    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $employees = array();
    $school_year = htmlspecialchars(strip_tags(trim(ucwords($_POST['school_year']))));

    $query_count = "SELECT worker_year, COUNT(*) AS count_workers_exists FROM workers_info WHERE worker_year=?";
	$request_count = $database_connect->prepare($query_count);
	$request_count->execute(array($school_year));
	$response_count = $request_count->fetchObject();

	if ($response_count->count_workers_exists != 0) {
		$query = "SELECT * FROM workers_info WHERE worker_year=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($school_year));
        while($response_employees = $request->fetchObject()) {
            array_push($employees, $response_employees);
        }

        
        $response['employees'] = $employees;
        echo json_encode($response);

	} else {

        $response['success'] = '1';
        $response['employees'] = $employees;
        echo json_encode($response);
    }
    
?>