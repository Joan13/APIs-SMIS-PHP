<?php

	require_once('../config/dbconnect.functions.php');
	include '../config/class.marksheet.functions.php';

	$cycle_name = find_cycle_name($_GET['cycle']);
	$class_number = find_class_number($_GET['class_id']);
	$true_class_number = find_class_number($_GET['class_id']);
	$order_name = find_order_name($_GET['order_id']);
	$section_name = find_section_name($_GET['section_id']);
	$option_name = find_option_name($_GET['option_id']);
	$school_year = find_school_year($_GET['school_year']);
	$periode = 1;//$_GET['periode'];

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
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javaScript" src="jquery-3.2.1.min.js"></script>
	<link rel="stylesheet" href="style.css" />
	<title>Document</title>
</head>
<body>
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
					
					<strong><?= $number ?> : <?= mb_strtoupper($query_fetch0->first_name)." ".mb_strtoupper($query_fetch0->second_name)." ".ucwords($query_fetch0->last_name)?></strong>
		<table style="border-collapse:collapse;margin-left: 0%;min-width:95%;text-align: center;" class="class_table">
		
		<h3 style="text-align: center;">FICHE SYNTHESE DES POINTS <?= mb_strtoupper($per) ?> : <?= mb_strtoupper($class_identity) ?></h3>
			<thead>
			<tr>
				<th>Cours</th>
				<th style="text-align: center;">P1</th>
				<th style="text-align: center;">P2</th>
				<th style="text-align: center;">EX1</th>
				<!-- <th style="text-align: center;">TOT1</th> -->
				<th style="text-align: center;">P3</th>
				<th style="text-align: center;">P4</th>
				<th style="text-align: center;">EX2</th>
				<!-- <th style="text-align: center;">TOT2</th> -->
				
				<!-- <th style="text-align:center; border: 1px solid black;">TOTAUX</th>
				<th style="text-align:center; border: 1px solid black;">MAXIMA GENERAUX</th>
				<th style="text-align:center; border: 1px solid black;">POUR-<br/>CENTAGE</th> -->
			</tr>
			</thead>
			
					<tr class="okokhover">
					
						<?php

						if(count_courses_exist($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
						{
							$query_fetch00_cn = "SELECT * FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY course_id ASC";
							$query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
							$query_fetch11_cn->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
							while($query_fetch_cn = $query_fetch11_cn->fetchObject())
							{
								?>
								<tr>
								<td style="text-align:left; border: 1px solid black;padding-left: 10px;">
									<?=$query_fetch_cn->course_name." /".$query_fetch_cn->total_marks?>
								</td>

								<td class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 1, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" style=" text-align: center; width: 50px;">
									<input type="number" id="" value="<?= find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 1, $_GET['school_year']); ?>" 
									style="width: 50px; text-align: center; height: 20px; border: none; font-size: 13px;" 
									class="input1 <?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 1, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" 
									id="<?= "yambi".$query_fetch0->pupil_id."".$query_fetch_cn->course_id ?>"
									/>
								</td>

								<td class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 2, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" style=" text-align: center; width: 50px;">
									<input type="number" id="" value="<?= find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 2, $_GET['school_year']); ?>" 
									style="width: 50px; text-align: center; height: 20px; border: none; font-size: 13px;" 
									class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 2, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" />
								</td>

								<td class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 10, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" style=" text-align: center; width: 50px;">
									<input type="number" id="" value="<?= find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 10, $_GET['school_year']); ?>" 
									style="width: 50px; text-align: center; height: 20px; border: none; font-size: 13px;" 
									class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 10, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" />
								</td>

								<td class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 3, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" style=" text-align: center; width: 50px;">
									<input type="number" id="" value="<?= find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 3, $_GET['school_year']); ?>" 
									style="width: 50px; text-align: center; height: 20px; border: none; font-size: 13px;" 
									class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 3, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" />
								</td>

								<td class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 4, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" style=" text-align: center; width: 50px;">
									<input type="number" id="" value="<?= find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 4, $_GET['school_year']); ?>" 
									style="width: 50px; text-align: center; height: 20px; border: none; font-size: 13px;" 
									class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 4, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" />
								</td>

								<td class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 11, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" style=" text-align: center; width: 50px;">
									<input type="number" id="" value="<?= find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 11, $_GET['school_year']); ?>" 
									style="width: 50px; text-align: center; height: 20px; border: none; font-size: 13px;" 
									class="<?= (find_pupil_period_marks($query_fetch0->pupil_id, $query_fetch_cn->course_id, 11, $_GET['school_year']) < ($query_fetch_cn->total_marks/2)) ? 'red' : ''; ?>" />
								</td>

								<script>
								$(document).ready(function() {
									

									console.log($("#<?= "yambi".$query_fetch0->pupil_id."".$query_fetch_cn->course_id ?>").val());
								});
								</script>
								
								<?php
								}
								?>
								</tr>

								<?php
							}
						
						?>
					</tr>
		</table>
		<?php
				}
			}
			?>
	</div>
	<br/><br/><br/>
</div>
</body>
</html>