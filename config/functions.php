<?php

	function insert_pupil($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24, $arg25, $arg26, $id_number, $perm_number, $nat, $stat, $paiement_category) {
		global $database_connect;

		$count_pupil_exists_query = "SELECT first_name, second_name, last_name, gender, cycle_school, class_school, class_order, class_section, class_option, school_year, is_inactive, COUNT(*) AS pupil_exists FROM pupils_info WHERE first_name=? AND second_name=? AND last_name=? AND gender=? AND cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? AND is_inactive=?";
		$count_pupil_exists_request = $database_connect->prepare($count_pupil_exists_query);
		$count_pupil_exists_request->execute(array($arg1, $arg2, $arg3, $arg4, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, 0));
		$count_pupil_exists_response = $count_pupil_exists_request->fetchObject();

		if($count_pupil_exists_response->pupil_exists == 0) {
			$day = date('d');
			$month = date('m');
			$year = date('Y');
			$date = $day."/".$month."/".$year;
			// $array_insert = array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24, $arg25, $arg26, $paiement_category);
			$query = "INSERT INTO pupils_info(first_name, second_name, last_name, gender, birth_date, birth_place, father_names, mother_names, parents_alive, parents_state, father_principal_work, mother_principal_work, lives_with, cycle_school, class_school, class_order, class_section, class_option, school_year, email_address, physical_address, contact_phone_1, contact_phone_2, contact_phone_3, contact_phone_4, pupilIdentification, statut_scolaire, is_inactive, permanent_number, identification_number, nationality, paiement_category, date_creation)
				VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
			$request = $database_connect->prepare($query);
			$request->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24, $arg25, $arg26, $stat, 0, $perm_number, $id_number, $nat, $paiement_category, $date));
		}
	}

	// function insert_pupil_export($pid, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24, $arg25, $arg26, $id_number, $perm_number, $nat, $stat) {
	// 	global $database_connect_export;

	// 	$count_pupil_exists_query = "SELECT first_name, second_name, last_name, gender, cycle_school, class_school, class_order, class_section, class_option, school_year, COUNT(*) AS pupil_exists FROM pupils_info WHERE first_name=? AND second_name=? AND last_name=? AND gender=? AND cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=?";
	// 	$count_pupil_exists_request = $database_connect_export->prepare($count_pupil_exists_query);
	// 	$count_pupil_exists_request->execute(array($arg1, $arg2, $arg3, $arg4, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19));
	// 	$count_pupil_exists_response = $count_pupil_exists_request->fetchObject();

	// 	if($count_pupil_exists_response->pupil_exists == 0) {
	// 		$array_insert = array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24, $arg25, $arg26);
	// 		$query = "INSERT INTO pupils_info(pupil_id, first_name, second_name, last_name, gender, birth_date, birth_place, father_names, mother_names, parents_alive, parents_state, father_principal_work, mother_principal_work, lives_with, cycle_school, class_school, class_order, class_section, class_option, school_year, email_address, physical_address, contact_phone_1, contact_phone_2, contact_phone_3, contact_phone_4, pupilIdentification, statut_scolaire, is_inactive, permanent_number, identification_number, nationality)
	// 			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
	// 		$request = $database_connect_export->prepare($query);
	// 		$request->execute(array($pid, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13, $arg14, $arg15, $arg16, $arg17, $arg18, $arg19, $arg20, $arg21, $arg22, $arg23, $arg24, $arg25, $arg26, $stat, 0, $perm_number, $id_number, $nat));
	// 	}
	// }

	function randomUserId($length) {
    	$alphabet = "0123456789abcdefghijklmnopqrstuvwxyz9876543210ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    	return substr(str_shuffle(str_repeat($alphabet, $length)), $length, $length);
    }

	function insert_class_completed($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7) {
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

	function insert_class_completed_export($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7) {
		global $database_connect_export;
    	$exist00 = "SELECT cycle_id, class_id, order_id, section_id, option_id, school_year, COUNT(*) AS count_class_completed_exist FROM classes_completed WHERE cycle_id=? AND class_id=? AND order_id=? AND section_id=? AND option_id=? AND school_year=?";
    	$exist11 = $database_connect_export->prepare($exist00);
    	$exist11->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6));
    	$exist = $exist11->fetchObject();
    	$res = $exist->count_class_completed_exist;

    	if($res == 0)
    	{
    		$query = "INSERT INTO classes_completed(cycle_id, class_id, order_id, section_id, option_id, school_year, classes_alignment)
    					VALUES(?, ?, ?, ?, ?, ?, ?) ";
    		$request = $database_connect_export->prepare($query);
    		$request->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7));
    	}
	}

	function count_classes_completed_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT cycle_id, class_id, order_id, section_id, option_id, school_year, COUNT(*) AS count_class_completed_exist FROM classes_completed";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_class_completed_exist;

    	return $response;
	}

	// function count_cycles_exist()
	// {
	// 	global $database_connect;
    // 	$exist00 = "SELECT cycle_id, cycle_name, COUNT(*) AS count_cycles_exist FROM cycle";
    // 	$exist11 = $database_connect->query($exist00);
    // 	$exist = $exist11->fetchObject();
    // 	$response = $exist->count_cycles_exist;

    // 	return $response;
	// }

	// function count_classes_exist()
	// {
	// 	global $database_connect;
    // 	$exist00 = "SELECT class_id, class_number, COUNT(*) AS count_classes_exist FROM classes";
    // 	$exist11 = $database_connect->query($exist00);
    // 	$exist = $exist11->fetchObject();
    // 	$response = $exist->count_classes_exist;

    // 	return $response;
	// }

	// function count_orders_exist()
	// {
	// 	global $database_connect;
    // 	$exist00 = "SELECT order_id, order_name, COUNT(*) AS count_orders_exist FROM class_order";
    // 	$exist11 = $database_connect->query($exist00);
    // 	$exist = $exist11->fetchObject();
    // 	$response = $exist->count_orders_exist;

    // 	return $response;
	// }

	// function count_sections_exist()
	// {
	// 	global $database_connect;
    // 	$exist00 = "SELECT section_id, section_name, COUNT(*) AS count_sections_exist FROM sections";
    // 	$exist11 = $database_connect->query($exist00);
    // 	$exist = $exist11->fetchObject();
    // 	$response = $exist->count_sections_exist;

    // 	return $response;
	// }

	// function count_options_exist()
	// {
	// 	global $database_connect;
    // 	$exist00 = "SELECT option_id, option_name, COUNT(*) AS count_options_exist FROM options";
    // 	$exist11 = $database_connect->query($exist00);
    // 	$exist = $exist11->fetchObject();
    // 	$response = $exist->count_options_exist;

    // 	return $response;
	// }

	function count_school_years_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT year_id, year_name, COUNT(*) AS count_years_exist FROM school_years";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_years_exist;

    	return $response;
	}

	function find_cycle_name($cycle_id)
	{
    	// if(count_cycles_exist() != 0)
    	// {
    		global $database_connect;
	    	$exist00 = "SELECT cycle_id, cycle_name, COUNT(*) AS count_cycles_exist FROM cycle WHERE cycle_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($cycle_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_cycles_exist != 0)
			{
	    		$cycles00 = "SELECT cycle_id, cycle_name FROM cycle WHERE cycle_id=?";
	    		$cycles11 = $database_connect->prepare($cycles00);
	    		$cycles11->execute(array($cycle_id));
	    		$cycless = $cycles11->fetchObject();
	    		$main_cycle = $cycless->cycle_name;

	    		return $main_cycle;
	    	}
    	// }
	}

	function find_class_number($class_id)
	{
    	// if(count_classes_exist() != 0)
    	// {
			global $database_connect;
	    	$exist00 = "SELECT class_id, class_number, COUNT(*) AS count_classes_exist FROM classes WHERE class_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($class_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_classes_exist != 0)
	    	{
	    		$classes00 = "SELECT class_id, class_number FROM classes WHERE class_id=?";
	    		$classes11 = $database_connect->prepare($classes00);
	    		$classes11->execute(array($class_id));
	    		$classess = $classes11->fetchObject();
	    		$main_class = $classess->class_number;

	    		return $main_class;
	    	}
    	// }
	}

	function find_order_name($order_id)
	{
		$ordre = "";

		// if(count_orders_exist() != 0)
		// {
			global $database_connect;
	    	$exist00 = "SELECT order_id, order_name, COUNT(*) AS count_orders_exist FROM class_order WHERE order_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($order_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_orders_exist != 0)
	    	{
				$orders00 = "SELECT order_id, order_name FROM class_order WHERE order_id=?";
				$orders11 = $database_connect->prepare($orders00);
				$orders11->execute(array($order_id));
				$orderss = $orders11->fetchObject();
				$main_order = $orderss->order_name;

				$ordre = " ".$main_order;
			}
			else
			{
				$ordre = "";
			}
		// }

		return $ordre;
	}

	function find_section_name($section_id)
	{
		$section = "";

		// $count_query = "SELECT section_id, COUNT(*) AS count_sections FROM sections WHERE "
		// if(count_sections_exist() != 0)
		// {
			global $database_connect;
	    	$exist00 = "SELECT section_id, section_name, COUNT(*) AS count_sections_exist FROM sections WHERE section_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($section_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_sections_exist != 0)
	    	{
				$sections00 = "SELECT section_id, section_name FROM sections WHERE section_id=?";
				$sections11 = $database_connect->prepare($sections00);
				$sections11->execute(array($section_id));
				$sectionss = $sections11->fetchObject();
				$main_section = $sectionss->section_name;

				$section = $main_section;
			}
			else
			{
				$section = "";
			}
		// }

		return $section;
	}

	function find_option_name($option_id)
	{
		$option = "";

		// if(count_options_exist() != 0)
		// {
			global $database_connect;
	    	$exist00 = "SELECT option_id, option_name, COUNT(*) AS count_options_exist FROM options WHERE option_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($option_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_options_exist != 0)
	    	{
				$options00 = "SELECT option_id, option_name FROM options WHERE option_id=?";
				$options11 = $database_connect->prepare($options00);
				$options11->execute(array($option_id));
				$optionss = $options11->fetchObject();
				$main_option = $optionss->option_name;

				$option = $main_option;
			}
			else
			{
				$option = "";
			}
		// }

		return $option;
	}

	function find_school_year($year_id)
	{
		if(count_school_years_exist() != 0)
		{
			global $database_connect;
	    	$exist00 = "SELECT year_id, year_name, COUNT(*) AS count_year_exist FROM school_years WHERE year_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($year_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_year_exist != 0)
	    	{
				$years00 = "SELECT year_id, year_name FROM school_years WHERE year_id=?";
				$years11 = $database_connect->prepare($years00);
				$years11->execute(array($year_id));
				$yearss = $years11->fetchObject();
				$main_year = $yearss->year_name;

				return $main_year;
			}
			else
			{
				return "";
			}
		}
	}


	function nbr_pupils_class($cycle_id, $class_id, $order_id, $section_id, $option_id, $school_year) {
		global $database_connect;

		$query = "SELECT cycle_school, class_school, class_order, class_section, class_option, school_year, COUNT(*) AS count_pupils FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=?";
		$request = $database_connect->prepare($query);
		$request->execute(array($cycle_id, $class_id, $order_id, $section_id, $option_id, $school_year));
		$response = $request->fetchObject();

		return $response->count_pupils;
	}

	function nbr_pupils_class_male($cycle_id, $class_id, $order_id, $section_id, $option_id, $school_year) {
		global $database_connect;

		$query = "SELECT cycle_school, class_school, class_order, class_section, class_option, school_year, gender, COUNT(*) AS count_pupils FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? AND gender=?";
		$request = $database_connect->prepare($query);
		$request->execute(array($cycle_id, $class_id, $order_id, $section_id, $option_id, $school_year, 1));
		$response = $request->fetchObject();

		return $response->count_pupils;
	}

	function nbr_pupils_class_female($cycle_id, $class_id, $order_id, $section_id, $option_id, $school_year) {
		global $database_connect;

		$query = "SELECT cycle_school, class_school, class_order, class_section, class_option, school_year, gender, COUNT(*) AS count_pupils FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? AND gender=?";
		$request = $database_connect->prepare($query);
		$request->execute(array($cycle_id, $class_id, $order_id, $section_id, $option_id, $school_year, 0));
		$response = $request->fetchObject();

		return $response->count_pupils;
	}

?>