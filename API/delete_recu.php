<?php

require_once("../config/dbconnect.functions.php");

header("Access-Control-Allow-Origin: *");
  $rest_json = file_get_contents("php://input");
  $_POST = json_decode($rest_json, true);

    $paiement_id = htmlspecialchars(trim(strip_tags($_POST['paiement_id'])));
    $paiement_validated = 0;

    $edit0 = "UPDATE paiements SET paiement_validated=? WHERE paiement_id=?";
    $edit = $database_connect->prepare($edit0);
    $edit->execute(array($paiement_validated, $paiement_id));

  ?>
