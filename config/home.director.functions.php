<?php

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

	function count_fees_exist($cycle, $class, $section, $option, $school_year)
	{
		global $database_connect;
    	$exist00 = "SELECT cycle_school, class_school, class_section, class_option, school_year, COUNT(*) AS count_fees_exist FROM total_fees_years WHERE cycle_school=? AND class_school=? AND class_section=? AND class_option=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($cycle, $class, $section, $option, $school_year));
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_fees_exist;

    	return $response;
	}

	// function insert_fees($cycle, $class, $section, $option, $school_year, $montant)
	// {
	// 	if(count_fees_exist($cycle, $class, $section, $option, $school_year) == 0)
	// 	{
	// 		global $database_connect;
	// 		$insert0 = "INSERT INTO total_fees_years(cycle_school, class_school, class_section, class_option, school_year, total_fees) 
	// 						VALUES(?, ?, ?, ?, ?, ?) ";
	// 		$insert = $database_connect->prepare($insert0);
	// 		$insert->execute(array($cycle, $class, $section, $option, $school_year, $montant));
	// 	}
	// 	else
	// 	{
	// 		global $database_connect;
	// 		$edit0 = "UPDATE total_fees_years SET total_fees=? WHERE cycle_school=? AND class_school=? AND class_section=? AND class_option=? AND school_year=?";
	// 		$edit = $database_connect->prepare($edit0);
	// 		$edit->execute(array($montant, $cycle, $class, $section, $option, $school_year));
	// 	}
	// }


?>