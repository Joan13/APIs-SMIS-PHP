<?php

    include '../config/dbconnect.functions.php';
    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $tricks = array();
    $school_year = htmlspecialchars(strip_tags(trim(ucwords($_POST['school_year']))));

    $query_count = "SELECT school_year, COUNT(*) AS count_tricks_exists FROM tricks_timetable WHERE school_year=?";
	$request_count = $database_connect->prepare($query_count);
	$request_count->execute(array($school_year));
	$response_count = $request_count->fetchObject();

	if ($response_count->count_tricks_exists != 0) {
		$query = "SELECT * FROM tricks_timetable WHERE school_year=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($school_year));
        while($response_tricks = $request->fetchObject()) {
            array_push($tricks, $response_tricks);
        }
        
        $response['tricks'] = $tricks;
        echo json_encode($response);

	} else {
        $response['tricks'] = $tricks;
        echo json_encode($response);
    }
    
?>