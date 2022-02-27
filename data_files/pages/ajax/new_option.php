<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/home.secretor.functions.php';

	$option_name = htmlspecialchars(strip_tags(trim($_POST['option_name'])));
	insert_option($option_name);

?>