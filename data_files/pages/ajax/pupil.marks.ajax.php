	<?php 

	include '../../config/dbconnect.functions.php';
	include '../../config/pupil.marks.functions.php';

	$cycle_name = find_cycle_name($_POST['cycle']);
	$class_number = find_class_number($_POST['class_id']);
	$true_class_number = find_class_number($_POST['class_id']);
	$order_name = find_order_name($_POST['order_id']);
	$section_name = find_section_name($_POST['section_id']);
	$option_name = find_option_name($_POST['option_id']);
	$school_year = find_school_year($_POST['school_year']);
	$yearr = $_POST['school_year'];

	$toUpper_class_name = strtoupper($cycle_name);

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

	if(selected_pupil_exists($_POST['pupil_id']) != 0)
	{
		$que00 = "SELECT * FROM pupils_info WHERE pupil_id=?";
		$que11 = $database_connect->prepare($que00);
		$que11->execute(array($_POST['pupil_id']));
		$que = $que11->fetchObject();
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

			$class_identity = $true_class_number."e ".$section_name."".$option_name;
			$class_identity_bis = $class_number_letter." ".$section_name."".$option_name;

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
			$class_identity = "$class_number $order_name";
			$class_identity_bis = $class_number_letter." ".$cycle_name;
		}
	}

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

	$total_maxima = 0;
	$total_marks = 0;

