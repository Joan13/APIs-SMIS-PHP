<?php

    require_once("../config/dbconnect.functions.php");
    require_once("../config/functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $paiements_day = 0;
    $annee = $_POST['school_year'];
    $day = $_POST['date'];

    $query_paiements = "SELECT SUM(montant_paye) AS somme_totale_payee FROM paiements WHERE date_creation=? AND paiement_validated=?";
    $request_paiements = $database_connect->prepare($query_paiements);
    $request_paiements->execute(array($day, 1));
    // while(
        $response_paiements = $request_paiements->fetchObject();//) {
        $paiements_day = $response_paiements->somme_totale_payee;
    // }

    $response['paiements_day'] = $paiements_day;
    echo json_encode($response);
