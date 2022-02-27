<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/class.marksheet.insert.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();
    $classes = array();

    $annee = $_POST['annee'];
    $periode = $_POST['periode'];
    $all_pupils = 0;
    $nbr_ee = 0;
    $nbr_tb = 0;
    $nbr_bb1 = 0;
    $nbr_bb2 = 0;
    $nbr_me = 0;
    $nbr_ma = 0;
    $nbr_classes = 0;

    $query_fetch00 = "SELECT id_classes, cycle_id, class_id, order_id, section_id, option_id, school_year, classes_alignment FROM classes_completed WHERE school_year=? ORDER BY classes_alignment";
    $query_fetch11 = $database_connect->prepare($query_fetch00);
    $query_fetch11->execute(array($annee));
    while($query_fetch = $query_fetch11->fetchObject())
    {
        $nbr_classes = $nbr_classes + 1;

        $cycle_name = find_cycle_name($query_fetch->cycle_id);
        $class_number = find_class_number($query_fetch->class_id);
        $order_name = find_order_name($query_fetch->order_id);
        $section_name = find_section_name($query_fetch->section_id);
        $option_name = find_option_name($query_fetch->option_id);

        if($class_number == 1)
        {
            $class_number = "1 ère";
        }
        else
        {
            $class_number = "$class_number ème";
        }

        $class = array();
        $class['id_classes'] = $query_fetch->id_classes;
        $class['cycle_id'] = $cycle_name;
        $class['class_id'] = $class_number;
        $class['order_id'] = $order_name;
        $class['section_id'] = $section_name;
        $class['option_id'] = $option_name;
        $class['pupils_count'] = nbr_pupils_class($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->order_id, $query_fetch->section_id, $query_fetch->option_id, $query_fetch->school_year);
        $class['pupils_count_male'] = nbr_pupils_class_male($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->order_id, $query_fetch->section_id, $query_fetch->option_id, $query_fetch->school_year);
        $class['pupils_count_female'] = nbr_pupils_class_female($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->order_id, $query_fetch->section_id, $query_fetch->option_id, $query_fetch->school_year);
        $class['cycle'] = $query_fetch->cycle_id;
        $class['class'] = $query_fetch->class_id;
        $class['order'] = $query_fetch->order_id;
        $class['section'] = $query_fetch->section_id;
        $class['option'] = $query_fetch->option_id;
        $class['school_year'] = $query_fetch->school_year;
        
        $all_pupils = $all_pupils + nbr_pupils_class($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->order_id, $query_fetch->section_id, $query_fetch->option_id, $query_fetch->school_year);

        $ee = 0;
        $tb = 0;
        $bb1 = 0;
        $bb2 = 0;
        $me = 0;
        $ma = 0;
        $pourcentages = array();
        $pre_moyennes = 0;

        $query_pupils = "SELECT * FROM pupils_info WHERE school_year='$annee' AND class_school='$query_fetch->class_id' AND cycle_school='$query_fetch->cycle_id' AND class_order='$query_fetch->order_id' AND class_section='$query_fetch->section_id' AND class_option='$query_fetch->option_id'";
        $request_pupils = $database_connect->query($query_pupils);
        while($response_pupils = $request_pupils->fetchObject()) {
            
            if ($periode == 1 || $periode == 2 || $periode == 3 || $periode == 4 || $periode == 10 || $periode == 11) {
                if(find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee) >= 80) {
                    $ee = $ee + 1;
                    $nbr_ee = $nbr_ee + 1;
                } else if(find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee) < 80 && find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee) >= 70) {
                    $tb = $tb + 1;
                    $nbr_tb = $nbr_tb + 1;
                } else if(find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee) < 70 && find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee) >= 60) {
                    $bb1 = $bb1 + 1;
                    $nbr_bb1 = $nbr_bb1 + 1;
                } else if(find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee) < 60 && find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee) >= 50) {
                    $bb2 = $bb2 + 1;
                    $nbr_bb2 = $nbr_bb2 + 1;
                } else if(find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee) < 50 && find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee) >= 40) {
                    $me = $me + 1;
                    $nbr_me = $nbr_me + 1;
                } else {
                    $ma = $ma + 1;
                    $nbr_ma = $nbr_ma + 1;
                }

                array_push($pourcentages, find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee));
                $pre_moyennes = $pre_moyennes + find_pupil_pourcentage($response_pupils->pupil_id, $periode, $annee);
            }

            if ($periode == 40) {
                if(find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee) >= 80) {
                    $ee = $ee + 1;
                    $nbr_ee = $nbr_ee + 1;
                } else if(find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee) < 80 && find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee) >= 70) {
                    $tb = $tb + 1;
                    $nbr_tb = $nbr_tb + 1;
                } else if(find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee) < 70 && find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee) >= 60) {
                    $bb1 = $bb1 + 1;
                    $nbr_bb1 = $nbr_bb1 + 1;
                } else if(find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee) < 60 && find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee) >= 50) {
                    $bb2 = $bb2 + 1;
                    $nbr_bb2 = $nbr_bb2 + 1;
                } else if(find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee) < 50 && find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee) >= 40) {
                    $me = $me + 1;
                    $nbr_me = $nbr_me + 1;
                } else {
                    $ma = $ma + 1;
                    $nbr_ma = $nbr_ma + 1;
                }

                array_push($pourcentages, find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee));
                $pre_moyennes = $pre_moyennes + find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 1, 2, 10, $annee);
            }

            if ($periode == 50) {
                if(find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee) >= 80) {
                    $ee = $ee + 1;
                    $nbr_ee = $nbr_ee + 1;
                } else if(find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee) < 80 && find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee) >= 70) {
                    $tb = $tb + 1;
                    $nbr_tb = $nbr_tb + 1;
                } else if(find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee) < 70 && find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee) >= 60) {
                    $bb1 = $bb1 + 1;
                    $nbr_bb1 = $nbr_bb1 + 1;
                } else if(find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee) < 60 && find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee) >= 50) {
                    $bb2 = $bb2 + 1;
                    $nbr_bb2 = $nbr_bb2 + 1;
                } else if(find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee) < 50 && find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee) >= 40) {
                    $me = $me + 1;
                    $nbr_me = $nbr_me + 1;
                } else {
                    $ma = $ma + 1;
                    $nbr_ma = $nbr_ma + 1;
                }

                array_push($pourcentages, find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee));
                $pre_moyennes = $pre_moyennes + find_pupil_pourcentage_sem_trim($response_pupils->pupil_id, 3, 4, 11, $annee);
            }

            if ($periode == 12) {
                if(find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee) >= 80) {
                    $ee = $ee + 1;
                    $nbr_ee = $nbr_ee + 1;
                } else if(find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee) < 80 && find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee) >= 70) {
                    $tb = $tb + 1;
                    $nbr_tb = $nbr_tb + 1;
                } else if(find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee) < 70 && find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee) >= 60) {
                    $bb1 = $bb1 + 1;
                    $nbr_bb1 = $nbr_bb1 + 1;
                } else if(find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee) < 60 && find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee) >= 50) {
                    $bb2 = $bb2 + 1;
                    $nbr_bb2 = $nbr_bb2 + 1;
                } else if(find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee) < 50 && find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee) >= 40) {
                    $me = $me + 1;
                    $nbr_me = $nbr_me + 1;
                } else {
                    $ma = $ma + 1;
                    $nbr_ma = $nbr_ma + 1;
                }

                array_push($pourcentages, find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee));
                $pre_moyennes = $pre_moyennes + find_pupil_pourcentage_sem_trimt($response_pupils->pupil_id, 1, 2, 10, 3, 4, 11, $annee);
            }

        }

        $class['ee'] = $ee;
        $class['tb'] = $tb;
        $class['bb1'] = $bb1;
        $class['bb2'] = $bb2;
        $class['me'] = $me;
        $class['ma'] = $ma;
        $class['pourcentage_plus'] = max($pourcentages);
        $class['pourcentage_min'] = min($pourcentages);
        $class['pre_moyennes'] = $pre_moyennes;

        array_push($classes, $class);
    }

    $response['nbr_classes'] = $nbr_classes;
    $response['nbr_ee'] = $nbr_ee;
    $response['nbr_tb'] = $nbr_tb;
    $response['nbr_bb1'] = $nbr_bb1;
    $response['nbr_bb2'] = $nbr_bb2;
    $response['nbr_me'] = $nbr_me;
    $response['nbr_ma'] = $nbr_ma;
    $response['all_pupils'] = $all_pupils;
    $response['classes'] = $classes;
    echo json_encode($response);


?>