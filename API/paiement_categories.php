<?php

    include '../config/dbconnect.functions.php';
    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $paiement_categories = array();
    $school_year = htmlspecialchars(strip_tags(trim(ucwords($_POST['school_year']))));

	// 	$query_countc = "SELECT school_year, category_name, COUNT(*) AS count_paiement_categories FROM paiement_categories WHERE school_year=? AND category_name=?";
	// $request_countc = $database_connect->prepare($query_countc);
	// $request_countc->execute(array($school_year, "0"));
	// $response_countc = $request_countc->fetchObject();

	// if ($response_countc->count_paiement_categories == 0) {
    //     $query = "INSERT INTO paiement_categories(category_name, category_amount, school_year)
	// 					VALUES(?, ?, ?) ";
	// 		$request = $database_connect->prepare($query);
	// 		$request->execute(array("0", "0", $school_year));
    // }

    $query_count = "SELECT school_year, COUNT(*) AS count_paiement_categories FROM paiement_categories WHERE school_year=?";
	$request_count = $database_connect->prepare($query_count);
	$request_count->execute(array($school_year));
	$response_count = $request_count->fetchObject();

	if ($response_count->count_paiement_categories != 0) {
		$query = "SELECT * FROM paiement_categories WHERE school_year=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($school_year));
        while($response_categories = $request->fetchObject()) {
            array_push($paiement_categories, $response_categories);
        }
        
        $response['paiement_categories'] = $paiement_categories;
        echo json_encode($response);

	} else {
        $response['paiement_categories'] = $paiement_categories;
        echo json_encode($response);
    }
    
?>