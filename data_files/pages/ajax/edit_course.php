<?php

    include '../../config/dbconnect.functions.php';

    $course_id = htmlspecialchars(trim(strip_tags($_POST['course_id'])));

    $select = "SELECT * FROM courses WHERE course_id=?";
    $select_request = $database_connect->prepare($select);
    $select_request->execute(array($course_id));
    $select_response = $select_request->fetchObject();

    $considered = "0";
    if ($select_response->considered == "0") {
      $considered = "1";
    } else {
      $considered = "0";
    }

    $edit0 = "UPDATE courses SET considered=? WHERE course_id=?";
    $edit = $database_connect->prepare($edit0);
    $edit->execute(array($considered, $course_id));

?>
