<?php

    include '../config/dbconnect.functions.php';
    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $libelles = array();
    $school_year = htmlspecialchars(strip_tags(trim(ucwords($_POST['school_year']))));

    $query_count = "SELECT school_year, COUNT(*) AS count_libelles FROM libelles WHERE school_year=?";
	$request_count = $database_connect->prepare($query_count);
	$request_count->execute(array($school_year));
	$response_count = $request_count->fetchObject();

	if ($response_count->count_libelles != 0) {
		$query = "SELECT * FROM libelles WHERE school_year=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($school_year));
        while($response_libelles = $request->fetchObject()) {
            array_push($libelles, $response_libelles);
        }
        
        $response['libelles'] = $libelles;
        echo json_encode($response);

	} else {
        $response['libelles'] = $libelles;
        echo json_encode($response);
    }
    
?>