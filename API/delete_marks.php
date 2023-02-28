<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$periode_marks = htmlspecialchars(strip_tags(trim($_POST['periode'])));
	// $year_school = htmlspecialchars(strip_tags(trim($_POST['school_year'])));
	$pupil_id = htmlspecialchars(strip_tags(trim($_POST['pupil_id'])));

	$qq = "DELETE FROM marks_info WHERE pupil=? AND school_period=?";
	$reqq = $database_connect->prepare($qq);
	$reqq->execute(array($pupil_id, $periode_marks));

	echo json_encode("1");

?>