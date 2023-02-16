<?php

	require_once("../config/dbconnect.functions.php");

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$selection_name = htmlspecialchars(strip_tags(trim($_POST['selection_name'])));
	$selection_type = htmlspecialchars(strip_tags(trim($_POST['selection_type'])));
	$selection_privacy = htmlspecialchars(strip_tags(trim($_POST['selection_privacy'])));
	$user_id = htmlspecialchars(strip_tags(trim($_POST['user_id'])));

	// $exist00 = "SELECT course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year, COUNT(*) AS count_course_exist FROM courses WHERE course_name=? AND cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND total_marks=? AND school_year=? ";
	// $exist11 = $database_connect->prepare($exist00);
	// $exist11->execute(array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7));
	// $exist = $exist11->fetchObject();
	// $res = $exist->count_course_exist;

	// if($res == 0) {
		$query = "INSERT INTO selections(selction_name, selection_type, selection_privacy, user_id)
					VALUES(?, ?, ?, ?) ";
		$request = $database_connect->prepare($query);
		$request->execute(array($selection_name, $selection_type, $selection_privacy, $user_id));
	// }

	echo json_encode("1");

?>