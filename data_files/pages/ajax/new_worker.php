<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/home.secretor.functions.php';

	// $full_name = htmlspecialchars(strip_tags(trim($_POST['full_name'])));
	// $gender = htmlspecialchars(strip_tags(trim($_POST['gender'])));
	// $birth_date = htmlspecialchars(strip_tags(trim($_POST['birth_date'])));
	// $birth_place = htmlspecialchars(strip_tags(trim($_POST['birth_place'])));
	// $poste = htmlspecialchars(strip_tags(trim($_POST['select_poste'])));
	// $civil_state = htmlspecialchars(strip_tags(trim($_POST['civil_state'])));
	// $mariage_state = htmlspecialchars(strip_tags(trim($_POST['mariage_state'])));
	// $children_number = htmlspecialchars(strip_tags(trim($_POST['children_number'])));
	// $secondary_in = htmlspecialchars(strip_tags(trim($_POST['secondary_in'])));
	// $graduated_in = htmlspecialchars(strip_tags(trim($_POST['graduated_in'])));
	// $bachelor_in = htmlspecialchars(strip_tags(trim($_POST['bachelor_in'])));
	// $masters_in = htmlspecialchars(strip_tags(trim($_POST['masters_in'])));
	// $phd_in = htmlspecialchars(strip_tags(trim($_POST['phd_in'])));
	// $email_address = htmlspecialchars(strip_tags(trim($_POST['email_address'])));
	// $physical_address = htmlspecialchars(strip_tags(trim($_POST['physical_address'])));
	// $contact_1 = htmlspecialchars(strip_tags(trim($_POST['contact_1'])));
	// $contact_2 = htmlspecialchars(strip_tags(trim($_POST['contact_2'])));
	// $contact_3 = htmlspecialchars(strip_tags(trim($_POST['contact_3'])));
	// $contact_4 = htmlspecialchars(strip_tags(trim($_POST['contact_4'])));

	// insert_worker($first_name, $second_name, $last_name, $gender, $birth_date, $birth_place, $poste, $civil_state, $mariage_state, $children_number, $secondary_in, $graduated_in, $bachelor_in, $masters_in, $phd_in, $email_address, $physical_address, $contact_1, $contact_2, $contact_3, $contact_4);

	$worker_name = htmlspecialchars(strip_tags(trim($_POST['worker_names'])));
	$gender_worker = htmlspecialchars(strip_tags(trim($_POST['gender_worker'])));
	$todo_worker = htmlspecialchars(strip_tags(trim($_POST['todo_worker'])));
	$worker_year = htmlspecialchars(strip_tags(trim($_POST['worker_year'])));

	$query_count = "SELECT full_name, gender, poste, worker_year, COUNT(*) AS count_worker_exists FROM workers_info WHERE full_name=? AND gender=? AND poste=? AND worker_year=?";
	$request_count = $database_connect->prepare($query_count);
	$request_count->execute(array($worker_name, $gender_worker, $todo_worker, $worker_year));
	$response_count = $request_count->fetchObject();

	if ($response_count->count_worker_exists == 0) {
		$query = "INSERT INTO workers_info(full_name, gender, poste, worker_year, photo, civil_state, children_number, mariage_state, birth_date, birth_place, email_user, secondary_in, graduated_in, bachelor_in, masters_in, phd_in, user_address, user_phone_1, user_phone_2, user_phone_3, user_phone_4, user_password) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$request = $database_connect->prepare($query);
		$request->execute(array($worker_name, $gender_worker, $todo_worker, $worker_year, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", sha1('000000')));
	}

?>