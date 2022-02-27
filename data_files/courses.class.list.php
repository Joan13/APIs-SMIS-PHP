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
	<h2 style="text-align: center;">Liste des cours : <?=$class_identity?></h2>

	<table style="border-collapse: collapse;margin-left: 15%;width:70%;text-align: center;" class="class_table">
		<tr>
			<th style="text-align:left;padding-left: 5px;">Intitule du cours</th>
			<th style="text-align:left;padding-left: 5px;">MAXIMUM</th>
		</tr>
		<?php

			if(count_courses_exist($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
			{
				$query_fetch00 = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY total_marks ASC";
				$query_fetch11 = $database_connect->prepare($query_fetch00);
				$query_fetch11->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
				while($query_fetch = $query_fetch11->fetchObject())
				{
					if(session_in() == 5)
					{
						$worker_id = session_worker();

						$worker_query = "SELECT * FROM attribution_teachers WHERE worker_id='$worker_id'";
						$worker_request = $database_connect->query($worker_query);
						while($worker_response = $worker_request->fetchObject()) {

						if ($worker_response->course_id == $query_fetch->course_id) {

							?>
							<tr>
								<td style=" text-align: left;padding-left: 5px;">
									<a href="./?_=class.marksheet.insert&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&course_id=<?=$query_fetch->course_id?>">
										<?= ($query_fetch->course_name) ?>
									</a>
								</td>
								<td style=" text-align: left;padding-left: 5px;"><?=($query_fetch->total_marks)?></td>
							</tr>
							<?php
						}}
					} 
					else {
						?>
						<tr>
							<td style=" text-align: left;padding-left: 5px;">
								<a href="./?_=class.marksheet.insert&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&course_id=<?=$query_fetch->course_id?>">
									<?=($query_fetch->course_name)?>
								</a>
							</td>
							<td style=" text-align: left;padding-left: 5px;"><?=($query_fetch->total_marks)?></td>
						</tr>
						<?php
					}
				}
			}
		?>
	</table>










<h2 style="text-align: center;">Entrez les points par période : <?=$class_identity?></h2>	
<table style="border-collapse: collapse;margin-left: 15%;width:70%;text-align: center;" class="class_table">
		<tr>
			<th style="text-align:left;padding-left: 5px;">Intitule du cours</th>
			<th style="text-align:left;padding-left: 5px;">MAXIMUM</th>
		</tr>
		<?php

			if(count_courses_exist($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
			{
				$query_fetch00 = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY total_marks ASC";
				$query_fetch11 = $database_connect->prepare($query_fetch00);
				$query_fetch11->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
				while($query_fetch = $query_fetch11->fetchObject())
				{
					if(session_in() == 5)
					{
						$worker_id = session_worker();

						$worker_query = "SELECT * FROM attribution_teachers WHERE worker_id='$worker_id'";
						$worker_request = $database_connect->query($worker_query);
						while($worker_response = $worker_request->fetchObject()) {

						if ($worker_response->course_id == $query_fetch->course_id) {

							?>
							<tr>
								<td style=" text-align: left;padding-left: 5px;">
									<a href="./?_=class.marksheet.insert2&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&course_id=<?=$query_fetch->course_id?>">
										<?= ($query_fetch->course_name) ?>
									</a>
								</td>
								<td style=" text-align: left;padding-left: 5px;"><?=($query_fetch->total_marks)?></td>
							</tr>
							<?php
						}}
					} 
					else {
						?>
						<tr>
							<td style=" text-align: left;padding-left: 5px;">
								<a href="./?_=class.marksheet.insert2&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&course_id=<?=$query_fetch->course_id?>">
									<?=($query_fetch->course_name)?>
								</a>
							</td>
							<td style=" text-align: left;padding-left: 5px;"><?=($query_fetch->total_marks)?></td>
						</tr>
						<?php
					}
				}
			}
		?>
	</table>
</div>
