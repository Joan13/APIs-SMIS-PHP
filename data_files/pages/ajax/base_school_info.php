<?php

     include '../../config/dbconnect.functions.php';

     $name_promoter = htmlspecialchars(trim(strip_tags($_POST['name_promoter'])));
     $code_school = htmlspecialchars(trim(strip_tags($_POST['code_school'])));
     $date_end = htmlspecialchars(trim(strip_tags($_POST['date_end'])));
     $school_year = htmlspecialchars(trim(strip_tags($_POST['school_year_info'])));

     $voirQuery = "SELECT school_year, COUNT(*) AS count_info FROM base_school_info WHERE school_year=?";
     $voirRequest = $database_connect->prepare($voirQuery);
     $voirRequest->execute(array($school_year));
     $voirResponse = $voirRequest->fetchObject();

     if($voirResponse->count_info == 0) {

          $insertQuery = "INSERT INTO base_school_info(name_promoter, code_school, date_end, school_year) VALUES(?, ?, ?, ?)";
          $insertRequest = $database_connect->prepare($insertQuery);
          $insertRequest->execute(array($name_promoter, $code_school, $date_end, $school_year));

     }
     else {

          $updateQuery = "UPDATE base_school_info SET name_promoter=?, code_school=?, date_end=?, school_year=? WHERE school_year=?";
          $updateRequest = $database_connect->prepare($updateQuery);
          $updateRequest->execute(array($name_promoter, $code_school, $date_end, $school_year, $school_year));

     }
     
?>
