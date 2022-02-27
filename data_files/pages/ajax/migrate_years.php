<?php

    include '../../config/dbconnect.functions.php';

	function find_pupil_sum_main_marks($pupil_id, $school_year){
		global $database_connect;
    	$exist00 = "SELECT pupil, school_year, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($pupil_id, $school_year));
    	$exist = $exist11->fetchObject();

    	if($exist->count_marks_exist != 0)
    	{
    		$request = $database_connect->prepare("SELECT SUM(main_marks) AS sum_main_marks FROM marks_info WHERE pupil=? AND school_year=?");
			$request->execute(array($pupil_id, $school_year));
			$response = $request->fetchObject();

			return $response->sum_main_marks;
    	}
	}

	function find_pupil_sum_total_marks($pupil_id, $school_year){
		global $database_connect;
    	$exist00 = "SELECT pupil, school_year, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($pupil_id, $school_year));
    	$exist = $exist11->fetchObject();

    	if($exist->count_marks_exist != 0)
    	{
    		$request = $database_connect->prepare("SELECT SUM(total_marks) AS sum_total_marks FROM marks_info WHERE pupil=? AND school_year=?");
			$request->execute(array($pupil_id, $school_year));
			$response = $request->fetchObject();

			return $response->sum_total_marks;
    	}
	}

	function insert_pupil($arg0, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24, $arg25)
	{
		global $database_connect;
		$array_insert = array($arg0, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24,$arg25);
		$query = "INSERT INTO pupils_info(pupil_id_bis, first_name, second_name, last_name, gender, birth_date, birth_place, father_names, mother_names, parents_alive, parents_state, father_principal_work, mother_principal_work, lives_with, cycle_school, class_school, class_order, class_section, class_option, school_year, email_address, physical_address, contact_phone_1, contact_phone_2, contact_phone_3, contact_phone_4)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
		$request = $database_connect->prepare($query);
		$request->execute(array($arg0, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24, $arg25));
	}

	function insert_class_completed($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7)
	{
		global $database_connect;
    	$exist00 = "SELECT cycle_id, class_id, order_id, section_id, option_id, school_year, COUNT(*) AS count_class_completed_exist FROM classes_completed WHERE cycle_id=? AND class_id=? AND order_id=? AND section_id=? AND option_id=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6));
    	$exist = $exist11->fetchObject();
    	$res = $exist->count_class_completed_exist;

    	if($res == 0)
    	{
    		$query = "INSERT INTO classes_completed(cycle_id, class_id, order_id, section_id, option_id, school_year, classes_alignment)
    					VALUES(?, ?, ?, ?, ?, ?, ?) ";
    		$request = $database_connect->prepare($query);
    		$request->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7));
    	}
	}


	$from_school_year = htmlspecialchars(strip_tags(trim($_POST['from_school_year'])));
	$to_school_year = htmlspecialchars(strip_tags(trim($_POST['to_school_year'])));
	$cycle_school_pupil = htmlspecialchars(strip_tags(trim($_POST['cycle_school_pupil'])));
	$class_school_pupil = htmlspecialchars(strip_tags(trim($_POST['class_school_pupil'])));
	$class_order_pupil = htmlspecialchars(strip_tags(trim($_POST['class_order_pupil'])));
	$class_section_pupil = htmlspecialchars(strip_tags(trim($_POST['class_section_pupil'])));
	$class_option_pupil = htmlspecialchars(strip_tags(trim($_POST['class_option_pupil'])));

	$query = "SELECT * FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=?";
	$request = $database_connect->prepare($query);
	$request->execute(array($cycle_school_pupil, $class_school_pupil, $class_order_pupil, $class_section_pupil, $class_option_pupil, $from_school_year));
	while ($response = $request->fetchObject()) {
		if (find_pupil_sum_main_marks($response->pupil_id_bis, $from_school_year)*2 > find_pupil_sum_total_marks($response->pupil_id_bis, $from_school_year)) {

			$classes_alignment = "$response->cycle_school $response->class_school $response->class_order $response->class_section $response->class_option $to_school_year";

			insert_pupil($response->pupil_id_bis, $response->first_name, $response->second_name, $response->last_name, $response->gender, $response->birth_date, $response->birth_place, $response->father_names, $response->mother_names, $response->parents_alive, $response->parents_state, $response->father_principal_work, $response->mother_principal_work, $response->lives_with, $response->cycle_school, $response->class_school, $response->class_order, $response->class_section, $response->class_option, $to_school_year, $response->email_address, $response->physical_address, $response->contact_phone_1, $response->contact_phone_2, $response->contact_phone_3, $response->contact_phone_4);

			insert_class_completed($response->cycle_school, $response->class_school, $response->class_order, $response->class_section, $response->class_option, $to_school_year, $classes_alignment);
		}
		else
		{
			$classes_alignment = "$response->cycle_school $response->class_school $response->class_order $response->class_section $response->class_option $from_school_year";

			insert_pupil($response->pupil_id_bis, $response->first_name, $response->second_name, $response->last_name, $response->gender, $response->birth_date, $response->birth_place, $response->father_names, $response->mother_names, $response->parents_alive, $response->parents_state, $response->father_principal_work, $response->mother_principal_work, $response->lives_with, $response->cycle_school, $response->class_school, $response->class_order, $response->class_section, $response->class_option, $from_school_year, $response->email_address, $response->physical_address, $response->contact_phone_1, $response->contact_phone_2, $response->contact_phone_3, $response->contact_phone_4);

			insert_class_completed($response->cycle_school, $response->class_school, $response->class_order, $response->class_section, $response->class_option, $from_school_year, $classes_alignment);
		}
	}

?>