<?php

    require_once("../config/dbconnect.functions.php");
    require_once("../config/functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $paiements_day = 0;
    $paiements = array();
    $frais_divers = array();
    $annee = $_POST['school_year'];
    $day = $_POST['date'];

    $query_paiements = "SELECT SUM(montant_paye) AS somme_totale_payee FROM paiements WHERE date_creation=? AND paiement_validated=?";
    $request_paiements = $database_connect->prepare($query_paiements);
    $request_paiements->execute(array($day, 1));
    $response_paiements = $request_paiements->fetchObject();
    $paiements_day = $response_paiements->somme_totale_payee;

    $query = "SELECT * FROM paiements WHERE date_creation=? AND paiement_validated=?";
    $request = $database_connect->prepare($query);
    $request->execute(array($day, "1"));
    while($response = $request->fetchObject()) {
        array_push($paiements, $response);
    }

    $queryfd = "SELECT * FROM frais_divers WHERE date_entry=? AND deleted=?";
    $requestfd = $database_connect->prepare($queryfd);
    $requestfd->execute(array($day, "0"));
    while($responsefd = $requestfd->fetchObject()) {
        array_push($frais_divers, $responsefd);
    }

    $response['paiements'] = $paiements;
    $response['frais_divers'] = $frais_divers;
    $response['paiements_day'] = $paiements_day;
    echo json_encode($response);

?>
