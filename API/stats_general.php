<?php

    require_once("../config/dbconnect.functions.php");
    require_once("../config/functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $classes = array();
    $nbr_eleves = 0;
    $ayant_paye = 0;
    $montants_payes = 0;
    $montant_total = 0;
    $ttrims = 0;
    $annee = $_POST['school_year'];
    $t1_percentages = array();
    $t2_percentages = array();
    $t3_percentages = array();
    $t1payed = 0;
    $t2payed = 0;
    $t3payed = 0;
    $categories = array();
    $frais_divers = array();
    $ffmontant = 0;
    $ffnombre = 0;

    // $categories_query = "SELECT * FROM paiement_categories WHERE school_year='$annee'";
    // $categories_request = $database_connect->query($categories_query);
    // $categories_response = $categories_request->fetchObject();

    $query_fetch00 = "SELECT id_classes, cycle_id, class_id, order_id, section_id, option_id, school_year, classes_alignment FROM classes_completed WHERE school_year=? ORDER BY classes_alignment";
    $query_fetch11 = $database_connect->prepare($query_fetch00);
    $query_fetch11->execute(array($annee));
    while($query_fetch = $query_fetch11->fetchObject())
    {
        $pupils_number = 0;
        $filles = 0;
        $garcons = 0;
        $cycle_name = find_cycle_name($query_fetch->cycle_id);
        $class_number = find_class_number($query_fetch->class_id);
        $order_name = find_order_name($query_fetch->order_id);
        $section_name = find_section_name($query_fetch->section_id);
        $option_name = find_option_name($query_fetch->option_id);

        if($class_number == 1) {
            $class_number = "1 ère";
        } else {
            $class_number = "$class_number ème";
        }

        $classe = array();
        $total_classe = 0;
        $payed_classe = 0;
        $classe_categories = array();
        $classe['classe'] = $query_fetch;
        $classe['name'] = $class_number . " " . $section_name . " " . $cycle_name . " " . $option_name . " " . $order_name;

        $query = "SELECT * FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? ORDER BY first_name ASC, second_name ASC, last_name ASC";
        $request = $database_connect->prepare($query);
        $request->execute(array($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->order_id, $query_fetch->section_id, $query_fetch->option_id, $annee));
        while($response_pupil = $request->fetchObject()) {
            $pupil_category = array();
            $pupils_number = $pupils_number + 1;
            if($response_pupil->gender == '1') {
                $garcons = $garcons + 1;
            } else {
                $filles = $filles + 1;
            }

            // if($response_pupil->paiement_category == '') {
            //     $modify_query = "UPDATE pupils_info SET paiement_category='$categories_response->category_id' WHERE pupil_id='$response_pupil->pupil_id'";
            //     $modify_request = $database_connect->query($modify_query);
            // }

            if($response_pupil->paiement_category != '0') {
                $category_query = "SELECT * FROM paiement_categories WHERE category_id='$response_pupil->paiement_category' AND school_year='$annee'";
                $category_request = $database_connect->query($category_query);
                $category_response = $category_request->fetchObject();

                $montant_total = $montant_total + $category_response->category_amount;
                $total_classe = $total_classe + $category_response->category_amount;
                $ttrims = $ttrims + $category_response->category_amount / 3;
                $total = $category_response->category_amount;
                array_push($categories, $category_response->category_id);
                array_push($classe_categories, $category_response->category_id);
            } else {
                $total = 0;
                array_push($categories, '0');
                array_push($classe_categories, '0');
            }

            $query_countc = "SELECT pupil_id, COUNT(*) AS count_paiements FROM paiements WHERE pupil_id=?";
            $request_countc = $database_connect->prepare($query_countc);
            $request_countc->execute(array($response_pupil->pupil_id));
            $response_countc = $request_countc->fetchObject();

            if ($response_countc->count_paiements != 0) {
                $querypaiements = "SELECT SUM(montant_paye) AS montant_paye FROM paiements WHERE pupil_id='$response_pupil->pupil_id' AND paiement_validated='1' ORDER BY paiement_id DESC";
                $requestpaiements = $database_connect->query($querypaiements);
                while($response_array_paiements = $requestpaiements->fetchObject()) {
                    $montants_payes = $montants_payes + $response_array_paiements->montant_paye;
                 
                    $payed = $response_array_paiements->montant_paye;
                    $payed_classe = $payed_classe + $response_array_paiements->montant_paye;
                    $t1 = $total/3;
                    $t2 = $t1*2;
                    $t3 = $t2;

                    $t1_payed = 0;
                    $t2_payed = 0;
                    $t3_payed = 0;
                    
                    if ($payed <= $t1) {
                        $t1_payed = $payed;
                        $t1payed = $t1payed + $payed;
                        $pourcentage = ($t1_payed * 100) / $t1;
                        array_push($t1_percentages, $pourcentage);
                    } 
                    else if($payed > $t1 && $payed <= $t2) {
                        if ($payed == $t2) {
                            $t1_payed = $payed - $t1;
                            $t1payed = $t1payed + $t1_payed;
                            $t2_payed = $payed - $t1_payed;
                            $t2payed = $t2payed + $t2_payed;
                            $pourcentage = ($t1_payed * 100)/$t1;
                            $pourcentage2 = ($t2_payed * 100)/$t2;
                            array_push($t1_percentages, $pourcentage);
                            array_push($t2_percentages, $pourcentage2);
                        } else {
                            $t2_payed = $payed - $t1;
                            $t1_payed = $payed - $t2_payed;
                            $t1payed = $t1payed + $t1_payed;
                            $t2payed = $t2payed + $t2_payed;
                            $pourcentage = ($t1_payed * 100)/$t1;
                            $pourcentage2 = ($t2_payed * 100)/$t2;
                            array_push($t1_percentages, $pourcentage);
                            array_push($t2_percentages, $pourcentage2);
                        }
                    } 
                    else if ($payed > $t2) {
                        if($payed == $total) {
                            $t1_payed = $payed/3;
                            $t2_payed = $t1_payed;
                            $t2payed = $t2payed + $t2_payed;
                            $t1payed = $t1payed + $t1_payed;
                            $t3_payed = $payed - ($t1_payed + $t2_payed);
                            $t3payed = $t3payed + $t3_payed;
                            $pourcentage = ($t1_payed * 100)/$t1;
                            $pourcentage2 = ($t2_payed * 100)/$t2;
                            $pourcentage3 = ($t3_payed * 100)/$t3;
                            array_push($t1_percentages, $pourcentage);
                            array_push($t2_percentages, $pourcentage2);
                            array_push($t3_percentages, $pourcentage3);
                        } else {
                            $t3_payed = $payed - ($t1*2);
                            $t1_payed = ($payed - $t3_payed)/2;
                            $t2_payed = $t1_payed;
                            $t1payed = $t1payed + $t1_payed;
                            $t2payed = $t2payed + $t2_payed;
                            $t3payed = $t3payed + $t3_payed;
                            
                            if($t1 != 0) {
                                $pourcentage = ($t1_payed * 100)/$t1;
                            } else {
                                $pourcentage = 0;
                            }

                            if ($t2 != 0) {
                                $pourcentage2 = ($t2_payed * 100)/$t2;
                            } else {
                                $pourcentage2 = 0;
                            }

                            if ($t3 != 0) {
                                $pourcentage3 = ($t3_payed * 100)/$t3;
                            } else {
                                $pourcentage3 = 0;
                            }
                            
                            array_push($t1_percentages, $pourcentage);
                            array_push($t2_percentages, $pourcentage2);
                            array_push($t3_percentages, $pourcentage3);
                        }
                    } else { }
                }
            } else {
                array_push($t1_percentages, 0);
                array_push($t2_percentages, 0);
                array_push($t3_percentages, 0);
            }

            $queryfrais = "SELECT * FROM frais_divers WHERE pupil_id='$response_pupil->pupil_id' AND deleted='0'";
            $requestfrais = $database_connect->query($queryfrais);
            while($response_array_frais = $requestfrais->fetchObject()) {

                $frais = array();
                $frais['libelle'] = $response_array_frais->libelle;
                $frais['montant'] = $response_array_frais->montant;
                $ffmontant = $ffmontant + $response_array_frais->montant;
                $ffnombre = $ffnombre + 1;
                array_push($frais_divers, $frais);
            }
        }

        $classe['total_classe'] = $total_classe;
        $classe['payed_classe'] = $payed_classe;
        $classe['pupils'] = $pupils_number;
        $classe['filles'] = $filles;
        $classe['garcons'] = $garcons;
        $classe['frais'] = $frais_divers;
        $classe['classe_categories'] = $classe_categories;
        array_push($classes, $classe);
    }

    

    // $query = "SELECT * FROM paiements WHERE school_year=? AND paiement_validated=?";
    // $request = $database_connect->prepare($query);
    // $request->execute(array($annee, "1"));
    // while($response = $request->fetchObject()) {
    //     array_push($paiements, $response);
    // }

    $response['classes'] = $classes;
    $response['categories'] = $categories;
    $response['montants_payes'] = $montants_payes;
    $response['ttrims'] = $ttrims;
    $response['montant_total'] = $montant_total;
    $response['t1_payed'] = $t1payed;
    $response['t2_payed'] = $t2payed;
    $response['t3_payed'] = $t3payed;
    $response['ffmontant'] = $ffmontant;
    $response['ffnombre'] = $ffnombre;
    $response['frais_divers'] = $frais_divers;
    $response['t1_percentages'] = $t1_percentages;
    $response['t2_percentages'] = $t2_percentages;
    $response['t3_percentages'] = $t3_percentages;
    echo json_encode($response);

?>
