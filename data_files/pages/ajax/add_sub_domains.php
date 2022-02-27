<?php

     include '../../config/dbconnect.functions.php';

     $domain_name = htmlspecialchars(trim(strip_tags($_POST['domain_name'])));
     $sub_domain_name = htmlspecialchars(trim(strip_tags($_POST['sub_domain_name'])));

     // $voirQuery = "SELECT school_year, COUNT(*) AS count_info FROM courses_domain WHERE school_year=?";
     // $voirRequest = $database_connect->prepare($voirQuery);
     // $voirRequest->execute(array($school_year));
     // $voirResponse = $voirRequest->fetchObject();

     // if($voirResponse->count_info == 0) {

          $insertQuery = "INSERT INTO sub_domains(domain_id, sub_domain_name) VALUES(?, ?)";
          $insertRequest = $database_connect->prepare($insertQuery);
          $insertRequest->execute(array($domain_name, $sub_domain_name));

     // }
     // else {

     //      $updateQuery = "UPDATE courses_domains SET domain_name=? AND school_year=? WHERE school_year=?";
     //      $updateRequest = $database_connect->prepare($updateQuery);
     //      $updateRequest->execute(array($name_promoter, $code_school, $date_end, $school_year, $school_year));

     // }
     
?>
