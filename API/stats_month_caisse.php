<?php

require_once("../config/dbconnect.functions.php");
require_once("../config/functions.php");

header("Access-Control-Allow-Origin: *");
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$response = array();
$paiements_day = 0;
$paiements = array();
$frais_divers = array();
$paiements_deleted = array();
$frais_divers_deleted = array();
$annee = $_POST['school_year'];
$day = $_POST['month'];
$classes = $_POST['classes'];

foreach ($classes as $value) {
    $query = "SELECT * FROM paiements WHERE school_year=?";
    $request = $database_connect->prepare($query);
    $request->execute(array($annee));
    while ($response_paiements = $request->fetchObject()) {

        if (substr($response_paiements->date_creation, 3, 2) == $day) {

            $query_classe = "SELECT * FROM classes_completed WHERE id_classes='$value'";
            $reques_classe = $database_connect->query($query_classe);
            $response_classe = $reques_classe->fetchObject();

            $query_pupil = "SELECT * FROM pupils_info WHERE pupil_id=? AND school_year=? AND cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=?";
            $request_pupil = $database_connect->prepare($query_pupil);
            $request_pupil->execute(array($response_paiements->pupil_id, $annee, $response_classe->cycle_id, $response_classe->class_id, $response_classe->order_id, $response_classe->section_id, $response_classe->option_id));
            $response_pupil = $request_pupil->fetchObject();

            if ($response_pupil != false) {
                $paiements_pupil = convert_class_to_array($response_paiements);
                $pupil = convert_class_to_array($response_pupil);
                $paiement = array_merge($paiements_pupil, $pupil);

                if ($response_paiements->paiement_validated == 1) {
                    array_push($paiements, $paiement);
                    $paiements_day = $paiements_day + $response_paiements->montant_paye;
                } else {
                    array_push($paiements_deleted, $paiement);
                }
            }
        }
    }

    $queryfd = "SELECT * FROM frais_divers WHERE school_year=?";
    $requestfd = $database_connect->prepare($queryfd);
    $requestfd->execute(array($annee));
    while ($responsefd = $requestfd->fetchObject()) {

        if (substr($responsefd->date_entry, 3, 2) == $day) {
            $query_classef = "SELECT * FROM classes_completed WHERE id_classes='$value'";
            $reques_classef = $database_connect->query($query_classef);
            $response_classef = $reques_classef->fetchObject();

            $query_pupilf = "SELECT * FROM pupils_info WHERE pupil_id=? AND school_year=? AND cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=?";
            $request_pupilf = $database_connect->prepare($query_pupilf);
            $request_pupilf->execute(array($responsefd->pupil_id, $annee, $response_classef->cycle_id, $response_classef->class_id, $response_classef->order_id, $response_classef->section_id, $response_classef->option_id));
            $response_pupilf = $request_pupilf->fetchObject();

            if ($response_pupilf != false) {
                $paiementsf = convert_class_to_array($responsefd);
                $pupilf = convert_class_to_array($response_pupilf);
                $paiementf = array_merge($paiementsf, $pupilf);

                if ($responsefd->deleted == 0) {
                    array_push($frais_divers, $paiementf);
                } else {
                    array_push($frais_divers_deleted, $paiementf);
                }
            }
        }
    }


}

$response['paiements'] = $paiements;
$response['frais_divers'] = $frais_divers;
$response['paiements_day'] = $paiements_day;
$response['frais_divers_deleted'] = $frais_divers_deleted;
$response['paiements_deleted'] = $paiements_deleted;
$response['month'] = $day;
echo json_encode($response);

?>