<?php

require_once("../config/dbconnect.functions.php");

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

	$response = array();
	$libelles = array();
    $school_year = htmlspecialchars(strip_tags(trim(ucwords($_POST['school_year']))));
	$description_libelle = htmlspecialchars(strip_tags(trim(ucwords($_POST['description_libelle']))));
	$gender_libelle = htmlspecialchars(strip_tags(trim(ucwords($_POST['gender_libelle']))));

	if($description_libelle != "") {
        $insert_libelle0 = "INSERT INTO libelles(description_libelle, gender_libelle, school_year) VALUES(?, ?, ?)";
        $insert_libelle = $database_connect->prepare($insert_libelle0);
        $insert_libelle->execute(array($description_libelle, $gender_libelle, $school_year));
    }

		$queryl = "SELECT * FROM libelles WHERE school_year=?";
        $requestl = $database_connect->prepare($queryl);
        $requestl->execute(array($school_year));
        while($response_libelles = $requestl->fetchObject()) {
            array_push($libelles, $response_libelles);
        }
        
        $response['libelles'] = $libelles;
        echo json_encode($response);

?>