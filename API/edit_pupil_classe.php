<?php

	include '../config/dbconnect.functions.php';
	include '../config/functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$first_name_pupil = htmlspecialchars(strip_tags(trim(ucwords($_POST['first_name_pupil']))));
	$second_name_pupil = htmlspecialchars(strip_tags(trim(ucwords($_POST['second_name_pupil']))));
	$last_name_pupil = htmlspecialchars(strip_tags(trim(ucwords($_POST['last_name_pupil']))));
	$gender_pupil = htmlspecialchars(strip_tags(trim($_POST['gender_pupil'])));
	$birth_date_pupil = htmlspecialchars(strip_tags(trim($_POST['birth_date_pupil'])));
	$birth_place_pupil = htmlspecialchars(strip_tags(trim($_POST['birth_place_pupil'])));
	$father_name = htmlspecialchars(strip_tags(trim(ucwords($_POST['father_name']))));
	$mother_name = htmlspecialchars(strip_tags(trim(ucwords($_POST['mother_name']))));
	$parents_alive = htmlspecialchars(strip_tags(trim($_POST['parents_alive'])));
	$parents_state = htmlspecialchars(strip_tags(trim($_POST['parents_state'])));
	$lives_with = htmlspecialchars(strip_tags(trim($_POST['lives_with'])));
	$father_work_pupil = htmlspecialchars(strip_tags(trim($_POST['father_work_pupil'])));
	$mother_work_pupil = htmlspecialchars(strip_tags(trim($_POST['mother_work_pupil'])));
	$cycle_school_pupil = htmlspecialchars(strip_tags(trim($_POST['cycle_school_pupil'])));
	$class_school_pupil = htmlspecialchars(strip_tags(trim($_POST['class_school_pupil'])));
	$class_order_pupil = htmlspecialchars(strip_tags(trim($_POST['class_order_pupil'])));
	$class_section_pupil = htmlspecialchars(strip_tags(trim($_POST['class_section_pupil'])));
	$class_option_pupil = htmlspecialchars(strip_tags(trim($_POST['class_option_pupil'])));
	$school_year_pupil = htmlspecialchars(strip_tags(trim($_POST['school_year_pupil'])));
	$email_address_pupil = htmlspecialchars(strip_tags(trim($_POST['email_address_pupil'])));
	$physical_address_pupil = htmlspecialchars(strip_tags(trim($_POST['physical_address_pupil'])));
	$contact_1_pupil = htmlspecialchars(strip_tags(trim($_POST['contact_1_pupil'])));
	$contact_2_pupil = htmlspecialchars(strip_tags(trim($_POST['contact_2_pupil'])));
	$contact_3_pupil = htmlspecialchars(strip_tags(trim($_POST['contact_3_pupil'])));
	$contact_4_pupil = htmlspecialchars(strip_tags(trim($_POST['contact_4_pupil'])));
	$id_number = htmlspecialchars(strip_tags(trim($_POST['id_number'])));
	$perm_number = htmlspecialchars(strip_tags(trim($_POST['perm_number'])));
	$nat = htmlspecialchars(strip_tags(trim($_POST['nationality'])));
	$statut_scolaire = htmlspecialchars(strip_tags(trim($_POST['statut_scolaire'])));


	$classes_alignment = "$cycle_school_pupil $class_school_pupil $class_order_pupil $class_section_pupil $class_option_pupil $school_year_pupil";

	insert_pupil($first_name_pupil, $second_name_pupil, $last_name_pupil, $gender_pupil, $birth_date_pupil, $birth_place_pupil, $father_name, $mother_name, $parents_alive, $parents_state, $father_work_pupil, $mother_work_pupil, $lives_with, $cycle_school_pupil, $class_school_pupil, $class_order_pupil, $class_section_pupil, $class_option_pupil, $school_year_pupil, $email_address_pupil, $physical_address_pupil, $contact_1_pupil, $contact_2_pupil, $contact_3_pupil, $contact_4_pupil, randomUserId(10), $id_number, $perm_number, $nat, $statut_scolaire);
	// insert_class_completed($cycle_school_pupil, $class_school_pupil, $class_order_pupil, $class_section_pupil, $class_option_pupil, $school_year_pupil, $classes_alignment);

	// $qq00 = "SELECT pupil_id, first_name, second_name, last_name, gender, father_names, mother_names FROM pupils_info WHERE first_name=? AND second_name=? AND gender=? AND father_names=? AND mother_names=?";
	// $qq11 = $database_connect->prepare($qq00);
	// $qq11->execute(array($first_name_pupil, $second_name_pupil, $last_name_pupil, $gender_pupil, $father_name, $mother_name));
	// $qq = $qq11->fetchObject();

	// $queryLastEntry = "SELECT pupil_id FROM pupils_info ORDER BY pupil_id DESC LIMIT 0, 1";
	// $requestLastEntry = $database_connect->query($queryLastEntry);
	// $responseLastEntry = $requestLastEntry->fetchObject();

	// $query = "UPDATE pupils_info SET pupil_id_bis=? WHERE first_name=? AND second_name=? AND last_name=? AND gender=? AND father_names=? AND mother_names=? AND school_year=?";
	// $request = $database_connect->prepare($query);
	// $request->execute(array($responseLastEntry->pupil_id, $first_name_pupil, $second_name_pupil, $last_name_pupil, $gender_pupil, $father_name, $mother_name, $school_year_pupil));

	echo json_encode("1");
?>