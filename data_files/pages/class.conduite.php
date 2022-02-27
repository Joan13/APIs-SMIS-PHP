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
	$toUpper_class_name = strtoupper($cycle_name);
	$periode_periode = $_GET['periode'];

	if ($periode_periode == 1) {
		$perper = "ère";
	} else {
		$perper = "ème";
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
	<h2 style="text-align: center;">Fiche de conduite : <?=$_GET['periode']." ".$perper?> période<br/><?=$class_identity?></h2>

	<?php
	if(session_in() == 5)
	{
		?><?php
	}
	?>

	<table style="border-collapse: collapse;margin-left: 15%;width:70%;text-align: center;" class="class_table">
		<tr>
			<th style="text-align:left;padding-left: 5px;">Nom</th>
			<th style="text-align:left;padding-left: 5px;">Post-nom</th>
			<th style="text-align:left;padding-left: 5px;">Prénom</th>
			<th>Sexe</th>
			<th>Mention</th>
		</tr>
		<?php

		if(count_pupils_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
		{
			$query_fetch00 = "SELECT * FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? ORDER BY first_name ASC";
			$query_fetch11 = $database_connect->prepare($query_fetch00);
			$query_fetch11->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
			while($query_fetch = $query_fetch11->fetchObject())
			{
				if($query_fetch->gender == 1)
				{
					$gender_sub = "M";
				}
				else
				{
					$gender_sub = "F";
				}

				?>
				<tr>
					<td style=" text-align: left;padding-left: 5px;">
						<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?=$query_fetch->pupil_id?>"><?=strtoupper($query_fetch->first_name)?></a>
					</td>
					<td style=" text-align: left;padding-left: 5px;">
						<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?=$query_fetch->pupil_id?>"><?=strtoupper($query_fetch->second_name)?></a>
					</td>
					<td style=" text-align: left;padding-left: 5px;">
						<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?=$query_fetch->pupil_id?>"><?=$query_fetch->last_name?></a>
					</td>
					<td style=" text-align: center;padding-left: 5px;">
						<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?=$query_fetch->pupil_id?>"><?=$gender_sub?></a>
					</td>
					<td>
						<?php

						if (count_conduite_already_exist($query_fetch->pupil_id, $_GET['periode'], $_GET['school_year']) == 1) {
							$eee000 = "SELECT pupil_id, periode, main_conduite, school_year FROM conduite WHERE pupil_id=? AND periode=? AND school_year=?";
							$eee111 = $database_connect->prepare($eee000);
							$eee111->execute(array($query_fetch->pupil_id, $_GET['periode'], $_GET['school_year']));
							$eer = $eee111->fetchObject();

							$conduitec = $eer->main_conduite;
							if ($conduitec == 1) {
								$conduitec = "Excellent";
							} else if ($conduitec == 2) {
								$conduitec = "Tres bien";
							} else if ($conduitec == 3) {
								$conduitec = "Bien";
							} else if ($conduitec == 4) {
								$conduitec = "Assez bien";
							} else if ($conduitec == 5) {
								$conduitec = "Mediocre";
							} else {
								$conduitec = "Mauvais";
							}
							
							echo $conduitec;
						}
						else
						{
							echo "-";
						}

						?>
					</td>
				</tr>
				<?php
			}
		}
		?>
	</table>
</div>