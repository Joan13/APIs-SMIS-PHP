<?php

    include '../../config/dbconnect.functions.php';

    $frais_divers_id = htmlspecialchars(trim(strip_tags($_POST['frais_divers_id'])));

    $edit0 = "UPDATE frais_divers SET deleted=? WHERE frais_divers_id=?";
    $edit = $database_connect->prepare($edit0);
    $edit->execute(array(1, $frais_divers_id));

  ?>
