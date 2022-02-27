<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/class.marksheet.insert.functions.php';

	$periode_marks = htmlspecialchars(strip_tags(trim($_POST['select_periode'])));
	$date_work = htmlspecialchars(strip_tags(trim($_POST['date_work'])));
	$year_school = htmlspecialchars(strip_tags(trim($_POST['year_school'])));
	$main_marks = htmlspecialchars(strip_tags(trim($_POST['yaambi'])));
	$course_id = htmlspecialchars(strip_tags(trim($_POST['course_id'])));
	$pupil_id = htmlspecialchars(strip_tags(trim($_POST['pupil_id'])));

	if($periode_marks == '' || $date_work == '' || $year_school == '')
	{

	}
	else
	{
		$ssl00 = "SELECT course_id, total_marks FROM courses WHERE course_id=?";
		$ssl11 = $database_connect->prepare($ssl00);
		$ssl11->execute(array($course_id));
		$ssl = $ssl11->fetchObject();
		$maxima = $ssl->total_marks;
		if($periode_marks == 7 || $periode_marks == 8 || $periode_marks == 9 || $periode_marks == 10 || $periode_marks == 11)
		{
			$maxima = $maxima*2;
		}

		if(count_marks_already_exist($pupil_id, $course_id, $periode_marks, $year_school) > 1)
		{
			$qq = "DELETE FROM marks_info WHERE course='$course_id' AND pupil='$pupil_id' AND school_period='$periode_marks' AND school_year='$year_school'";
			$reqq = $database_connect->query($qq);
			
			insert_course_marks($pupil_id, $course_id, $main_marks, $maxima, $periode_marks, $year_school, $date_work);
		}
		else if(count_marks_already_exist($pupil_id, $course_id, $periode_marks, $year_school) == 1)
		{
			edit_course_marks($pupil_id, $course_id, $main_marks, $maxima, $periode_marks, $year_school, $date_work);
			// echo count_marks_already_exist($pupil_id, $course_id, $periode_marks, $year_school);
		}
		else
		{
			insert_course_marks($pupil_id, $course_id, $main_marks, $maxima, $periode_marks, $year_school, $date_work);
			// echo count_marks_already_exist($pupil_id, $course_id, $periode_marks, $year_school);
		}

		if(count_courses_exist($_POST['cycle'], $_POST['class_id'], $_POST['section_id'], $_POST['option_id'], $_POST['school_year']) != 0)
		{
			$query_fetch00_cn = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY total_marks DESC";
			$query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
			$query_fetch11_cn->execute(array($_POST['cycle'], $_POST['class_id'], $_POST['section_id'], $_POST['option_id'], $_POST['school_year']));
			while($query_fetch_cn = $query_fetch11_cn->fetchObject())
			{
				if(count_marks_already_exist($pupil_id, $query_fetch_cn->course_id, $periode_marks, $year_school) == 0)
				{
					insert_course_marks($pupil_id, $query_fetch_cn->course_id, 0, 0, $periode_marks, $year_school, " ");
				}
			}
		}
	}

?>