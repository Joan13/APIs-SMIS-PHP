<?php

	require_once("../config/dbconnect.functions.php");

	function count_pupils_exist($cycle_id, $class_id, $order_id, $section_id, $option_id, $school_year)
	{
		global $database_connect;
    	$exist00 = "SELECT pupil_id, COUNT(*) AS count_pupils_exist FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=?";
    	$exist11 = $database_connect->prepare($exist00);
    	$exist11->execute(array($cycle_id, $class_id, $order_id, $section_id, $option_id, $school_year));
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_pupils_exist;

    	return $response;
	}

	function count_cycles_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT cycle_id, cycle_name, COUNT(*) AS count_cycles_exist FROM cycle";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_cycles_exist;

    	return $response;
	}

	function count_classes_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT class_id, class_number, COUNT(*) AS count_classes_exist FROM classes";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_classes_exist;

    	return $response;
	}

	function count_orders_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT order_id, order_name, COUNT(*) AS count_orders_exist FROM class_order";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_orders_exist;

    	return $response;
	}

	function count_sections_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT section_id, section_name, COUNT(*) AS count_sections_exist FROM sections";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_sections_exist;

    	return $response;
	}

	function count_options_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT option_id, option_name, COUNT(*) AS count_options_exist FROM options";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_options_exist;

    	return $response;
	}

	function count_school_years_exist()
	{
		global $database_connect;
    	$exist00 = "SELECT year_id, year_name, COUNT(*) AS count_years_exist FROM school_years";
    	$exist11 = $database_connect->query($exist00);
    	$exist = $exist11->fetchObject();
    	$response = $exist->count_years_exist;

    	return $response;
	}

	function find_cycle_name($cycle_id)
	{
    	if(count_cycles_exist() != 0)
    	{
    		global $database_connect;
	    	$exist00 = "SELECT cycle_id, cycle_name, COUNT(*) AS count_cycles_exist FROM cycle WHERE cycle_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($cycle_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_cycles_exist != 0)
			{
	    		$cycles00 = "SELECT cycle_id, cycle_name FROM cycle WHERE cycle_id=?";
	    		$cycles11 = $database_connect->prepare($cycles00);
	    		$cycles11->execute(array($cycle_id));
	    		$cycless = $cycles11->fetchObject();
	    		$main_cycle = $cycless->cycle_name;

	    		return $main_cycle;
	    	}
    	}
	}

	function find_class_number($class_id)
	{
    	if(count_classes_exist() != 0)
    	{
			global $database_connect;
	    	$exist00 = "SELECT class_id, class_number, COUNT(*) AS count_classes_exist FROM classes WHERE class_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($class_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_classes_exist != 0)
	    	{
	    		$classes00 = "SELECT class_id, class_number FROM classes WHERE class_id=?";
	    		$classes11 = $database_connect->prepare($classes00);
	    		$classes11->execute(array($class_id));
	    		$classess = $classes11->fetchObject();
	    		$main_class = $classess->class_number;

	    		return $main_class;
	    	}
    	}
	}

	function find_order_name($order_id)
	{
		$ordre = "";

		if(count_orders_exist() != 0)
		{
			global $database_connect;
	    	$exist00 = "SELECT order_id, order_name, COUNT(*) AS count_orders_exist FROM class_order WHERE order_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($order_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_orders_exist != 0)
	    	{
				$orders00 = "SELECT order_id, order_name FROM class_order WHERE order_id=?";
				$orders11 = $database_connect->prepare($orders00);
				$orders11->execute(array($order_id));
				$orderss = $orders11->fetchObject();
				$main_order = $orderss->order_name;

				$ordre = " ".$main_order;
			}
		}

		return $ordre;
	}

	function find_section_name($section_id)
	{
		$section = "";

		if(count_sections_exist() != 0)
		{
			global $database_connect;
	    	$exist00 = "SELECT section_id, section_name, COUNT(*) AS count_sections_exist FROM sections WHERE section_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($section_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_sections_exist != 0)
	    	{
				$sections00 = "SELECT section_id, section_name FROM sections WHERE section_id=?";
				$sections11 = $database_connect->prepare($sections00);
				$sections11->execute(array($section_id));
				$sectionss = $sections11->fetchObject();
				$main_section = $sectionss->section_name;

				$section = $main_section;
			}
			else
			{
				$section = "";
			}
		}

		return $section;
	}

	function find_option_name($option_id)
	{
		$option = "";

		if(count_options_exist() != 0)
		{
			global $database_connect;
	    	$exist00 = "SELECT option_id, option_name, COUNT(*) AS count_options_exist FROM options WHERE option_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($option_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_options_exist != 0)
	    	{
				$options00 = "SELECT option_id, option_name FROM options WHERE option_id=?";
				$options11 = $database_connect->prepare($options00);
				$options11->execute(array($option_id));
				$optionss = $options11->fetchObject();
				$main_option = $optionss->option_name;

				$option = $main_option;
			}
			else
			{
				$option = "";
			}
		}

		return $option;
	}

	function find_school_year($year_id)
	{
		if(count_school_years_exist() != 0)
		{
			global $database_connect;
	    	$exist00 = "SELECT year_id, year_name, COUNT(*) AS count_year_exist FROM school_years WHERE year_id=?";
	    	$exist11 = $database_connect->prepare($exist00);
	    	$exist11->execute(array($year_id));
	    	$exist = $exist11->fetchObject();
	    	if($exist->count_year_exist != 0)
	    	{
				$years00 = "SELECT year_id, year_name FROM school_years WHERE year_id=?";
				$years11 = $database_connect->prepare($years00);
				$years11->execute(array($year_id));
				$yearss = $years11->fetchObject();
				$main_year = $yearss->year_name;

				return $main_year;
			}
			else
			{
				return "";
			}
		}
	}

    function generate_month($month)
    {
        if($month == 1)
        {
            $month = "Janvier";
        } 
        else if($month == 2)
        {
            $month = "Février";
        } 
        else if($month == 3)
        {
            $month = "Mars";
        } 
        else if($month == 4)
        {
            $month = "Avril";
        } 
        else if($month == 5)
        {
            $month = "Mai";
        } 
        else if($month == 6)
        {
            $month = "Juin";
        } 
        else if($month == 7)
        {
            $month = "Juillet";
        } 
        else if($month == 8)
        {
            $month = "Août";
        } 
        else if($month == 9)
        {
            $month ="Septembre";
        } 
        else if($month == 10)
        {
            $month = "Octobre";
        } 
        else if($month == 11)
        {
            $month = "Novembre";
        }
        else if($month == 11)
        {
            $month = "Décembre";
        } 
        else
        {
            $month = "";
        }

        return $month;
    }

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
	<link rel="stylesheet" href="main.css" />
	<title>Document</title>
