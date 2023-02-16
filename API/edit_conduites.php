<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$response = array();
	$date_work = date('Y') . "-" . date('m') . "-" . date("d");
	$all_marks = array();
	$success = '';

	$conduites = $_POST['conduites'];

	foreach($conduites as $key => $value){

		$periode = $value['periode'];
		$year_school = $value['school_year'];
		$conduite = $value['conduite'];
		$pupil_id = $value['pupil_id'];

		if($conduite != '') {
			$query = "SELECT pupil_id, main_conduite, periode, school_year, COUNT(*) AS count_conduite_exist FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
			$request = $database_connect->prepare($query);
			$request->execute(array($pupil_id, $periode, $year_school));
			$response_array = $request->fetchObject();
	
			if ($response_array->count_conduite_exist == 0) {
				$insert00 = "INSERT INTO conduite(pupil_id, main_conduite, periode, school_year) 
				VALUES(?, ?, ?, ?)";
				$insert = $database_connect->prepare($insert00);
				$insert->execute(array($pupil_id, $conduite, $periode, $year_school));
	
				$success = '1';
			} else {
				$edit00 = "UPDATE conduite SET  main_conduite=? WHERE pupil_id=? AND periode=? AND school_year=?";
				$edit = $database_connect->prepare($edit00);
				$edit->execute(array($conduite, $pupil_id, $periode, $year_school));
				$success = '2';
			}
		} else {
			$success = '3';
		}
	}

	$response['success'] = $success;
	echo json_encode($response);

?>