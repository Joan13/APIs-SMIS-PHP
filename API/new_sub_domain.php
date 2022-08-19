<?php

	include '../config/dbconnect.functions.php';
	include '../config/functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$response = array();
	$domains = array();
	$sub_domains = array();

	$domain_id = htmlspecialchars(strip_tags(trim($_POST['domain_id'])));
	$sub_domain_name = htmlspecialchars(strip_tags(trim($_POST['sub_domain_name'])));
	$course_1 = htmlspecialchars(strip_tags(trim($_POST['course_1'])));
	$course_2 = htmlspecialchars(strip_tags(trim($_POST['course_2'])));
    $course_3 = htmlspecialchars(strip_tags(trim($_POST['course_3'])));
    $course_4 = htmlspecialchars(strip_tags(trim($_POST['course_4'])));
    $course_5 = htmlspecialchars(strip_tags(trim($_POST['course_5'])));
    $course_6 = htmlspecialchars(strip_tags(trim($_POST['course_6'])));
    $course_7 = htmlspecialchars(strip_tags(trim($_POST['course_7'])));
    $course_8 = htmlspecialchars(strip_tags(trim($_POST['course_8'])));
    $course_9 = htmlspecialchars(strip_tags(trim($_POST['course_9'])));
    $course_10 = htmlspecialchars(strip_tags(trim($_POST['course_10'])));
    $total_marks = htmlspecialchars(strip_tags(trim($_POST['total_marks'])));

    $query_count_sub_domains = "SELECT domain_id, COUNT(*) AS count_sub_domains_exist FROM courses_sub_domains WHERE domain_id=? AND course_1=? AND course_2=? AND course_3=? AND course_4=? AND course_5=?";
    $request_count_sub_domains = $database_connect->prepare($query_count_sub_domains);
    $request_count_sub_domains->execute(array($domain_id, $course_1, $course_2, $course_3, $course_4, $course_5));
    $response_count_sub_domains = $request_count_sub_domains->fetchObject();

    if($response_count_sub_domains->count_sub_domains_exist == 0) {
        $insert_sub_domain = "INSERT INTO courses_sub_domains(domain_id, sub_domain_name, course_1, course_2, course_3, course_4,course_5,course_6,course_7,course_8,course_9,course_10,total_marks) 
        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
        $rinsert_sub_domain = $database_connect->prepare($insert_sub_domain);
        $rinsert_sub_domain->execute(array($domain_id, $sub_domain_name, $course_1, $course_2, $course_3, $course_4, $course_5, $course_6, $course_7, $course_8, $course_9, $course_10, $total_marks));

        echo json_encode('1');
    }
    else 
    {
        echo json_encode('0');
    }
?>