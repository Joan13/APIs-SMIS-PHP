<?php

require_once("../config/dbconnect.functions.php");

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	function insert_course($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7)
	{
		global $database_connect;
		$exist00 = "SELECT course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year, COUNT(*) AS count_course_exist FROM courses WHERE course_name=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND total_marks=? AND school_year=? ";
		$exist11 = $database_connect->prepare($exist00);
		$exist11->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7));
		$exist = $exist11->fetchObject();
		$res = $exist->count_course_exist;

		if($res == 0)
		{
			$query = "INSERT INTO courses(course_name, cycle_id, class_id, section_id, option_id, total_marks, considered, hours_week, school_year)
						VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?) ";
			$request = $database_connect->prepare($query);
			$request->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, "", "", $arg7));
		}
	}

	$course_name = htmlspecialchars(strip_tags(trim($_POST['course'])));
	$cycle_school_course = htmlspecialchars(strip_tags(trim($_POST['cycle_id'])));
	$class_school_course = htmlspecialchars(strip_tags(trim($_POST['class_id'])));
	$school_year_course = htmlspecialchars(strip_tags(trim($_POST['school_year'])));
	$class_section_course = htmlspecialchars(strip_tags(trim($_POST['section_id'])));
	$class_option_course = htmlspecialchars(strip_tags(trim($_POST['option_id'])));
	$maxima = htmlspecialchars(strip_tags(trim($_POST['maxima'])));

	insert_course($course_name, $cycle_school_course, $class_school_course, $class_section_course, $class_option_course, $maxima, $school_year_course);

	echo json_encode("1");

?>