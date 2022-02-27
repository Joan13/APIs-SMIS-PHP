<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/home.secretor.functions.php';

	$class_order = htmlspecialchars(strip_tags(trim($_POST['class_order'])));
	insert_class_order($class_order);

?>