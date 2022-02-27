<?php

    include '../../config/dbconnect.functions.php';

    $year = htmlspecialchars(trim(strip_tags($_POST['school_year_reset'])));
    $total = $_POST['total_montant_reset'];

    $edit0 = "UPDATE paiements SET total_montant=? WHERE school_year=?";
    $edit = $database_connect->prepare($edit0);
    $edit->execute(array($total, $year));

?>