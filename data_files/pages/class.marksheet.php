<?php

	if(session_in() == 0)
	{
		Header('Location: ./?_=login');
	}

	$cycle_name = find_cycle_name($_GET['cycle']);
	$class_number = find_class_number($_GET['class_id']);
	$true_class_number = find_class_number($_GET['class_id']);
	$order_name = find_order_name($_GET['order_id']);
	$section_name = find_section_name($_GET['section_id']);
	$option_name = find_option_name($_GET['option_id']);
	$school_year = find_school_year($_GET['school_year']);
	$periode = $_GET['periode'];

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
		<h3 style="text-align: center;">FICHE SYNTHESE DES POINTS <?= mb_strtoupper($per) ?> : <?= mb_strtoupper($class_identity) ?></h3>
			<thead>
			<tr>
				<th>No</th>
				<th style="text-align:left;padding-left: 5px;">Noms de l'élève</th>
				<?php 
				if(count_courses_exist($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
				{
					$query_fetch00_cn = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY total_marks ASC";
					$query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
					$query_fetch11_cn->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
					while($query_fetch_cn = $query_fetch11_cn->fetchObject())
					{
						?>
						<th style="text-align:center; border: 1px solid black;">
							<?=$query_fetch_cn->course_name." /".$query_fetch_cn->total_marks?>
						</th>
						<?php
					}
				}
				?>
				<th style="text-align:center; border: 1px solid black;">TOTAUX</th>
				<th style="text-align:center; border: 1px solid black;">MAXIMA GENERAUX</th>
				<th style="text-align:center; border: 1px solid black;">POUR-<br/>CENTAGE</th>
			</tr>
			</thead>
			<?php

			if(count_pupils_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
			{
				$query_fetch000 = "SELECT * FROM pupils_info WHERE (cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=?) ORDER BY first_name ASC";
				$query_fetch110 = $database_connect->prepare($query_fetch000);
				$query_fetch110->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
				while($query_fetch0 = $query_fetch110->fetchObject())
				{
					$number = $number + 1;
					?>
					<tr class="okokhover">
					<td style="text-align: left;font-weight: normal; padding-left: 5px;">
					<?= $number ?>
					</td>
						<td style="text-align: left;font-weight: normal; padding-left: 5px;">
							<a>
								<?=strtoupper($query_fetch0->first_name)." ".strtoupper($query_fetch0->second_name)." ".ucwords($query_fetch0->last_name)?>
							</a>
						</td>
						<?php

						if(count_marks_exist($query_fetch0->pupil_id, $periode) != 0)
						{
							$query_fetch00_cn1 = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY total_marks ASC";
							$query_fetch11_cn1 = $database_connect->prepare($query_fetch00_cn1);
							$query_fetch11_cn1->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
							while($query_fetch_cn1 = $query_fetch11_cn1->fetchObject())
							{
								$marks00tt = "SELECT pupil, course, main_marks, total_marks, school_period, date_work, COUNT(*) AS count_markss FROM marks_info WHERE pupil=? AND course=? AND school_period=? AND school_year=? ORDER BY total_marks ASC";
								$marks11tt = $database_connect->prepare($marks00tt);
								$marks11tt->execute(array($query_fetch0->pupil_id, $query_fetch_cn1->course_id, $periode, $_GET['school_year']));
								$markstt = $marks11tt->fetchObject();

								if ($markstt->count_markss != 0) {
									$marks00 = "SELECT pupil, course, main_marks, total_marks, school_period, date_work FROM marks_info WHERE pupil=? AND course=? AND school_period=? AND school_year=? ORDER BY total_marks ASC";
									$marks11 = $database_connect->prepare($marks00);
									$marks11->execute(array($query_fetch0->pupil_id, $query_fetch_cn1->course_id, $periode, $_GET['school_year']));
									$marks = $marks11->fetchObject();
		
									if($marks->main_marks == 0)
									{
										$mm = 0;
									}
									else
									{
										$mm = $marks->main_marks;
									}
		
									?>
									<td class="<?= ($mm < ($marks->total_marks/2)) ? 'red' : ''; ?>" style=" text-align: center;"><?=$mm?></td>
									<?php
								} else {
									?>
									<td style=" text-align: center;">-</td>
									<?php
								}
							}

							$mm0 = "SELECT SUM(main_marks) AS sum_main_marks FROM marks_info WHERE pupil=? AND school_period=? ORDER BY course ASC";
							$mm1 = $database_connect->prepare($mm0);
							$mm1->execute(array($query_fetch0->pupil_id, $periode));
							$mmm = $mm1->fetchObject();
							
							// if ($query_fetch00_cn1->considered == "" || $query_fetch00_cn1->considered == "1") {
								$tmm0 = "SELECT SUM(total_marks) AS sum_total_marks FROM marks_info WHERE pupil=?  AND school_period=? ORDER BY course ASC";
								$tmm1 = $database_connect->prepare($tmm0);
								$tmm1->execute(array($query_fetch0->pupil_id, $periode));
								$tmmm = $tmm1->fetchObject();
							// }
							// else {
							// 	$tmmm = 0;
							// }

							$pourcentage = ($mmm->sum_main_marks*100)/$tmmm->sum_total_marks;
							$main_pourcentage = "$pourcentage";

							?>
							<td class="<?= ($mmm->sum_main_marks < ($tmmm->sum_total_marks/2)) ? 'red' : ''; ?>" style="font-weight: bold; text-align: center;"><?=$mmm->sum_main_marks?></td>

							<td style="font-weight: bold; text-align: center;">
								<?=$tmmm->sum_total_marks?>
							</td>
							<td class="<?= ($pourcentage < 50) ? 'red' : ''; ?>" style=" font-weight: bold; text-align: center;">
								<?=substr($main_pourcentage, 0, 4)." %"?>
							</td>
							<?php
						}
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