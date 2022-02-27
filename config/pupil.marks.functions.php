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

	function count_pupil_marks($pupil_id, $periode, $school_year)
	{
		global $database_connect;
    	$exist00 = "SELECT pupil, school_period, school_year, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=? AND school_period=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($pupil_id, $periode, $school_year));
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_marks_exist;

    	return $response;
	}

	function count_pupil_marks_exist($pupil_id, $periode, $course, $school_year)
	{
		global $database_connect;
    	$exist00 = "SELECT pupil, school_period, course, school_year, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=? AND course=? AND school_period=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($pupil_id, $course, $periode, $school_year));
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_marks_exist;

    	return $response;
	}

	function pupil_period_marks($cycle_id, $class_id, $section, $option, $pupil_id, $course, $periode, $school_year){
		global $database_connect;
		$marks = 0;

		$countCourseQuery = "SELECT course_name, course_id, COUNT(*) AS count_course FROM courses WHERE course_name=? AND school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=?";
		$countCourseRequest = $database_connect->prepare($countCourseQuery);
		$countCourseRequest->execute(array($course, $school_year, $cycle_id, $class_id, $section, $option));
		$countCourseResponse = $countCourseRequest->fetchObject();

		if ($countCourseResponse->count_course != 0) {

			$findCourseQuery = "SELECT * FROM courses WHERE course_name=? AND school_year=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=?";
			$findCourseRequest = $database_connect->prepare($findCourseQuery);
			$findCourseRequest->execute(array($course, $school_year, $cycle_id, $class_id, $section, $option));
			$findCourseResponse = $findCourseRequest->fetchObject();

			$exist00 = "SELECT pupil, school_period, school_year, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=? AND course=? AND school_period=? AND school_year=?";
			$exist11 = $database_connect->prepare($exist00);
			$exist11->execute(array($pupil_id, $findCourseResponse->course_id, $periode, $school_year));
			$exist = $exist11->fetchObject();

			if($exist->count_marks_exist != 0)
			{
				$request = $database_connect->prepare("SELECT * FROM marks_info WHERE pupil=? AND course=? AND school_period=? AND school_year=?");
					$request->execute(array($pupil_id,  $findCourseResponse->course_id, $periode, $school_year));
					$marks = array();
					$marks_found = $request->fetchObject();
					$marks = $marks_found->main_marks;

					return $marks;
			}
		}
	}

	function find_pupil_period_marks($pupil_id, $course, $periode, $school_year){
		global $database_connect;
    	$exist00 = "SELECT pupil, school_period, school_year, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=? AND course=? AND school_period=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($pupil_id, $course, $periode, $school_year));
    	$exist = $exist11->fetchObject();

    	if($exist->count_marks_exist != 0) {
			$request = $database_connect->prepare("SELECT * FROM marks_info WHERE pupil=? AND course=? AND school_period=? AND school_year=? GROUP BY course ORDER BY total_marks DESC");
			$request->execute(array($pupil_id, $course, $periode, $school_year));
			$marks = array();
			while($marks_found = $request->fetchObject()){
				$marks[] = $marks_found; 
			}

			return $marks;
		}
	}

	function find_pupil_semester_trimester_marks($pupil_id, $course, $periode, $periode1, $periode2, $school_year){
		global $database_connect;
		$marks = array();

		// if(count_pupil_marks($pupil_id, $periode, $school_year) != 0 && count_pupil_marks($pupil_id, $periode1, $school_year) != 0 && count_pupil_marks($pupil_id, $periode2, $school_year) != 0)
		// {
			$request = $database_connect->prepare("SELECT SUM(main_marks) AS sum_main_marks, SUM(total_marks) AS sum_total_marks FROM marks_info WHERE pupil=? AND course=? AND (school_period=? OR school_period=? OR school_period=?) AND school_year=? GROUP BY course ORDER BY total_marks DESC");
				$request->execute(array($pupil_id, $course, $periode, $periode1, $periode2, $school_year));
				while($marks_found = $request->fetchObject()){
					$marks[] = $marks_found; 
				}
		// }
		return $marks;
	}

	function find_pupil_semester_trimester_marks_total($pupil_id, $course, $p1, $p2, $e1, $p3, $p4, $e2, $school_year){
		global $database_connect;
		$marks = array();

		// if(count_pupil_marks($pupil_id, $periode, $school_year) != 0 && count_pupil_marks($pupil_id, $periode1, $school_year) != 0 && count_pupil_marks($pupil_id, $periode2, $school_year) != 0)
		// {
			$request = $database_connect->prepare("SELECT SUM(main_marks) AS sum_main_marks, SUM(total_marks) AS sum_total_marks FROM marks_info WHERE pupil=? AND course=? AND (school_period=? OR school_period=? OR school_period=? OR school_period=? OR school_period=? OR school_period=?) AND school_year=? GROUP BY course ORDER BY total_marks DESC");
				$request->execute(array($pupil_id, $course, $p1, $p2, $e1, $p3, $p4, $e2, $school_year));
				while($marks_found = $request->fetchObject()){
					$marks[] = $marks_found; 
				}
		// }
		return $marks;
	}

	function find_pupil_sum_main_marks_sem_trim($pupil_id, $periode, $periode1, $periode2, $school_year){
		global $database_connect;
		$ress = 0;

    	if(count_pupil_marks($pupil_id, $periode, $school_year) != 0 && count_pupil_marks($pupil_id, $periode1, $school_year) != 0 && count_pupil_marks($pupil_id, $periode2, $school_year) != 0)
    	{
    		$request = $database_connect->prepare("SELECT SUM(main_marks) AS sum_main_marks FROM marks_info WHERE pupil=? AND (school_period=? OR school_period=? OR school_period=?) AND school_year=?");
		$request->execute(array($pupil_id, $periode, $periode1, $periode2, $school_year));
		$response = $request->fetchObject();

		$ress = $response->sum_main_marks;
    	}

    	return $ress;
	}

	function find_pupil_sum_total_marks_sem_trim($pupil_id, $periode, $periode1, $periode2, $school_year){
		global $database_connect;
		$ress = "";

    	// if(count_pupil_marks($pupil_id, $periode, $school_year) != 0 && count_pupil_marks($pupil_id, $periode1, $school_year) != 0 && count_pupil_marks($pupil_id, $periode2, $school_year) != 0)
    	// {
    		$request = $database_connect->prepare("SELECT SUM(total_marks) AS sum_main_marks FROM marks_info WHERE pupil=? AND (school_period=? OR school_period=? OR school_period=?) AND school_year=?");
		$request->execute(array($pupil_id, $periode, $periode1, $periode2, $school_year));
		$response = $request->fetchObject();

		$ress = $response->sum_main_marks;
    	// }

    	return $ress;
	}

	function find_pupil_pourcentage_sem_trim($pupil_id, $periode, $periode1, $periode2, $school_year){
		global $database_connect;
		$ress = 0;

		if(count_pupil_marks($pupil_id, $periode, $school_year) != 0 && count_pupil_marks($pupil_id, $periode1, $school_year) != 0 && count_pupil_marks($pupil_id, $periode2, $school_year) != 0)
		{
			$pourcentage = (find_pupil_sum_main_marks_sem_trim($pupil_id, $periode, $periode1, $periode2, $school_year)*100)/find_pupil_sum_total_marks_sem_trim($pupil_id, $periode, $periode1, $periode2, $school_year);
			$main_pourcentage = "$pourcentage";
			
			$ress = substr($main_pourcentage, 0, 4);
		}

    	return $ress;
	}

	function find_pupil_pourcentage($pupil_id, $periode, $school_year){
		global $database_connect;
    	$exist00 = "SELECT pupil, school_period, school_year, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=? AND school_period=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($pupil_id, $periode, $school_year));
    	$exist = $exist11->fetchObject();

		if($exist->count_marks_exist != 0)
		{
			$jj00 = "SELECT SUM(main_marks) AS sum_main_marks FROM marks_info WHERE pupil=? AND school_period=? AND school_year=?";
			$jj11 = $database_connect->prepare($jj00);
			$jj11->execute(array($pupil_id, $periode, $school_year));
			$jj = $jj11->fetchObject();

			$uu00 = "SELECT SUM(total_marks) AS sum_total_marks FROM marks_info WHERE pupil=? AND school_period=? AND school_year=?";
			$uu11 = $database_connect->prepare($uu00);
			$uu11->execute(array($pupil_id, $periode, $school_year));
			$uu = $uu11->fetchObject();

			$pourcentage = ($jj->sum_main_marks*100)/$uu->sum_total_marks;
			$main_pourcentage = "$pourcentage";
			
			return substr($main_pourcentage, 0, 4);
			// return $uu->sum_total_marks;
		}
	}

	function find_pupil_sum_main_marks($pupil_id, $periode, $school_year){
		global $database_connect;
		$exist00 = "SELECT pupil, school_period, school_year, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=? AND school_period=? AND school_year=?";
		$exist11 = $database_connect->prepare($exist00);
		$exist11->execute(array($pupil_id, $periode, $school_year));
		$exist = $exist11->fetchObject();

		if($exist->count_marks_exist != 0)
		{
			$request = $database_connect->prepare("SELECT SUM(main_marks) AS sum_main_marks FROM marks_info WHERE pupil=? AND school_period=? AND school_year=?");
			$request->execute(array($pupil_id, $periode, $school_year));
			$response = $request->fetchObject();

			return $response->sum_main_marks;
		}
	}

	function find_pupil_sum_total_marks($pupil_id, $periode, $school_year){
		global $database_connect;
		$exist00 = "SELECT pupil, school_period, school_year, COUNT(*) AS count_marks_exist FROM marks_info WHERE pupil=? AND school_period=? AND school_year=?";
		$exist11 = $database_connect->prepare($exist00);
		$exist11->execute(array($pupil_id, $periode, $school_year));
		$exist = $exist11->fetchObject();

		if($exist->count_marks_exist != 0)
		{
			$request = $database_connect->prepare("SELECT SUM(total_marks) AS sum_main_marks FROM marks_info WHERE pupil=? AND school_period=? AND school_year=?");
			$request->execute(array($pupil_id, $periode, $school_year));
			$response = $request->fetchObject();

			return $response->sum_main_marks;
		}
	}

	/////////////////

	function find_all_pupil_marks_exist($pupil_id, $p1, $p2, $e1, $p3, $p4, $e2, $school_year){
		global $database_connect;
		$ress = "";

    	// if(count_pupil_marks($pupil_id, $periode, $school_year) != 0 && count_pupil_marks($pupil_id, $periode1, $school_year) != 0 && count_pupil_marks($pupil_id, $periode2, $school_year) != 0)
    	// {
    		$request = $database_connect->prepare("SELECT SUM(main_marks) AS sum_main_marks FROM marks_info WHERE pupil=? AND (school_period=? OR school_period=? OR school_period=? OR school_period=? OR school_period=? OR school_period=?) AND school_year=?");
		$request->execute(array($pupil_id, $p1, $p2, $e1, $p3, $p4, $e2, $school_year));
		$response = $request->fetchObject();

		$ress = $response->sum_main_marks;
    	// }

    	return $ress;
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

	function selected_pupil_exists($pupil_id)
	{
		global $database_connect;
    	$exist00 = "SELECT pupil_id, COUNT(*) AS selected_pupil_exists FROM pupils_info";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($pupil_id));
    	$exist = $exist11->fetchObject();
    	$response = $exist->selected_pupil_exists;

    	return $response;
	}

	function find_order_name($order_id)
	{
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

				return $main_order;
			}
		}
	}

	function find_section_name($section_id)
	{
		$secc = "";
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

				$secc = $main_section;
			}
			else
			{
				$secc = "-";
			}
		}
		return $secc;
	}

	function find_option_name($option_id)
	{
		$opt = "";
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

				$opt = $main_option;
			}
			else
			{
				$opt = "-";
			}
		}
		return $opt;
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

	function count_courses_exist($cycle_id, $class_id, $section_id, $option_id, $school_year)
	{
		global $database_connect;
    	$exist00 = "SELECT cycle_id, class_id, section_id, option_id, school_year, COUNT(*) AS count_courses_exist FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($cycle_id, $class_id, $section_id, $option_id, $school_year));
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_courses_exist;

    	return $response;	
	}

	function find_course_name($course_id)
	{
		global $database_connect;
    	$exist00 = "SELECT course_id, course_name, COUNT(*) AS count_course_exists FROM courses WHERE course_id=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($course_id));
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_course_exists;

    	if($response != 0)
    	{
			$ff00 = "SELECT course_id, course_name FROM courses WHERE course_id=?";
			$ff11 = $database_connect->prepare($ff00);
			$ff11->execute(array($course_id));
			$ff = $ff11->fetchObject();
			$main_course = $ff->course_name;

			return $main_course;
    	}
	}

    function generate_month($month)
    {
        if($month == 1)
        {
            $month = "Janvier";
        } 
        else if($month == 2)
        {
            $month = "Février";
        } 
        else if($month == 3)
        {
            $month = "Mars";
        } 
        else if($month == 4)
        {
            $month = "Avril";
        } 
        else if($month == 5)
        {
            $month = "Mai";
        } 
        else if($month == 6)
        {
            $month = "Juin";
        } 
        else if($month == 7)
        {
            $month = "Juillet";
        } 
        else if($month == 8)
        {
            $month = "Août";
        } 
        else if($month == 9)
        {
            $month ="Septembre";
        } 
        else if($month == 10)
        {
            $month = "Octobre";
        } 
        else if($month == 11)
        {
            $month = "Novembre";
	   } 
	   else if($month == 12)
        {
            $month = "Décembre";
        } 
        else
        {
            $month = "";
        }

        return $month;
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
?>