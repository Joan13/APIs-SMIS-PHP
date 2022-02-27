<?php

    include '../../config/dbconnect.functions.php';

    $input_observation = htmlspecialchars(trim(strip_tags($_POST['input_observation'])));
	$date = htmlspecialchars(trim(strip_tags($_POST['date_observation'])));
	$mention = htmlspecialchars(trim(strip_tags($_POST['mention'])));
    $school_year = htmlspecialchars(trim(strip_tags($_POST['school_year'])));
    $pupil_id = htmlspecialchars(trim(strip_tags($_POST['pupil_id'])));

    $query = "INSERT INTO observations(pupil_id, main_observation, mention, date_observation, school_year, deleted) 
    VALUES(?, ?, ?, ?, ?, ?)";
    $request = $database_connect->prepare($query);
    $request->execute(array($pupil_id, $input_observation, $mention, $date, $school_year, 0));

?>