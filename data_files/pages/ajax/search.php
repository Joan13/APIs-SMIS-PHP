<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/home.livre_caisse.functions.php';

	$first_name = htmlspecialchars(trim(strip_tags($_POST['first_name'])));
	$second_name = htmlspecialchars(trim(strip_tags($_POST['second_name'])));
	$yearr = $_POST['school_year_search'];

	$ssl0 = "SELECT * FROM pupils_info WHERE first_name=? AND second_name=? AND school_year=?";
	$ssl1 = $database_connect->prepare($ssl0);
	$ssl1->execute(array($first_name, $second_name, $yearr));

	function find_cycle_name($cycle_id) {
		if(count_cycles_exist() != 0) {
			global $database_connect;
			$exist00 = "SELECT cycle_id, cycle_name, COUNT(*) AS count_cycles_exist FROM cycle WHERE cycle_id=?";
			$exist11 = $database_connect->prepare($exist00);
			$exist11->execute(array($cycle_id));
			$exist = $exist11->fetchObject();
			if($exist->count_cycles_exist != 0) {
				$cycles00 = "SELECT cycle_id, cycle_name FROM cycle WHERE cycle_id=?";
				$cycles11 = $database_connect->prepare($cycles00);
				$cycles11->execute(array($cycle_id));
				$cycless = $cycles11->fetchObject();
				$main_cycle = $cycless->cycle_name;

				return $main_cycle;
			}
		}
	}

	function find_class_number($class_id) {
		if(count_classes_exist() != 0) {
			global $database_connect;
			$exist00 = "SELECT class_id, class_number, COUNT(*) AS count_classes_exist FROM classes WHERE class_id=?";
			$exist11 = $database_connect->prepare($exist00);
			$exist11->execute(array($class_id));
			$exist = $exist11->fetchObject();
			if($exist->count_classes_exist != 0) {
				$classes00 = "SELECT class_id, class_number FROM classes WHERE class_id=?";
				$classes11 = $database_connect->prepare($classes00);
				$classes11->execute(array($class_id));
				$classess = $classes11->fetchObject();
				$main_class = $classess->class_number;

				return $main_class;
			}
		}
	}

	function find_order_name($order_id) {
		$ordre = "";

		if(count_orders_exist() != 0) {
			global $database_connect;
			$exist00 = "SELECT order_id, order_name, COUNT(*) AS count_orders_exist FROM class_order WHERE order_id=?";
			$exist11 = $database_connect->prepare($exist00);
			$exist11->execute(array($order_id));
			$exist = $exist11->fetchObject();
			if($exist->count_orders_exist != 0) {
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

	function find_section_name($section_id) {
		$section = "";

		if(count_sections_exist() != 0) {
			global $database_connect;
			$exist00 = "SELECT section_id, section_name, COUNT(*) AS count_sections_exist FROM sections WHERE section_id=?";
			$exist11 = $database_connect->prepare($exist00);
			$exist11->execute(array($section_id));
			$exist = $exist11->fetchObject();
			if($exist->count_sections_exist != 0) {
				$sections00 = "SELECT section_id, section_name FROM sections WHERE section_id=?";
				$sections11 = $database_connect->prepare($sections00);
				$sections11->execute(array($section_id));
				$sectionss = $sections11->fetchObject();
				$main_section = $sectionss->section_name;

				$section = $main_section;
			}
			else {
				$section = "";
			}
		}

		return $section;
	}

	function find_option_name($option_id) {
		$option = "";

		if(count_options_exist() != 0) {
			global $database_connect;
			$exist00 = "SELECT option_id, option_name, COUNT(*) AS count_options_exist FROM options WHERE option_id=?";
			$exist11 = $database_connect->prepare($exist00);
			$exist11->execute(array($option_id));
			$exist = $exist11->fetchObject();
			if($exist->count_options_exist != 0) {
				$options00 = "SELECT option_id, option_name FROM options WHERE option_id=?";
				$options11 = $database_connect->prepare($options00);
				$options11->execute(array($option_id));
				$optionss = $options11->fetchObject();
				$main_option = $optionss->option_name;

				$option = $main_option;
			}
			else {
				$option = "";
			}
		}

		return $option;
	}

	function find_school_year($year_id) {
		if(count_school_years_exist() != 0) {
			global $database_connect;
			$exist00 = "SELECT year_id, year_name, COUNT(*) AS count_year_exist FROM school_years WHERE year_id=?";
			$exist11 = $database_connect->prepare($exist00);
			$exist11->execute(array($year_id));
			$exist = $exist11->fetchObject();
			if($exist->count_year_exist != 0) {
				$years00 = "SELECT year_id, year_name FROM school_years WHERE year_id=?";
				$years11 = $database_connect->prepare($years00);
				$years11->execute(array($year_id));
				$yearss = $years11->fetchObject();
				$main_year = $yearss->year_name;

				return $main_year;
			}
			else {
				return "";
			}
		}
	}

	?>
	<style type="text/css">
		.table_search
		{
			color: black;
		    width: 100%;
		    border-color: white;
		    border-collapse: collapse;
		}
		.table_search td, th
		{
			text-align: left;
			padding-left: 15px;
			height: 40px;
			border-color: rgba(0, 0, 0, 0.2);
		}
		th
		{
			color: black;
		}
	</style><br/>
	<button id="yearr" style="display:none;" value="<?=$yearr?>"></button>
	<div style="text-align: center;" class="logo_right_sub_container">
		<img class="close_div_search" src="images/other/close_2.png" width="35" height="35" /><br/><br/><br/><br/><br/><br/><br/>
		<img class="logo_login" style="border-radius:50%;border: 1px solid rgba(255, 255, 255, 0.5);" src=" <?php if ($school_name == "Collège Alfajiri") {echo "images/other/Alfajiri.jpeg";} else if($school_name == "Collège Saint-Paul") {echo "images/other/Saint_Paul.jpeg";} else if($school_name == "Complexe Scolaire \"Elite\"") {echo "images/other/logo.png";} else {echo "images/other/user-3.png";} ?>" width="140" height="180" />
	</div><br/>
	<div>
		<table class="table_search">
			<caption><b>RESULTATS DE LA RECHERCHE</b></caption>
			<tr>
				<th>Nom</th>
				<th>Post-nom</th>
				<th>Prénom</th>
				<th>Sexe</th>
				<th>Classe</th>
				<th style="text-align: center">^</th>
			</tr>
			<?php
			while($ssl = $ssl1->fetchObject()) {
				$gender = $ssl->gender;
				if($gender == 1) {
					$gender = "M";
				}
				else {
					$gender = "F";
				}

				$cycle_name = find_cycle_name($ssl->cycle_school);
				$class_number = find_class_number($ssl->class_school);
				$true_class_number = find_class_number($ssl->class_school);
				$order_name = find_order_name($ssl->class_order);
				$section_name = find_section_name($ssl->class_section);
				$option_name = find_option_name($ssl->class_option);
				$school_year = find_school_year($ssl->school_year);
			
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
					if($true_class_number >= 3)
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
				if (session_in() == 3 || session_in() == 1 || session_in() == 5) {
					?>
				<tr>
					<td><?= strtoupper($ssl->first_name) ?></td>
					<td><?= strtoupper($ssl->second_name) ?></td>
					<td><?= $ssl->last_name ?></td>
					<td><?= $gender ?></td>
					<td><?= $class_identity ?></td>
					<td style="background-color:white;padding: 0px;">
						<button class="open_found_pupil" style="color:black;background-color:rgb(25, 119, 255);border:1px solid white;font-weight: bold;cursor:pointer;height: inherit;width: 100%;" value="<?=$ssl->pupil_id?>">Ouvrir</button>
						<button style="display: none;" id="pupil_pupil" value="<?= $ssl->pupil_id ?>"></button>
					</td>
				</tr>
				<?php
				} else if(session_in() == 2) {
					?>
				<tr>
					<td><?= strtoupper($ssl->first_name) ?></td>
					<td><?= strtoupper($ssl->second_name) ?></td>
					<td><?= $ssl->last_name ?></td>
					<td><?= $gender ?></td>
					<td><?= $class_identity ?></td>
					<td style="background-color:white;padding: 0px;">
						<button class="open_found_pupil_discipline" style="color:black;background-color:rgb(25, 119, 255);border:1px solid white;font-weight: bold;cursor:pointer;height: inherit;width: 100%;" value="<?=$ssl->pupil_id?>">Ouvrir</button>
						<button style="display: none;" id="pupil_pupil" value="<?= $ssl->pupil_id ?>"></button>
					</td>
				</tr>
				<?php
				} else {
					?>
				<tr>
					<td><?= strtoupper($ssl->first_name) ?></td>
					<td><?= strtoupper($ssl->second_name) ?></td>
					<td><?= $ssl->last_name ?></td>
					<td><?= $gender ?></td>
					<td><?= $class_identity ?></td>
					<td style="background-color:white;padding: 0px;">
						<button class="open_found_pupil" style="color:black;background-color:rgb(25, 119, 255);border:1px solid white;font-weight: bold;cursor:pointer;height: inherit;width: 100%;" value="<?=$ssl->pupil_id?>">Ouvrir</button>
						<button style="display: none;" id="pupil_pupil" value="<?= $ssl->pupil_id ?>"></button>
					</td>
				</tr>
				<?php
				}
			}
			?>
		</table>
	</div>
	<script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {

		$('.close_div_search').on('click', function() {
			$('.main_div_search_main').fadeOut(400);
		});

		$('.open_found_pupil').on('click', function() {
			let pupil_id = $(this).val();
			let yearr = $('#yearr').val();
			let pupil_pupil = $('#pupil_pupil').val();

			if (pupil_id != '' || yearr != '')
			{
				$.post('pages/ajax/view_pupil_caisse.php', {pupil_id:pupil_id, pupil_pupil:pupil_pupil, yearr:yearr}, function(data) {
					$('.main_div_view_caisse').html(data);
					$('#view_view_caisse').fadeIn(400);
				});
			}
		});

		$('.open_found_pupil_discipline').on('click', function() {
			let pupil_id = $(this).val();
			let yearr = $('#yearr').val();
			let pupil_pupil = $('#pupil_pupil').val();

			if (pupil_id != '' || yearr != '')
			{
				$.post('pages/ajax/pupil_discipline.php', {pupil_id:pupil_id, pupil_pupil:pupil_pupil, yearr:yearr}, function(data) {
					$('.main_div_view_caisse').html(data);
					$('#view_view_caisse').fadeIn(400);
				});
			}
		});
	});
	</script>
