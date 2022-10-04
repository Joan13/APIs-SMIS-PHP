<?php

	require_once("../config/dbconnect.functions.php");

	header("Access-Control-Allow-Origin: *");
	$rest_json = file_get_contents("php://input");
	$_POST = json_decode($rest_json, true);

	$pupil_id = htmlspecialchars(strip_tags(trim($_POST['pupil_id'])));
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
	$perm_number = htmlspecialchars(strip_tags(trim($_POST['permanent_number'])));
	$nationality = htmlspecialchars(strip_tags(trim($_POST['nationality'])));
	$id_number = htmlspecialchars(strip_tags(trim($_POST['identification_number'])));
	$email_address_pupil = htmlspecialchars(strip_tags(trim($_POST['email_address_pupil'])));
	$physical_address_pupil = htmlspecialchars(strip_tags(trim($_POST['physical_address_pupil'])));
	$contact_1_pupil = htmlspecialchars(strip_tags(trim($_POST['contact_1_pupil'])));
	$contact_2_pupil = htmlspecialchars(strip_tags(trim($_POST['contact_2_pupil'])));
	$contact_3_pupil = htmlspecialchars(strip_tags(trim($_POST['contact_3_pupil'])));
	$contact_4_pupil = htmlspecialchars(strip_tags(trim($_POST['contact_4_pupil'])));
	$is_inactive = htmlspecialchars(strip_tags(trim($_POST['is_inactive'])));

	$query = "UPDATE pupils_info SET first_name=?, second_name=?, last_name=?, gender=?, birth_date=?, birth_place=?, father_names=?, mother_names=?, parents_alive=?, parents_state=?, father_principal_work=?, mother_principal_work=?, lives_with=?, cycle_school=?, class_school=?, class_order=?, class_section=?, class_option=?, school_year=?, physical_address=?, email_address=?, contact_phone_1=?, contact_phone_2=?, contact_phone_3=?, contact_phone_4=?, is_inactive=?, identification_number=?, permanent_number=?, nationality=? WHERE pupil_id=?";
	$request = $database_connect->prepare($query);
	$request->execute(array($first_name_pupil, $second_name_pupil, $last_name_pupil, $gender_pupil, $birth_date_pupil, $birth_place_pupil, $father_name, $mother_name, $parents_alive, $parents_state, $father_work_pupil, $mother_work_pupil, $lives_with, $cycle_school_pupil, $class_school_pupil, $class_order_pupil, $class_section_pupil, $class_option_pupil, $school_year_pupil, $physical_address_pupil, $email_address_pupil, $contact_1_pupil, $contact_2_pupil, $contact_3_pupil, $contact_4_pupil, $is_inactive, $id_number, $perm_number, $nationality, $pupil_id));

	echo json_encode("1");

?>