	<?php 

	require_once('../config/dbconnect.functions.php');
	include '../config/pupil.marks.functions.php';

	$cycle_name = find_cycle_name($_GET['cycle']);
	$class_number = find_class_number($_GET['class_id']);
	$true_class_number = find_class_number($_GET['class_id']);
	$order_name = find_order_name($_GET['order_id']);
	$section_name = find_section_name($_GET['section_id']);
	$option_name = find_option_name($_GET['option_id']);
	$school_year = find_school_year($_GET['school_year']);
	$yearr = $_GET['school_year'];

	$toUpper_class_name = strtoupper($cycle_name);
	$array_places_1 = array();
	$array_places_2 = array();
	$array_places_10 = array();
	$array_places_tot1 = array();

	if(count_pupils_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
	{
		$query_fetch00b = "SELECT * FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? ORDER BY first_name ASC";
		$query_fetch11b = $database_connect->prepare($query_fetch00b);
		$query_fetch11b->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
		while($query_fetchb = $query_fetch11b->fetchObject())
		{
			array_push($array_places_1, find_pupil_sum_main_marks($query_fetchb->pupil_id, 1, $query_fetchb->school_year));
			array_push($array_places_2, find_pupil_sum_main_marks($query_fetchb->pupil_id, 2, $query_fetchb->school_year));
			array_push($array_places_10, find_pupil_sum_main_marks($query_fetchb->pupil_id, 10, $query_fetchb->school_year));
			array_push($array_places_tot1, find_pupil_sum_main_marks_sem_trim($query_fetchb->pupil_id, 1, 2, 10, $query_fetchb->school_year));
		}
	}
	rsort($array_places_1);
	rsort($array_places_2);
	rsort($array_places_10);
	rsort($array_places_tot1);

	$voirQuery = "SELECT school_year, COUNT(*) AS count_info FROM base_school_info WHERE school_year=?";
     $voirRequest = $database_connect->prepare($voirQuery);
     $voirRequest->execute(array($yearr));
	$voirResponse = $voirRequest->fetchObject();

	if($voirResponse->count_info != 0) {

		$que1111 = "SELECT * FROM base_school_info WHERE school_year=?";
		$que111111 = $database_connect->prepare($que1111);
		$que111111->execute(array($yearr));
		$que0011 = $que111111->fetchObject();

		$ttt = $que0011->date_end;
		$tt_year = substr($ttt, 0, 4);
		$tt_month = generate_month(substr($ttt, 5, 2));
		$tt_day = substr($ttt, 8, 2);
		$date_end = $tt_day." ".$tt_month." ".$tt_year;

		$chef = $que0011->name_promoter;
		$code = $que0011->code_school;

	} else {
		$chef = "Chef d'établissement";
		$date_end = "Date fin d'année";
		$code = "00000000000";
	}

	$class_number_letter = $class_number;
	if ($class_number_letter == 1) {
		$class_number_letter = "PREMIERE";
	} else if ($class_number_letter == 2) {
		$class_number_letter = "DEUXIEME";
	} else if ($class_number_letter == 3) {
		$class_number_letter = "TROISIEME";
	} else if ($class_number_letter == 4) {
		$class_number_letter = "QUATRIEME";
	} else if ($class_number_letter == 5) {
		$class_number_letter = "CINQUIEME";
	} else if ($class_number_letter == 6) {
		$class_number_letter = "SIXIEME";
	} else if ($class_number_letter == 7) {
		$class_number_letter = "SEPTIEME";
	} else if ($class_number_letter == 8) {
		$class_number_letter = "HUITIEME";
	} else {
		$class_number_letter = "NIEME";
	}

	if($class_number == 1)
	{
		$class_number = "1 ère";
	}
	else
	{
		$class_number = $class_number." ème";
	}

	if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL")
	{
		$class_identity = "$class_number $cycle_name $order_name";
		$class_identity_bis = $class_number_letter." ".$cycle_name;
	}

	if($toUpper_class_name == "PRIMAIRE")
	{
		$class_identity = "$class_number $cycle_name $order_name";
		$class_identity_bis = $class_number_letter." ".$cycle_name;
	}

	if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
	{
		if($true_class_number < 7)
		{
			if($option_name == "-")
			{
				$option_name = "";
			}
			else
			{
				$option_name_bis = $option_name;
				$option_name = " ".$option_name;
			}

			$class_identity = $class_number." ".$section_name."".$option_name;
			$class_identity_bis = $class_number_letter." ANNEE HUMANITE ".$section_name."".$option_name;

			// if ($class_number > 2) {

			// 	$class_identity_bis = $class_number_letter." ".$option_name_bis;
			// }

			// else if ($class_number == 4 && strtoupper($section_name) == "SCIENTIFIQUE") {

			// 	$class_identity_bis = $class_number_letter." ".$section_name;

			// } else {

			// 	$class_identity_bis = $class_number_letter." ".$cycle_name;
			// }
		}
		else
		{
			$class_identity = $class_number." CTEB ".$order_name;
			$class_identity_bis = $class_number_letter." ".$cycle_name;
		}
	}

	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style.css" />
		<title>Yambi class SMIS</title>
	</head>
	<body>
	<div class="main_middle_container">

		<style>
			.class_table tr{
				/* overflow: hidden;
				height: 14px;
				white-space: nowrap; */
			}

			td {
				height: 10px;
			}

			.ok_print {
				/* break-inside: avoid; */
			}

		</style>
		<br/><div id="bulletins_draft_class"><?php

		if(count_pupils_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
		{
			$query_fetch00 = "SELECT * FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? ORDER BY first_name ASC";
			$query_fetch11 = $database_connect->prepare($query_fetch00);
			$query_fetch11->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
			while($query_fetch = $query_fetch11->fetchObject())
			{
				// array_push($array_places_1, find_pupil_sum_main_marks($query_fetch->pupil_id, 1, $query_fetch->school_year));

				$exist0000 = "SELECT astuce_bulletin, COUNT(*) AS astuce_bulletin FROM other_settings";
				$exist1100 = $database_connect->query($exist0000);
				$exist00 = $exist1100->fetchObject();
				if ($exist00->astuce_bulletin == 0)
				{
					$insert0000 = "INSERT INTO other_settings(astuce_bulletin) VALUES(?)";
					$insert00 = $database_connect->prepare($insert0000);
					$insert00->execute(array(0));
				}
				else
				{
					$edit0000 = "UPDATE other_settings SET astuce_bulletin=?";
					$edit00 = $database_connect->prepare($edit0000);
					$edit00->execute(array(0));
				}

				// if(selected_pupil_exists($query_fetch->pupil_id) != 0)
				// {
					$que00 = "SELECT * FROM pupils_info WHERE pupil_id=?";
					$que11 = $database_connect->prepare($que00);
					$que11->execute(array($query_fetch->pupil_id));
					$que = $que11->fetchObject();
				// }

				$ttt = $que->birth_date;
				$tt_year = substr($ttt, 0, 4);
				$tt_month = generate_month(substr($ttt, 5, 2));
				$tt_day = substr($ttt, 8, 2);
				$birth_date = $tt_day." ".$tt_month." ".$tt_year;

				$ggender = $que->gender;
				if ($ggender == 1) {
					$ggender = "M";
				} else {
					$ggender = "F";
				}

				if($_GET['class_id'] == 1) {

					$pouurcentagee = pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) + pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']);
					$pouurcentage = ($pouurcentagee * 100) / 400;

				?>
				<div class="ok_print" style="height: 100%; margin-bottom: 10px;">
					<table class="class_table" style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;">
						<tr style="">
							<td style="border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<img style="float: left; min-width: 100px;" src="images/other/flag_drc.jpg" height ="60" />
							</td>
							<td style="font-weight: bold; border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<span style="font-size: 17px;">REPUBLIQUE DEMOCRATIQUE DU CONGO</span><br/>
								<span style="font-size: 17px;">MINISTERE DE L'ENSEIGNEMENT PRIMAIRE, SECONDAIRE ET TECHNIQUE</span>
							</td>
							<td style="border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<img style="float: right; min-width: 70px;" src="images/other/armoirie_rdc.png" height ="60" />
							</td>
						</tr>
					</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="border: none; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">
								<div style=" padding: 0px; color: transparent;">
									<span style="font-weight: bold; padding: 5px; border-right: none; padding-left: 7px; padding-right: 7px; color: black;">No ID. :</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 0, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 1, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 2, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 3, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 4, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 5, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 6, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 7, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 8, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 9, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 10, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 11, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 12, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 13, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 14, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 15, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 16, 1) ?></span><span style="border: 1px solid black; border-right: 1px solid black; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 17, 1) ?></span>
									<!-- <span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span> -->
								</div>
							</td>
						</tr>
					</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
							<tr>
								<td style="border: none; font-weight: bold; font-size: 11px; height: 10px; padding-left: 10px; text-align: left;">
									PROVINCE : SUD-KIVU
								</td>
							</tr>
						</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="padding: 5px; padding-left: 10px; border-bottom: none; text-align: left; width: 50%;">
								<span>VILLE : </span><strong><?= $school_city ?></strong><br/>
								<span>COMMUNE : </span><strong><?= $school_commune ?></strong><br/>
								<span>ECOLE : </span><strong><?= $school_name ?></strong><br/>
								<span style="border-right: none; padding-left: 0px; padding-right: 7px; color: black; display: inline-block; padding-top: 2px;">CODE : </span>
								<span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 0, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 1, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 2, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 3, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 4, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 5, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 6, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 7, 1) ?></span>
							</td>
							<td style="padding: 5px; padding-left: 10px; border-bottom: none; text-align: left; width: 50%;">
								<span>ELEVE : </span><strong><?= strtoupper($que->first_name." ".$que->second_name)." ".$que->last_name?></strong><span style="margin-left: 50px;">SEXE : </span><strong><?= $ggender ?></strong><br/>
								<span>NE (E) A : </span><strong><?= $que->birth_place ?></strong> <?php if($que->birth_place == "") {} else{ echo ", LE";} ?> <strong><?= $birth_date ?></strong><br/>
								<span>CLASSE : </span><strong><?= $class_identity ?></strong><br/>
								<span style="border-right: none; padding-left: 0px; padding-right: 7px; color: black; display: inline-block; padding-top: 2px;">No PERM. : </span>
								<span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 0, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 1, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 2, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 3, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 4, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 5, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 6, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 7, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 8, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 9, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 10, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 11, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 12, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 13, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 14, 1) ?></span>
							</td>
						</tr>
					</table>

						<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
							<tr>
								<td style="border: none; font-weight: bold; font-size: 11px; height: 10px;">
									<span>BULLETIN DE LA 7e ANNEE CYCLE TERMINAL DE L'EDUCATION DE BASE (CETB)<span
										style="color: transparent;">......</span>
										ANNEE SCOLAIRE <?= $school_year ?></span>
								</td>
							</tr>
						</table>

						<table  style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
							<tr>
								<td rowspan="3" style="font-weight: bold; border: 1px solid black; width: 500px;">
									BRANCHES
								</td>
								
								<td colspan="7" style="font-weight: bold; border: 1px solid black; font-size: 12px; height: 10px;">
									PREMIER TRIMESTRE
								</td>
								
								<td colspan="7" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px; height: 10px;">
									DEUXIEME TRIMESTRE
								</td>
								<td colspan="2" rowspan="3" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px;  height: 10px;">
									TOTAL GENERAL
								</td>
								<td rowspan="5" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px; background-color: black; height: 10px;">
									000
								</td>
								<td colspan="2" rowspan="2" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px; border-right: 2px solid black; height: 10px;">
									EXAMEN DE REPECHAGE
								</td>
							</tr>
							<tr>
								<td rowspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									MAX.<br />
								</td>
								<td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									TRAVAUX JOURNAL.<br />
								</td>


								<td rowspan="2" style="border: 1px solid black; border-right: none; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									MAX.<br/>
								</td>
								<td rowspan="2" style="border: 1px solid black; border-left: none; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									EXAM.
								</td>
								<td rowspan="2" style="border: 1px solid black; border-right: none; font-size: 10px; height: 10px; width: 80px;font-weight: bold; text-align: right; padding-right: 0px;">
									TO<br/>
								</td>
								<td rowspan="2" style="border: 1px solid black; border-left: none; font-size: 10px; height: 10px; width: 80px;font-weight: bold; text-align: left;">
									TAL
								</td>

								<td rowspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									MAX.<br />
								</td>
								<td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									TRAVAUX JOURNAL.<br />
								</td>


								<td rowspan="2" style="border: 1px solid black; border-right: none; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									MAX.<br/>
								</td>
								<td rowspan="2" style="border: 1px solid black; border-left: none; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									EXAM.
								</td>
								<td rowspan="2" style="border: 1px solid black; border-right: none; font-size: 10px; height: 10px; width: 80px;font-weight: bold; text-align: right; padding-right: 0px;">
									TO<br/>
								</td>
								<td rowspan="2" style="border: 1px solid black; border-left: none; font-size: 10px; height: 10px; width: 80px;font-weight: bold; text-align: left;">
									TAL
								</td>
							</tr>
							<tr>
								
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									1P
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									2P
								</td>
								


								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									3P
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									4P
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; border-right: 2px solid black;">
									Sign. prof.
								</td>



								
							</tr>
							<tr style="white-space: nowrap;">
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 100%; font-weight: bold; background-color: rgba(0, 0, 0, 0.2);">
									DOMAINE DES SCIENCES
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									Sign. prof.
								</td>
							</tr>
							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2);">
									Sous-domaine des Mathématiques
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									Sign. prof.
								</td>
							</tr>






							<tr style="height: 10px;">
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px; height: 0px;">
									Agèbre</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) < 40) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) < 20) ? 'red' : ''; ?>" >
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) < 20) ? 'red' : ''; ?>" >
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) < 40) ? 'red' : ''; ?>" >
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Arithmétique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Géométrie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Statistique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
									<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) 
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) 
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) 
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) 
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">640</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr style="height: 10px;">
								<td colspan="17" style="border: 1px solid black; border-right: 1px solid black; font-size: 10px;width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									Sous-domaine des Sciences de la Vie et de la Terre
								</td>
								<td style="border: 1px solid black; font-size: 10px; width: 80px; font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; width: 130px; font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; width: 130px; font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Anatomie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year'])
								?>	
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Botanique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year'])

								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Zoologie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">240</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									Sous-domaine des Sciences Physiques, Technologies et TIC
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Sciences Physiques</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Technologie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 10px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px; width: 500px;">
									Tec. d'info. et Com(TIC)</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">240</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									DOMAINE DES LANGUES
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Anglais</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">240</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Français</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">70</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) < 35) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) < 35) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">140</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) < 70) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">280</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">70</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) < 35) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) < 35) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">70</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) < 70) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">140</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">280</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">100</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">100</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									DOMAINE DE L'UNIVERS SOCIAL ET DE L'ENVIRONNEMENT
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Religion (1)</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Education à la vie</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>
							<tr>
								<td style="font-size: 10px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Ed. Civique et morale</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Géographie</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Histoire</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">100</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">100</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									DOMAINE DES ARTS
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Dessin</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Musique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>



							<tr style=" height: 10px;">
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2);">
									DOMAINE DU DEVELOPPEMENT PERSONNEL
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Education Physique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>
							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									MAXIMA GENERAUX</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								400
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">1600</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">1600</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">1600</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">1600</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">3200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">3200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px; background-color: black;"></td>
							</tr>

							<tr>
							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									TOTAUX</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?=find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) ?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 3, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 4, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 11, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?=find_pupil_sum_main_marks_sem_trim($que->pupil_id, 3, 4, 11, $que->school_year) ?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 11, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black; background-color: black;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td> -->
								<td colspan="2" rowspan="6" style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;">
									<div style="font-size: 11px; text-align: left; padding-left: 10px;">
										- PASSE (1) <br/>
										- DOUBLE (1) <br/>
										- ORIENTE VERS (1) <br/><br/>
										LE........../........./20.......<br/>
										Sceau de l'école
									</div>
								</td>
							</tr>
							<tr>
							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									POURCENTAGE</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 10, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) ?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 11, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 3, 4, 11, $que->school_year) ?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
									((pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 11, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) + 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) ) * 100 ) / 3200
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td> -->
							</tr>
							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
										PLACE/NBRE ELEVES</td>
								<?php

									$nombre00 = "SELECT cycle_school, class_school, class_order, class_section, class_option, COUNT(*) AS cccc FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=?";
									$nombre11 = $database_connect->prepare($nombre00);
									$nombre11->execute(array($que->cycle_school, $que->class_school, $que->class_order, $que->class_section, $que->class_option));
									while($nombre = $nombre11->fetchObject())
									{
										?>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_1 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											}
										 	?>
										</td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_2 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											}
										 	?>
										</td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_10 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											}
										 	?>
										</td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size: 10px;"><?php
											if(find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_tot1 as $element => $value) {
													if($value == find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											} 
										?>
										</td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<?php
									}
								?>
							</tr>

							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									APPLICATION</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-size: 10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-size: 10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-size: 10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-size: 10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black; background-color: black;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td> -->
							</tr>

							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									CONDUITE</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 1, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 1, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 2, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 2, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 3, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 3, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 4, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 4, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black; background-color: black;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td> -->
							</tr>

							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									SIGNATURE</td>
								<td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td colspan="4" style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td> -->

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td colspan="7" style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td> -->
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td> -->
							</tr>

							<!-- <tr>
								<td style="font-weight: bold; font-size: 12px; text-align: left; border: 1px solid black; height: 10px;">
									SING. DE L'INST.</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 12px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 12px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 12px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px; background-color: black;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 12px; text-align: left; border: 1px solid black; height: 10px;">
									SIGN. DU RESP.</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 12px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 12px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 12px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px; background-color: black;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr> -->

						</table>

						<table style="border: 2px solid black; border-collapse:collapse; border-top: none; border-bottom: none;text-align: center; width: 100%;" class="class_table">
						<tr>
							<td colspan="4" style="width: 100%; border-top: none; border-bottom: 0px solid black; border-right: none; text-align: left; padding: 0px; padding-left: 10px;">
								<span style="display: block; font-size: 11px; text-align: left; padding-right: 10px; width: 100%;">
									<span>- L'élève ne pourra passer dans la classe supérieure s'il a subi avec succès un examen de repêchage en . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . (1)</span><br/>
									<span>- L'élève passe dans la classe supérieure (1)</span><br/>
									<span>- L'élève double la classe (1)</span><br/>
									<span>- L'élève est orienté (e) vers . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . (1)</span><br/>
								</span>
							</td>
						</tr>
						<tr>
							<td style="border-bottom: 0px solid black; width: 20%; border-top: none; padding-top: 0px; border-left: none; border-right: none; text-align: center; padding: 5px; padding-left: 10px;">
								<span style="font-size: 11px;">
									<!-- <span style="font-size: 50px; color: transparent;">i</span><br/> --><br/>
									<strong>Signature de l'élève</strong>
								</span>
							</td>
							<td style="border-bottom: 0px solid black; width: 20%; border-top: none; padding-top: 0px; border-left: none; border-right: none; text-align: center; padding: 5px; padding-left: 10px;">
								<span style="font-size: 11px;">
									<!-- <span style="font-size: 50px; color: transparent;">i</span><br/> --><br/>
									<strong>Sceau de l'école</strong>
								</span>
							</td>
							<td style="width: 40%; border-top: none; border-left: none; border-bottom: 0px solid black; text-align: center; padding: 5px; padding-left: 10px; border-right: 2px solid black;">
								<span style="font-size: 11px;">
									<br/><span>Fait à </span><strong><?= $school_city ?></strong>, le <strong><?= $date_end ?></strong><br/>
									<span>LE CHEF D'ETABLISSEMENT</span><br/><br/><br/>
									<span><?= $chef ?></span>
								</span>
							</td>
						</tr>
					</table>
					<table style="border: 2px solid black; margin-bottom: 0px; border-collapse: collapse; border-top: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="border: none; border-top: none; border-right: none; text-align: left; padding: 5px; padding-left: 10px;">
								<span style="font-size: 9px;">
									<strong style="">(I) Buffer la mention inutile <br/> Note impotante : le bulletin est sans valeur s'il est raturé ou surchargé</strong>
								</span><br/>
							</td>
						</tr>
					</table>
				</div>
			<?php
			}
			if($_GET['class_id'] == 2) {

				?>
				<div style="height: 100%; margin-bottom: 10px;">
					<table class="class_table" style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;">
						<tr style="">
							<td style="border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<img style="float: left; min-width: 100px;" src="images/other/flag_drc.jpg" height ="60" />
							</td>
							<td style="font-weight: bold; border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<span style="font-size: 17px;">REPUBLIQUE DEMOCRATIQUE DU CONGO</span><br/>
								<span style="font-size: 17px;">MINISTERE DE L'ENSEIGNEMENT PRIMAIRE, SECONDAIRE ET TECHNIQUE</span>
							</td>
							<td style="border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<img style="float: right; min-width: 70px;" src="images/other/armoirie_rdc.png" height ="60" />
							</td>
						</tr>
					</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="border: none; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">
								<div style=" padding: 0px; color: transparent;">
									<span style="font-weight: bold; padding: 5px; border-right: none; padding-left: 7px; padding-right: 7px; color: black;">No ID. :</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 0, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 1, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 2, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 3, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 4, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 5, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 6, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 7, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 8, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 9, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 10, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 11, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 12, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 13, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 14, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 15, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 16, 1) ?></span><span style="border: 1px solid black; border-right: 1px solid black; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 17, 1) ?></span>
									<!-- <span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span> -->
								</div>
							</td>
						</tr>
					</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
							<tr>
								<td style="border: none; font-weight: bold; font-size: 11px; height: 10px; padding-left: 10px; text-align: left;">
									PROVINCE : SUD-KIVU
								</td>
							</tr>
						</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="padding: 5px; padding-left: 10px; border-bottom: none; text-align: left; width: 50%;">
								<span>VILLE : </span><strong><?=$school_city ?></strong><br/>
								<span>COMMUNE : </span><strong><?=$school_commune ?></strong><br/>
								<span>ECOLE : </span><strong><?=$school_name ?></strong><br/>
								<span style="border-right: none; padding-left: 0px; padding-right: 7px; color: black; display: inline-block; padding-top: 2px;">CODE : </span>
								<span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 0, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 1, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 2, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 3, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 4, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 5, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 6, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 7, 1) ?></span>
							</td>
							<td style="padding: 5px; padding-left: 10px; border-bottom: none; text-align: left; width: 50%;">
								<span>ELEVE : </span><strong><?= strtoupper($que->first_name." ".$que->second_name)." ".$que->last_name?></strong><span style="margin-left: 50px;">SEXE : </span><strong><?= $ggender ?></strong><br/>
								<span>NE (E) A : </span><strong><?=$que->birth_place ?></strong> <?php if($que->birth_place == "") {} else{ echo ", LE";} ?> <strong><?= $birth_date ?></strong><br/>
								<span>CLASSE : </span><strong><?=$class_identity ?></strong><br/>
								<span style="border-right: none; padding-left: 0px; padding-right: 7px; color: black; display: inline-block; padding-top: 2px;">No PERM. : </span>
								<span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 0, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 1, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 2, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 3, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 4, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 5, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 6, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 7, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 8, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 9, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 10, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 11, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 12, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 13, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 14, 1) ?></span>
							</td>
						</tr>
					</table>

						<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
							<tr>
								<td style="border: none; font-weight: bold; font-size: 11px; height: 10px;">
									<span>BULLETIN DE LA 8e ANNEE CYCLE TERMINAL DE L'EDUCATION DE BASE (CETB)<span
										style="color: transparent;">......</span>
										ANNEE SCOLAIRE <?= $school_year ?></span>
								</td>
							</tr>
						</table>

						<table  style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
							<tr>
								<td rowspan="3" style="font-weight: bold; border: 1px solid black; width: 500px;">
									BRANCHES
								</td>
								
								<td colspan="7" style="font-weight: bold; border: 1px solid black; font-size: 12px; height: 10px;">
									PREMIER TRIMESTRE
								</td>
								
								<td colspan="7" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px; height: 10px;">
									DEUXIEME TRIMESTRE
								</td>
								<td colspan="2" rowspan="3" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px;  height: 10px;">
									TOTAL GENERAL
								</td>
								<td rowspan="5" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px; background-color: black; height: 10px;">
									000
								</td>
								<td colspan="2" rowspan="2" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px; border-right: 2px solid black; height: 10px;">
									EXAMEN DE REPECHAGE
								</td>
							</tr>
							<tr>
								<td rowspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									MAX.<br />
								</td>
								<td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									TRAVAUX JOURNAL.<br />
								</td>


								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									MAX<br/>EX
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									Pts<br />OBT.
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									MAX<br />TRIM
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									Pts<br /> OBT.
								</td>

								<td rowspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									MAX.<br />
								</td>
								<td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									TRAVAUX JOURNAL.<br />
								</td>


								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									MAX<br/>EX
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									Pts<br />OBT.
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									MAX<br />TRIM
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									Pts<br /> OBT.
								</td>
							</tr>
							<tr>
								
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									1P
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									2P
								</td>
								


								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									3P
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									4P
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; border-right: 2px solid black;">
									Sign. prof.
								</td>



								
							</tr>
							<tr style="white-space: nowrap;">
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 100%; font-weight: bold; background-color: rgba(0, 0, 0, 0.2);">
									DOMAINE DES SCIENCES
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									Sign. prof.
								</td>
							</tr>
							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2);">
									Sous-domaine des Mathématiques
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									Sign. prof.
								</td>
							</tr>






							<tr style="height: 10px;">
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px; height: 0px;">
									Algèbre</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) < 40) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) < 40) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year'])  
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Arithmétique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Géométrie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Statistique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">640</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Algèbre", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Arithmétique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Statistique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr style="height: 10px;">
								<td colspan="17" style="border: 1px solid black; border-right: 1px solid black; font-size: 10px;width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									Sous-domaine des Sciences de la Vie et de la Terre
								</td>
								<td style="border: 1px solid black; font-size: 10px; width: 80px; font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; width: 130px; font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; width: 130px; font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Anatomie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year'])
								?>	
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Botanique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year'])

								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Zoologie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anatomie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Botanique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Zoologie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									Sous-domaine des Sciences Physiques, Technologies et TIC
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Sciences Physiques</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Technologie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 10px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px; width: 500px;">
									Tec. d'info. et Com(TIC)</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">240</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Technologie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sciences Physiques", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Tec. d'info. et Com(TIC)", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									DOMAINE DES LANGUES
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Anglais</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">240</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Français</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">50</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) < 25) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) < 25) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">100</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) < 50) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">50</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) < 25) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) < 25) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">100</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) < 50) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">640</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									DOMAINE DE L'UNIVERS SOCIAL ET DE L'ENVIRONNEMENT
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Religion (1)</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Education à la vie</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>
							<tr>
								<td style="font-size: 10px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Ed. Civique et morale</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Géographie</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">240</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Histoire</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">110</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">220</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">440</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">110</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">220</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">440</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">880</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									DOMAINE DES ARTS
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Dessin</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Musique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 1, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 2, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 10, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 3, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 4, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Musique", 11, $_GET['school_year']) + 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>



							<tr style=" height: 10px;">
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2);">
									DOMAINE DU DEVELOPPEMENT PERSONNEL
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Education Physique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"  class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>
							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									MAXIMA GENERAUX</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
									400
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">1600</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">1600</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">1600</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">1600</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">3200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">3200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px; background-color: black;"></td>
							</tr>

							<tr>
							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									TOTAUX</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) ?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 3, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 4, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 11, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks_sem_trim($que->pupil_id, 3, 4, 11, $que->school_year) ?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) + find_pupil_sum_main_marks_sem_trim($que->pupil_id, 3, 5, 11, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black; background-color: black;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td> -->
								<td colspan="2" rowspan="6" style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;">
									<div style="font-size: 11px; text-align: left; padding-left: 10px;">
										- PASSE (1) <br/>
										- DOUBLE (1) <br/>
										- ORIENTE VERS (1) <br/><br/>
										LE........../........./20.......<br/>
										Sceau de l'école
									</div>
								</td>
							</tr>

							<tr>
							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									POURCENTAGE</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 10, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) ?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 11, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage_sem_trim($que->pupil_id, 3, 4, 11, $que->school_year) ?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= find_pupil_pourcentage_sem_trim($que->pupil_id, 3, 4, 11, $que->school_year) + find_pupil_pourcentage_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td> -->
							</tr>
							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
										PLACE/NBRE ELEVES</td>
								<?php

									$nombre00 = "SELECT cycle_school, class_school, class_order, class_section, class_option, COUNT(*) AS cccc FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=?";
									$nombre11 = $database_connect->prepare($nombre00);
									$nombre11->execute(array($que->cycle_school, $que->class_school, $que->class_order, $que->class_section, $que->class_option));
									while($nombre = $nombre11->fetchObject())
									{
										?>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_1 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											}
										 	?>
										</td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_2 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											}
										 	?>
										</td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_10 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											}
										 	?>
										</td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size: 10px;"><?php
											if(find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_tot1 as $element => $value) {
													if($value == find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											} 
										?>
										</td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<?php
									}
								?>
							</tr>

							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									APPLICATION</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-size: 10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td> -->
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-size: 10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-size: 10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-size: 10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black; background-color: black;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td> -->
							</tr>

							<tr>
							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									CONDUITE</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 1, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 1, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 2, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 2, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 3, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 3, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 4, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 4, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
							</tr>

							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									SIGNATURE</td>
								<td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td colspan="4" style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td> -->

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td colspan="7" style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;"></td>
							</tr>

						</table>

						<table style="border: 2px solid black; border-collapse:collapse; border-top: none; border-bottom: none;text-align: center; width: 100%;" class="class_table">
						<tr>
							<td colspan="4" style="width: 100%; border-top: none; border-bottom: 0px solid black; border-right: none; text-align: left; padding: 0px; padding-left: 10px;">
								<span style="display: block; font-size: 11px; text-align: left; padding-right: 10px; width: 100%;">
									<span>- L'élève ne pourra passer dans la classe supérieure s'il a subi avec succès un examen de repêchage en . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . (1)</span><br/>
									<span>- L'élève passe dans la classe supérieure (1)</span><br/>
									<span>- L'élève double la classe (1)</span><br/>
									<span>- L'élève est orienté (e) vers . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . (1)</span><br/>
								</span>
							</td>
						</tr>
						<tr>
							<td style="border-bottom: 0px solid black; width: 20%; border-top: none; padding-top: 0px; border-left: none; border-right: none; text-align: center; padding: 5px; padding-left: 10px;">
								<span style="font-size: 11px;">
									<!-- <span style="font-size: 50px; color: transparent;">i</span><br/> --><br/>
									<strong>Signature de l'élève</strong>
								</span>
							</td>
							<td style="border-bottom: 0px solid black; width: 20%; border-top: none; padding-top: 0px; border-left: none; border-right: none; text-align: center; padding: 5px; padding-left: 10px;">
								<span style="font-size: 11px;">
									<!-- <span style="font-size: 50px; color: transparent;">i</span><br/> --><br/>
									<strong>Sceau de l'école</strong>
								</span>
							</td>
							<td style="width: 40%; border-top: none; border-left: none; border-bottom: 0px solid black; text-align: center; padding: 5px; padding-left: 10px; border-right: 2px solid black;">
								<span style="font-size: 11px;">
									<br/><span>Fait à </span><strong><?= $school_city ?></strong>, le <strong><?= $date_end ?></strong><br/><br/>
									<span>LE CHEF D'ETABLISSEMENT</span><br/><br/>
									<span><?= $chef ?></span>
								</span>
							</td>
						</tr>
					</table>
					<table style="border: 2px solid black; margin-bottom: 0px; border-collapse: collapse; border-top: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="border: none; border-top: none; border-right: none; text-align: left; padding: 5px; padding-left: 10px;">
								<span style="font-size: 9px;">
									<strong style="">(I) Buffer la mention inutile <br/> Note impotante : le bulletin est sans valeur s'il est raturé ou surchargé</strong>
								</span><br/>
							</td>
						</tr>
					</table>
				</div>
			<?php
			}

			if($class_number == 18 && strtoupper($section_name) == "SCIENTIFIQUE") {

				?>
				<div style="height: 100%; margin-bottom: 10px;">
					<table class="class_table" style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;">
						<tr style="">
							<td style="border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<img style="float: left; min-width: 100px;" src="images/other/flag_drc.jpg" height ="60" />
							</td>
							<td style="font-weight: bold; border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<span style="font-size: 17px;">REPUBLIQUE DEMOCRATIQUE DU CONGO</span><br/>
								<span style="font-size: 17px;">MINISTERE DE L'ENSEIGNEMENT PRIMAIRE, SECONDAIRE ET TECHNIQUE</span>
							</td>
							<td style="border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<img style="float: right; min-width: 70px;" src="images/other/armoirie_rdc.png" height ="60" />
							</td>
						</tr>
					</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="border: none; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">
								<div style=" padding: 0px; color: transparent;">
									<span style="font-weight: bold; padding: 5px; border-right: none; padding-left: 7px; padding-right: 7px; color: black;">No ID. :</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 0, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 1, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 2, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 3, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 4, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 5, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 6, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 7, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 8, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 9, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 10, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 11, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 12, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 13, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 14, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 15, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 16, 1) ?></span><span style="border: 1px solid black; border-right: 1px solid black; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 17, 1) ?></span>
									<!-- <span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span> -->
								</div>
							</td>
						</tr>
					</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
							<tr>
								<td style="border: none; font-weight: bold; font-size: 11px; height: 10px; padding-left: 10px; text-align: left;">
									PROVINCE : SUD-KIVU
								</td>
							</tr>
						</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="padding: 5px; padding-left: 10px; border-bottom: none; text-align: left; width: 50%;">
								<span>VILLE : </span><strong><?=$school_city ?></strong><br/>
								<span>COMMUNE : </span><strong><?=$school_commune ?></strong><br/>
								<span>ECOLE : </span><strong><?=$school_name ?></strong><br/>
								<span style="border-right: none; padding-left: 0px; padding-right: 7px; color: black; display: inline-block; padding-top: 2px;">CODE : </span>
								<span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 0, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 1, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 2, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 3, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 4, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 5, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 6, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 7, 1) ?></span>
							</td>
							<td style="padding: 5px; padding-left: 10px; border-bottom: none; text-align: left; width: 50%;">
								<span>ELEVE : </span><strong><?= strtoupper($que->first_name." ".$que->second_name)." ".$que->last_name?></strong><span style="margin-left: 50px;">SEXE : </span><strong><?= $ggender ?></strong><br/>
								<span>NE (E) A : </span><strong><?=$que->birth_place ?></strong> <?php if($que->birth_place == "") {} else{ echo ", LE";} ?> <strong><?= $birth_date ?></strong><br/>
								<span>CLASSE : </span><strong><?=$class_identity ?></strong><br/>
								<span style="border-right: none; padding-left: 0px; padding-right: 7px; color: black; display: inline-block; padding-top: 2px;">No PERM. : </span>
								<span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 0, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 1, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 2, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 3, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 4, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 5, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 6, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 7, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 8, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 9, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 10, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 11, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 12, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 13, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 14, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 15, 1) ?></span>
							</td>
						</tr>
					</table>

						<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
							<tr>
								<td style="border: none; font-weight: bold; font-size: 11px; height: 10px;">
									<span>BULLETIN DE LA 1ère ANNEE DES HUMANITES SCIENTIFIQUES
									<span style="color: transparent;">......</span>
										ANNEE SCOLAIRE <?= $school_year ?>
									</span>
								</td>
							</tr>
						</table>

						<table  style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
							<tr>
								<td rowspan="3" style="font-weight: bold; border: 1px solid black; width: 500px;">
									BRANCHES
								</td>
								
								<td colspan="7" style="font-weight: bold; border: 1px solid black; font-size: 12px; height: 10px;">
									PREMIER TRIMESTRE
								</td>
								
								<td colspan="7" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px; height: 10px;">
									DEUXIEME TRIMESTRE
								</td>
								<td colspan="2" rowspan="3" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px;  height: 10px;">
									TOTAL GENERAL
								</td>
								<td rowspan="5" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px; background-color: black; height: 10px;">
									000
								</td>
								<td colspan="2" rowspan="2" style="font-weight: bold; line-height: 20px; border: 1px solid black; font-size: 12px; border-right: 2px solid black; height: 10px;">
									EXAMEN DE REPECHAGE
								</td>
							</tr>
							<tr>
								<td rowspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									MAX.<br />
								</td>
								<td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									TRAVAUX JOURNAL.<br />
								</td>


								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									MAX<br/>EX
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									Pts<br />OBT.
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									MAX<br />TRIM
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									Pts<br /> OBT.
								</td>

								<td rowspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									MAX.<br />
								</td>
								<td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
									TRAVAUX JOURNAL.<br />
								</td>


								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									MAX<br/>EX
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									Pts<br />OBT.
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									MAX<br />TRIM
								</td>
								<td rowspan="2" style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									Pts<br /> OBT.
								</td>
							</tr>
							<tr>
								
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									1P
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									2P
								</td>
								


								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									3P
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									4P
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; border-right: 2px solid black;">
									Sign. prof.
								</td>



								
							</tr>
							<tr style="white-space: nowrap;">
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 100%; font-weight: bold; background-color: rgba(0, 0, 0, 0.2);">
									DOMAINE DES SCIENCES
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									Sign. prof.
								</td>
							</tr>
							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2);">
									Sous-domaine des Mathématiques
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									Sign. prof.
								</td>
							</tr>






							<tr style="height: 10px;">
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px; height: 0px;">
									Agèbre, Stat. et Anal.</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 1, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 2, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 10, $_GET['school_year']) < 40) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year']) < 40) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Géométrie et Trigo.</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Dessin Scientifique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">70</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 1, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 2, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">140</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 10, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">280</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">70</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">140</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">280</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">560</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr style="height: 10px;">
								<td colspan="17" style="border: 1px solid black; border-right: 1px solid black; font-size: 10px;width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									Sous-domaine des Sciences de la Vie et de la Terre
								</td>
								<td style="border: 1px solid black; font-size: 10px; width: 80px; font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; width: 130px; font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; width: 130px; font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>





							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Biologie générale</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Microbiologie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Géologie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 1, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 2, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 10, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									Sous-domaine des Sciences Physiques, Technologies et TIC
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Chimie</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 1, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 2, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 10, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">240</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Physique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 1, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 2, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 10, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">240</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 10px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px; width: 500px;">
									Techn. d'info. et Com(TIC)</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">70</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 1, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 2, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">140</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 10, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">280</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 1, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">70</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">140</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?=
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">280</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">560</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 1, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									DOMAINE DES LANGUES
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>





							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Français</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">50</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) < 25) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) < 25) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">100</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) < 50) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">50</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) < 25) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) < 25) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">100</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) < 50) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Anglais</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">30</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) < 15) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">60</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) < 30) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">120</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">240</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year'])
									
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">320</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">640</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>

							<tr>
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2); height: 10px;">
									DOMAINE DE L'UNIVERS SOCIAL ET DE L'ENVIRONNEMENT
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black; height: 10px;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black; height: 10px;">
									Sign. prof.
								</td>
							</tr>



							<tr>
								<td style="font-size: 10px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Ed. Civique et morale</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>
							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Géographie</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Histoire</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>
							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Education à la vie</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Sociologie africaine</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 1, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 2, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 10, $_GET['school_year']) < 40) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) < 40) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year'])  
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Religion (1)</td>
									<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) < 20) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">160</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">110</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">220</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">440</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">110</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">220</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">440</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">880</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 100px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 450px;"></td>
							</tr>



							<tr style=" height: 10px;">
								<td colspan="17"
									style="border: 1px solid black; border-right: 1px solid black; font-size: 10px; height: 10px; width: 80px; text-align: center; font-weight: bold; background-color: rgba(0, 0, 0, 0.2);">
									DOMAINE DU DEVELOPPEMENT PERSONNEL
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;">
									%
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 130px;font-weight: bold; background-color: black;">
									Sign. prof.
								</td>
							</tr>







							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Education Physique</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) 
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) < 5) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;" class="<?= (pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) < 10) ? 'red' : ''; ?>" ><?= pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) ?></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year']) +
								pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year']) 
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>
							<tr>
								<td style="font-size: 12px; text-align: left; border: 1px solid black; height: 5px; padding-left: 7px;">
									Sous-total</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year'])
								?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">10</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">20</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">40</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year'])
								?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">80</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year'])+
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year'])
								?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td>
							</tr>

							<tr>
							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									MAXIMA GENERAUX</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								400
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								400
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">1600</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">1600</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">800</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;">1600</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold;">3200</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px; background-color: black;"></td>
							</tr>

							<tr>
							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									TOTAUX</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;">400</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?=find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) ?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year'])                                                   
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 4, $_GET['school_year'])                                                   
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year'])                                                   
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year'])  +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +  
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year'])                                                
									?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year'])  +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year'])  +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +  
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year'])                                                
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black; background-color: black;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td> -->
								<td colspan="2" rowspan="6" style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;">
									<div style="font-size: 11px; text-align: left; padding-left: 10px;">
										- PASSE (1) <br/>
										- DOUBLE (1) <br/>
										- ORIENTE VERS (1) <br/><br/>
										LE ........../........./20.......<br/>
										Sceau de l'école
									</div>
								</td>
							</tr>

							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									POURCENTAGE</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 10, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) ?>
								</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 3, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 4, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= find_pupil_pourcentage($que->pupil_id, 11, $que->school_year) ?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;">
								<?= 
									((pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year'])  +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +  
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year'])    ) *100)/1600                                                 
									?>
								</td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px;">
								<?= 
									((pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 1, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 2, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 10, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 10, $_GET['school_year'])  +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 3, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 3, $_GET['school_year'])  +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 4, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 4, $_GET['school_year']) +  
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Agèbre, Stat. et Anal.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géométrie et Trigo.", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Dessin Scientifique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Biologie générale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Microbiologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géologie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Chimie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Physique", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Techn. d'info. et Com(TIC)", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Français", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Anglais", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Ed. Civique et morale", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Géographie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Histoire", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education à la vie", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Sociologie africaine", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Religion", 11, $_GET['school_year']) +
									pupil_period_marks($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $que->pupil_id, "Education Physique", 11, $_GET['school_year'])     ) *100)/3200                                              
									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td> -->
							</tr>

							<tr>
								<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
										PLACE/NBRE ELEVES</td>
								<?php

									$nombre00 = "SELECT cycle_school, class_school, class_order, class_section, class_option, COUNT(*) AS cccc FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=?";
									$nombre11 = $database_connect->prepare($nombre00);
									$nombre11->execute(array($que->cycle_school, $que->class_school, $que->class_order, $que->class_section, $que->class_option));
									while($nombre = $nombre11->fetchObject())
									{
										?>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_1 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											}
										 	?>
										</td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_2 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											}
										 	?>
										</td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_10 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											}
										 	?>
										</td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"><?php
											if(find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_tot1 as $element => $value) {
													if($value == find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place."/".$nombre->cccc;
											} 
										?>
										</td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
										<?php
									}
								?>
							</tr>


							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									APPLICATION</td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="height: 10px;font-size:10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td> -->
								<td style="height: 10px;font-size:10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>

								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 80px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; width: 70px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black; background-color: black;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; border-right: 2px solid black; font-size: 10px; height: 10px; width: 80px;"></td> -->
							</tr>

							<tr>
							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									CONDUITE</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 1, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 1, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 2, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 2, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold; background-color: black;"></td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 3, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 3, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="height: 10px;font-size: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 4, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 4, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td>
							</tr>

							<td style="font-weight: bold; font-size: 10px; height: 10px; text-align: left; border: 1px solid black; height: 10px; padding-left: 7px;">
									SIGNATURE</td>
								<td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td colspan="4" style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;"></td>
								<!-- <td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td> -->

								<td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td style="border: 1px solid black; font-size: 10px; height: 10px;"></td>
								<td colspan="7" style="border: 1px solid black; font-size: 10px; height: 10px;font-weight: bold;"></td>
							</tr>

						</table>

						<table style="border: 2px solid black; border-collapse:collapse; border-top: none; border-bottom: none;text-align: center; width: 100%;" class="class_table">
						<tr>
							<td colspan="4" style="width: 100%; border-top: none; border-bottom: 0px solid black; border-right: none; text-align: left; padding: 0px; padding-left: 10px;">
								<span style="display: block; font-size: 11px; text-align: left; padding-right: 10px; width: 100%;">
									<span>- L'élève ne pourra passer dans la classe supérieure s'il a subi avec succès un examen de repêchage en . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . (1)</span><br/>
									<span>- L'élève passe dans la classe supérieure (1)</span><br/>
									<span>- L'élève double la classe (1)</span><br/>
									<span>- L'élève est orienté (e) vers . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . (1)</span><br/>
								</span>
							</td>
						</tr>
						<tr>
							<td style="border-bottom: 0px solid black; width: 20%; border-top: none; padding-top: 0px; border-left: none; border-right: none; text-align: center; padding: 5px; padding-left: 10px;">
								<span style="font-size: 11px;">
									<!-- <span style="font-size: 50px; color: transparent;">i</span><br/> --><br/>
									<strong>Signature de l'élève</strong>
								</span>
							</td>
							<td style="border-bottom: 0px solid black; width: 20%; border-top: none; padding-top: 0px; border-left: none; border-right: none; text-align: center; padding: 5px; padding-left: 10px;">
								<span style="font-size: 11px;">
									<!-- <span style="font-size: 50px; color: transparent;">i</span><br/> --><br/>
									<strong>Sceau de l'école</strong>
								</span>
							</td>
							<td style="width: 40%; border-top: none; border-left: none; border-bottom: 0px solid black; text-align: center; padding: 5px; padding-left: 10px; border-right: 2px solid black;">
								<span style="font-size: 11px;">
									<br/><span>Fait à </span><strong><?= $school_city ?></strong>, le <strong><?= $date_end ?></strong><br/>
									<span>LE CHEF D'ETABLISSEMENT</span><br/><br/><br/>
									<span><?= $chef ?></span>
								</span>
							</td>
						</tr>
					</table>
					<table style="border: 2px solid black; margin-bottom: 0px; border-collapse: collapse; border-top: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="border: none; border-top: none; border-right: none; text-align: left; padding: 5px; padding-left: 10px;">
								<span style="font-size: 9px;">
									<strong style="">(I) Buffer la mention inutile <br/> Note impotante : le bulletin est sans valeur s'il est raturé ou surchargé</strong>
								</span><br/>
							</td>
						</tr>
					</table>
				</div>
			<?php
			} else {
				if($_GET['class_id'] != 1) {
					if($_GET['class_id'] != 2) {
				?>
				<div id="print_pupil_marks" style="background-color: white; margin-left: 0cm;margim-bottom:100px;height:cals(100vh-20mm);">
					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;" class="class_table">
						<tr style="">
							<td style="border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<img style="float: left; min-width: 100px;" src="images/other/flag_drc.jpg" height ="60" />
							</td>
							<td style="font-weight: bold; border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<span style="font-size: 17px;">REPUBLIQUE DEMOCRATIQUE DU CONGO</span><br/>
								<span style="font-size: 13px;">MINISTERE DE L'ENSEIGNEMENT PRIMAIRE, SECONDAIRE ET PROFESSIONNEL</span>
							</td>
							<td style="border: none; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; line-height: 20px;">
								<img style="float: right; min-width: 70px;" src="images/other/armoirie_rdc.png" height ="60" />
							</td>
						</tr>
					</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="border: none; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">
								<div style="color: transparent;">
									<span style="font-weight: bold; padding: 5px; border-right: none; padding-left: 7px; padding-right: 7px; color: black;">No ID. :</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 0, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 1, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 2, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 3, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 4, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 5, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 6, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 7, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 8, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 9, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 10, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 11, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 12, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 13, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 14, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 15, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 16, 1) ?></span><span style="border: 1px solid black; border-right: 1px solid black; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 17, 1) ?></span>
									<!-- <span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span> -->
								</div>
							</td>
						</tr>
					</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
							<tr>
								<td style="border: none; font-weight: bold; font-size: 11px; height: 10px; padding-left: 10px; text-align: left;">
									PROVINCE : SUD-KIVU
								</td>
							</tr>
						</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="padding: 5px; padding-left: 10px; border-bottom: none; text-align: left; line-height: 18px; width: 50%;">
								<span>VILLE : </span><strong><?=$school_city ?></strong><br/>
								<span>COMMUNE : </span><strong><?=$school_commune ?></strong><br/>
								<span>ECOLE : </span><strong><?=$school_name ?></strong><br/>
								<span style="padding: 5px; border-right: none; padding-left: 0px; padding-right: 7px; color: black;">CODE : </span>
								<span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 0, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 1, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 2, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 3, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 4, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 5, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 6, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 7, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black; border-left: none;"><?= substr($code, 8, 1) ?></span>
							</td>
							<td style="padding: 5px; padding-left: 10px; border-bottom: none; text-align: left; line-height: 18px;">
								<span>ELEVE : </span><strong><?= strtoupper($que->first_name." ".$que->second_name)." ".$que->last_name?></strong><span style="margin-left: 50px;">SEXE : </span><strong><?= $ggender ?></strong><br/>
								<span>NE (E) A : </span><strong><?=$que->birth_place ?></strong> <?php if($que->birth_place == "") {} else{ echo ", LE";} ?> <strong><?= $birth_date ?></strong><br/>
								<span>CLASSE : </span><strong><?=$class_identity ?></strong><br/>
								<span style="padding: 5px; border-right: none; padding-left: 0px; padding-right: 7px; color: black;">No PERM. : </span>
								<span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 0, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 1, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 2, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 3, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 4, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 5, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 6, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 7, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 8, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 9, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 10, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 11, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 12, 1) ?></span><span style="border: 1px solid black; border-left: none; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 13, 1) ?></span>
							</td>
						</tr>
					</table>

					<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center; width: 100%;" class="class_table">
						<tr>
							<td style="border: none; font-weight: bold; height: 10px;">
								BULLETIN DE LA <?= mb_strtoupper($class_identity_bis) ?><span style="color: transparent;">.....................</span>
								ANNEE SCOLAIRE <?= $school_year ?>
							</td>
						</tr>
					</table>

					<table style="border: 2px solid black; border-collapse:collapse; text-align: center; width: 100%;" class="class_table principal">
						<?php
						if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL")
						{
							?>
							<tr>
								<th class="tth">Branches</th>
								<th class="tth">1P</th>
								<th class="tth">2P</th>
								<th class="tth">Ex.1T</th>
								<th class="tth">TRIM. 1</th>
								<th class="tth">3P</th>
								<th class="tth">4P</th>
								<th class="tth">Ex.2T</th>
								<th class="tth">TRIM. 2</th>
								<th class="tth">5P</th>
								<th class="tth">6P</th>
								<th class="tth">Ex.3T</th>
								<th class="tth">TRIM. 3</th>
							</tr>
							<?php
						}
						if($toUpper_class_name == "PRIMAIRE")
						{
							?>
							<tr style="border: 1px solid black;">
								<th class="tth" rowspan="3" style="border: 1px solid black;">Branches</th>
								<th class="tth" colspan="4" style="border: 1px solid black;">Premier Trimestre</th>
								<th colspan="4" class="tth" style="border: 1px solid black;">Deuxième Trimestre</th>
								<th class="tth" colspan="4" style="border: 1px solid black;">Troisieme Trimestre</th>
							</tr>
							<tr>
								<th class="tth" colspan="2" style="border: 1px solid black;">Trav. Jour</th>
								<th class="tth" rowspan="2" style="border: 1px solid black;">Compo-<br/>sition</th>
								<th class="tth" rowspan="2" style="border: 1px solid black;">Totaux</th>
								<th class="tth" colspan="2" style="border: 1px solid black;">Trav. Jour</th>
								<th class="tth" rowspan="2" style="border: 1px solid black;">Compo-<br/>sition</th>
								<th class="tth" rowspan="2" style="border: 1px solid black;">Totaux</th>
								<th class="tth" colspan="2" style="border: 1px solid black;">Trav. Jour</th>
								<th class="tth" rowspan="2" style="border: 1px solid black;">Compo-<br/>sition</th>
								<th class="tth" rowspan="2" style="border: 1px solid black;">Totaux</th>
							</tr>
							<tr>
								<th class="tth" style="border: 1px solid black;">1P</th>
								<th class="tth" style="border: 1px solid black;">2P</th>
								<th class="tth" style="border: 1px solid black;">3P</th>
								<th class="tth" style="border: 1px solid black;">4P</th>
								<th class="tth" style="border: 1px solid black;">5P</th>
								<th class="tth" style="border: 1px solid black;">6P</th>
							</tr>
							<?php
						}

						if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
						{
							?>
							<tr style="border: 1px solid black;">
								<th class="tth" rowspan="3" style="border: 1px solid black;">Branches</th>
								<th class="tth" colspan="4" style="border: 1px solid black;">Premier Semestre</th>
								<th colspan="4" class="tth" style="border: 1px solid black;">Second Semestre</th>
								<th class="tth" rowspan="3" style="border: 1px solid black;">Totaux<br/>Généraux</th>
								<th class="tth" colspan="3" style="border: 1px solid black;">EXAMEN DE REPECHAGE</th>
							</tr>
							<tr>
								<th class="tth" colspan="2" style="border: 1px solid black;">Trav. Jour</th>
								<th class="tth" rowspan="2" style="border: 1px solid black;">Compo-<br/>sition</th>
								<th class="tth" rowspan="2" style="border: 1px solid black;">Totaux</th>
								<th class="tth" colspan="2" style="border: 1px solid black;">Trav. Jour</th>
								<th class="tth" rowspan="2" style="border: 1px solid black;">Compo-<br/>sition</th>
								<th class="tth" rowspan="2" style="border: 1px solid black;">Totaux</th>
								
								<th class="tth" style="width: 30px;">%</th>
								<th class="tth" style="border-right: 2px solid black;" rowspan="2"><strong>Signature du<br/>professeur</strong></th>
							</tr>
							<tr>
								<th class="tth" style="border: 1px solid black;">1P</th>
								<th class="tth" style="border: 1px solid black;">2P</th>
								<th class="tth" style="border: 1px solid black;">3P</th>
								<th class="tth" style="border: 1px solid black;">4P</th>
								<th class="tth" style="width: 30px;"></th>
							</tr>
							<?php
						}

						if(count_courses_exist($que->cycle_school, $que->class_school, $que->class_section, $que->class_option, $que->school_year) != 0)
						{
							$query_fetch00_cn = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY total_marks ASC";
							$query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
							$query_fetch11_cn->execute(array($que->cycle_school, $que->class_school, $que->class_section, $que->class_option, $que->school_year));
							while($query_fetch_cn = $query_fetch11_cn->fetchObject())
							{

							// $query_fetch00_cn = "SELECT course_id, course_name, cycle_id, class_id, order_id, section_id, option_id, total_marks FROM courses WHERE (cycle_id=? AND class_id=? AND order_id=? AND section_id=? AND option_id=?) OR (cycle_id=?) OR (cycle_id=? AND class_id=?) OR (section_id=? AND section_id!=?) OR (option_id=? AND option_id!=?) ORDER BY total_marks ASC";
							// $query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
							// $query_fetch11_cn->execute(array($que->cycle_school, $que->class_school, $que->class_order, $que->class_section, $que->class_option, $que->cycle_school, $que->cycle_school, $que->class_school, $que->class_section, 0, $que->class_option, 0));
							// while($query_fetch_cn = $query_fetch11_cn->fetchObject())
							// {
								$sss0000 = "SELECT astuce_bulletin FROM other_settings";
								$sss1100 = $database_connect->query($sss0000);
								$sss00 = $sss1100->fetchObject();
								if ($sss00->astuce_bulletin != $query_fetch_cn->total_marks) {

									$edit0000 = "UPDATE other_settings SET astuce_bulletin=?";
									$edit00 = $database_connect->prepare($edit0000);
									$edit00->execute(array($query_fetch_cn->total_marks));

									$query_fetch00_cnr = "SELECT course_id, total_marks FROM courses WHERE course_id=? ORDER BY total_marks ASC";
									$query_fetch11_cnr = $database_connect->prepare($query_fetch00_cnr);
									$query_fetch11_cnr->execute(array($query_fetch_cn->course_id));
									while($query_fetch_cnr = $query_fetch11_cnr->fetchObject())
									{
										if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
										{	
											?>
											<!-- <tr>
												<td style="font-weight: bold;">MAXIMA</td>
												<td style="font-weight: bold;"></td>
												<td style="font-weight: bold;"></td>
												<td style="font-weight: bold;"></td>
												<td style="font-weight: bold;"></td>
												<td style="font-weight: bold;"></td>
												<td style="font-weight: bold;"></td>
												<td style="font-weight: bold;"></td>
												<td style="font-weight: bold;"></td>
												<td style="font-weight: bold;"></td>
												<td style="background-color: rgb(0, 0, 0);"></td>
												<td style="border-right: 2px solid black; background-color: rgb(0, 0, 0);"></td>
											</tr> -->
											<tr>
												<td style="font-weight: bold;height: 10px;text-align:left;padding-left:5px;">MAXIMA</td>
												<td style="font-weight: bold;height: 10px;"><?=$query_fetch_cnr->total_marks ?></td>
												<td style="font-weight: bold;height: 10px;"><?=$query_fetch_cnr->total_marks ?></td>
												<td style="font-weight: bold;height: 10px;"><?=$query_fetch_cnr->total_marks*2 ?></td>
												<td style="font-weight: bold;height: 10px;"><?=$query_fetch_cnr->total_marks*4 ?></td>
												<td style="font-weight: bold;height: 10px;"><?=$query_fetch_cnr->total_marks ?></td>
												<td style="font-weight: bold;height: 10px;"><?=$query_fetch_cnr->total_marks ?></td>
												<td style="font-weight: bold;height: 10px;"><?=$query_fetch_cnr->total_marks*2 ?></td>
												<td style="font-weight: bold;height: 10px;"><?=$query_fetch_cnr->total_marks*4 ?></td>
												<td style="font-weight: bold;height: 10px;"><?=$query_fetch_cnr->total_marks*8 ?></td>
												<td style="background-color: rgb(0, 0, 0);height: 10px;"></td>
												<td style="border-right: 2px solid black;height: 10px; background-color: rgb(0, 0, 0);"></td>
											</tr>
											<?php
										}
									}
								}

								?>
								<tr style="height: 10px;">
									<td style="text-align: left;padding-left: 5px;height: 10px;"><?=$query_fetch_cn->course_name?></td>
									
										<?php

									if ($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
									{

										if (count_pupil_marks_exist($que->pupil_id, 1, $query_fetch_cn->course_id, $que->school_year) != 0) {
											foreach(find_pupil_period_marks($que->pupil_id, $query_fetch_cn->course_id, 1, $que->school_year) as $period1)
											{
												?>
												<td style="height: 10px;" class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
												<?php
											}
										} else {
											?><td style="font-size:10px;height:10px;"></td><?php
										}

										if (count_pupil_marks_exist($que->pupil_id, 2, $query_fetch_cn->course_id, $que->school_year) != 0) {
											foreach(find_pupil_period_marks($que->pupil_id, $query_fetch_cn->course_id, 2, $que->school_year) as $period2)
											{
												?>
												<td style="height: 10px;" class="<?= ($period2->main_marks < ($period2->total_marks/2)) ? 'red' : ''; ?>"><?=$period2->main_marks?></td>
												<?php
											}
										} else {
											?><td style="font-size:10px;height:10px;"></td><?php
										}

										if (count_pupil_marks_exist($que->pupil_id, 10, $query_fetch_cn->course_id, $que->school_year) != 0) {
											foreach(find_pupil_period_marks($que->pupil_id, $query_fetch_cn->course_id, 10, $que->school_year) as $exam1)
											{
												?>
												<td style="height: 10px;" class="<?= ($exam1->main_marks < ($exam1->total_marks/2)) ? 'red' : ''; ?>"><?=$exam1->main_marks?></td>
												<?php
											}
										} else {
											?><td style="font-size:10px;height:10px;"></td><?php
										}

										foreach(find_pupil_semester_trimester_marks($que->pupil_id, $query_fetch_cn->course_id, 1, 2, 10, $que->school_year) as $trim_sem)
										{
											?>
											<td style="height: 10px;" class="<?= ($trim_sem->sum_main_marks < ($trim_sem->sum_total_marks/2)) ? 'red' : ''; ?>"><?=$trim_sem->sum_main_marks?></td>
											<?php
										}
									}

									

									?><td style="font-size:10px;height:10px;"> </td><?php
									?><td style="font-size:10px;height:10px;"></td><?php
									

										if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
									{
										
											?><td style="height: 10px;"></td><?php
										
									}

									if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
									{
										if (count_pupil_marks($que->pupil_id, 8, $que->school_year) != 0) {
											foreach(find_pupil_period_marks($que->pupil_id, $query_fetch_cn->course_id, 8, $que->school_year) as $period1)
											{
												?>
												<td style="height: 10px;" class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
												<?php
											}
										}
									}

									if ($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
									{
										foreach(find_pupil_semester_trimester_marks($que->pupil_id, $query_fetch_cn->course_id, 3, 4, 11, $que->school_year) as $trim_sem)
										{
											?>
											<td style="height: 10px;"></td>
											<?php
										}

										?>
										<td style="width: 30px;height: 10px;"></td>
										<td style="border-right: 0px solid black;height: 10px;"></td>
										<?php
									} 
									

									if ($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
									{
										foreach(find_pupil_semester_trimester_marks_total($que->pupil_id, $query_fetch_cn->course_id, 1, 2, 10, 3, 4, 11, $que->school_year) as $trim_sem)
										{
											?>
											<td style="height: 10px;"></td>
											<?php
										}

										?>
										<td style="width: 30px;height:10px;"></td>
										<td style="border-right: 2px solid black;height: 10px;"></td>
										<?php
									} 

									if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
									{
										foreach(find_pupil_semester_trimester_marks($que->pupil_id, $query_fetch_cn->course_id, 3, 4, 8, $que->school_year) as $trim_sem)
										{
											?>
											<td style="height: 10px;" class="<?= ($trim_sem->sum_main_marks < ($trim_sem->sum_total_marks/2)) ? 'red' : ''; ?>"><?=$trim_sem->sum_main_marks?></td>
											<?php
										}
									}

									if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE") 
									{
										if (count_pupil_marks($que->pupil_id, 5, $que->school_year) != 0) 
										{
											foreach(find_pupil_period_marks($que->pupil_id, $query_fetch_cn->course_id, 5, $que->school_year) as $period1)
											{
												?>
												<td style="height: 10px;" class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
												<?php
											}
										}

										if (count_pupil_marks($que->pupil_id, 6, $que->school_year) != 0) {
											foreach(find_pupil_period_marks($que->pupil_id, $query_fetch_cn->course_id, 6, $que->school_year) as $period1)
											{
												?>
												<td style="height: 10px;" class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
												<?php
											}
										} else {
											?><td style="height: 10px;"></td><?php
										}

										if (count_pupil_marks($que->pupil_id, 9, $que->school_year) != 0) 
										{
											foreach(find_pupil_period_marks($que->pupil_id, $query_fetch_cn->course_id, 9, $que->school_year) as $period1)
											{
												?>
												<td style="height: 10px;" class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
												<?php
											}
										} else {
											?><td style="height: 10px;"></td><?php
										}

										foreach(find_pupil_semester_trimester_marks($que->pupil_id, $query_fetch_cn->course_id, 5, 6, 9, $que->school_year) as $trim_sem2)
										{
											?>
											<td style="height: 10px;" class="<?= ($trim_sem2->sum_main_marks < ($trim_sem2->sum_total_marks/2)) ? 'red' : ''; ?>"><?=$trim_sem2->sum_main_marks?></td>
											<?php
										}
								}
								?>
								</tr>
								<?php
							}
						}
						?>
							<tr>
								<td style="font-weight: bold; text-align: center;padding-left: 5px;height: 10px;text-align:left;">MAXIMA GENERAUX</td>

								<!-- <td style="font-weight: bold;"></td>
								<td style="font-weight: bold;"></td> -->

								<?php

								if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
								{
									?>
									
									<td style="font-weight: bold;height: 10px;"><?=find_pupil_sum_total_marks($que->pupil_id, 1, $que->school_year) ?></td>
									<td style="font-weight: bold;height: 10px;"><?=find_pupil_sum_total_marks($que->pupil_id, 2, $que->school_year) ?></td>
									<td style="font-weight: bold;height: 10px;"><?=find_pupil_sum_total_marks($que->pupil_id, 10, $que->school_year) ?></td>
									<td class="<?= (find_pupil_sum_total_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) < find_pupil_sum_total_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height:10px">
									<?=find_pupil_sum_total_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) ?>
								</td>

									<?php
								}

								if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
								{
									?>
									<td style="font-weight: bold;height: 10px;"><?=find_pupil_sum_total_marks($que->pupil_id, 7, $que->school_year) ?></td>

								<td style="font-weight: bold;height: 10px;"><?=find_pupil_sum_total_marks_sem_trim($que->pupil_id, 1, 2, 7, $que->school_year) ?></td>
									<?php
								}
								?>
								<td style="font-weight: bold;height: 10px;"></td>
								<td style="font-weight: bold;height: 10px;"></td>

								<?php if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
								{
									$total_marks = find_all_pupil_marks_exist($que->pupil_id, 1, 2, 10, 3, 4, 11, $que->school_year);
									?>
									<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 3, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 3, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
									<?=find_pupil_sum_main_marks($que->pupil_id, 3, $que->school_year) ?>
								</td>

								<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 4, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 4, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
									<?=find_pupil_sum_main_marks($que->pupil_id, 4, $que->school_year) ?>
								</td>

									<td style="height: 10px;">
									</td>

									<td rowspan="8" colspan="2" style="font-size: 11px; padding-left: 10px; text-align: left; border-right: 2px solid black; border-bottom: 2px solid black;">
										<span style="line-height: 17px;">
											<span>PASSE (1)</span><br/>
											<span>DOUBLE (1)</span><br/>
											<span>ORIENTE VERS (1)</span><br/>
										</span>
										<span>
											<span>Fait à </span><strong><?= $school_city ?></strong>, le <strong><?= $date_end ?></strong>
										</span><br/><br/>
										<span>LE CHEF DE L'ETABLISSEMENT</span><br/><br/>
										<strong style="display: block; text-align: center;">Sceau de l'école</strong>
									</td>
									<?php
								}

								if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
								{
									$total_maxima = find_pupil_sum_total_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) + find_pupil_sum_total_marks_sem_trim($que->pupil_id, 3, 4, 11, $que->school_year);
									if($total_maxima == 0) {
										$total_maxima = "";
									}
									?>
									<!-- <td style="font-weight: bold;height: 10px;"></td>

									<td style="font-weight: bold;height: 10px;"></td>
									<td style="font-weight: bold;height: 10px;"></td> -->
									<?php
								}
								?>
							</tr>
							<tr>
								<td style="font-weight: bold; text-align: left;padding-left: 5px;height: 10px;">TOTAUX</td>

								<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 1, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
									<?=find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year) ?>
								</td>

								<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 2, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
									<?=find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year) ?>
								</td>

								<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 10, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
									<?=find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year) ?>
								</td>

								<td class="<?= (find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) < find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
									<?=find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) ?>
								</td>

								<?php

								if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
								{
									?>
									<td style="height: 10px;">
									</td>

									<td style="height: 10px;">
									</td>
									<?php
								}

								
								?>

									<td style="height: 10px;">
									</td>

									<td style="height: 10px;">
									</td>

								<?php

								if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
								{
									$total_marks = find_all_pupil_marks_exist($que->pupil_id, 1, 2, 10, 3, 4, 11, $que->school_year);
									?>
									<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 3, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 3, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
									<?=find_pupil_sum_main_marks($que->pupil_id, 3, $que->school_year) ?>
								</td>

								<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 4, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 4, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
									<?=find_pupil_sum_main_marks($que->pupil_id, 4, $que->school_year) ?>
								</td>

									<td style="height: 10px;">
									</td>
									<?php
								}

								if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
								{
									?>

									<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 8, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 8, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
										<?=find_pupil_sum_main_marks($que->pupil_id, 8, $que->school_year) ?>
									</td>

									<td class="<?= (find_pupil_sum_main_marks_sem_trim($que->pupil_id, 3, 4, 8, $que->school_year) < find_pupil_sum_total_marks_sem_trim($que->pupil_id, 3, 4, 8, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
										<?=find_pupil_sum_main_marks_sem_trim($que->pupil_id, 3, 4, 8, $que->school_year) ?>
									</td>

									<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 5, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 5, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
										<?=find_pupil_sum_main_marks($que->pupil_id, 5, $que->school_year) ?>
									</td>

									<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 6, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 6, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
										<?=find_pupil_sum_main_marks($que->pupil_id, 6, $que->school_year) ?>
									</td>

									<td class="<?= (find_pupil_sum_main_marks($que->pupil_id, 9, $que->school_year) < find_pupil_sum_total_marks($que->pupil_id, 9, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
										<?=find_pupil_sum_main_marks($que->pupil_id, 9, $que->school_year) ?>
									</td>

									<td class="<?= (find_pupil_sum_main_marks_sem_trim($que->pupil_id, 3, 4, 9, $que->school_year) < find_pupil_sum_total_marks_sem_trim($que->pupil_id, 3, 4, 9, $que->school_year)/2) ? 'red' : ''; ?>" style="font-weight: bold;height: 10px;">
										<?=find_pupil_sum_main_marks_sem_trim($que->pupil_id, 5, 6, 9, $que->school_year) ?>
									</td>
									<?php
								}

								?>
							</tr>

							<tr>
								<td style="text-align: left;padding-left: 5px;height: 10px;">Pourcentage</td>

								<!-- <td>
								</td> -->
								<!-- <td>
								</td> -->

								<?php

								if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
								{
									?>
									<td style="height: 10px;" class="<?= (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 50) ? 'red' : ''; ?>" style="">
										<?=find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) ?>
									</td>
									<td style="height: 10px;" class="<?= (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 50) ? 'red' : ''; ?>" style="">
										<?=find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) ?>
									</td>
									<td style="height: 10px;" class="<?= (find_pupil_pourcentage($que->pupil_id, 10, $que->school_year) < 50) ? 'red' : ''; ?>" style="">
										<?=find_pupil_pourcentage($que->pupil_id, 10, $que->school_year) ?>
									</td>
									<td style="height: 10px;" class="<?= (find_pupil_pourcentage_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) < 50) ? 'red' : ''; ?>" style="">
										<?=find_pupil_pourcentage_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) ?>
									</td>
									<?php
								}

								if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
								{
									?>
									<td style="height: 10px;" class="<?= (find_pupil_pourcentage($que->pupil_id, 7, $que->school_year) < 50) ? 'red' : ''; ?>" style="">
										<?=find_pupil_pourcentage($que->pupil_id, 7, $que->school_year) ?>
									</td>

									<td style="height: 10px;" class="<?= (find_pupil_pourcentage_sem_trim($que->pupil_id, 1, 2, 7, $que->school_year) < 50) ? 'red' : ''; ?>" style=""><?=find_pupil_pourcentage_sem_trim($que->pupil_id, 1, 2, 7, $que->school_year) ?>
									</td>
									<?php
								}

								?>

								<td style="height: 10px;">
								</td>

								<td style="height: 10px;">
									</td>

								<?php

								if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
								{
									// $total_maxima = find_pupil_sum_total_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) + find_pupil_sum_total_marks_sem_trim($que->pupil_id, 3, 4, 11, $que->school_year);
									
									if($total_maxima == 0) {
										$total_pourcentage = "";
									} else {
										$total_pourcentage = ($total_marks*100)/$total_maxima;
									}

									// if($total_pourcentage =)
									?>
									<td style="height: 10px;">
									</td>

									<td style="height: 10px;">
									</td>

									<td style="height: 10px;">
									</td>
									<?php
								}

								if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
								{
									?>
									<td style="height: 10px;" class="<?= (find_pupil_pourcentage($que->pupil_id, 8, $que->school_year) < 50) ? 'red' : ''; ?>" style="">
										<?=find_pupil_pourcentage($que->pupil_id, 8, $que->school_year) ?>
									</td>

									<td style="height: 10px;" class="<?= (find_pupil_pourcentage_sem_trim($que->pupil_id, 3, 4, 8, $que->school_year) < 50) ? 'red' : ''; ?>" style=""><?=find_pupil_pourcentage_sem_trim($que->pupil_id, 3, 4, 8, $que->school_year) ?>
									</td>

									<td style="height: 10px;" class="<?= (find_pupil_pourcentage($que->pupil_id, 5, $que->school_year) < 50) ? 'red' : ''; ?>" style="">
										<?=find_pupil_pourcentage($que->pupil_id, 5, $que->school_year) ?>
									</td>

									<td style="height: 10px;" class="<?= (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 50) ? 'red' : ''; ?>"style="">
										<?=find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) ?>
										</td>

									<td style="height: 10px;" class="<?= (find_pupil_pourcentage($que->pupil_id, 9, $que->school_year) < 50) ? 'red' : ''; ?>" style="">
										<?=find_pupil_pourcentage($que->pupil_id, 9, $que->school_year) ?>
									</td>

									<td style="height: 10px;" class="<?= (find_pupil_pourcentage_sem_trim($que->pupil_id, 5, 6, 9, $que->school_year) < 50) ? 'red' : ''; ?>" style="">
										<?= find_pupil_pourcentage_sem_trim($que->pupil_id, 5, 6, 9, $que->school_year) ?>		
									</td>
									<?php
								}

								?>
							</tr>
							<!-- <tr>
								<td style="text-align: left;padding-left: 5px;">Place</td>
							</tr> -->
							<tr>
							<td style="text-align: left;padding-left: 5px;height: 10px;">Place</td>
								<?php

									$nombre00 = "SELECT cycle_school, class_school, class_order, class_section, class_option, COUNT(*) AS cccc FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=?";
									$nombre11 = $database_connect->prepare($nombre00);
									$nombre11->execute(array($que->cycle_school, $que->class_school, $que->class_order, $que->class_section, $que->class_option));
									while($nombre = $nombre11->fetchObject())
									{
										?>
										<!-- <td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td> -->
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_1 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 1, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place;
											}
										 	?>
										</td>
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_2 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 2, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place;
											}
										 	?>
										</td>
										<!-- <td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td> -->
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_10 as $element => $value) {
													if($value == find_pupil_sum_main_marks($que->pupil_id, 10, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place;
											}
										 	?>
										</td>
										<!-- <td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td> -->
										<td style="text-align: center;padding-left: 5px;height:10px;font-size:10px;"><?php
											if(find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year) != "") {
												$place = 0;
												foreach($array_places_tot1 as $element => $value) {
													if($value == find_pupil_sum_main_marks_sem_trim($que->pupil_id, 1, 2, 10, $que->school_year)) {
														$place = $element + 1;
													}
												}
												echo $place;
											} 
										?>
										</td>
										<!-- <td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td> -->
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<!-- <td style="text-align: center;padding-left: 5px;height:10px;"></td> -->
										<!-- <td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td> -->
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<!-- <td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td> -->
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<!-- <td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td> -->
										<td style="text-align: center;padding-left: 5px;height:10px;"></td>
										<!-- <td style="font-weight: bold; border: 1px solid black; font-size: 10px; height: 10px; background-color: black;"></td> -->
										<?php
									}
								?>
							</tr>

							<tr>
								<td style="text-align: left;padding-left: 5px;height: 10px;">Nombre</td>
								<?php

								$nombre00 = "SELECT cycle_school, class_school, class_order, class_section, class_option, COUNT(*) AS cccc FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=?";
								$nombre11 = $database_connect->prepare($nombre00);
								$nombre11->execute(array($que->cycle_school, $que->class_school, $que->class_order, $que->class_section, $que->class_option));
								while($nombre = $nombre11->fetchObject())
								{
									?>
									<td style="text-align: center;padding-left: 5px;height:10px;"><?= $nombre->cccc ?></td>
									<td style="text-align: center;padding-left: 5px;height:10px;"><?= $nombre->cccc ?></td>
									<td style="text-align: center;padding-left: 5px;height:10px;"><?= $nombre->cccc ?></td>
									<td style="text-align: center;padding-left: 5px;height:10px;"><?= $nombre->cccc ?></td>
									<td style="text-align: center;padding-left: 5px;height:10px;"><?= $nombre->cccc ?></td>
									<td style="text-align: center;padding-left: 5px;height:10px;"><?= $nombre->cccc ?></td>
									<td style="text-align: center;padding-left: 5px;height:10px;"><?= $nombre->cccc ?></td>
									<td style="text-align: center;padding-left: 5px;height:10px;"><?= $nombre->cccc ?></td>
									<td style="text-align: center;padding-left: 5px;height:10px;"><?= $nombre->cccc ?></td>
									<?php
								}
								?>
							</tr>
							<tr>
								<td style="text-align: left;padding-left: 5px;height:10px;">Application</td>
							
								<td style="height: 10px;font-size:10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 1, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>

								<td style="height: 10px;font-size:10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 2, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>

								<?php

								if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
								{
									?>
									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>
									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>
									<?php
								}

								if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
								{
									?>
									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>
									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>
									<?php
								}

								?>

								<td style="height: 10px;font-size:10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>

								<td style="height: 10px;">
									<?php
										if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
									?>		
								</td>

								<?php

								if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
								{
									?>
									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>
									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>
									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>
									<?php
								}

								if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
								{
									?>
									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>
									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>

									<td style="height: 10px;font-size:10px;">
										<?php
										if (find_pupil_pourcentage($que->pupil_id, 5, $que->school_year) < 45) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 5, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 5, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 5, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 5, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 5, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 5, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
										?>		
									</td>

									<td style="height: 10px;">
										<?php
										if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) == 0) {
											echo "";
										}
										elseif (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 45 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) != 0) {
											echo "M";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) > 44 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 56) {
											echo "AB";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) > 55 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 66) {
											echo "B";
										}
										else if (find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) > 65 && find_pupil_pourcentage($que->pupil_id, 6, $que->school_year) < 80) {
											echo "TB";
										}
										else
										{
											echo "E";
										}
										?>		
									</td>

									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>
									<td style="border: none;background-color: rgb(0, 0, 0);height:10px;"></td>
									<?php
								}

								?>
							</tr>

							<tr>
								<td style="text-align: left;padding-left: 5px;height:10px;">Conduite</td>
								<td style="height: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 1, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 1, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}

										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="height: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 2, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 2, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}
										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
									<td style="border: ;background-color: rgb(0, 0, 0);height: 10px;"></td>
									<td style="border: ;background-color: rgb(0, 0, 0);height: 10px;"></td>
								<td style="height: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 3, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 3, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}
										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="height: 10px;">
									<?php

									if (count_conduite_already_exist($que->pupil_id, 4, $que->school_year) == 1) {
										$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
										$eee111 = $database_connect->prepare($eee000);
										$eee111->execute(array($que->pupil_id, 4, $que->school_year));
										$eer = $eee111->fetchObject();

										$conduitec = $eer->main_conduite;
										if ($conduitec == 1) {
											$conduitec = "E";
										} else if ($conduitec == 2) {
											$conduitec = "TB";
										} else if ($conduitec == 3) {
											$conduitec = "B";
										} else if ($conduitec == 4) {
											$conduitec = "AB";
										} else if ($conduitec == 5) {
											$conduitec = "M";
										} else {
											$conduitec = "MA";
										}
										echo $conduitec;
									}
									else
									{
										echo "";
									}

									?>
								</td>
								<td style="border: ;background-color: rgb(0, 0, 0);height: 10px;"></td>
								<td style="border: ;background-color: rgb(0, 0, 0);height: 10px;"></td>
								<td style="border: ;background-color: rgb(0, 0, 0);height: 10px;"></td>

								<?php
									if ($toUpper_class_name == "PRIMAIRE") {
										?>
										<td style="height: 10px;">
											<?php

											if (count_conduite_already_exist($que->pupil_id, 5, $que->school_year) == 1) {
												$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
												$eee111 = $database_connect->prepare($eee000);
												$eee111->execute(array($que->pupil_id, 5, $que->school_year));
												$eer = $eee111->fetchObject();

												$conduitec = $eer->main_conduite;
												if ($conduitec == 1) {
													$conduitec = "E";
												} else if ($conduitec == 2) {
													$conduitec = "TB";
												} else if ($conduitec == 3) {
													$conduitec = "B";
												} else if ($conduitec == 4) {
													$conduitec = "AB";
												} else if ($conduitec == 5) {
													$conduitec = "M";
												} else {
													$conduitec = "MA";
												}
												echo $conduitec;
											}
											else
											{
												echo "";
											}

											?>
										</td>

										<td style="height: 10px;">
											<?php

											if (count_conduite_already_exist($que->pupil_id, 6, $que->school_year) == 1) {
												$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
												$eee111 = $database_connect->prepare($eee000);
												$eee111->execute(array($que->pupil_id, 6, $que->school_year));
												$eer = $eee111->fetchObject();

												$conduitec = $eer->main_conduite;
												if ($conduitec == 1) {
													$conduitec = "E";
												} else if ($conduitec == 2) {
													$conduitec = "TB";
												} else if ($conduitec == 3) {
													$conduitec = "B";
												} else if ($conduitec == 4) {
													$conduitec = "AB";
												} else if ($conduitec == 5) {
													$conduitec = "M";
												} else {
													$conduitec = "MA";
												}
												echo $conduitec;
											}
											else
											{
												echo "";
											}

											?>
										</td>
										<td style="border: none;background-color: rgb(0, 0, 0);height: 10px;"></td>
										<td style="border: none;background-color: rgb(0, 0, 0);height: 10px;"></td>
										<?php
									}
								?>
							</tr>
							<tr>
							<td style="text-align: left;padding-left: 5px;height:10px;">Signature</td>
							<td style="text-align: center;padding-left: 5px;height:10px;"></td>
							<td style="text-align: center;padding-left: 5px;height:10px;"></td>
							<td style="text-align: center;padding-left: 5px;height:10px;"></td>
							<td style="text-align: center;padding-left: 5px;height:10px;"></td>
							<td style="text-align: center;padding-left: 5px;height:10px;"></td>
							<td style="text-align: center;padding-left: 5px;height:10px;"></td>
							<td style="text-align: center;padding-left: 5px;height:10px;"></td>
							<td style="text-align: center;padding-left: 5px;height:10px;"></td>
							<td style="text-align: center;padding-left: 5px;height:10px;"></td>
							</tr>
					</table>

