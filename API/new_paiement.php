<?php

    include '../config/dbconnect.functions.php';

	header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $pupil_id = htmlspecialchars(trim(strip_tags($_POST['pupil_paiement'])));
    $input_montant = htmlspecialchars(trim(strip_tags($_POST['input_montant'])));
    $input_montant_text = htmlspecialchars(trim(strip_tags($_POST['input_montant_text'])));
    $selectionner_le_libelle = htmlspecialchars(trim(strip_tags($_POST['selectionner_le_libelle'])));
    $total_montant = htmlspecialchars(trim(strip_tags($_POST['total_montant'])));
    $school_year_input = htmlspecialchars(trim(strip_tags($_POST['school_year'])));
    $date = htmlspecialchars(trim(strip_tags($_POST['date_entry'])));
    // $day = date('d');
    // $month = date('m');
    // $year = date('Y');
    // $date = $day."/".$month."/".$year;
    $paiement_validated = 1;

    $response = array();
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

    $insert00 = "INSERT INTO paiements(pupil_id, montant_paye, montant_text, libelle, total_montant, school_year, paiement_validated, date_creation)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
    $insert = $database_connect->prepare($insert00);
    $insert->execute(array($pupil_id, $input_montant, $input_montant_text, $selectionner_le_libelle, $total_montant, $school_year_input, $paiement_validated, $date));

    $edit0 = "UPDATE paiements SET total_montant=? WHERE pupil_id=? AND school_year=?";
    $edit = $database_connect->prepare($edit0);
    $edit->execute(array($total_montant, $pupil_id, $school_year_input));

    $query_pupils_class = "SELECT * FROM pupils_info WHERE pupil_id='$pupil_id'";
    $request_pupils_class = $database_connect->query($query_pupils_class);
    while($response_pupils_class = $request_pupils_class->fetchObject()) {

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

        $queryfrais = "SELECT * FROM frais_divers WHERE pupil_id='$pupil_id' ORDER BY frais_divers_id DESC";
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

    $response['success'] = "1";
    $response['pupil'] = $pupil;
    echo json_encode($response);

  ?>
