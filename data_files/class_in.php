<?php

	require_once("../config/dbconnect.functions.php");
	include("../config/class_in.functions.php");

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

	$number = 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css" />
	<title>Document</title>
</head>
<body>
	<div class="main_middle_container">
		<div id="ficheClasse" style="text-align: center;">
		<table style="border-collapse: collapse;width:100%;text-align: center;" class="class_table">
			
			<caption style="font-weight: bold;">
			<table style="width: 100%; border-collapse: collapse;border: none;">
				<tr>
					<td style="padding: 15px;padding-left: 0px; padding-top: 0px; width:200px; border: none;">
						<span><?= mb_strtoupper($school_name) ?></span><br/>
						<span><?= mb_strtoupper($school_bp) ?></span>
					</td>
					<td style="padding: 15px;padding-left: 0px; padding-top: 0px;border: none;">
						<span><?= "ANNEE SCOLAIRE : " . mb_strtoupper($school_year) ?></span><br/>
						<span style="text-align: center;">LISTE NOMMINATIVE : <?= mb_strtoupper($class_identity)?></span>
					</td>
				</tr>
			</table>
			</caption>
			<thead>
				<tr>
					<th style="text-align:left;padding-left: 5px; width: 50px;">No</th>
					<th style="text-align:left;padding-left: 5px;">Nom</th>
					<th style="text-align:left;padding-left: 5px;">Post-nom</th>
					<th style="text-align:left;padding-left: 5px;">Prénom</th>
					<th style="text-align: center;width: 70px;">Sexe</th>
				</tr>
			</thead>
			<?php

			if(count_pupils_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
			{
				$query_fetch00 = "SELECT * FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? ORDER BY first_name ASC";
				$query_fetch11 = $database_connect->prepare($query_fetch00);
				$query_fetch11->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
				while($query_fetch = $query_fetch11->fetchObject())
				{
					if($query_fetch->gender == 1) {
						$gender_sub = "M";
					} else if ($query_fetch->gender == 0) {
						$gender_sub = "F";
					} else {
						$gender_sub = "";
					}

					$number += 1;

					if ($query_fetch->statut_scolaire == "0") {
						$statut_scolaire = "";
					}
					else if ($query_fetch->statut_scolaire == 1) {
						$statut_scolaire = "(D)";
					}
					else if ($query_fetch->statut_scolaire == 2) {
						$statut_scolaire = "(N)";
					}
					else if ($query_fetch->statut_scolaire == 3) {
						$statut_scolaire = "(ND)";
					} 
					else {
						$statut_scolaire = "";
					}

					?>
					<tr>
						<td style=" text-align: left;padding-left: 5px; width: 10px;">
							<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?=$query_fetch->pupil_id?>"><?=$number?></a>
						</td>
						<td style=" text-align: left;padding-left: 5px;">
							<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?=$query_fetch->pupil_id?>"><?=strtoupper($query_fetch->first_name)?> <?=($query_fetch->last_name == "" && $query_fetch->last_name == " " && $query_fetch->second_name == "" && $query_fetch->second_name == " " && $query_fetch->first_name != "" && $query_fetch->first_name != " ") ? $statut_scolaire : '';?></a>
						</td>
						<td style=" text-align: left;padding-left: 5px;">
							<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?=$query_fetch->pupil_id?>"><?=strtoupper($query_fetch->second_name)?> <?=($query_fetch->last_name == "" || $query_fetch->last_name == " " && $query_fetch->second_name != "" && $query_fetch->second_name != " ") ? $statut_scolaire : '';?></a>
						</td>
						<td style=" text-align: left;padding-left: 5px;">
							<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?=$query_fetch->pupil_id?>"><?=$query_fetch->last_name?> <?=($query_fetch->last_name != "" && $query_fetch->last_name != " ") ? $statut_scolaire : '';?></a>
						</td>
						<td style=" text-align: center;padding-left: 5px;">
							<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?=$query_fetch->pupil_id?>"><?=$gender_sub?></a>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</table></div><br/><br/><br/><br/>
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
</html>