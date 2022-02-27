<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/home.secretor.functions.php';

	$school_year = htmlspecialchars(strip_tags(trim($_POST['school_year'])));
	insert_school_year($school_year);

?>