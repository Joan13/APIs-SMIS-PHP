<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/home.secretor.functions.php';

	$study_cycle = htmlspecialchars(strip_tags(trim($_POST['study_cycle'])));
	insert_cycle($study_cycle);

?>