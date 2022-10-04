<?php

    require_once("../config/dbconnect.functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $nbr = 0;
    $nbr1 = 0;
    $response = array();

    $sel = "SELECT * FROM libelles WHERE school_year=4";
    $sel_request = $database_connect->query($sel);
    while($sel_response = $sel_request->fetchObject()) {
      $querypaiements = "SELECT * FROM paiements WHERE school_year='$sel_response->school_year'";
      $requestpaiements = $database_connect->query($querypaiements);
      while($response_array_paiements = $requestpaiements->fetchObject()) {

        $sel2 = "SELECT * FROM libelles WHERE libelle_id='$response_array_paiements->libelle'";
        $sel_request2 = $database_connect->query($sel2);
        $sel_response2 = $sel_request2->fetchObject();

        if ($sel_response2->description_libelle == $sel_response->description_libelle) {
          $edit0 = "UPDATE paiements SET libelle=? WHERE paiement_id=?";
          $edit = $database_connect->prepare($edit0);
          $edit->execute(array($sel_response->libelle_id, $response_array_paiements->libelle));

          $nbr = $nbr + 1;
        }
      }
    }

    $sell = "SELECT * FROM libelles WHERE school_year=4";
    $sel_requestl = $database_connect->query($sell);
    while($sel_responsel = $sel_requestl->fetchObject()) {
      $querypaiementsl = "SELECT * FROM frais_divers WHERE school_year='$sel_responsel->school_year'";
      $requestpaiementsl = $database_connect->query($querypaiementsl);
      while($response_array_paiementsl = $requestpaiementsl->fetchObject()) {

        $sel2l = "SELECT * FROM libelles WHERE libelle_id='$response_array_paiementsl->libelle'";
        $sel_request2l = $database_connect->query($sel2l);
        $sel_response2l = $sel_request2l->fetchObject();

        if ($sel_response2l->description_libelle == $sel_responsel->description_libelle) {
          $edit0l = "UPDATE frais_divers SET libelle=? WHERE frais_divers_id=?";
          $editl = $database_connect->prepare($edit0);
          $editl->execute(array($sel_responsel->libelle_id, $response_array_paiementsl->libelle));

          $nbr1 = $nbr1 + 1;
        }
      }
    }

    $response['nombre'] = $nbr;
    $response['nombre1'] = $nbr1;
    echo json_encode($response);

  ?>
