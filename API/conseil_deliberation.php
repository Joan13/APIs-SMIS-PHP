<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$pupil_id = htmlspecialchars(strip_tags(trim($_POST['pupil_id'])));
	$main_conseil = htmlspecialchars(strip_tags(trim($_POST["main_conseil"])));
	$school_year = htmlspecialchars(strip_tags(trim($_POST['school_year'])));

	

	if($pupil_id == '' || $main_conseil == "" || $school_year == "")
	{

	}
	else
	{
		$sel_query = "SELECT pupil_id, COUNT(*) AS count_deliberation FROM conseil_deliberation WHERE pupil_id='$pupil_id'";
		$sel_req = $database_connect->query($sel_query);
		$sel_res = $sel_req->fetchObject();
		if ($sel_res->count_deliberation == 0) {
			$modify_query2 = "INSERT INTO conseil_deliberation(pupil_id, main_conseil, school_year) 
					VALUES(?, ?, ?)";
			$modify_request2 = $database_connect->prepare($modify_query2);
			$modify_request2->execute(array($pupil_id, $main_conseil, $school_year));
		} else {
			$modify_query3 = "UPDATE conseil_deliberation SET main_conseil='$main_conseil' WHERE pupil_id='$pupil_id'";
			$modify_request3 = $database_connect->query($modify_query3);
		}
	}

	echo json_encode("1");

?>