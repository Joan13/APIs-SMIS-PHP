<?php

	function count_pupils_exist($cycle_id, $class_id, $order_id, $section_id, $option_id, $school_year)
	{
		global $database_connect;
    	$exist00 = "SELECT pupil_id, COUNT(*) AS count_pupils_exist FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($cycle_id, $class_id, $order_id, $section_id, $option_id, $school_year));
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_pupils_exist;

    	return $response;
	}

	function count_conduite_already_exist($pupil, $periode, $school_year)
	{
		global $database_connect;
    	$exist00 = "SELECT pupil_id, periode, school_year, COUNT(*) AS count_conduite_already_exists FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($pupil, $periode, $school_year));
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_conduite_already_exists;

    	return $response;
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

	function count_course_exists($course_id)
	{
		global $database_connect;
    	$exist00 = "SELECT course_id, COUNT(*) AS count_course_exists FROM courses";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($course_id));
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_course_exists;

    	return $response;
	}

	function find_course_name($course_id)
	{
    	if(count_course_exists($course_id) != 0)
    	{
    		global $database_connect;
	    	$exist00 = "SELECT course_id, course_name, COUNT(*) AS count_course_exists FROM courses WHERE course_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($course_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_course_exists != 0)
			{
	    		$c00 = "SELECT course_id, course_name FROM courses WHERE course_id=?";
	    		$c11 = $database_connect->prepare($c00);
	    		$c11->execute(array($course_id));
	    		$c = $c11->fetchObject();
	    		$main_course = $c->course_name;

	    		return $main_course;
	    	}
    	}
	}

	function insert_pupil_conduite($pupil_id, $main_conduite, $periode, $school_year, $date_conduite)
	{
		global $database_connect;
		$insert00 = "INSERT INTO conduite(pupil_id, main_conduite, periode, school_year, date_conduite) 
						VALUES(?, ?, ?, ?, ?)";
		$insert = $database_connect->prepare($insert00);
		$insert->execute(array($pupil_id, $main_conduite, $periode, $school_year, $date_conduite));
	}

	function edit_pupil_conduite($pupil_id, $main_conduite, $periode, $school_year, $date_conduite)
	{
		global $database_connect;
		$edit00 = "UPDATE conduite SET main_conduite=?, date_conduite=? WHERE pupil_id=? AND periode=? AND school_year=?";
		$edit = $database_connect->prepare($edit00);
		$edit->execute(array($main_conduite, $date_conduite, $pupil_id, $periode, $school_year));
	}


	function find_cycle_name($cycle_id)
	{
    	if(count_cycles_exist() != 0)
    	{
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
    	}
	}

	function find_class_number($class_id)
	{
    	if(count_classes_exist() != 0)
    	{
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
    	}
	}

	function find_order_name($order_id)
	{
		$ordre = "";

		if(count_orders_exist() != 0)
		{
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
		}

		return $ordre;
	}

	function find_section_name($section_id)
	{
		$section = "";

		if(count_sections_exist() != 0)
		{
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
				$section = "-";
			}
		}

		return $section;
	}

	function find_option_name($option_id)
	{
		$option = "-";

		if(count_options_exist() != 0)
		{
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
				$option = "-";
			}
		}

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
				return "-";
			}
		}
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

?>