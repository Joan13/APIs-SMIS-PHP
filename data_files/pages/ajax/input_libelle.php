<?php

    include '../../config/dbconnect.functions.php';

    $input = htmlspecialchars(trim(strip_tags($_POST['input_du_libelle'])));
    $gender_libelle = $_POST['gender_libelle'];

    if($input != "")
    {
        $insert_libelle0 = "INSERT INTO libelles(description_libelle, gender_libelle) VALUES(?, ?)";
        $insert_libelle = $database_connect->prepare($insert_libelle0);
        $insert_libelle->execute(array($input, $gender_libelle));
    }

?>
