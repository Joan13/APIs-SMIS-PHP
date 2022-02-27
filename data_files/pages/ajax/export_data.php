<?php

     include '../../config/dbconnect.functions.php';
	include '../../config/home.secretor.functions.php';

     $pupils = array();
     $paiements = array();
     $frais_divers = array();
     $libelles = array();
     $classes_completed = array();
     $classes = array();
     $orders = array();
     $cycles = array();
     $depenses = array();
     $school_years = array();

     $query_pupils = "SELECT * FROM pupils_info";
     $request_pupils = $database_connect->query($query_pupils);
     while($response_pupils = $request_pupils->fetchObject()) {
          array_push($pupils, $response_pupils);
     }


     $query_paiements = "SELECT * FROM paiements";
     $request_paiements = $database_connect->query($query_paiements);
     while($response_paiements = $request_paiements->fetchObject()) {
          array_push($paiements, $response_paiements);    
     }

     $query_frais_divers = "SELECT * FROM frais_divers";
     $request_frais_divers = $database_connect->query($query_frais_divers);
     while($response_frais_divers = $request_frais_divers->fetchObject()) {
          array_push($frais_divers, $response_frais_divers);
     }

     $query_libelles = "SELECT * FROM libelles";
     $request_libelles = $database_connect->query($query_libelles);
     while($response_libelles = $request_libelles->fetchObject()) {
          array_push($libelles, $response_libelles);
     }

     $query_classes_completed = "SELECT * FROM classes_completed";
     $request_classes_completed = $database_connect->query($query_classes_completed);
     while($response_classes_completed = $request_classes_completed->fetchObject()) {
          array_push($classes_completed, $response_classes_completed);
     }

     $query_classes = "SELECT * FROM classes";
     $request_classes = $database_connect->query($query_classes);
     while($response_classes = $request_classes->fetchObject()) {
          array_push($classes, $response_classes);
     }

     $query_class_orders = "SELECT * FROM class_order";
     $request_class_orders = $database_connect->query($query_class_orders);
     while($response_class_orders = $request_class_orders->fetchObject()) {
          array_push($orders, $response_class_orders);
     }

     $query_cycle = "SELECT * FROM cycle";
     $request_cycle = $database_connect->query($query_cycle);
     while($response_cycle = $request_cycle->fetchObject()) {
          array_push($cycles, $response_cycle);
     }

     $query_depenses = "SELECT * FROM depenses";
     $request_depenses = $database_connect->query($query_depenses);
     while($response_depenses = $request_depenses->fetchObject()) {
          array_push($depenses, $response_depenses);
     }

     $query_school_years = "SELECT * FROM school_years";
     $request_school_years = $database_connect->query($query_school_years);
     while($response_school_years = $request_school_years->fetchObject()) {
          array_push($school_years, $response_school_years);
     }
?>

<script type="text/javaScript" src="../../design/dynamic/jquery-3.2.1.min.js"></script>
<script type="text/javaScript">
	$(document).ready(function() {

		let ppupils = <?= json_encode($pupils) ?>;
		$.post('http://185.98.137.117/Yambi_class_export/export_pupils.php', { ppupils }, () => {});

          let paiements = <?= json_encode($paiements) ?>;
		$.post('http://185.98.137.117/Yambi_class_export/export_paiements.php',{ paiements }, () => {});

          let frais_divers = <?= json_encode($frais_divers) ?>;
		$.post('http://185.98.137.117/Yambi_class_export/export_frais_divers.php',{ frais_divers }, () => {});

          let libelles = <?= json_encode($libelles) ?>;
		$.post('http://185.98.137.117/Yambi_class_export/export_libelles.php',{ libelles }, () => {});

          let classes_completed = <?= json_encode($classes_completed) ?>;
		$.post('http://185.98.137.117/Yambi_class_export/export_classes_completed.php',{ classes_completed }, () => {});

          let classes = <?= json_encode($classes) ?>;
		$.post('http://185.98.137.117/Yambi_class_export/export_classes.php',{ classes }, () => {});

          let orders = <?= json_encode($orders) ?>;
		$.post('http://185.98.137.117/Yambi_class_export/export_orders.php',{ orders }, () => {});

          let cycles = <?= json_encode($cycles) ?>;
		$.post('http://185.98.137.117/Yambi_class_export/export_cycles.php',{ cycles }, () => {});

          let depenses = <?= json_encode($depenses) ?>;
		$.post('http://185.98.137.117/Yambi_class_export/export_depenses.php',{ depenses }, () => {});

          let school_years = <?= json_encode($school_years) ?>;
		$.post('http://185.98.137.117/Yambi_class_export/export_school_years.php',{ school_years }, () => {});
	});
</script>
