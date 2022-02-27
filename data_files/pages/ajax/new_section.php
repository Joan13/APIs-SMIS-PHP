<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/home.secretor.functions.php';

	$section_name = htmlspecialchars(strip_tags(trim($_POST['section_name'])));
	insert_section($section_name);

?>