<?php

    include '../../config/dbconnect.functions.php';

    $id_entry = $_POST['delete_lib'];
    $delete_entry0 = "DELETE FROM libelles WHERE libelle_id=? ";
    $delete_entry = $database_connect->prepare($delete_entry0);
    $delete_entry->execute(array($id_entry));

?>