<table style="border: 2px solid black; border-collapse:collapse; border-top: none; border-bottom: none;text-align: center; width: 100%;" class="class_table">
						<tr>
							<td colspan="4" style="width: 100%; border-top: none; border-bottom: 0px solid black; border-right: none; text-align: left; padding: 0px; padding-left: 10px;">
								<?php

if($que->class_school != 6) {
	?>
	<span style="display: block; font-size: 11px; text-align: left; padding-right: 10px; width: 100%;">
									<span>- L'élève ne pourra passer dans la classe supérieure s'il a subi avec succès un examen de repêchage en . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . (1)</span><br/>
									<span>- L'élève passe dans la classe supérieure (1)</span><br/>
									<span>- L'élève double la classe (1)</span><br/>
									<span>- L'élève est orienté (e) vers . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . (1)</span><br/>
								</span><?php
}
								?>
							</td>
						</tr>
						<tr>
							<td style="border-bottom: 0px solid black; width: 20%; border-top: none; padding-top: 0px; border-left: none; border-right: none; text-align: center; padding: 5px; padding-left: 10px;">
								<span style="font-size: 11px;">
									<!-- <span style="font-size: 50px; color: transparent;">i</span><br/> --><br/>
									<strong>Signature de l'élève</strong>
								</span>
							</td>
							<td style="border-bottom: 0px solid black; width: 20%; border-top: none; padding-top: 0px; border-left: none; border-right: none; text-align: center; padding: 5px; padding-left: 10px;">
								<span style="font-size: 11px;">
									<!-- <span style="font-size: 50px; color: transparent;">i</span><br/> --><br/>
									<strong>Sceau de l'école</strong>
								</span>
							</td>
							<td style="width: 40%; border-top: none; border-left: none; border-bottom: 0px solid black; text-align: center; padding: 5px; padding-left: 10px; border-right: 2px solid black;">
								<span style="font-size: 11px;">
									<br/><span>Fait à </span><strong><?= $school_city ?></strong>, le <strong><?= $date_end ?></strong><br/>
									<span>LE CHEF D'ETABLISSEMENT</span><br/><br/><br/>
									<span><?= $chef ?></span>
								</span>
							</td>
						</tr>
					</table>
					<table style="border: 2px solid black; margin-bottom: 10px; border-collapse: collapse; border-top: none; text-align: center; width: 100%;margin-top:-20px;" class="class_table">
						<tr>
							<td style="border: none; border-top: none; border-right: none; text-align: left; padding: 5px; padding-left: 10px;">
								<span style="font-size: 9px;">
									<strong style="">(I) Buffer la mention inutile <br/> Note impotante : le bulletin est sans valeur s'il est raturé ou surchargé</strong>
								</span><br/>
							</td>
						</tr>
					</table>
				</div>
				<?php
			}
			}}
		}
		}
		?>
		</div>
	</div>
	<script type="text/javaScript" src="design/dynamic/class_in.functions.js"></script>
	<?php 
		if (isset($_GET['print_content'])) {
			?>
			<script>
		window.print();
	</script><?php
		}
	?>
</body>
