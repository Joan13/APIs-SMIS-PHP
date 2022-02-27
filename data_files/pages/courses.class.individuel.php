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

	function selected_pupil_exists($pupil_id)
	{
		global $database_connect;
    	$exist00 = "SELECT pupil_id, COUNT(*) AS selected_pupil_exists FROM pupils_info";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($pupil_id));
    	$exist = $exist11->fetchObject();
    	$response = $exist->selected_pupil_exists;

    	return $response;
	}

	if(selected_pupil_exists($_GET['pupil_id']) != 0)
	{
		$query_fetch000 = "SELECT * FROM pupils_info WHERE pupil_id=?";
		$query_fetch110 = $database_connect->prepare($query_fetch000);
		$query_fetch110->execute(array($_GET['pupil_id']));
		$query_fetch0 = $query_fetch110->fetchObject();
	}

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
	<h2 style="text-align: center;">Entrez les points : <?=$class_identity?></h2>
	<h2 style="text-align: center;"><?= strtoupper($query_fetch0->first_name) ." ". strtoupper($query_fetch0->second_name) ." ". strtoupper($query_fetch0->last_name) ?></h2>

	<form method="POST" style="text-align: center;">

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
						<option value=""> ---------------- </option>
						<option value="10"> Examen semestre 1 </option>
						<option value="11"> Examen semestre 2 </option>
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
			<th style="text-align:left;padding-left: 5px;">Intitule du cours</th>
			<th style="text-align:left;padding-left: 5px;">MAXIMUM</th>
		</tr>
		<?php

			if(count_courses_exist($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
			{
				$query_fetch00 = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY course_id ASC";
				$query_fetch11 = $database_connect->prepare($query_fetch00);
				$query_fetch11->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
				while($query_fetch = $query_fetch11->fetchObject())
				{
					if(isset($_POST['validate_enter_bulletins'])) {
						$main_marks = htmlspecialchars(trim(strip_tags($_POST["main_marks".$query_fetch->course_id."".$_GET['pupil_id']])));
						$maxima = $query_fetch->total_marks;
						$pupil_id = $_GET['pupil_id'];
						$periode_marks = htmlspecialchars(strip_tags(trim($_POST['select_periode'])));
						$date_work = htmlspecialchars(strip_tags(trim($_POST['date_work'])));
						$year_school = htmlspecialchars(strip_tags(trim($_POST['year_school'])));
						$course_id = $query_fetch->course_id;

						if($periode_marks == 7 || $periode_marks == 8 || $periode_marks == 9 || $periode_marks == 10 || $periode_marks == 11)
						{
							$maxima = $maxima*2;
						}

						if($main_marks != "") {
							// if(count_marks_already_exist($pupil_id, $course_id, $periode_marks, $year_school) == 1)
							// {
							// 	edit_course_marks($pupil_id, $course_id, $main_marks, $maxima, $periode_marks, $year_school, $date_work);
							// }
							// else
							// {
							// 	insert_course_marks($pupil_id, $course_id, $main_marks, $maxima, $periode_marks, $year_school, $date_work);
							// }

							if(count_marks_already_exist($pupil_id, $course_id, $periode_marks, $year_school) > 1)
							{
								$qq = "DELETE FROM marks_info WHERE course='$course_id' AND pupil='$pupil_id' AND school_period='$periode_marks' AND school_year='$year_school'";
								$reqq = $database_connect->query($qq);
								
								insert_course_marks($pupil_id, $course_id, $main_marks, $maxima, $periode_marks, $year_school, $date_work);
							}
							else if(count_marks_already_exist($pupil_id, $course_id, $periode_marks, $year_school) == 1)
							{
								edit_course_marks($pupil_id, $course_id, $main_marks, $maxima, $periode_marks, $year_school, $date_work);
								// echo count_marks_already_exist($pupil_id, $course_id, $periode_marks, $year_school);
							}
							else
							{
								insert_course_marks($pupil_id, $course_id, $main_marks, $maxima, $periode_marks, $year_school, $date_work);
								// echo count_marks_already_exist($pupil_id, $course_id, $periode_marks, $year_school);
							}
						}

						if(count_courses_exist($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
						{
							// echo "Courses exist";

							$query_fetch0000 = "SELECT cycle_school, class_school, class_order, class_section, class_option, pupil_id, first_name, second_name, last_name FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? ORDER BY first_name ASC";
							$query_fetch1100 = $database_connect->prepare($query_fetch0000);
							$query_fetch1100->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
							while($query_fetch00 = $query_fetch1100->fetchObject())
							{
								$query_fetch00_cn = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY total_marks DESC";
								$query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
								$query_fetch11_cn->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
								$query_fetch_cn = $query_fetch11_cn->fetchObject();

								// echo $pupil_id;
								if(count_marks_already_exist($query_fetch00->pupil_id, $query_fetch_cn->course_id, $periode_marks, $year_school) == 0)
								{
									// echo "Marks ready to insert";
									// insert_course_marks($query_fetch00->pupil_id, $course_id, 0, 1, $periode_marks, $year_school, " ");
								}
							}
						}
					}
					?>
					<tr>
						<td style=" text-align: left;padding-left: 5px;">
							<a>
								<?=($query_fetch->course_name)?> (<?= $query_fetch->total_marks ?>)
							</a>
						</td>
						<td style=" text-align: left;padding-left: 5px; width: 110px;">
							<input type="number" name="<?="main_marks".$query_fetch->course_id."".$_GET['pupil_id']?>" class="main_marks" style="border: 1px solid rgba(0, 0, 0, 0.2); width: 100px; height: 30px;">
						</td>
					</tr>
					<?php
				}
			}
		?>
	</table><br/>

	<button type="submit" name="validate_enter_bulletins" class="validate_login" style="width: 60%;">Valider et enregistrer</button>

	</form><br/><br/><br/>
</div>
