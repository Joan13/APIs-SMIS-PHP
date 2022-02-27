<?php

    include '../../config/dbconnect.functions.php';

    $worker_id = htmlspecialchars(trim(strip_tags($_POST['worker_id'])));
    $course_id = htmlspecialchars(trim(strip_tags($_POST['course_id'])));

    $query = "SELECT worker_id, course_id, COUNT(*) AS count_attribution FROM attribution_teachers WHERE worker_id='$worker_id' AND course_id='$course_id'";
    $request = $database_connect->query($query);
    $response = $request->fetchObject();

    if ($response->count_attribution == 0) {
      $insert00 = "INSERT INTO attribution_teachers(worker_id, course_id) VALUES(?, ?)";
      $insert = $database_connect->prepare($insert00);
      $insert->execute(array($worker_id, $course_id));

      echo "";
    } else {
      $qq = "DELETE FROM attribution_teachers WHERE course_id='$course_id' AND worker_id='$worker_id'";
			$reqq = $database_connect->query($qq);

      echo "Attribution effacee avec succes";
    }

  ?>
