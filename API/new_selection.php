<?php

	require_once("../config/dbconnect.functions.php");

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$selection_name = htmlspecialchars(strip_tags(trim($_POST['selection_name'])));
	$selection_type = htmlspecialchars(strip_tags(trim($_POST['selection_type'])));
	$selection_privacy = htmlspecialchars(strip_tags(trim($_POST['selection_privacy'])));
	$user_id = htmlspecialchars(strip_tags(trim($_POST['user_id'])));
	$school_year = htmlspecialchars(strip_tags(trim($_POST['school_year'])));
	$selection_data = $_POST['selection_data'];

	$response = array();
	$uccess = '';

	$query = "INSERT INTO selections(selection_name, selection_type, selection_privacy, user_id, school_year)
				VALUES('$selection_name', '$selection_type', '$selection_privacy', '$user_id', '$school_year')";
	$database_connect->exec($query);

	$selection_id = $database_connect->lastInsertId();
	
	foreach($selection_data as $value){
		$select_count = "SELECT selection_id, data_id, COUNT(*) AS count_data FROM selection_data WHERE selection_id='$selection_id' AND data_id='$value'";
		$request_count = $database_connect->query($select_count);
		$response_count = $request_count->fetchObject();

		
		if($response_count->count_data == 0) {
			$query = "INSERT INTO selection_data(selection_id, data_id)
					VALUES(?, ?) ";
			$request = $database_connect->prepare($query);
			$request->execute(array($selection_id, $value));

			$success = "1";
		}
	}

	$response['success'] = $success;
	echo json_encode($response);

?>