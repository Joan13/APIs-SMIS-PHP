<?php

	if(session_in() == 0)
	{
		Header('Location: ./?_=login');
	}

	$ccycle = $_GET['cycle'];
	$cclass = $_GET['class_id'];
	$oorder = $_GET['order_id'];
	$ssection = $_GET['section_id'];
	$ooption = $_GET['option_id'];
	$sschool = $_GET['school_year'];

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
			if($option_name == "-")
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
	<h2 style="text-align: center;">Insertion conduite : <?=$class_identity?></h2>

	<form method="POST">
		<table style="border-collapse: collapse;margin-left: 15%;width:70%;text-align: center; margin-bottom: 20px;" class="class_table">
			<tr>
				<th style="text-align:left;padding-left: 5px;">Année scolaire</th>
				<th style="text-align:left;padding-left: 5px;">Sélectionner la période</th>
				<th style="text-align:left;padding-left: 5px;">Date de l'entrée</th>
			</tr>
			<tr>
				<td style="width: 33.333333333%;">
					<select class="innput_tl" name="year_school" id="school_year_pupil" style="width : 100%">
				    	<option value="" class="login_item"> -- Sélectionner l'année --</option>
				    	<?php
				    	if(count_school_years_exist() != 0)
				    	{
				    		$years00 = "SELECT year_id, year_name FROM school_years";
				    		$years11 = $database_connect->query($years00);
				    		while($yearss = $years11->fetchObject())
				    		{
				    			?>
				    			<option value="<?= $yearss->year_id ?>" class="login_item"><?= $yearss->year_name ?></option>
				    			<?php
				    		}
				    	}
				    	?>
				    </select>
				</td>

				<td style="width: 33.333333333%">
					<select class="innput_tl" name="select_periode" style="width : 100%">
						<option value=""> -- Sélectionner la période -- </option>
						<option value="1"> 1 ère période </option>
						<option value="2"> 2 ème période </option>
						<option value="3"> 3 ème période </option>
						<option value="4"> 4 ème période </option>
						<?php
						if ($toUpper_class_name == "PRIMAIRE") {
							?>
							<option value="5"> 5 ème période </option>
							<option value="6"> 6 ème période </option>
							<?php
						}
						?>
					</select>
				</td>
				<td style="width: 33.333333333%">
					<input class="innput_tl" style="width : 90%" type="date" name="date_work" />
				</td>
			</tr>
		</table>
		<table style="border-collapse: collapse;margin-left: 15%;width:70%;text-align: center;" class="class_table">
			<tr>
				<th style="text-align:left;padding-left: 5px;">Noms de l'élève</th>
				<th style="text-align:left;padding-left: 5px;">Résultats</th>
			</tr>
			<?php

			if(count_pupils_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
			{
				$query_fetch00 = "SELECT cycle_school, class_school, class_order, class_section, class_option, pupil_id, first_name, second_name, last_name FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? ORDER BY first_name ASC";
				$query_fetch11 = $database_connect->prepare($query_fetch00);
				$query_fetch11->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
				while($query_fetch = $query_fetch11->fetchObject())
				{
					if(isset($_POST['validate_conduite']))
					{
						$main_conduite = htmlspecialchars(strip_tags(trim($_POST[$query_fetch->first_name."".$query_fetch->pupil_id])));
						$periode_conduite = htmlspecialchars(strip_tags(trim($_POST['select_periode'])));
						$date_conduite = htmlspecialchars(strip_tags(trim($_POST['date_work'])));
						$year_school = htmlspecialchars(strip_tags(trim($_POST['year_school'])));

						if($periode_conduite == '' || $date_conduite == '' || $year_school == '' || $main_conduite == "")
						{

						}
						else
						{

							if(count_conduite_already_exist($query_fetch->pupil_id, $periode_conduite, $year_school) == 1)
							{
								edit_pupil_conduite($query_fetch->pupil_id, $main_conduite, $periode_conduite, $year_school, $date_conduite);
							}
							else
							{
								insert_pupil_conduite($query_fetch->pupil_id, $main_conduite, $periode_conduite, $year_school, $date_conduite);
							}

							// if(count_courses_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id']) != 0)
							// {
							// 	$query_fetch00_cn = "SELECT course_id, course_name, cycle_id, class_id, order_id, section_id, option_id, total_marks FROM courses WHERE (cycle_id=? AND class_id=? AND order_id=? AND section_id=? AND option_id=?) OR (cycle_id=?) OR (cycle_id=? AND class_id=?) OR (section_id=? AND section_id!=?) OR (option_id=? AND option_id!=?) ORDER BY course_id ASC";
							// 	$query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
							// 	$query_fetch11_cn->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['cycle'], $_GET['cycle'], $_GET['class_id'], $_GET['section_id'], 0, $_GET['option_id'], 0));
							// 	while($query_fetch_cn = $query_fetch11_cn->fetchObject())
							// 	{
							// 		if(count_marks_already_exist($query_fetch->pupil_id, $query_fetch_cn->course_id, $periode_marks, $year_school) == 0)
							// 		{
							// 			insert_course_marks($query_fetch->pupil_id, $query_fetch_cn->course_id, 0,0, $periode_marks, $year_school, " ");
							// 		}
							// 	}
							// }
						}
					}

					?>
					<tr>
						<td style=" text-align: left;padding-left: 5px;">
							<?=strtoupper($query_fetch->first_name)." ".strtoupper($query_fetch->second_name)." ".ucwords($query_fetch->last_name)?>
						</td>
						<td style="text-align: left; width: 150px;">
							<!-- <input class="cccl" type="number"  /> -->
							<select class="innput_tl" name="<?=$query_fetch->first_name."".$query_fetch->pupil_id?>"  style="width : 100%">
								<option value=""> -- Choisir mention -- </option>
								<option value="1"> Excellent </option>
								<option value="2"> Très bien </option>
								<option value="3"> Bien </option>
								<option value="4"> Assez bien </option>
								<option value="5"> Médiocre </option>
								<option value="6"> Mauvais </option>
							</select>
						</td>
					</tr>
					<?php
				}
			}

			?>

		</table>

		<table style="border-collapse: collapse; margin-left: 15%;width:70%;text-align: center; margin-top: 20px;" class="class_table">
			<tr>
				<td style=" text-align: center;padding-left: 5px; border-color: transparent;">
					<button type="submit" name="validate_conduite" class="validate_login" style="width: 100%;">Enregistrer la fiche de conduite</button>
				</td>
			</tr>
		</table>
	</form><br/><br/><br/><br/>
</div>
