<?php

    require_once("../config/dbconnect.functions.php");
    require_once("../config/functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $pupil_id = $_POST['pupil_id'];

    $query_pupils_class = "SELECT * FROM pupils_info WHERE pupil_id='$pupil_id'";
    $request_pupils_class = $database_connect->query($query_pupils_class);
    while($response_pupils_class = $request_pupils_class->fetchObject()) {

        $pupil = array();
        $marks = array();
        $paiements = array();
        $frais_divers = array();
        $soldes_paiements = array();
        $montants_payes = 0;
        $montant_total = 0;
        $message_soldes_t1 = 0;
        $message_soldes_t2 = 0;
        $message_soldes_t3 = 0;
        $main_total_montant = 0;

        $querymarks = "SELECT * FROM marks_info WHERE pupil='$pupil_id'";
        $requestmarks = $database_connect->query($querymarks);
        while($response_array_marks = $requestmarks->fetchObject()) {
            array_push($marks, $response_array_marks);
        }

        $querypaiements = "SELECT * FROM paiements WHERE pupil_id='$pupil_id' ORDER BY paiement_id DESC";
        $requestpaiements = $database_connect->query($querypaiements);
        while($response_array_paiements = $requestpaiements->fetchObject()) {

            if ($response_array_paiements->paiement_validated == 1) {
                $montants_payes = $montants_payes + $response_array_paiements->montant_paye;
                if ($response_array_paiements->total_montant != 0) {
                    $montant_total = $response_array_paiements->total_montant;
                    $main_total_montant = $response_array_paiements->total_montant;
                } else {
                    $montant_total = $main_total_montant;
                }
            }
            
            array_push($paiements, $response_array_paiements);
        }

        $queryfrais = "SELECT * FROM frais_divers WHERE pupil_id='$pupil_id' AND deleted='0' ORDER BY frais_divers_id DESC";
        $requestfrais = $database_connect->query($queryfrais);
        while($response_array_frais = $requestfrais->fetchObject()) {
            
            array_push($frais_divers, $response_array_frais);
        }

            if($montant_total !== 0) {
                $s1 = $montant_total/3;
            } else {
                $s1 = $main_total_montant/3;
            }

            $s2 = $s1 + $s1;
            $s3 = $s2 + $s1;

            $montant = $montants_payes;
            if($montant != 0)
            {
                if($montant <= $s1)
                {
                    if($montant == $s1)
                    {
                        $message_soldes_t1 = "0";
                        $message_soldes_t2 = $s1;
                        $message_soldes_t3 = $s1;
                    }
                    else
                    {
                        $tr1 = $s1-$montant;
                        $message_soldes_t1 = "$tr1";
                        $message_soldes_t2 = $s1;
                        $message_soldes_t3 = $s1;
                    }
                }

                if($montant > $s1 && $montant <= $s2)
                {
                    if($montant == $s2)
                    {
                        $message_soldes_t1 = "0";
                        $message_soldes_t2 = "0";
                        $message_soldes_t3 = $s1;
                    }
                    else
                    {
                        $tr2 = $s2-$montant;
                        $message_soldes_t1 = "0";
                        $message_soldes_t2 = "$tr2";
                        $message_soldes_t3 = $s1;
                    }
                }

                if($montant > $s2)
                {
                    if($montant == $s3)
                    {
                        $message_soldes_t1 = "0";
                        $message_soldes_t2 = "0";
                        $message_soldes_t3 = "0";
                    }
                    else
                    {
                        $tr3 = $s3-$montant;
                        $message_soldes_t1 = "0";
                        $message_soldes_t2 = "0";
                        $message_soldes_t3 = "$tr3";
                    }
                }
            }
            else
            {
                $message_soldes_t1 = $s1;
                $message_soldes_t2 = $s1;
                $message_soldes_t3 = $s1;
            }

        $soldes_paiements['solde'] = $montant_total-$montants_payes;
        $soldes_paiements['solde1'] = $message_soldes_t1;
        $soldes_paiements['solde2'] = $message_soldes_t2;
        $soldes_paiements['solde3'] = $message_soldes_t3;
            
        $pupil['pupil'] = $response_pupils_class;
        $pupil['paiements'] = $paiements;
        $pupil['frais_divers'] = $frais_divers;
        $pupil['soldes'] = $soldes_paiements;
        $pupil['marks'] = $marks;
    }

    $response['pupil'] = $pupil;
    echo json_encode($response);


?>