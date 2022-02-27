<?php

    include '../../config/dbconnect.functions.php';

    $poste_number = htmlspecialchars(trim(strip_tags($_POST['poste_number'])));
    $old_p = sha1(htmlspecialchars(trim(strip_tags($_POST['old_password']))));
    $new_p = sha1(htmlspecialchars(trim(strip_tags($_POST['new_password2']))));

    $ss_p00 = "SELECT poste, user_password FROM users WHERE poste=?";
    $ss_p11 = $database_connect->prepare($ss_p00);
    $ss_p11->execute(array($poste_number));
    $ss_p = $ss_p11->fetchObject();

    if($old_p == $ss_p->user_password)
    {
        $edit0 = "UPDATE users SET user_password=? WHERE poste=?";
        $edit = $database_connect->prepare($edit0);
        $edit->execute(array($new_p, $poste_number));
    }
    else
    {
        echo "L'ancien mot de passe n'est pas correct";
    }

  ?>
