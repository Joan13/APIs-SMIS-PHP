<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $paiements = array();
    $frais_divers = array();
    $array_paiements = array();
    $paiement_categories = array();
    $libelles = array();
    $classes = array();

    $pupils = array();

    foreach($_POST as $element) {
        array_push($paiements, $element['paiements']);
        array_push($frais_divers, $element['frais_divers']);
        array_push($pupils, $element['pupils']);
        array_push($paiement_categories, $element['paiement_categories']);
        array_push($libelles, $element['libelles']);
        array_push($classes, $element['classes_completed']);
    }

    // $truncate_paiements_query = "TRUNCATE paiements";
    // $truncate_paiements_request = $database_connect_export->query($truncate_paiements_query);

    foreach($paiements[0] as $cle => $paiement) {

        // array_push($array_paiements, $paiement);

        $verify_query = "SELECT paiement_id, COUNT(*) AS count_paiement_exist FROM paiements WHERE paiement_id=?";
        $verify_request = $database_connect_export->prepare($verify_query);
        $verify_request->execute(array($paiement['paiement_id']));
        $verify_response = $verify_request->fetchObject();

        if ($verify_response->count_paiement_exist == 0) {
            $insert_query = "INSERT INTO paiements(paiement_id, pupil_id, montant_paye, montant_text, libelle, total_montant, school_year, paiement_validated, date_creation)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_request = $database_connect_export->prepare($insert_query);
            $insert_request->execute(array($paiement['paiement_id'], $paiement['pupil_id'], $paiement['montant_paye'], $paiement['montant_text'], $paiement['libelle'], $paiement['total_montant'], $paiement['school_year'], $paiement['paiement_validated'], $paiement['date_creation']));
        } else {
            $edit0 = "UPDATE paiements SET paiement_validated=? WHERE paiement_id=?";
            $edit = $database_connect_export->prepare($edit0);
            $edit->execute(array($paiement['paiement_validated'], $paiement['paiement_id']));
        }
    }


    foreach($frais_divers[0] as $cle => $frais_divers) {

        // array_push($array_paiements, $paiement);

        $verify_query = "SELECT frais_divers_id, COUNT(*) AS count_paiement_exist FROM frais_divers WHERE frais_divers_id=?";
        $verify_request = $database_connect_export->prepare($verify_query);
        $verify_request->execute(array($frais_divers['frais_divers_id']));
        $verify_response = $verify_request->fetchObject();

        if ($verify_response->count_paiement_exist == 0) {
            $insert_query = "INSERT INTO frais_divers(frais_divers_id, pupil_id, libelle, montant, school_year, date_entry, deleted, visible_print)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_request = $database_connect_export->prepare($insert_query);
            $insert_request->execute(array($frais_divers['frais_divers_id'], $frais_divers['pupil_id'], $frais_divers['libelle'], $frais_divers['montant'], $frais_divers['school_year'], $frais_divers['date_entry'], $frais_divers['deleted'], $frais_divers['visible_print']));
        } else {
            // $edit0 = "UPDATE paiements SET paiement_validated=? WHERE paiement_id=?";
            // $edit = $database_connect_export->prepare($edit0);
            // $edit->execute(array($paiement['paiement_validated'], $paiement['paiement_id']));
        }
    }

    foreach($paiement_categories[0] as $cle => $category) {

        $verify_query = "SELECT category_id, COUNT(*) AS count_category_exists FROM paiement_categories WHERE category_id=?";
        $verify_request = $database_connect_export->prepare($verify_query);
        $verify_request->execute(array($category['category_id']));
        $verify_response = $verify_request->fetchObject();

        if ($verify_response->count_category_exists == 0) {
            $insert_query = "INSERT INTO paiement_categories(category_id, category_name, category_amount, school_year)
                    VALUES(?, ?, ?, ?)";
            $insert_request = $database_connect_export->prepare($insert_query);
            $insert_request->execute(array($category['category_id'], $category['category_name'], $category['category_amount'], $paiement['school_year']));
        }
    }

    foreach($libelles[0] as $cle => $libelle) {

        $verify_query = "SELECT libelle_id, COUNT(*) AS count_libelle_exists FROM libelles WHERE libelle_id=?";
        $verify_request = $database_connect_export->prepare($verify_query);
        $verify_request->execute(array($libelle['libelle_id']));
        $verify_response = $verify_request->fetchObject();

        if ($verify_response->count_libelle_exists == 0) {
            $insert_query = "INSERT INTO libelles(libelle_id, gender_libelle, description_libelle, school_year)
                    VALUES(?, ?, ?, ?)";
            $insert_request = $database_connect_export->prepare($insert_query);
            $insert_request->execute(array($libelle['libelle_id'], $libelle['gender_libelle'], $libelle['description_libelle'], $libelle['school_year']));
        }
    }

    foreach($pupils[0] as $cle => $pupil) {
        // $verify_query = "SELECT pupil_id, COUNT(*) AS count_pupil_exist FROM pupils_info WHERE pupil_id=?";
        // $verify_request = $database_connect_export->prepare($verify_query);
        // $verify_request->execute(array($pupil['pupil_id']));
        // $verify_response = $verify_request->fetchObject();

        // if ($verify_response->count_pupil_exist == 0) {
            // $classes_alignment = $pupil['cycle_school'] ." ". $pupil['class_school'] ." ". $pupil['class_order'] ." ". $pupil['class_section'] ." ". $pupil['class_option'] ." ". $pupil['school_year'];

            insert_pupil_export($pupil['pupil_id'], $pupil['first_name'], $pupil['second_name'], $pupil['last_name'], $pupil['gender'], $pupil['birth_date'], $pupil['birth_place'], $pupil['father_names'], $pupil['mother_names'], $pupil['parents_alive'], $pupil['parents_state'], $pupil['father_principal_work'], $pupil['mother_principal_work'], $pupil['lives_with'], $pupil['cycle_school'], $pupil['class_school'], $pupil['class_order'], $pupil['class_section'], $pupil['class_option'], $pupil['school_year'], $pupil['email_address'], $pupil['physical_address'], $pupil['contact_phone_1'], $pupil['contact_phone_2'], $pupil['contact_phone_3'], $pupil['contact_phone_4'], $pupil['pupilIdentification'], $pupil['identification_number'], $pupil['permanent_number'], $pupil['nationality'], $pupil['statut_scolaire'], $pupil['paiement_category'], $pupil['date_creation'], $pupil['is_inactive']);
            // insert_class_completed_export($pupil['cycle_school'], $pupil['class_school'], $pupil['class_order'], $pupil['class_section'], $pupil['class_option'], $pupil['school_year'], $classes_alignment);
        // } else {
        // }
    }

    foreach($classes[0] as $cle => $class) {
            $classes_alignment = $class['cycle_id'] ." ". $class['class_id'] ." ". $class['order_id'] ." ". $class['section_id'] ." ". $class['option_id'] ." ". $class['school_year'];
            insert_class_completed_export($class['id_classes'], $class['cycle_id'], $class['class_id'], $class['order_id'], $class['section_id'], $class['option_id'], $class['school_year'], $classes_alignment);

// echo($class);
    }

    // foreach($libelles[0] as $cle => $libelle) {

    //     $verify_query = "SELECT libelle_id, COUNT(*) AS count_libelle_exist FROM libelles WHERE libelle_id=?";
    //     $verify_request = $database_connect_export->prepare($verify_query);
    //     $verify_request->execute(array($libelle['libelle_id']));
    //     $verify_response = $verify_request->fetchObject();

    //     if ($verify_response->count_paiement_exist === 0) {
    //         $insert_query = "INSERT INTO frais_divers(frais_divers_id, pupil_id, libelle, montant, school_year, date_entry, deleted, visible_print)
    //                 VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
    //         $insert_request = $database_connect_export->prepare($insert_query);
    //         $insert_request->execute(array($frais_divers['frais_divers_id'], $frais_divers['pupil_id'], $frais_divers['libelle'], $frais_divers['montant'], $frais_divers['school_year'], $frais_divers['date_entry'], $frais_divers['deleted'], $frais_divers['visible_print']));
    //     } else {
    //         // $edit0 = "UPDATE paiements SET paiement_validated=? WHERE paiement_id=?";
    //         // $edit = $database_connect_export->prepare($edit0);
    //         // $edit->execute(array($paiement['paiement_validated'], $paiement['paiement_id']));
    //     }
    // }

    // $response['classes'] = $element['classes_completed'];
    $response['success'] = '1';
    echo json_encode($response);


?>