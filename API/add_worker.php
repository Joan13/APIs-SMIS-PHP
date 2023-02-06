<?php

    include '../config/dbconnect.functions.php';
    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $employees = array();

    $first_name = htmlspecialchars(strip_tags(trim(ucwords($_POST['first_name']))));
    $second_name = htmlspecialchars(strip_tags(trim(ucwords($_POST['second_name']))));
    $last_name = htmlspecialchars(strip_tags(trim(ucwords($_POST['last_name']))));
    $gender = htmlspecialchars(strip_tags(trim(ucwords($_POST['gender']))));
    $poste = htmlspecialchars(strip_tags(trim(ucwords($_POST['poste']))));
    $free_day_1 = htmlspecialchars(strip_tags(trim(ucwords($_POST['free_day_1']))));
    $free_day_2 = htmlspecialchars(strip_tags(trim(ucwords($_POST['free_day_2']))));
    $user_name = htmlspecialchars(strip_tags(trim($_POST['user_name'])));
    $school_year = htmlspecialchars(strip_tags(trim(ucwords($_POST['school_year']))));

    $query_count = "SELECT first_name, second_name, last_name, user_name, gender, poste, worker_year, COUNT(*) AS count_worker_exists FROM workers_info WHERE first_name=? AND second_name=? AND last_name=? AND user_name=? AND gender=? AND poste=? AND worker_year=?";
	$request_count = $database_connect->prepare($query_count);
	$request_count->execute(array($first_name, $second_name, $last_name, $user_name, $gender, $poste, $school_year));
	$response_count = $request_count->fetchObject();

	if ($response_count->count_worker_exists == 0) {
		$query = "INSERT INTO workers_info(first_name, second_name, last_name, user_name, gender, poste, worker_year, photo, civil_state, children_number, mariage_state, birth_date, birth_place, email_user, secondary_in, graduated_in, bachelor_in, masters_in, phd_in, user_address, user_phone_1, user_phone_2, user_phone_3, user_phone_4, free_day_1, free_day_2, user_password) 
        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$request = $database_connect->prepare($query);
		$request->execute(array($first_name, $second_name, $last_name, $user_name, $gender, $poste, $school_year, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", $free_day_1, $free_day_2, sha1('000000')));

        $query_employees = "SELECT * FROM workers_info WHERE worker_year=?";
        $request_employees = $database_connect->prepare($query_employees);
        $request_employees->execute(array($school_year));
        while($response_employees = $request_employees->fetchObject()) {
            array_push($employees, $response_employees);
        }

        $response['success'] = '1';
        $response['employees'] = $employees;
        echo json_encode($response);
	} else {
        $response['success'] = '2';
        $response['employees'] = $employees;
        echo json_encode($response);
    }
    
?>