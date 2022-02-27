<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/home.secretor.functions.php';

	$course_name = htmlspecialchars(strip_tags(trim($_POST['course_name'])));
	$cycle_school_course = htmlspecialchars(strip_tags(trim($_POST['cycle_school_course'])));
	$class_school_course = htmlspecialchars(strip_tags(trim($_POST['class_school_course'])));
	$school_year_course = htmlspecialchars(strip_tags(trim($_POST['school_year_course'])));
	$class_section_course = htmlspecialchars(strip_tags(trim($_POST['class_section_course'])));
	$class_option_course = htmlspecialchars(strip_tags(trim($_POST['class_option_course'])));
	$maxima = htmlspecialchars(strip_tags(trim($_POST['maxima'])));

	insert_course($course_name, $cycle_school_course, $class_school_course, $class_section_course, $class_option_course, $maxima, $school_year_course);

?>