?>

	<div id="print_pupil_marks" style="background-color: white; margin-left: 2.5cm;">
		<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center;" class="class_table">
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

		<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center;" class="class_table">
			<tr>
				<td style="border: none; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">
					<div style=" padding: 10px; color: transparent;">
						<span style="font-weight: bold; padding: 5px; border-right: none; padding-left: 7px; padding-right: 7px; color: black;">No ID. :</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 0, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 1, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 2, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 3, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 4, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 5, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 6, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 7, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 8, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 9, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 10, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 11, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 12, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 13, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 14, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 15, 1) ?></span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 16, 1) ?></span><span style="border: 1px solid black; border-right: 1px solid black; padding: 5px; padding-left: 7px; padding-right: 7px; color: black;"><?= substr($que->identification_number, 17, 1) ?></span>
						<!-- <span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; border-right: none; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span><span style="border: 1px solid black; padding: 5px; padding-left: 7px; padding-right: 7px;">..</span> -->
					</div>
				</td>
			</tr>
		</table>

		<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none; text-align: center;" class="class_table">
			<tr>
				<td style="padding: 5px; padding-left: 10px; border-bottom: none; text-align: left; line-height: 18px;">
					<span>ECOLE : </span><strong><?=$school_name ?></strong><br/>
					<span>VILLE : </span><strong><?=$school_city ?></strong><br/>
					<span>COMMUNE : </span><strong><?=$school_commune ?></strong><br/>
					<span style="padding: 5px; border-right: none; padding-left: 0px; padding-right: 7px; color: black;">CODE : </span>
					<span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 0, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 1, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 2, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 3, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 4, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 5, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 6, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($code, 7, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black; border-left: none;"><?= substr($code, 8, 1) ?></span>
				</td>
				<td style="padding: 5px; padding-left: 10px; border-bottom: none; text-align: left; line-height: 18px;">
					<span>ELEVE : </span><strong><?= strtoupper($que->first_name." ".$que->second_name)." ".$que->last_name?></strong><span style="margin-left: 50px;">SEXE : </span><strong><?= $ggender ?></strong><br/>
					<span>NE (E) A : </span><strong><?=$que->birth_place ?></strong>, LE <strong><?= $birth_date ?></strong><br/>
					<span>CLASSE : </span><strong><?=$class_identity ?></strong><br/>
					<span style="padding: 5px; border-right: none; padding-left: 0px; padding-right: 7px; color: black;">No PERM. : </span>
					<span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 0, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 1, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 2, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 3, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 4, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 5, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 6, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 7, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 8, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; border-right: none; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 9, 1) ?></span><span style="border: 1px solid black; font-weight: bold; padding: 3px; padding-left: 5px; padding-right: 5px; color: black;"><?= substr($que->permanent_number, 10, 1) ?></span>
				</td>
			</tr>
		</table>

		<table style="border: 2px solid black; border-collapse:collapse; border-bottom: none;text-align: center;" class="class_table">
			<tr>
				<td style="border: none; font-weight: bold; line-height: 20px;">
					BULLETIN DE LA <?= strtoupper($class_identity_bis) ?><span style="color: transparent;">.....................</span>
					ANNEE SCOLAIRE <?= $school_year ?>
				</td>
			</tr>
		</table>

		<table style="border: 2px solid black; border-collapse:collapse; text-align: center;" class="class_table principal">
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

			if(count_courses_exist($_POST['cycle'], $_POST['class_id'], $_POST['section_id'], $_POST['option_id'], $_POST['school_year']) != 0)
			{
				$query_fetch00_cn = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY total_marks ASC";
				$query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
				$query_fetch11_cn->execute(array($_POST['cycle'], $_POST['class_id'], $_POST['section_id'], $_POST['option_id'], $_POST['school_year']));
				while($query_fetch_cn = $query_fetch11_cn->fetchObject())
				{

				// $query_fetch00_cn = "SELECT course_id, course_name, cycle_id, class_id, order_id, section_id, option_id, total_marks FROM courses WHERE (cycle_id=? AND class_id=? AND order_id=? AND section_id=? AND option_id=?) OR (cycle_id=?) OR (cycle_id=? AND class_id=?) OR (section_id=? AND section_id!=?) OR (option_id=? AND option_id!=?) ORDER BY total_marks ASC";
				// $query_fetch11_cn = $database_connect->prepare($query_fetch00_cn);
				// $query_fetch11_cn->execute(array($_POST['cycle'], $_POST['class_id'], $_POST['order_id'], $_POST['section_id'], $_POST['option_id'], $_POST['cycle'], $_POST['cycle'], $_POST['class_id'], $_POST['section_id'], 0, $_POST['option_id'], 0));
				// while($query_fetch_cn = $query_fetch11_cn->fetchObject())
				// {
					$sss0000 = "SELECT astuce_bulletin FROM other_settings";
					$sss1100 = $database_connect->query($sss0000);
					$sss00 = $sss1100->fetchObject();

					if ($sss00->astuce_bulletin != $query_fetch_cn->total_marks) {
						$query_fetch00_cnr = "SELECT course_id, total_marks FROM courses WHERE course_id=? ORDER BY total_marks ASC";

						$edit0000 = "UPDATE other_settings SET astuce_bulletin=?";
						$edit00 = $database_connect->prepare($edit0000);
						$edit00->execute(array($query_fetch_cn->total_marks));


						$query_fetch11_cnr = $database_connect->prepare($query_fetch00_cnr);
						$query_fetch11_cnr->execute(array($query_fetch_cn->course_id));
						while($query_fetch_cnr = $query_fetch11_cnr->fetchObject())
						{
							if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
							{	
								?>
								<tr>
									<td style="font-weight: bold;">MAXIMA</td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*2 ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*4 ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*2 ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*4 ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*8 ?></td>
									<td style="background-color: rgb(0, 0, 0);"></td>
									<td style="border-right: 2px solid black; background-color: rgb(0, 0, 0);"></td>
								</tr>
								<?php
							}

							if($toUpper_class_name == "PRIMAIRE")
							{	
								?>
								<tr>
									<td style="font-weight: bold;">MAXIMA</td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*2 ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*4 ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*2 ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*4 ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*2 ?></td>
									<td style="font-weight: bold;"><?=$query_fetch_cnr->total_marks*4 ?></td>
								</tr>
								<?php
							}
						}
					}

					?>
					<tr>
						<td style="text-align: left;padding-left: 5px;"><?=$query_fetch_cn->course_name?></td>
						
						<?php
						if (count_pupil_marks($_POST['pupil_id'], 1, $_POST['school_year']) != 0) {
							foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 1, $_POST['school_year']) as $period1)
							{
								?>
								<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
								<?php
							}
						} 
						else {
							?><td></td><?php
						}

						if (count_pupil_marks($_POST['pupil_id'], 2, $_POST['school_year']) != 0) {
							foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 2, $_POST['school_year']) as $period1)
							{
								?>
								<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
								<?php
							}
						} 
						else {
							?><td></td><?php
						}

						if ($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
						{
							if (count_pupil_marks($_POST['pupil_id'], 10, $_POST['school_year']) != 0) {
								foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 10, $_POST['school_year']) as $period1)
								{
									?>
									<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
									<?php
								}
							} 
							else {
								?><td></td><?php
							}
						}

						if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
						{
							if (count_pupil_marks($_POST['pupil_id'], 7, $_POST['school_year']) != 0) {
								foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 7, $_POST['school_year']) as $period1)
								{
									?>
									<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
									<?php
								}
							}
						}

						if ($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
						{
							foreach(find_pupil_semester_trimester_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 1, 2, 10, $_POST['school_year']) as $trim_sem)
							{
								?>
								<td class="<?= ($trim_sem->sum_main_marks < ($trim_sem->sum_total_marks/2)) ? 'red' : ''; ?>"><?=$trim_sem->sum_main_marks?></td>
								<?php
							}
						}

						if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
						{
							foreach(find_pupil_semester_trimester_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 1, 2, 7, $_POST['school_year']) as $trim_sem)
							{
								?>
								<td class="<?= ($trim_sem->sum_main_marks < ($trim_sem->sum_total_marks/2)) ? 'red' : ''; ?>"><?=$trim_sem->sum_main_marks?></td>
								<?php
							}
						}

						if (count_pupil_marks($_POST['pupil_id'], 3, $_POST['school_year']) != 0) {
							foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 3, $_POST['school_year']) as $period1)
							{
								?>
								<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
								<?php
							}
						} 
						else {
							?><td></td><?php
						}

						if (count_pupil_marks($_POST['pupil_id'], 4, $_POST['school_year']) != 0) {
							foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 4, $_POST['school_year']) as $period1)
							{
								?>
								<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
								<?php
							}
						} 
						else {
							?><td></td><?php
						}

						if ($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
						{
							if (count_pupil_marks($_POST['pupil_id'], 11, $_POST['school_year']) != 0) {
								foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 11, $_POST['school_year']) as $period1)
								{
									?>
									<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
									<?php
								}
							}
							else {
								?><td></td><?php
							}
						}

						if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
						{
							if (count_pupil_marks($_POST['pupil_id'], 8, $_POST['school_year']) != 0) {
								foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 8, $_POST['school_year']) as $period1)
								{
									?>
									<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
									<?php
								}
							}
						}

						if ($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
						{
							foreach(find_pupil_semester_trimester_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 3, 4, 11, $_POST['school_year']) as $trim_sem)
							{
								?>
								<td class="<?= ($trim_sem->sum_main_marks < ($trim_sem->sum_total_marks/2)) ? 'red' : ''; ?>"><?=$trim_sem->sum_main_marks?></td>
								<?php
							}

							?>
							<td style="width: 30px;"></td>
							<td style="border-right: 0px solid black;"></td>
							<?php
						} 
						

						if ($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
						{
							foreach(find_pupil_semester_trimester_marks_total($_POST['pupil_id'], $query_fetch_cn->course_id, 1, 2, 10, 3, 4, 11, $_POST['school_year']) as $trim_sem)
							{
								?>
								<td class="<?= ($trim_sem->sum_main_marks < ($trim_sem->sum_total_marks/2)) ? 'red' : ''; ?>"><?=$trim_sem->sum_main_marks?></td>
								<?php
							}

							?>
							<td style="width: 30px;"></td>
							<td style="border-right: 2px solid black;"></td>
							<?php
						} 

						if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
						{
							foreach(find_pupil_semester_trimester_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 3, 4, 8, $_POST['school_year']) as $trim_sem)
							{
								?>
								<td class="<?= ($trim_sem->sum_main_marks < ($trim_sem->sum_total_marks/2)) ? 'red' : ''; ?>"><?=$trim_sem->sum_main_marks?></td>
								<?php
							}
						}

						if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE") 
						{
							if (count_pupil_marks($_POST['pupil_id'], 5, $_POST['school_year']) != 0) 
							{
								foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 5, $_POST['school_year']) as $period1)
								{
									?>
									<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
									<?php
								}
							}

							if (count_pupil_marks($_POST['pupil_id'], 6, $_POST['school_year']) != 0) {
								foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 6, $_POST['school_year']) as $period1)
								{
									?>
									<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
									<?php
								}
							} else {
								?><td></td><?php
							}

							if (count_pupil_marks($_POST['pupil_id'], 9, $_POST['school_year']) != 0) 
							{
								foreach(find_pupil_period_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 9, $_POST['school_year']) as $period1)
								{
									?>
									<td class="<?= ($period1->main_marks < ($period1->total_marks/2)) ? 'red' : ''; ?>"><?=$period1->main_marks?></td>
									<?php
								}
							} else {
								?><td></td><?php
							}

							foreach(find_pupil_semester_trimester_marks($_POST['pupil_id'], $query_fetch_cn->course_id, 5, 6, 9, $_POST['school_year']) as $trim_sem2)
							{
								?>
								<td class="<?= ($trim_sem2->sum_main_marks < ($trim_sem2->sum_total_marks/2)) ? 'red' : ''; ?>"><?=$trim_sem2->sum_main_marks?></td>
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
					<td style="font-weight: bold; text-align: center;padding-left: 5px;">TOTAUX</td>

					<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 1, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 1, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
						<?=find_pupil_sum_main_marks($_POST['pupil_id'], 1, $_POST['school_year']) ?>
					</td>

					<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 2, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 2, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
						<?=find_pupil_sum_main_marks($_POST['pupil_id'], 2, $_POST['school_year']) ?>
					</td>

					<?php

					if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
					{
						?>
						<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 10, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 10, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks($_POST['pupil_id'], 10, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_sum_main_marks_sem_trim($_POST['pupil_id'], 1, 2, 10, $_POST['school_year']) < find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 1, 2, 10, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks_sem_trim($_POST['pupil_id'], 1, 2, 10, $_POST['school_year']) ?>
						</td>
						<?php
					}

					if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
					{
						?>
						<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 7, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 7, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks($_POST['pupil_id'], 7, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_sum_main_marks_sem_trim($_POST['pupil_id'], 1, 2, 7, $_POST['school_year']) < find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 1, 2, 7, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks_sem_trim($_POST['pupil_id'], 1, 2, 7, $_POST['school_year']) ?>
						</td>
						<?php
					}
					?>

					<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 3, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 3, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
						<?=find_pupil_sum_main_marks($_POST['pupil_id'], 3, $_POST['school_year']) ?>
					</td>

					<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 4, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 4, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
						<?=find_pupil_sum_main_marks($_POST['pupil_id'], 4, $_POST['school_year']) ?>
					</td>

					<?php

					if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
					{
						$total_marks = find_all_pupil_marks_exist($_POST['pupil_id'], 1, 2, 10, 3, 4, 11, $_POST['school_year']);
						?>
						<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 11, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 11, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks($_POST['pupil_id'], 11, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_sum_main_marks_sem_trim($_POST['pupil_id'], 3, 4, 11, $_POST['school_year']) < find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 3, 4, 11, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks_sem_trim($_POST['pupil_id'], 3, 4, 11, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_all_pupil_marks_exist($_POST['pupil_id'], 1, 2, 10, 3, 4, 11, $_POST['school_year']) < find_all_pupil_marks_exist($_POST['pupil_id'], 1, 2, 10, 3, 4, 11, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?= find_all_pupil_marks_exist($_POST['pupil_id'], 1, 2, 10, 3, 4, 11, $_POST['school_year']) ?>
						</td>

						<td rowspan="7" colspan="2" style="font-size: 11px; padding-left: 10px; text-align: left; border-right: 2px solid black; border-bottom: 2px solid black;">
							<span style="line-height: 17px;">
								<span>PASSE (1)</span><br/>
								<span>DOUBLE (1)</span><br/>
								<span>ORIENTE VERS (1)</span><br/>
							</span><br/><br/>
							<span>
								<span>Fait à </span><strong><?= $school_city ?></strong>, le <strong><?= $date_end ?></strong>
							</span><br/><br/><br/>
							<span>LE CHEF DE L'ETABLISSEMENT</span><br/><br/><br/><br/>
							<strong style="display: block; text-align: center;">Sceau de l'école</strong>
						</td>
						<?php
					}

					if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
					{
						?>

						<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 8, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 8, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks($_POST['pupil_id'], 8, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_sum_main_marks_sem_trim($_POST['pupil_id'], 3, 4, 8, $_POST['school_year']) < find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 3, 4, 8, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks_sem_trim($_POST['pupil_id'], 3, 4, 8, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 5, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 5, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks($_POST['pupil_id'], 5, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 6, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 6, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks($_POST['pupil_id'], 6, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_sum_main_marks($_POST['pupil_id'], 9, $_POST['school_year']) < find_pupil_sum_total_marks($_POST['pupil_id'], 9, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks($_POST['pupil_id'], 9, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_sum_main_marks_sem_trim($_POST['pupil_id'], 3, 4, 9, $_POST['school_year']) < find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 3, 4, 9, $_POST['school_year'])/2) ? 'red' : ''; ?>" style="font-weight: bold;">
							<?=find_pupil_sum_main_marks_sem_trim($_POST['pupil_id'], 5, 6, 9, $_POST['school_year']) ?>
						</td>
						<?php
					}

					?>
				</tr>


				<tr>
					<td style="font-weight: bold; text-align: center;padding-left: 5px;">MAXIMA GENERAUX</td>

					<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 1, $_POST['school_year']) ?></td>
					<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 2, $_POST['school_year']) ?></td>

					<?php

					if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
					{
						?>
						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 10, $_POST['school_year']) ?></td>

						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 1, 2, 10, $_POST['school_year']) ?></td>
						<?php
					}

					if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
					{
						?>
						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 7, $_POST['school_year']) ?></td>

					<td style="font-weight: bold;"><?=find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 1, 2, 7, $_POST['school_year']) ?></td>
						<?php
					}
					?>
					<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 3, $_POST['school_year']) ?></td>
					<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 4, $_POST['school_year']) ?></td>
					<?php

					if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
					{
						$total_maxima = find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 1, 2, 10, $_POST['school_year']) + find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 3, 4, 11, $_POST['school_year']);
						if($total_maxima == 0) {
							$total_maxima = "";
						}
						?>
						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 11, $_POST['school_year']) ?></td>

						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 3, 4, 11, $_POST['school_year']) ?></td>
						<td style="font-weight: bold;"><?= $total_maxima ?></td>
						<?php
					}

					if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
					{
						?>
						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 8, $_POST['school_year']) ?></td>

						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 3, 4, 8, $_POST['school_year']) ?></td>

						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 5, $_POST['school_year']) ?></td>
						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 6, $_POST['school_year']) ?></td>
						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks($_POST['pupil_id'], 9, $_POST['school_year']) ?></td>
						<td style="font-weight: bold;"><?=find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 5, 6, 9, $_POST['school_year']) ?></td>
						<?php
					}
					?>
				</tr>

				<tr>
					<td style="text-align: left;padding-left: 5px;">Pourcentage</td>

					<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 1, $_POST['school_year']) < 50) ? 'red' : ''; ?>"   style="">
						<?=find_pupil_pourcentage($_POST['pupil_id'], 1, $_POST['school_year']) ?>
					</td>
					<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 2, $_POST['school_year']) < 50) ? 'red' : ''; ?>"  style="">
						<?=find_pupil_pourcentage($_POST['pupil_id'], 2, $_POST['school_year']) ?>
					</td>

					<?php

					if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
					{
						?>
						<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 10, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style="">
							<?=find_pupil_pourcentage($_POST['pupil_id'], 10, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_pourcentage_sem_trim($_POST['pupil_id'], 1, 2, 10, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style=""><?=find_pupil_pourcentage_sem_trim($_POST['pupil_id'], 1, 2, 10, $_POST['school_year']) ?>
						</td>
						<?php
					}

					if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
					{
						?>
						<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 7, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style="">
							<?=find_pupil_pourcentage($_POST['pupil_id'], 7, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_pourcentage_sem_trim($_POST['pupil_id'], 1, 2, 7, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style=""><?=find_pupil_pourcentage_sem_trim($_POST['pupil_id'], 1, 2, 7, $_POST['school_year']) ?>
						</td>
						<?php
					}

					?>

					<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 3, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style="">
						<?=find_pupil_pourcentage($_POST['pupil_id'], 3, $_POST['school_year']) ?>
					</td>

					<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 4, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style="">
						<?=find_pupil_pourcentage($_POST['pupil_id'], 4, $_POST['school_year']) ?>
						</td>

					<?php

					if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
					{
						// $total_maxima = find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 1, 2, 10, $_POST['school_year']) + find_pupil_sum_total_marks_sem_trim($_POST['pupil_id'], 3, 4, 11, $_POST['school_year']);
						
						if($total_maxima == 0) {
							$total_pourcentage = "";
						} else {
							$total_pourcentage = ($total_marks*100)/$total_maxima;
						}

						// if($total_pourcentage =)
						?>
						<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 11, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style="">
							<?=find_pupil_pourcentage($_POST['pupil_id'], 11, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_pourcentage_sem_trim($_POST['pupil_id'], 3, 4, 11, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style="">
						<?=find_pupil_pourcentage_sem_trim($_POST['pupil_id'], 3, 4, 11, $_POST['school_year']) ?>
						</td>

						<td class="<?= ($total_pourcentage < 50) ? 'red' : ''; ?>" style="">
						<?= $total_pourcentage ?>
						</td>
						<?php
					}

					if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
					{
						?>
						<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 8, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style="">
							<?=find_pupil_pourcentage($_POST['pupil_id'], 8, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_pourcentage_sem_trim($_POST['pupil_id'], 3, 4, 8, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style=""><?=find_pupil_pourcentage_sem_trim($_POST['pupil_id'], 3, 4, 8, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 5, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style="">
							<?=find_pupil_pourcentage($_POST['pupil_id'], 5, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 50) ? 'red' : ''; ?>"style="">
							<?=find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) ?>
							</td>

						<td class="<?= (find_pupil_pourcentage($_POST['pupil_id'], 9, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style="">
							<?=find_pupil_pourcentage($_POST['pupil_id'], 9, $_POST['school_year']) ?>
						</td>

						<td class="<?= (find_pupil_pourcentage_sem_trim($_POST['pupil_id'], 5, 6, 9, $_POST['school_year']) < 50) ? 'red' : ''; ?>" style="">
							<?=find_pupil_pourcentage_sem_trim($_POST['pupil_id'], 5, 6, 9, $_POST['school_year']) ?>		
						</td>
						<?php
					}

					?>
				</tr>
				<!-- <tr>
					<td style="text-align: left;padding-left: 5px;">Place</td>
				</tr> -->
				<tr>
					<td style="text-align: left;padding-left: 5px;">Nombre</td>
					<?php

					$nombre00 = "SELECT cycle_school, class_school, class_order, class_section, class_option, COUNT(*) AS cccc FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=?";
					$nombre11 = $database_connect->prepare($nombre00);
					$nombre11->execute(array($_POST['cycle'], $_POST['class_id'], $_POST['order_id'], $_POST['section_id'], $_POST['option_id']));
					while($nombre = $nombre11->fetchObject())
					{
						if($toUpper_class_name == "SECONDAIRE" || $toUpper_class_name == "HUMANITES" || $toUpper_class_name == "CYCLE TERMINAL EDUCATION DE BASE")
						{
							?>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<?php
						}
						if($toUpper_class_name == "PRIMAIRE")
						{
							?>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<td style="text-align: center;padding-left: 5px;"><?= $nombre->cccc ?></td>
							<?php
						}
					}
					?>
				</tr>
				<tr>
					<td style="text-align: left;padding-left: 5px;">Application</td>
				
					<td>
						<?php
							if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) == 0) {
								echo "";
							}
							elseif (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 45 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) != 0) {
								echo "M";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 44 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 56) {
								echo "AB";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 55 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 66) {
								echo "B";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 65 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 80) {
								echo "TB";
							}
							else
							{
								echo "E";
							}
						?>		
					</td>

					<td>
						<?php
							if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) == 0) {
								echo "";
							}
							elseif (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 45 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) != 0) {
								echo "M";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 44 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 56) {
								echo "AB";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 55 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 66) {
								echo "B";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 65 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 80) {
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
						<td style="border: none;background-color: rgb(0, 0, 0);"></td>
						<td style="border: none;background-color: rgb(0, 0, 0);"></td>
						<?php
					}

					if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
					{
						?>
						<td style="border: none;background-color: rgb(0, 0, 0);"></td>
						<td style="border: none;background-color: rgb(0, 0, 0);"></td>
						<?php
					}

					?>

					<td>
						<?php
							if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) == 0) {
								echo "";
							}
							elseif (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 45 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) != 0) {
								echo "M";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 44 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 56) {
								echo "AB";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 55 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 66) {
								echo "B";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 65 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 80) {
								echo "TB";
							}
							else
							{
								echo "E";
							}
						?>		
					</td>

					<td>
						<?php
							if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) == 0) {
								echo "";
							}
							elseif (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 45 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) != 0) {
								echo "M";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 44 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 56) {
								echo "AB";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 55 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 66) {
								echo "B";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 65 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 80) {
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
						<td style="border: none;background-color: rgb(0, 0, 0);"></td>
						<td style="border: none;background-color: rgb(0, 0, 0);"></td>
						<td style="border: none;background-color: rgb(0, 0, 0);"></td>
						<?php
					}

					if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL" || $toUpper_class_name == "PRIMAIRE")
					{
						?>
						<td style="border: none;background-color: rgb(0, 0, 0);"></td>
						<td style="border: none;background-color: rgb(0, 0, 0);"></td>

						<td>
							<?php
							if (find_pupil_pourcentage($_POST['pupil_id'], 5, $_POST['school_year']) < 45) {
								echo "M";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 5, $_POST['school_year']) > 44 && find_pupil_pourcentage($_POST['pupil_id'], 5, $_POST['school_year']) < 56) {
								echo "AB";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 5, $_POST['school_year']) > 55 && find_pupil_pourcentage($_POST['pupil_id'], 5, $_POST['school_year']) < 66) {
								echo "B";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 5, $_POST['school_year']) > 65 && find_pupil_pourcentage($_POST['pupil_id'], 5, $_POST['school_year']) < 80) {
								echo "TB";
							}
							else
							{
								echo "E";
							}
							?>		
						</td>

						<td>
							<?php
							if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) == 0) {
								echo "";
							}
							elseif (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 45 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) != 0) {
								echo "M";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 44 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 56) {
								echo "AB";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 55 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 66) {
								echo "B";
							}
							else if (find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) > 65 && find_pupil_pourcentage($_POST['pupil_id'], 6, $_POST['school_year']) < 80) {
								echo "TB";
							}
							else
							{
								echo "E";
							}
							?>		
						</td>

						<td style="border: none;background-color: rgb(0, 0, 0);"></td>
						<td style="border: none;background-color: rgb(0, 0, 0);"></td>
						<?php
					}

					?>
				</tr>

				<tr>
					<td style="text-align: left;padding-left: 5px;">Conduite</td>
					<td>
						<?php

						if (count_conduite_already_exist($_POST['pupil_id'], 1, $_POST['school_year']) == 1) {
							$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
							$eee111 = $database_connect->prepare($eee000);
							$eee111->execute(array($_POST['pupil_id'], 1, $_POST['school_year']));
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
					<td>
						<?php

						if (count_conduite_already_exist($_POST['pupil_id'], 2, $_POST['school_year']) == 1) {
							$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
							$eee111 = $database_connect->prepare($eee000);
							$eee111->execute(array($_POST['pupil_id'], 2, $_POST['school_year']));
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
						<td style="border: ;background-color: rgb(0, 0, 0);"></td>
						<td style="border: ;background-color: rgb(0, 0, 0);"></td>
					<td>
						<?php

						if (count_conduite_already_exist($_POST['pupil_id'], 3, $_POST['school_year']) == 1) {
							$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
							$eee111 = $database_connect->prepare($eee000);
							$eee111->execute(array($_POST['pupil_id'], 3, $_POST['school_year']));
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
					<td>
						<?php

						if (count_conduite_already_exist($_POST['pupil_id'], 4, $_POST['school_year']) == 1) {
							$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
							$eee111 = $database_connect->prepare($eee000);
							$eee111->execute(array($_POST['pupil_id'], 4, $_POST['school_year']));
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
					<td style="border: ;background-color: rgb(0, 0, 0);"></td>
					<td style="border: ;background-color: rgb(0, 0, 0);"></td>
					<td style="border: ;background-color: rgb(0, 0, 0);"></td>

					<?php
						if ($toUpper_class_name == "PRIMAIRE") {
							?>
							<td>
								<?php

								if (count_conduite_already_exist($_POST['pupil_id'], 5, $_POST['school_year']) == 1) {
									$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
									$eee111 = $database_connect->prepare($eee000);
									$eee111->execute(array($_POST['pupil_id'], 5, $_POST['school_year']));
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

							<td>
								<?php

								if (count_conduite_already_exist($_POST['pupil_id'], 6, $_POST['school_year']) == 1) {
									$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
									$eee111 = $database_connect->prepare($eee000);
									$eee111->execute(array($_POST['pupil_id'], 6, $_POST['school_year']));
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
							<td style="border: none;background-color: rgb(0, 0, 0);"></td>
							<td style="border: none;background-color: rgb(0, 0, 0);"></td>
							<?php
						}
					?>
				</tr>
		</table>

		<table style="border: 2px solid black; border-collapse:collapse; border-top: none; border-bottom: none;text-align: center;" class="class_table">
			<tr>
				<td style="width: 40%; border-top: none; border-bottom: 0px solid black; border-right: none; text-align: left; padding: 0px; padding-left: 10px;">
					<span style="font-size: 11px;">
						<!-- <span>- L'élève ne pourra passer dans la classe supérieure s'il a subi avec succès un examen de 2ème session(I)</span><br/> -->
						<span>- L'élève passe dans la classe supérieure (I)</span><br/>
						<span>- L'élève double la classe (I)</span><br/>
						<span>- L'élève a echoué (I)</span><br/>
					</span><br/>
				</td>
				<td style="border-bottom: 0px solid black; width: 20%; border-top: none; padding-top: 50px; border-left: none; border-right: none; text-align: center; padding: 5px; padding-left: 10px;">
					<span style="font-size: 11px;">
						<span style="font-size: 50px; color: transparent;">i</span><br/>
						<span>SCEAU DE L'ECOLE</span><br/>
					</span><br/>
				</td>
				<td style="width: 40%; border-top: none; border-left: none; border-bottom: 0px solid black; text-align: center; padding: 5px; padding-left: 10px;">
					<span style="font-size: 11px;">
						<br/><span>Fait à </span><strong><?= $school_city ?></strong>, le <strong><?= $date_end ?></strong><br/><br/><br/><br/><br/>
						<span>LE CHEF D'ETABLISSEMENT</span><br/>
						<span><?= $chef ?></span>
					</span><br/><br/><br/>
				</td>
			</tr>
		</table>
		<table style="border: 2px solid black; margin-bottom: 50px; border-collapse: collapse; border-top: none; text-align: center;" class="class_table">
			<tr>
				<td style="border: none; border-top: none; border-right: none; text-align: left; padding: 5px; padding-left: 10px;">
					<span style="font-size: 9px;">
						<strong style="">(I) Buffer la mention inutile <br/> Note impotante : le bulletin est sans valeur s'il est raturé ou surchargé</strong>
					</span><br/>
				</td>
			</tr>
		</table>
	</div><br/><br/>

  <script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
  <script type="text/javascript">
	$(document).ready(() => {
  	});
  </script>
