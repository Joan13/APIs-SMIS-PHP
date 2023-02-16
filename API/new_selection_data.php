<?php

	require_once("../config/dbconnect.functions.php");

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$selection_data = $_POST['selection_data'];

	foreach($marks as $key => $value){

		$selection_id = $value['selection_id'];
		$data_id = $value['data_id'];

		if($data_id != "") {
			$query = "INSERT INTO selection_data(selction_id, data_id)
					VALUES(?, ?) ";
			$request = $database_connect->prepare($query);
			$request->execute(array($selection_id, $data_id));
		}
	}
		

	echo json_encode("1");

?>