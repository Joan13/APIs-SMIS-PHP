<?php

    include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $worker_id = htmlspecialchars(trim(strip_tags($_POST['worker_id'])));;

    $response = array();
    // $worker = array();
    $fiche_paie = array();

    $query = "SELECT * FROM fiche_paie WHERE worker_id='$worker_id'";
    $request = $database_connect->query($query);
    while($response_array = $request->fetchObject()) {
        array_push($fiche_paie, $response_array);
    }

    $queryw = "SELECT * FROM workers_info WHERE worker_id='$worker_id'";
    $requestw = $database_connect->query($queryw);
    $worker = $requestw->fetchObject();
        // array_push($worker, $response_arrayw);

    $response['worker'] = $worker;
    $response['fiche_paie'] = $fiche_paie;
    echo json_encode($response);

  ?>
