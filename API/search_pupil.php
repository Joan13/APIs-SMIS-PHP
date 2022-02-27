<?php

    require_once("../config/dbconnect.functions.php");
    require_once("../config/functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $pupils = array();
    $pupils_count = 0;

    $annee = $_POST['annee'];
    $name = htmlspecialchars(trim(strip_tags($_POST['name'])));

    if($name === "") {
        $querypupils = "SELECT * FROM pupils_info WHERE school_year=?";
        $requestpupils = $database_connect->prepare($querypupils);
        $requestpupils->execute(array($annee));
        while($response_array_pupils = $requestpupils->fetchObject()) {
            $pupil = array();
            $pupil['pupil'] = $response_array_pupils;
            $pupil['paiements'] = array();
            $pupil['frais_divers'] = array();
            $pupil['soldes'] = array();

            array_push($pupils, $pupil);
            $pupils_count = $pupils_count + 1;
        }
    } else {
        $querypupils = "SELECT * FROM pupils_info WHERE school_year=? AND (first_name LIKE '%$name%' OR second_name LIKE '%$name%' OR last_name LIKE '%$name%')";
        $requestpupils = $database_connect->prepare($querypupils);
        $requestpupils->execute(array($annee));
        while($response_array_pupils = $requestpupils->fetchObject()) {
            $pupil = array();
            $pupil['pupil'] = $response_array_pupils;
            $pupil['paiements'] = array();
            $pupil['frais_divers'] = array();
            $pupil['soldes'] = array();

            array_push($pupils, $pupil);
            $pupils_count = $pupils_count + 1;
        }
    }

    $response['pupils'] = $pupils;
    $response['pupils_count'] = $pupils_count;
    echo json_encode($response);

?>