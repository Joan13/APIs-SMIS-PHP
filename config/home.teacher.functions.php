<?php

	function insert_pupil($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24, $arg25)
	{
		global $database_connect;
		$array_insert = array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24,$arg25);
		$query = "INSERT INTO pupils_info(first_name, second_name, last_name, gender, birth_date, birth_place, father_names, mother_names, parents_alive, parents_state, father_principal_work, mother_principal_work, lives_with, cycle_school, class_school, class_order, class_section, class_option, school_year, email_address, physical_address, contact_phone_1, contact_phone_2, contact_phone_3, contact_phone_4)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
		$request = $database_connect->prepare($query);
		$request->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24, $arg25));
	}

	function pupil_year($pupil_id, $school_year)
	{
    	$exist00 = "SELECT pupil_id, school_year, COUNT(*) AS count_pupil_year_exists FROM pupils_years WHERE pupil_id=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_pupil_year_exists;

    	return $response;
	}


	function insert_worker($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21)
	{
		global $database_connect;
		$array_insert = array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21);
		$query = "INSERT INTO workers_info(first_name, second_name, last_name, gender, birth_date, birth_place, poste, civil_state, mariage_state, children_number, secondary_in, graduated_in, bachelor_in, masters_in, phd_in, email_user, user_address, user_phone_1, user_phone_2, user_phone_3, user_phone_4)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
		$request = $database_connect->prepare($query);
		$request->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21));
	}


	function insert_class($class_number)
	{
		global $database_connect;
		$query = "INSERT INTO classes(class_number) VALUES(?)";
		$request = $database_connect->prepare($query);
		$request->execute(array($class_number));
	}

	function insert_section($section)
	{
		global $database_connect;
		$query = "INSERT INTO sections(section_name) VALUES(?)";
		$request = $database_connect->prepare($query);
		$request->execute(array($section));
	}

	function insert_option($option)
	{
		global $database_connect;
		$query = "INSERT INTO options(option_name) VALUES(?)";
		$request = $database_connect->prepare($query);
		$request->execute(array($option));
	}

	function insert_cycle($cycle)
	{
		global $database_connect;
		$query = "INSERT INTO cycle(cycle_name) VALUES(?)";
		$request = $database_connect->prepare($query);
		$request->execute(array($cycle));
	}

	function insert_class_order($order_name)
	{
		global $database_connect;
		$query = "INSERT INTO class_order(order_name) VALUES(?)";
		$request = $database_connect->prepare($query);
		$request->execute(array($order_name));
	}

	function insert_school_year($year)
	{
		global $database_connect;
		$query = "INSERT INTO school_years(year_name) VALUES(?)";
		$request = $database_connect->prepare($query);
		$request->execute(array($year));
	}

	function count_cycles_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT cycle_id, cycle_name, COUNT(*) AS count_cycles_exist FROM cycle";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_cycles_exist;

    	return $response;
	}

	function count_classes_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT class_id, class_number, COUNT(*) AS count_classes_exist FROM classes";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_classes_exist;

    	return $response;
	}

	function count_orders_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT order_id, order_name, COUNT(*) AS count_orders_exist FROM class_order";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_orders_exist;

    	return $response;
	}

	function count_sections_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT section_id, section_name, COUNT(*) AS count_sections_exist FROM sections";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_sections_exist;

    	return $response;
	}

	function count_options_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT option_id, option_name, COUNT(*) AS count_options_exist FROM options";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_options_exist;

    	return $response;
	}

	function count_school_years_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT year_id, year_name, COUNT(*) AS count_years_exist FROM school_years";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_years_exist;

    	return $response;
	}

	function count_courses_exist($cycle_id, $class_id, $order_id, $section_id, $option_id)
	{
		global $database_connect;
    	$exist00 = "SELECT cycle_id, class_id, order_id, section_id, option_id, COUNT(*) AS count_courses_exist FROM courses WHERE (cycle_id=? AND class_id=? AND order_id=? AND section_id=? AND option_id=?) OR (cycle_id=?) OR (cycle_id=? AND class_id=?) OR (section_id=?) OR (option_id=?) ";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($cycle_id, $class_id, $order_id, $section_id, $option_id, $cycle_id, $cycle_id, $class_id, $section_id, $option_id));
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_courses_exist;

    	return $response;
	}

	function insert_course($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7)
	{
		global $database_connect;
    	$exist00 = "SELECT course_name, cycle_id, class_id, order_id, section_id, option_id, total_marks, COUNT(*) AS count_course_exist FROM courses WHERE course_name=? AND cycle_id=? AND class_id=? AND total_marks=? ";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($arg1, $arg2, $arg3, $arg7));
    	$exist = $exist11->fetchObject();
    	$res = $exist->count_course_exist;

    	if($res == 0)
    	{
    		$query = "INSERT INTO courses(course_name, cycle_id, class_id, order_id, section_id, option_id, total_marks)
    					VALUES(?, ?, ?, ?, ?, ?, ?) ";
    		$request = $database_connect->prepare($query);
    		$request->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7));
    	}
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

?>
