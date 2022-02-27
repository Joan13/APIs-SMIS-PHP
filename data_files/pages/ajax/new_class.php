<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/home.secretor.functions.php';

	$class_number = htmlspecialchars(strip_tags(trim($_POST['class_number'])));
	insert_class($class_number);

?>