</head>
<body>
<div class="main_middle_container">

	<div id="ficheClasse" style="text-align: center;">

	<table style="width: 100%; border-collapse: collapse;border: none;">
				<tr>
					<td style="padding: 15px;padding-left: 0px; padding-top: 0px; width:200px; border: none;">
					<strong style="font-size: 14px;"><?= mb_strtoupper($school_name) ?></strong><br/>
	<strong style="font-size: 14px;"><?= strtoupper($school_bp) ?></strong>
					</td>
					<td style="padding: 15px;padding-left: 0px; padding-top: 0px;border: none;">
						<strong style="font-size: 14px;"><?= "ANNEE SCOLAIRE : " . mb_strtoupper($school_year) ?></strong><br/>
						<strong style="text-align: center;font-size: 14px;">FICHE DES IDENTITES : <?=$class_identity?></strong>
					</td>
				</tr>
			</table>
	<table style="border-collapse: collapse;min-width:100%;text-align: center;" class="class_table">
		<tr>
			<th style="text-align:left;padding-left: 5px; width: 50px;height: 10px;border: 1px solid black;">No</th>
			<th style="text-align:left;padding-left: 5px; width: 150px;height: 10px;border: 1px solid black;">Nom</th>
			<th style="text-align:left;padding-left: 5px; width: 150px;height: 10px;border: 1px solid black;">Post-nom</th>
			<th style="text-align:left;padding-left: 5px; width: 150px;height: 10px;border: 1px solid black;">Prénom</th>
			<th style="text-align: center;width: 70px;height: 10px;border: 1px solid black;">Sexe</th>
			<th style="text-align:left;padding-left: 5px; width: 100px;height: 10px;border: 1px solid black;">Lieu naiss.</th>
			<th style="text-align:left;padding-left: 5px; width: 150px;height: 10px;border: 1px solid black;">Date naiss.</th>
			<th style="text-align:left;padding-left: 5px; width: 300px;height: 10px;border: 1px solid black;">Num. Identification</th>
			<th style="text-align:left;padding-left: 5px; width: 400px;height: 10px;border: 1px solid black;">Noms père</th>
			<th style="text-align:left;padding-left: 5px; width: 400px;height: 10px;border: 1px solid black;">Noms mère</th>
			<th style="text-align:left;padding-left: 5px; width: 100px;height: 10px;border: 1px solid black;">Nationalité</th>
			<th style="text-align:left;padding-left: 5px; width: 350px;height: 10px;border: 1px solid black;">Adresse domicile</th>

		</tr>
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

				$ttt = $query_fetch->birth_date;
				$tt_year = substr($ttt, 0, 4);
				$tt_month = substr($ttt, 5, 2);
				$tt_day = substr($ttt, 8, 2);
				$birth_date = $tt_day."/".$tt_month."/".$tt_year;

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
					<td style=" text-align: left;padding-left: 5px;height: 10px;font-size: 10px;">
						<span><?=$number?></span>
					</td>
					<td style=" text-align: left;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=strtoupper($query_fetch->first_name)?> <?=($query_fetch->last_name == "" && $query_fetch->last_name == " " && $query_fetch->second_name == "" && $query_fetch->second_name == " " && $query_fetch->first_name != "" && $query_fetch->first_name != " ") ? $statut_scolaire : '';?></span>
					</td>
					<td style=" text-align: left;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=strtoupper($query_fetch->second_name)?> <?=($query_fetch->last_name == "" || $query_fetch->last_name == " " && $query_fetch->second_name != "" && $query_fetch->second_name != " ") ? $statut_scolaire : '';?></span>
					</td>
					<td style=" text-align: left;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=$query_fetch->last_name?> <?=($query_fetch->last_name != "" && $query_fetch->last_name != " ") ? $statut_scolaire : '';?></span>
					</td>
					<td style=" text-align: center;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=$gender_sub?></span>
					</td>
					<td style=" text-align: center;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=$query_fetch->birth_place?></span>
					</td>
					<td style=" text-align: center;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=$birth_date?></span>
					</td>
					<td style=" text-align: center;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=$query_fetch->identification_number?></span>
					</td>
					<td style=" text-align: center;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=$query_fetch->father_names?></span>
					</td>
					<td style=" text-align: center;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=$query_fetch->mother_names?></span>
					</td>
					<td style=" text-align: center;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=$query_fetch->nationality?></span>
					</td>
					<td style=" text-align: center;padding-left: 5px;height: 10px;border: 1px solid black;">
						<span><?=$query_fetch->physical_address?></span>
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

