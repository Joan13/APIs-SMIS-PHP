<?php

    include '../../config/dbconnect.functions.php';

    $paiement_id = htmlspecialchars(trim(strip_tags($_POST['paiement_id'])));
    $paiement_validated = 0;

    $edit0 = "UPDATE paiements SET paiement_validated=? WHERE paiement_id=?";
    $edit = $database_connect->prepare($edit0);
    $edit->execute(array($paiement_validated, $paiement_id));

  ?>
