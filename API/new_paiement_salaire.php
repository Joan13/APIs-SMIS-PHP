<?php

    include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $user_id = htmlspecialchars(trim(strip_tags($_POST['user_id'])));
    $montant_paie = htmlspecialchars(trim(strip_tags($_POST['montant_paie'])));
    $month_paie = htmlspecialchars(trim(strip_tags($_POST['month_paie'])));
    $worker_id = htmlspecialchars(trim(strip_tags($_POST['worker_id'])));
    $school_year = htmlspecialchars(trim(strip_tags($_POST['school_year'])));
    $date = htmlspecialchars(trim(strip_tags($_POST['date_entry'])));

    $response = array();
    $fiche_paie = array();

    $insert00 = "INSERT INTO fiche_paie(user_id, montant_paye, month_paye, worker_id, date_entry, school_year)
                    VALUES(?, ?, ?, ?, ?, ?)";
    $insert = $database_connect->prepare($insert00);
    $insert->execute(array($user_id, $montant_paie, $month_paie, $worker_id, $date, $school_year));

    // $query = "SELECT * FROM fiche_paie WHERE worker_id='$worker_id'";
    // $request = $database_connect->query($query);
    // while($response_array = $request->fetchObject()) {
    //     array_push($fiche_paie, $response_array);
    // }

    // $response['fiche_paie'] = $fiche_paie;
    echo json_encode($response);

  ?>
