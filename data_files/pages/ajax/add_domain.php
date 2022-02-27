<?php

     include '../../config/dbconnect.functions.php';

     $domain_name = htmlspecialchars(trim(strip_tags($_POST['domain_name_domain'])));
     $school_year = htmlspecialchars(trim(strip_tags($_POST['school_year_domain'])));

     // $voirQuery = "SELECT school_year, COUNT(*) AS count_info FROM courses_domain WHERE school_year=?";
     // $voirRequest = $database_connect->prepare($voirQuery);
     // $voirRequest->execute(array($school_year));
     // $voirResponse = $voirRequest->fetchObject();

     // if($voirResponse->count_info == 0) {

          $insertQuery = "INSERT INTO courses_domains(domain_name, school_year) VALUES(?, ?)";
          $insertRequest = $database_connect->prepare($insertQuery);
          $insertRequest->execute(array($domain_name, $school_year));

     // }
     // else {

     //      $updateQuery = "UPDATE courses_domains SET domain_name=? AND school_year=? WHERE school_year=?";
     //      $updateRequest = $database_connect->prepare($updateQuery);
     //      $updateRequest->execute(array($name_promoter, $code_school, $date_end, $school_year, $school_year));

     // }
     
?>
