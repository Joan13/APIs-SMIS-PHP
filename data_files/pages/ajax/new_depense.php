<?php

    include '../../config/dbconnect.functions.php';

    $cout_depense = htmlspecialchars(trim(strip_tags($_POST['cout_depense'])));
    $number_depense = htmlspecialchars(trim(strip_tags($_POST['number_depense'])));
    $libelle_depense = htmlspecialchars(trim(strip_tags($_POST['libelle_depense'])));
    $day = date('d');
    $month = date('m');
    $year = date('Y');
    $hour = date('H');
    $minutes = date('i');
    $date = $day."/".$month."/".$year;
    $depense_validated = 1;
    $heure = "$hour heures : $minutes'";

    $insert00 = "INSERT INTO depenses(cout_depense, number_depense, libelle, depense_validated, heure_depense, date_creation)
                    VALUES(?, ?, ?, ?, ?, ?)";
    $insert = $database_connect->prepare($insert00);
    $insert->execute(array($cout_depense, $number_depense, $libelle_depense, $depense_validated, $heure, $date));

  ?>
