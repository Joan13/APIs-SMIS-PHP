<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$response = array();
	$pupil_id = htmlspecialchars(strip_tags(trim($_POST['pupil'])));
	$category = htmlspecialchars(strip_tags(trim($_POST["category"])));
	

	if($pupil_id == '' || $category == "") {
		$response['success'] = "0";
		echo json_encode($response);
	}
	else {
		$modify_query = "UPDATE pupils_info SET paiement_category='$category' WHERE pupil_id='$pupil_id'";
		$modify_request = $database_connect->query($modify_query);

		if($category != "0") {
			$query = "SELECT * FROM paiement_categories WHERE category_id=?";
			$request = $database_connect->prepare($query);
			$request->execute(array($category));
			$response_category = $request->fetchObject();

			$modify_query = "UPDATE paiements SET total_montant='$response_category->category_amount' WHERE pupil_id='$pupil_id'";
			$modify_request = $database_connect->query($modify_query);
		}

		$response['success'] = "1";
		echo json_encode($response);
	}

?>