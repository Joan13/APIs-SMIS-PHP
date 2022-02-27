<?php

    include '../../config/dbconnect.functions.php';

    $pupil_id = htmlspecialchars(trim(strip_tags($_POST['pupil_paiement'])));
    $input_montant = htmlspecialchars(trim(strip_tags($_POST['input_montant'])));
    $input_montant_text = htmlspecialchars(trim(strip_tags($_POST['input_montant_text'])));
    $selectionner_le_libelle = htmlspecialchars(trim(strip_tags($_POST['selectionner_le_libelle'])));
    $total_montant = htmlspecialchars(trim(strip_tags($_POST['total_montant'])));
    $school_year_input = htmlspecialchars(trim(strip_tags($_POST['school_year_input'])));
    $day = date('d');
    $month = date('m');
    $year = date('Y');
    $date = $day."/".$month."/".$year;
    $paiement_validated = 1;

    $insert00 = "INSERT INTO paiements(pupil_id, montant_paye, montant_text, libelle, total_montant, school_year, paiement_validated, date_creation)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
    $insert = $database_connect->prepare($insert00);
    $insert->execute(array($pupil_id, $input_montant, $input_montant_text, $selectionner_le_libelle, $total_montant, $school_year_input, $paiement_validated, $date));

    $edit0 = "UPDATE paiements SET total_montant=? WHERE pupil_id=? AND school_year=?";
    $edit = $database_connect->prepare($edit0);
    $edit->execute(array($total_montant, $pupil_id, $school_year_input));

  ?>
