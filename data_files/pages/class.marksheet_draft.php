<?php

	if(session_in() == 0)
	{
		Header('Location: ./?_=login');
	}

	include 'config/class.marksheet.insert.functions.php';

	$cycle_name = find_cycle_name($_GET['cycle']);
	$class_number = find_class_number($_GET['class_id']);
	$true_class_number = find_class_number($_GET['class_id']);
	$order_name = find_order_name($_GET['order_id']);
	$section_name = find_section_name($_GET['section_id']);
	$option_name = find_option_name($_GET['option_id']);
	$school_year = find_school_year($_GET['school_year']);

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

<h2 style="text-align: center;"><u class="printFicheClass" style="cursor: pointer;">Imprimer la fiche</u></h2>

	<div id="print_fiche_points_draft" style="display: block !important; height: 0px;">
		

		<table style="border-collapse:collapse;margin-left: 0%;min-width:100%;text-align: center;" class="class_table">
		<caption>
		<div style="text-align: left;">
			<strong><?= mb_strtoupper($school_name) ?></strong><br/>
			<strong><?= strtoupper($school_bp) ?></strong><br/>
			<strong>PERIODE/EXAMEN:...............................</strong>
		</div>
		<h3 style="text-align: center;">FICHE SYNTHESE DES POINTS : <?=$class_identity?></h3>
		</caption>
		<thead>
			<tr>
				<th style="text-align:left;padding-left: 5px;">N0</th>
				<th style="text-align:left;padding-left: 5px;width: 500px;">Noms de l'élève</th>
				<?php 
				if(count_courses_exist($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
				{
					$query_fetch00_cn = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY course_id ASC";
					$query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
					$query_fetch11_cn->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
					while($query_fetch_cn = $query_fetch11_cn->fetchObject())
					{

						?>
						<th style="text-align:center; border: 1px solid black;width: 400px;" colspan="2">
							<?=$query_fetch_cn->course_name." /".$query_fetch_cn->total_marks?>
						</th>
						<?php
					}
				}
				?>
			</tr>
			<tr>
				<th style="text-align:left;padding-left: 5px;border:1px solid black;"></th>
				<th style="text-align:left;padding-left: 5px;width: 500px; border: 1px solid black;"></th>
				<?php 
				if(count_courses_exist($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
				{
					$query_fetch00_cn = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY course_id ASC";
					$query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
					$query_fetch11_cn->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
					while($query_fetch_cn = $query_fetch11_cn->fetchObject())
					{

						?>
						<th style="text-align:center; border: 1px solid black;">
							2P
						</th>
						<th style="text-align:center; border: 1px solid black;">
							EX
						</th>
						<?php
					}
				}
				?>
			</tr>
			
			</thead>
			<?php

			if(count_pupils_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
			{
				$number = 0;
				$query_fetch000 = "SELECT pupil_id, first_name, second_name, last_name, cycle_school, class_school, class_order, class_section, class_option, school_year FROM pupils_info WHERE (cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=?) ORDER BY first_name ASC";
				$query_fetch110 = $database_connect->prepare($query_fetch000);
				$query_fetch110->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
				while($query_fetch0 = $query_fetch110->fetchObject())
				{
					$number += 1;
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

						// if(count_marks_exist($query_fetch0->pupil_id, $periode) != 0)
						// {
							$query_fetch00_cn1 = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY course_id ASC";
							$query_fetch11_cn1 = $database_connect->prepare($query_fetch00_cn1);
							$query_fetch11_cn1->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
							while($query_fetch_cn1 = $query_fetch11_cn1->fetchObject())
							{
								?>
								<!-- <td style="text-align: center; border: 1px solid black;"></td> -->
								<td style="text-align: center; border: 1px solid black;"></td>
								<td style="text-align: center; border: 1px solid black;"></td>
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