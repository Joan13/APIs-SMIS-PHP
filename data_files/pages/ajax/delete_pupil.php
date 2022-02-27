<?php

    include '../../config/dbconnect.functions.php';

    $pupil_id = htmlspecialchars(trim(strip_tags($_POST['pupil_pupil'])));

    $deletePupilPaiementsQuery = "DELETE FROM paiements WHERE pupil_id=?";
    $deletePupilPaiementsRequest = $database_connect->prepare($deletePupilPaiementsQuery);
    $deletePupilPaiementsRequest->execute(array($pupil_id));

    $deletePupilMarksQuery = "DELETE FROM marks_info WHERE pupil=?";
    $deletePupilMarksRequest = $database_connect->prepare($deletePupilMarksQuery);
    $deletePupilMarksRequest->execute(array($pupil_id));

    $deletePupilMainQuery = "DELETE FROM pupils_info WHERE pupil_id=?";
    $deletePupilMainRequest = $database_connect->prepare($deletePupilMainQuery);
    $deletePupilMainRequest->execute(array($pupil_id));

?>
