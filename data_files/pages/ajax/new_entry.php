<?php

    include '../../config/dbconnect.functions.php';

    $day_entry = date('d');//htmlspecialchars(trim(strip_tags($_POST['day_entry'])));
    $month_entry = date('m');//htmlspecialchars(trim(strip_tags($_POST['month_entry'])));
    $year_entry = date('Y');//htmlspecialchars(trim(strip_tags($_POST['year_entry'])));
    $libelle = htmlspecialchars(trim(strip_tags($_POST['sel_libelle'])));
    $operation = htmlspecialchars(trim(strip_tags($_POST['sel_operation'])));
    $cout = htmlspecialchars(trim(strip_tags($_POST['cout'])));
    $nombre = htmlspecialchars(trim(strip_tags($_POST['nombre'])));

    $find_child0 = "SELECT * FROM input_output ORDER BY id DESC LIMIT 0, 1";
    $find_child_1 = $database_connect->query($find_child0);
    $find_child = $find_child_1->fetchObject();

    if($operation == 0)
    {
        $intermediate = $nombre*$cout;
        $solde = $find_child->solde-$intermediate;
    } 
    else
    {
        $intermediate = $nombre*$cout;
        $solde = $find_child->solde+$intermediate;
    }


    $insert_entry0 = "INSERT INTO input_output(day, month, year, libelle, type_operation, cout_unitaire, nombre, solde) 
    VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_entry = $database_connect->prepare($insert_entry0);
    $insert_entry->execute(array($day_entry, $month_entry, $year_entry, $libelle, $operation, $cout, $nombre, $solde));

?>