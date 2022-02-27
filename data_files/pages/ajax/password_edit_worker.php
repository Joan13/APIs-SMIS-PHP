<?php

    include '../../config/dbconnect.functions.php';

    $poste_number = htmlspecialchars(trim(strip_tags($_POST['worker_id'])));
    $old_p = sha1(htmlspecialchars(trim(strip_tags($_POST['old_password']))));
    $new_p = sha1(htmlspecialchars(trim(strip_tags($_POST['new_password2']))));

    $ss_p00 = "SELECT worker_id, user_password FROM workers_info WHERE worker_id=?";
    $ss_p11 = $database_connect->prepare($ss_p00);
    $ss_p11->execute(array($poste_number));
    $ss_p = $ss_p11->fetchObject();

    if($old_p == $ss_p->user_password)
    {
        $edit0 = "UPDATE workers_info SET user_password=? WHERE worker_id=?";
        $edit = $database_connect->prepare($edit0);
        $edit->execute(array($new_p, $poste_number));
    }
    else
    {
        echo "L'ancien mot de passe n'est pas correct";
    }

  ?>
