<?php

	include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$response = array();
	$page_index = htmlspecialchars(strip_tags(trim($_POST['page_index'])));
	$worker_id = htmlspecialchars(strip_tags(trim($_POST['worker_id'])));
	$password = htmlspecialchars(strip_tags(trim(sha1($_POST["password"]))));
	
	if ($page_index == 1) {
		$modify_query = "UPDATE workers_info SET user_password='$password' WHERE worker_id='$worker_id'";
		$modify_request = $database_connect->query($modify_query);

		$response['success'] = "1";
		echo json_encode($response);
	}

?>