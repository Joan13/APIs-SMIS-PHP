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
    $paiements_deleted = array();
    $frais_divers_deleted = array();
    $annee = $_POST['school_year'];
    $day = $_POST['date'];

    // $query_paiements = "SELECT SUM(montant_paye) AS somme_totale_payee FROM paiements WHERE date_creation=? AND paiement_validated=?";
    // $request_paiements = $database_connect->prepare($query_paiements);
    // $request_paiements->execute(array($day, 1));
    // $response_paiements = $request_paiements->fetchObject();
    // $paiements_day = $response_paiements->somme_totale_payee;

    $query = "SELECT * FROM paiements WHERE date_creation=?";
    $request = $database_connect->prepare($query);
    $request->execute(array($day));
    while($response_paiements = $request->fetchObject()) {

        $query_pupil = "SELECT * FROM pupils_info WHERE pupil_id=?";
        $request_pupil = $database_connect->prepare($query_pupil);
        $request_pupil->execute(array($response_paiements->pupil_id));
        $response_pupil = $request_pupil->fetchObject();

        $paiements_pupil = convert_class_to_array($response_paiements);
        $pupil = convert_class_to_array($response_pupil);
        $paiement = array_merge($paiements_pupil, $pupil);

        if($response_paiements->paiement_validated == 1) {
            array_push($paiements, $paiement);
            $paiements_day = $paiements_day + $response_paiements->montant_paye;
        } else {
            array_push($paiements_deleted, $paiement);
        }
    }

    $queryfd = "SELECT * FROM frais_divers WHERE date_entry=?";
    $requestfd = $database_connect->prepare($queryfd);
    $requestfd->execute(array($day));
    while($responsefd = $requestfd->fetchObject()) {

        $query_pupilf = "SELECT * FROM pupils_info WHERE pupil_id=?";
        $request_pupilf = $database_connect->prepare($query_pupilf);
        $request_pupilf->execute(array($responsefd->pupil_id));
        $response_pupilf = $request_pupilf->fetchObject();

        $paiementsf = convert_class_to_array($responsefd);
        $pupilf = convert_class_to_array($response_pupilf);
        $paiementf = array_merge($paiementsf, $pupilf);

        if($responsefd->deleted == 0) {
            array_push($frais_divers, $paiementf);
        } else {
            array_push($frais_divers_deleted, $paiementf);
        }
    }

    $response['paiements'] = $paiements;
    $response['frais_divers'] = $frais_divers;
    $response['paiements_day'] = $paiements_day;
    $response['frais_divers_deleted'] = $frais_divers_deleted;
    $response['paiements_deleted'] = $paiements_deleted;
    echo json_encode($response);

?>
