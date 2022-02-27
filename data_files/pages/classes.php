<?php

	if(session_in() == 0)
	{
		Header('Location: ./?_=login');
	}

	$annee = $_GET['year'];
	$year_name = find_school_year($annee);

	?>
	<style>
		tr:hover {
			color: black;
			background-color: rgb(107, 168, 255);
		}
	</style>
	<?php

	if(session_in() == 4)
	{
		?>

		<div class="main_middle_container">
			<h2 style="text-align: center;">Liste des classes : année scolaire <?=$year_name?></h2>
			<table style="border-collapse: collapse;margin-left: 15%;width:70%;text-align: center;" class="class_table">
				<tr>
					<th>Cycle</th>
					<th>Classe</th>
					<th>Ordre</th>
					<th>Section</th>
					<th>Option</th>
					<th>^</th>
				</tr>
				<?php

				if(count_classes_completed_exist() != 0)
				{
					$query_fetch00 = "SELECT id_classes, cycle_id, class_id, order_id, section_id, option_id, school_year, classes_alignment FROM classes_completed WHERE school_year=? ORDER BY classes_alignment";
					$query_fetch11 = $database_connect->prepare($query_fetch00);
					$query_fetch11->execute(array($annee));
					while($query_fetch = $query_fetch11->fetchObject())
					{
						$cycle_name = find_cycle_name($query_fetch->cycle_id);
						$class_number = find_class_number($query_fetch->class_id);
						$order_name = find_order_name($query_fetch->order_id);
						$section_name = find_section_name($query_fetch->section_id);
						$option_name = find_option_name($query_fetch->option_id);

						if($class_number == 1)
						{
							$class_number = "1 ère";
						}
						else
						{
							$class_number = "$class_number ème";
						}

						?>
						<tr>
							<td><?=$cycle_name?></td>
							<td><?=$class_number?></td>
							<td><?=$order_name?></td>
							<td><?=$section_name?></td>
							<td><?=$option_name?></td>
							<td style="background-color: #021c42;border-bottom: 1px solid white;"><a href="./?_=class_in&cycle=<?=$query_fetch->cycle_id?>&class_id=<?=$query_fetch->class_id?>&order_id=<?=$query_fetch->order_id?>&section_id=<?=$query_fetch->section_id?>&option_id=<?=$query_fetch->option_id?>&school_year=<?=$annee?>"><button class="open_button">Ouvrir</button></a></td>
						</tr>
						<?php
					}
				}

				?>
			</table>
		</div><br/><br/><br/>
		<?php
	}

	else
	{
		?>

		<div class="main_middle_container">
			<h2 style="text-align: center;">Liste des classes : année scolaire <?=$year_name?></h2>
			<table style="border-collapse: collapse;margin-left: 15%;width:70%;text-align: center;" class="class_table">
				<tr>
					<th>Cycle</th>
					<th>Classe</th>
					<th>Ordre</th>
					<th>Section</th>
					<th>Option</th>
					<th>^</th>
				</tr>
				<?php

				if(count_classes_completed_exist() != 0)
				{	
					if(session_in() == 5)
					{
						$worker_id = session_worker();

						$worker_query = "SELECT * FROM workers_info WHERE worker_id='$worker_id'";
						$worker_request = $database_connect->query($worker_query);
						$worker_response = $worker_request->fetchObject();
						
						$query_fetch00 = "SELECT id_classes, cycle_id, class_id, order_id, section_id, option_id, school_year, classes_alignment FROM classes_completed WHERE school_year=? ORDER BY classes_alignment";
						$query_fetch11 = $database_connect->prepare($query_fetch00);
						$query_fetch11->execute(array($annee));
						while($query_fetch = $query_fetch11->fetchObject())
						{
							$cycle_name = find_cycle_name($query_fetch->cycle_id);
							$class_number = find_class_number($query_fetch->class_id);
							$order_name = find_order_name($query_fetch->order_id);
							$section_name = find_section_name($query_fetch->section_id);
							$option_name = find_option_name($query_fetch->option_id);

							if($class_number == 1)
							{
								$class_number = "1 ère";
							}
							else
							{
								$class_number = "$class_number ème";
							}

							?>
							<tr class="trhover">
								<td><?=$cycle_name?></td>
								<td><?=$class_number?></td>
								<td><?=$order_name?></td>
								<td><?=$section_name?></td>
								<td><?=$option_name?></td>
								<?php
									if (session_in() == 6 || session_in() == 3 || session_in() == 1) {
										?><td style="background-color: #021c42;border-bottom: 1px solid white;"><a href="./?_=class.caisse&cycle=<?=$query_fetch->cycle_id?>&class_id=<?=$query_fetch->class_id?>&order_id=<?=$query_fetch->order_id?>&section_id=<?=$query_fetch->section_id?>&option_id=<?=$query_fetch->option_id?>&school_year=<?=$annee?>">
											<button class="open_button">Ouvrir</button></a></td><?php
									}
									else {
										?><td style="background-color: #021c42;border-bottom: 1px solid white;"><a href="./?_=class_in&cycle=<?=$query_fetch->cycle_id?>&class_id=<?=$query_fetch->class_id?>&order_id=<?=$query_fetch->order_id?>&section_id=<?=$query_fetch->section_id?>&option_id=<?=$query_fetch->option_id?>&school_year=<?=$annee?>">
											<button class="open_button">Ouvrir</button></a></td><?php
									}
								?>
							</tr>
							<?php
						}
					} else {
						$query_fetch00 = "SELECT id_classes, cycle_id, class_id, order_id, section_id, option_id, school_year, classes_alignment FROM classes_completed WHERE school_year=? ORDER BY classes_alignment";
						$query_fetch11 = $database_connect->prepare($query_fetch00);
						$query_fetch11->execute(array($annee));
						while($query_fetch = $query_fetch11->fetchObject())
						{
							$cycle_name = find_cycle_name($query_fetch->cycle_id);
							$class_number = find_class_number($query_fetch->class_id);
							$order_name = find_order_name($query_fetch->order_id);
							$section_name = find_section_name($query_fetch->section_id);
							$option_name = find_option_name($query_fetch->option_id);

							if($class_number == 1)
							{
								$class_number = "1 ère";
							}
							else
							{
								$class_number = "$class_number ème";
							}

							?>
							<tr class="trhover">
								<td><?=$cycle_name?></td>
								<td><?=$class_number?></td>
								<td><?=$order_name?></td>
								<td><?=$section_name?></td>
								<td><?=$option_name?></td>
								<?php
									if (session_in() == 6 || session_in() == 3 || session_in() == 1) {
										?><td style="background-color: #021c42;border-bottom: 1px solid white;"><a href="./?_=class.caisse&cycle=<?=$query_fetch->cycle_id?>&class_id=<?=$query_fetch->class_id?>&order_id=<?=$query_fetch->order_id?>&section_id=<?=$query_fetch->section_id?>&option_id=<?=$query_fetch->option_id?>&school_year=<?=$annee?>">
											<button class="open_button">Ouvrir</button></a></td><?php
									}
									else {
										?><td style="background-color: #021c42;border-bottom: 1px solid white;"><a href="./?_=class_in&cycle=<?=$query_fetch->cycle_id?>&class_id=<?=$query_fetch->class_id?>&order_id=<?=$query_fetch->order_id?>&section_id=<?=$query_fetch->section_id?>&option_id=<?=$query_fetch->option_id?>&school_year=<?=$annee?>">
											<button class="open_button">Ouvrir</button></a></td><?php
									}
								?>
							</tr>
							<?php
						}
					}
				}

				?>
			</table>
		</div><br/><br/><br/>
		<?php
	}

?>

<br/><br/><br/>
