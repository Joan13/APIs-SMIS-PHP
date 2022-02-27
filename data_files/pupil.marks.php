<?php

	if(session_in() == 0)
	{
		Header('Location: index.php?_=login');
	}

	if (!isset($_GET['cycle']) && !isset($_GET['class_id']) && !isset($_GET['order_id']) && !isset($_GET['section_id']) && !isset($_GET['option_id']) && !isset($_GET['school_year'])) {
		Header("Location: 0.php");
	}

	if(selected_pupil_exists($_GET['pupil_id']) != 0)
	{
		$que000 = "SELECT * FROM pupils_info WHERE pupil_id=?";
		$que110 = $database_connect->prepare($que000);
		$que110->execute(array($_GET['pupil_id']));
		$que0 = $que110->fetchObject();
	}
?>

<input style="display: none;" id="cycle" value="<?=$_GET['cycle'] ?>" />
<input style="display: none;" id="class_id" value="<?=$_GET['class_id'] ?>" />
<input style="display: none;" id="order_id" value="<?=$_GET['order_id'] ?>" />
<input style="display: none;" id="section_id" value="<?=$_GET['section_id'] ?>" />
<input style="display: none;" id="option_id" value="<?=$_GET['option_id'] ?>" />
<input style="display: none;" id="school_year" value="<?=$_GET['school_year'] ?>" />
<input style="display: none;" id="pupil_id" value="<?=$_GET['pupil_id'] ?>" />

<div id="print_marks_div"></div>

<div id="print_pupil_bulletin" class="print_bulletin_button" style="text-align: center;"><h2 id="fiche_des_points_texte">Fiche des points : <?= strtoupper($que0->first_name." ".$que0->second_name)." ".$que0->last_name?> / <u style="cursor: pointer;">Imprimer le bulletin</u></h2></div>

