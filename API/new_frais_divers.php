<?php

    include '../config/dbconnect.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    // $day_entry = date('d');//htmlspecialchars(trim(strip_tags($_POST['day_entry'])));
    // $month_entry = date('m');//htmlspecialchars(trim(strip_tags($_POST['month_entry'])));
    // $year_entry = date('Y');//htmlspecialchars(trim(strip_tags($_POST['year_entry'])));
    // $date = $day_entry."/".$month_entry."/".$year_entry;
    $date = htmlspecialchars(trim(strip_tags($_POST['date_entry'])));
    $libelle = htmlspecialchars(trim(strip_tags($_POST['libelle_frais_divers'])));
    $montant = htmlspecialchars(trim(strip_tags($_POST['montant_frais_divers'])));
    $school_year = htmlspecialchars(trim(strip_tags($_POST['school_year'])));
    $pupil_id = htmlspecialchars(trim(strip_tags($_POST['pupil_id'])));

    // $find_child0 = "SELECT * FROM input_output ORDER BY id DESC LIMIT 0, 1";
    // $find_child_1 = $database_connect->query($find_child0);
    // $find_child = $find_child_1->fetchObject();

    // if($operation == 0)
    // {
    //     $intermediate = $nombre*$cout;
    //     $solde = $find_child->solde-$intermediate;
    // } 
    // else
    // {
    //     $intermediate = $nombre*$cout;
    //     $solde = $find_child->solde+$intermediate;
    // }


    $queryInsertFraisDivers = "INSERT INTO frais_divers(pupil_id, libelle, montant, school_year, date_entry, visible_print, deleted) 
    VALUES(?, ?, ?, ?, ?, ?, ?)";
    $requestInsertFraisDivers = $database_connect->prepare($queryInsertFraisDivers);
    $requestInsertFraisDivers->execute(array($pupil_id, $libelle, $montant, $school_year, $date, 1, 0));

    echo json_encode("1");

?>