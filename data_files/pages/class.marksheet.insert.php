
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
	$course_id = $_GET['course_id'];
	$course_name = find_course_name($course_id);

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

<input style="display: none;" id="cycle" value="<?=$_GET['cycle'] ?>" />
<input style="display: none;" id="class_id" value="<?=$_GET['class_id'] ?>" />
<input style="display: none;" id="order_id" value="<?=$_GET['order_id'] ?>" />
<input style="display: none;" id="section_id" value="<?=$_GET['section_id'] ?>" />
<input style="display: none;" id="option_id" value="<?=$_GET['option_id'] ?>" />
<input style="display: none;" id="school_year" value="<?=$_GET['school_year'] ?>" />
<input style="display: none;" id="course_id" value="<?=$_GET['course_id'] ?>" />

<div class="main_middle_container">
<!-- <button id="consider_disconsider" class="validate_login ooooo" style="width: 100%;">Considérer ce cours sur le bulletin</button> -->
	<div id="ff"></div>
	<h2 style="text-align: center;">Insertion des points : <?=strtoupper($course_name)?> || <?=$class_identity?></h2>


	<div id="form_insert_marks" method="POST">
		<?php
		$years00 = "SELECT year_id, year_name FROM school_years WHERE year_id=?";
		$years11 = $database_connect->prepare($years00);
		$years11->execute(array($sschool));
		$yearss = $years11->fetchObject();
		?>
									
		<button id="year_school" value="<?= $yearss->year_id ?>" style="background-color: transparent; border: none; font-weight: bold; display: inline-block;display:none;"><?= $yearss->year_name ?></button>
		<table style="border-collapse: collapse;margin-left: 15%;width:70%;text-align: center;" class="class_table">
			<tr>
				<th style="text-align:left;padding-left: 5px;">N0</th>
				<th style="text-align:left;padding-left: 5px;">Noms de l'élève</th>
				<th style="text-align:left;padding-left: 5px;padding-right: 5px;">P1</th>
				<th style="text-align:left;padding-left: 5px;padding-right: 5px;">P2</th>
				<th style="text-align:left;padding-left: 5px;padding-right: 5px;">EX1</th>
			</tr>
			<?php

			if(count_pupils_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
			{
				$number = 0;
				$query_fetch00 = "SELECT * FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? ORDER BY first_name ASC, second_name ASC";
				$query_fetch11 = $database_connect->prepare($query_fetch00);
				$query_fetch11->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
				while($query_fetch = $query_fetch11->fetchObject())
				{
					$number += 1;
					$query_fetch0055 = "SELECT cycle_school, class_school, class_order, class_section, class_option, school_year, COUNT(*) AS count_pupils_for_class FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=?";
					$query_fetch1155 = $database_connect->prepare($query_fetch0055);
					$query_fetch1155->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
					$query_fetch55 = $query_fetch1155->fetchObject();

					?>
					<script type="text/javascript">
						
						$(document).ready(function() {

							$('#validate_marks').on('click', function() {

								let yaambi = $('.yaambi').val();
								let year_school = $('#year_school').val();
								let select_periode = "1";
								let date = new Date();
								let mois = parseInt(date.getMonth()) + 1;
								let date_work = date.getDate() + "/" + mois + "/" + date.getFullYear();
								let cycle = $('#cycle').val();
								let class_id = $('#class_id').val();
								let order_id = $('#order_id').val();
								let section_id = $('#section_id').val();
								let option_id = $('#option_id').val();
								let school_year = $('#school_year').val();
								let pupil_id = $('.pupil_id').val();
								let course_id = $('#course_id').val();

								if (yaambi == "" || year_school == "" || select_periode == "" || date_work == "") {
									// $('#information-div-global').fadeIn(400);
								} else {
									$('#progress_bar_div').fadeIn();
									$.post('pages/ajax/insert_pupil_marks.php', { yaambi:yaambi, year_school:year_school, select_periode:select_periode, date_work:date_work, cycle:cycle, class_id:class_id, order_id:order_id, section_id:section_id, option_id:option_id, school_year:school_year, course_id:course_id, pupil_id:pupil_id}, function() {
										$('#success-information-div-global').fadeIn(400);
										$(".<?= 'yambi'.$query_fetch->pupil_id ?>").val('');
										$('#progress_bar_div').fadeOut();
									});
									$(".<?= 'yambi'.$query_fetch->pupil_id ?>").removeClass('yaambi');
									$(".<?= 'yambi'.$query_fetch->pupil_id ?>").removeClass('pupil_id');
								}





								let yaambi2 = $('.yaambi2').val();
								let select_periode2 = "2";

								if (yaambi2 == "" || year_school == "" || select_periode2 == "" || date_work == "") {
									// $('#information-div-global').fadeIn(400);
								} else {
									$('#progress_bar_div').fadeIn();
									$.post('pages/ajax/insert_pupil_marks.php', { yaambi:yaambi2, year_school:year_school, select_periode:select_periode2, date_work:date_work, cycle:cycle, class_id:class_id, order_id:order_id, section_id:section_id, option_id:option_id, school_year:school_year, course_id:course_id, pupil_id:pupil_id}, function() {
										$('#success-information-div-global').fadeIn(400);
										$(".<?= 'yambi2'.$query_fetch->pupil_id ?>").val('');
										$('#progress_bar_div').fadeOut();
									});
									$(".<?= 'yambi2'.$query_fetch->pupil_id ?>").removeClass('yaambi2');
									$(".<?= 'yambi2'.$query_fetch->pupil_id ?>").removeClass('pupil_id');
								}




								let yaambi10 = $('.yaambi10').val();
								let select_periode10 = "10";

								if (yaambi10 == "" || year_school == "" || select_periode10 == "" || date_work == "") {
									// $('#information-div-global').fadeIn(400);
								} else {
									$('#progress_bar_div').fadeIn();
									$.post('pages/ajax/insert_pupil_marks.php', { yaambi:yaambi10, year_school:year_school, select_periode:select_periode10, date_work:date_work, cycle:cycle, class_id:class_id, order_id:order_id, section_id:section_id, option_id:option_id, school_year:school_year, course_id:course_id, pupil_id:pupil_id}, function() {
										$('#success-information-div-global').fadeIn(400);
										$(".<?= 'yambi10'.$query_fetch->pupil_id ?>").val('');
										$('#progress_bar_div').fadeOut();
									});
									$(".<?= 'yambi10'.$query_fetch->pupil_id ?>").removeClass('yaambi10');
									$(".<?= 'yambi10'.$query_fetch->pupil_id ?>").removeClass('pupil_id');
								}

							});
						});

					</script>

					<tr class="okokhover">
					<td style=" text-align: left;padding-left: 5px;">
					<?= $number ?>
					</td>
						<td style=" text-align: left;padding-left: 5px;">
							<input style="display: none;" class="<?= "yambi".$query_fetch->pupil_id ?> pupil_id" value="<?=$query_fetch->pupil_id ?>" />
							<?=strtoupper($query_fetch->first_name)." ".strtoupper($query_fetch->second_name)." ".ucwords($query_fetch->last_name)?>
						</td>
						<td style="text-align: left;width: 50px;">
							<input class="<?= "yambi".$query_fetch->pupil_id ?> yaambi cccl" type="number" style="width: 49px;text-align:center;" />
						</td>
						<td style="text-align: left;width: 50px;">
							<input class="<?= "yambi2".$query_fetch->pupil_id ?> yaambi2 cccl" type="number" style="width: 49px;text-align:center;" />
						</td>
						<td style="text-align: left;width: 50px;">
							<input class="<?= "yambi10".$query_fetch->pupil_id ?> yaambi10 cccl" type="number" style="width: 49px;text-align:center;" />
						</td>
					</tr>
					<?php
				}
			}

			?>

		</table>

		<table style="border-collapse: collapse;margin-left: 15%;width:70%;text-align: center; margin-top: 20px;" class="class_table">
			<tr>
				<td style=" text-align: center;padding-left: 5px; border-color: transparent;">
					<button type="submit" name="validate_marks" id="validate_marks" class="validate_login ooooo" style="width: 100%;">Enregistrer la fiche des points</button>
				</td>
			</tr>
		</table>
	</div><br/><br/><br/><br/>
</div>

<script type="text/javascript">				
	$(document).ready(function() {
		$('#consider_disconsider').on('click', function() {
			let course_id = $('#course_id').val();

			$.post('pages/ajax/edit_course.php', {course_id:course_id}, function() {
				$('#success-information-div-global').fadeIn(400);
			});
		});
	});
</script>
