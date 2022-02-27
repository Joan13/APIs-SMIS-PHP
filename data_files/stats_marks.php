<?php

	if(session_in() == 0)
	{
		Header('Location: ./?_=login');
	}

	include 'config/pupil.marks.functions.php';

	$cycle_name = find_cycle_name($_GET['cycle']);
	$class_number = find_class_number($_GET['class_id']);
	$true_class_number = find_class_number($_GET['class_id']);
	$order_name = find_order_name($_GET['order_id']);
	$section_name = find_section_name($_GET['section_id']);
	$option_name = find_option_name($_GET['option_id']);
	$school_year = find_school_year($_GET['school_year']);
	$periode = 1;


	$toUpper_class_name = strtoupper($cycle_name);
	$array_places_1 = array();
	$array_places_2 = array();
	$array_places_10 = array();
	$array_places_tot1 = array();

	$delqqq = "TRUNCATE TABLE astuces_palmares";
	$delrrr = $database_connect->query($delqqq);

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

			$qqq = "INSERT INTO astuces_palmares(pupil_id, periode, marks_infos) VALUES(?, ?, ?)";
			$rrr = $database_connect->prepare($qqq);
			$rrr->execute(array($query_fetchb->pupil_id, 1, find_pupil_sum_main_marks($query_fetchb->pupil_id, 1, $query_fetchb->school_year)));

			$rrr->execute(array($query_fetchb->pupil_id, 2, find_pupil_sum_main_marks($query_fetchb->pupil_id, 2, $query_fetchb->school_year)));

			$rrr->execute(array($query_fetchb->pupil_id, 10, find_pupil_sum_main_marks($query_fetchb->pupil_id, 10, $query_fetchb->school_year)));

			$rrr->execute(array($query_fetchb->pupil_id, 20, find_pupil_sum_main_marks_sem_trim($query_fetchb->pupil_id, 1, 2, 10, $query_fetchb->school_year)));
		}
	}

	//print_r($array_places_tot1);

	rsort($array_places_1);
	rsort($array_places_2);
	rsort($array_places_10);
	rsort($array_places_tot1);

	//print_r($array_places_tot1);


	

	if($periode == 1) {
		$per = "de la 1ère période";
	} else if($periode == 2) {
		$per = "de la 2ème période";
	} else if($periode == 3) {
		$per = "de la 3ème période";
	} else if($periode == 4) {
		$per = "de la 4ème période";
	} else if($periode == 5) {
		$per = "de la 5ème période";
	} else if($periode == 6) {
		$per = "de la 6ème période";
	}  else if($periode == 7) {
		$per = "des examens du 1er trimestre";
	} else if($periode == 8) {
		$per = "des examens du 2e trimestre";
	} else if($periode == 9) {
		$per = "des examens du 3e trimestre";
	} else if($periode == 10) {
		$per = "des examens du 1er semestre";
	} else if($periode == 11) {
		$per = "des examens du 2e semestre";
	} else {
		$per = "";
	}

	$number = 0;

	$toUpper_class_name = strtoupper($cycle_name);

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
	}
	if($toUpper_class_name == "PRIMAIRE")
	{
		$class_identity = "$class_number $cycle_name $order_name";
	}

	if($toUpper_class_name == "SECONDAIRE")
	{
		if($true_class_number >= 1)
		{
			if($option_name == "")
			{
				$option_name = "";
			}
			else
			{
				$option_name = ", ".$option_name;
			}

			$class_identity = $class_number." ".$section_name."".$order_name."".$option_name;
		}
		else
		{
			$class_identity = "$class_number $order_name";
		}
	}

?>

<div class="main_middle_container">

<style>
	@media print {
		/* table,
		table tr td,
		table tr th {
			page-break-inside: avoid;
		} */

		thead {
			display: table-header-group;
		}

	}
</style>

	<h2 style="text-align: center;"><u class="printFicheClassPoints" style="cursor: pointer;">Imprimer la fiche</u></h2>
	<div id="print_fiche_points" style="display: block !important; height: 0px;">

		<table style="border-collapse:collapse;margin-left: 0%;min-width:95%;text-align: center;" class="class_table">
		<caption>
		<div style="text-align: left;">
			<strong><?= mb_strtoupper($school_name) ." : ". $school_year ?></strong><br/>
			<strong><?= strtoupper($school_bp) ?></strong><br/>
		</div>
		<h3 style="text-align: center;">PALMARES DES RESULTATS DU PREMIER SEMESTRE : <?= mb_strtoupper($class_identity) ?></h3>
			<thead>
			<tr>
				<th>No</th>
				<th style="text-align:left;padding-left: 5px;">NOMS DE L'ELEVE</th>
				<th style="text-align:center; border: 1px solid black;">POINTS OBTENUS</th>
				<th style="text-align:center; border: 1px solid black;">PLACE</th>
			</tr>
			</thead>
			<?php

			if(count_pupils_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
			{
				$query_fetch000kk = "SELECT * FROM astuces_palmares WHERE periode=20 ORDER BY marks_infos DESC";
				$query_fetch110kk = $database_connect->query($query_fetch000kk);
				while($query_fetch0kk = $query_fetch110kk->fetchObject()) {

					$query_fetch000 = "SELECT * FROM pupils_info WHERE pupil_id=?";
					$query_fetch110 = $database_connect->prepare($query_fetch000);
					$query_fetch110->execute(array($query_fetch0kk->pupil_id));
					$query_fetch0 = $query_fetch110->fetchObject();

					$plus_80 = "";
					$plus_70 = "";
					$plus_60 = "";
					$plus_50 = "";
					$plus_40 = "";

					$number = $number + 1;

					?>
					<tr class="okokhover">
					<td style="text-align: left;font-weight: normal; padding-left: 5px;">
					<?= $number ?>
					</td>
						<td style="text-align: left;font-weight: normal; padding-left: 5px;">
							<a>
								<?= strtoupper($query_fetch0->first_name)." ".strtoupper($query_fetch0->second_name)." ".ucwords($query_fetch0->last_name)?>
							</a>
						</td>
						<td style="text-align: left;font-weight: normal; padding-left: 5px;">
							<a>

								<?php
								if(find_pupil_sum_main_marks_sem_trim($query_fetch0->pupil_id, 1, 2, 10, $query_fetch0->school_year) != "") {
									$place = 0;
									foreach($array_places_tot1 as $element => $value) {
										if($value == find_pupil_sum_main_marks_sem_trim($query_fetch0->pupil_id, 1, 2, 10, $query_fetch0->school_year)) {
											$place = $value;//element + 1;
										}
									}
									echo $place;
								}
							 	?>
							</a>
						</td>

						<td style="text-align: left;font-weight: normal; padding-left: 5px;">
							<a>

								<?php
								if(find_pupil_sum_main_marks_sem_trim($query_fetch0->pupil_id, 1, 2, 10, $query_fetch0->school_year) != "") {
									$place = 0;
									foreach($array_places_tot1 as $element => $value) {
										if($value == find_pupil_sum_main_marks_sem_trim($query_fetch0->pupil_id, 1, 2, 10, $query_fetch0->school_year)) {
											$place = $element + 1;
										}
									}
									echo $place;
								}
							 	?>
							</a>
						</td>
						<?php

						?>
					</tr>
					<?php
				}
			}
			?>
		</table>
	</div>
	<br/><br/><br/>
</div>
