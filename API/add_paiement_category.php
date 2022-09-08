<?php

require_once("../config/dbconnect.functions.php");

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$response = array();
	$paiement_categories = array();
    $school_year = htmlspecialchars(strip_tags(trim(ucwords($_POST['school_year']))));
	$category_name = htmlspecialchars(strip_tags(trim(ucwords($_POST['category_name']))));
	$category_amount = htmlspecialchars(strip_tags(trim(ucwords($_POST['category_amount']))));

	$query_count = "SELECT school_year, COUNT(*) AS count_paiement_categories FROM paiement_categories WHERE school_year=? AND category_name=? AND category_amount=?";
	$request_count = $database_connect->prepare($query_count);
	$request_count->execute(array($school_year, $category_name, $category_amount));
	$response_count = $request_count->fetchObject();

	if ($response_count->count_paiement_categories == 0) {
		$query = "INSERT INTO paiement_categories(category_name, category_amount, school_year)
							VALUES(?, ?, ?) ";
		$request = $database_connect->prepare($query);
		$request->execute(array($category_name, $category_amount, $school_year));

		$queryc = "SELECT * FROM paiement_categories WHERE school_year=?";
        $requestc = $database_connect->prepare($queryc);
        $requestc->execute(array($school_year));
        while($response_categoriesc = $requestc->fetchObject()) {
            array_push($paiement_categories, $response_categoriesc);
        }
        
        $response['paiement_categories'] = $paiement_categories;
        echo json_encode($response);
	} else {
		$response['paiement_categories'] = $paiement_categories;
        echo json_encode($response);
	}

?>