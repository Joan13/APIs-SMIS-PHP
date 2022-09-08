<?php

require_once("../config/dbconnect.functions.php");

header("Access-Control-Allow-Origin: *");
  $rest_json = file_get_contents("php://input");
  $_POST = json_decode($rest_json, true);

    $frais_divers_id = htmlspecialchars(trim(strip_tags($_POST['frais_divers_id'])));
    $paiement_validated = 1;

    $edit0 = "UPDATE frais_divers SET deleted=? WHERE frais_divers_id=?";
    $edit = $database_connect->prepare($edit0);
    $edit->execute(array($paiement_validated, $frais_divers_id));

  ?>
