<?php

    require_once("../config/dbconnect.functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $paiements = array();
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

    // $categories_query = "SELECT * FROM paiement_categories WHERE school_year='$annee'";
    // $categories_request = $database_connect->query($categories_query);
    // $categories_response = $categories_request->fetchObject();

    $query = "SELECT * FROM pupils_info WHERE school_year=? ORDER BY first_name ASC, second_name ASC, last_name ASC";
    $request = $database_connect->prepare($query);
    $request->execute(array($annee));
    while($response_pupil = $request->fetchObject()) {

        // if($response_pupil->paiement_category == '') {
        //     $modify_query = "UPDATE pupils_info SET paiement_category='$categories_response->category_id' WHERE pupil_id='$response_pupil->pupil_id'";
		//     $modify_request = $database_connect->query($modify_query);
        // }

        if($response_pupil->paiement_category != '0') {
            $category_query = "SELECT * FROM paiement_categories WHERE category_id='$response_pupil->paiement_category' AND school_year='$annee'";
            $category_request = $database_connect->query($category_query);
            $category_response = $category_request->fetchObject();

            $montant_total = $montant_total + $category_response->category_amount;
            $ttrims = $ttrims + $category_response->category_amount / 3;
        }

        $query_countc = "SELECT pupil_id, COUNT(*) AS count_paiements FROM paiements WHERE pupil_id=?";
        $request_countc = $database_connect->prepare($query_countc);
        $request_countc->execute(array($response_pupil->pupil_id));
        $response_countc = $request_countc->fetchObject();

        if ($response_countc->count_paiements != 0) {
            $querypaiements = "SELECT * FROM paiements WHERE pupil_id='$response_pupil->pupil_id' AND paiement_validated='1' ORDER BY paiement_id DESC";
            $requestpaiements = $database_connect->query($querypaiements);
            while($response_array_paiements = $requestpaiements->fetchObject()) {
                $montants_payes = $montants_payes + $response_array_paiements->montant_paye;
                
                $total = $response_array_paiements->total_montant;
                $payed = $response_array_paiements->montant_paye;
                $t1 = $total/3;
                $t2 = $t1 + $t1;
                $t3 = $t2 + $t1;

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
                    $t2_payed = $payed - $t1_payed;
                    $t2payed = $t2payed + $t2_payed;
                    $pourcentage = ($t2_payed * 100) / $t2;
                    array_push($t2_percentages, $pourcentage);
                } 
                else if ($payed > $t2) {
                    $t3_payed = $payed - ($t1_payed + $t2_payed);
                    $t3payed = $t3payed + $t3_payed;
                    $pourcentage = ($t3_payed * 100) / $t3;
                    array_push($t3_percentages, $pourcentage);
                } else {
                    // $t3payed = $t3payed + $t3_payed;
                    // $t1_payed = $payed;
                    // $pourcentage = ($payed * 100) / $payed;
                    // array_push($t1_percentages, $pourcentage);
                }
            }
        } else {
            array_push($t1_percentages, 0);
            array_push($t2_percentages, 0);
            array_push($t3_percentages, 0);
        }
    }

    

    // $query = "SELECT * FROM paiements WHERE school_year=? AND paiement_validated=?";
    // $request = $database_connect->prepare($query);
    // $request->execute(array($annee, "1"));
    // while($response = $request->fetchObject()) {
    //     array_push($paiements, $response);
    // }

    // $response['paiements'] = $paiements;
    $response['montants_payes'] = $montants_payes;
    $response['ttrims'] = $ttrims;
    $response['montant_total'] = $montant_total;
    $response['t1_payed'] = $t1payed;
    $response['t2_payed'] = $t2payed;
    $response['t3_payed'] = $t3payed;
    $response['t1_percentages'] = $t1_percentages;
    $response['t2_percentages'] = $t2_percentages;
    $response['t3_percentages'] = $t3_percentages;
    echo json_encode($response);

?>
