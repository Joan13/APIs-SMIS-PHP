<?php

    include '../../config/dbconnect.functions.php';

    $depense_id = htmlspecialchars(trim(strip_tags($_POST['depense_id'])));
    $depense_validated = 0;

    $edit0 = "UPDATE depenses SET depense_validated=0 WHERE depense_id="."'$depense_id'";
    $edit = $database_connect->query($edit0);

  ?>
